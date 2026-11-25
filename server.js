
require('dotenv').config();

const express = require('express');
const mysql = require('mysql2/promise');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');

const app = express();

app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(express.static(path.join(__dirname, 'public')));

const PORT = process.env.PORT || 3000;
const SECRET = process.env.JWT_SECRET;

if (!SECRET || SECRET.length < 32) {
    throw new Error('Set a secure JWT_SECRET in your .env file.');
}

let db;

// --------------------------------------------------
// DATABASE
// --------------------------------------------------

const fs = require('fs');

const dbConfig = {
    host: process.env.DB_HOST,
    port: Number(process.env.DB_PORT || 4000),
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
ssl: {
    minVersion: 'TLSv1.2'
},
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0,
    dateStrings: true,
    multipleStatements: true
};

// --------------------------------------------------
// HELPERS
// --------------------------------------------------

const handle = fn => (req, res, next) => {
    Promise.resolve(fn(req, res, next)).catch(next);
};

function createToken(user) {
    return jwt.sign(
        { id: user.id },
        SECRET,
        { expiresIn: '7d' }
    );
}

function sign(user) {
    return {
        token: createToken(user),
        user: {
            id: user.id,
            name: user.name,
            email: user.email,
            account_type: user.account_type
        }
    };
}

// --------------------------------------------------
// PAYFAST HELPERS
// --------------------------------------------------

function pfEncode(value) {
    return encodeURIComponent(String(value).trim())
        .replace(/%20/g, '+')
        .replace(/[!'()*]/g, char =>
            '%' + char.charCodeAt(0).toString(16).toUpperCase()
        );
}

function pfSignature(data) {
    const parts = Object.entries(data)
        .map(([key, value]) => `${key}=${pfEncode(value ?? '')}`);

    if (process.env.PAYFAST_PASSPHRASE) {
        parts.push(
            `passphrase=${pfEncode(process.env.PAYFAST_PASSPHRASE)}`
        );
    }

    return crypto
        .createHash('md5')
        .update(parts.join('&'))
        .digest('hex');
}

function pfConfig() {
    const sandbox = process.env.PAYFAST_MODE !== 'live';

    return {
        merchantId: process.env.PAYFAST_MERCHANT_ID,
        merchantKey: process.env.PAYFAST_MERCHANT_KEY,
        passphrase: process.env.PAYFAST_PASSPHRASE || '',
        paymentUrl: sandbox
            ? 'https://sandbox.payfast.co.za/eng/process'
            : 'https://www.payfast.co.za/eng/process',
        returnUrl: process.env.PAYFAST_RETURN_URL,
        cancelUrl: process.env.PAYFAST_CANCEL_URL,
        notifyUrl: process.env.PAYFAST_NOTIFY_URL
    };
}

function isPayFastIp(ip) {
    const ranges = [
        ['197.97.145.144', 28],
        ['41.74.179.192', 27],
        ['102.216.36.0', 28],
        ['102.216.36.128', 28],
        ['144.126.193.139', 32]
    ];

    const value = ipToNumber(ip);
    if (value === null) return false;

    return ranges.some(([network, bits]) => {
        const base = ipToNumber(network);
        const mask = bits === 0 ? 0 : (0xffffffff << (32 - bits)) >>> 0;
        return (value & mask) === (base & mask);
    });
}

function ipToNumber(ip) {
    const parts = String(ip).replace(/^::ffff:/, '').split('.');

    if (
        parts.length !== 4 ||
        parts.some(part => !/^\d+$/.test(part) ||
            Number(part) < 0 || Number(part) > 255)
    ) return null;

    return parts.reduce(
        (value, part) => ((value << 8) | Number(part)) >>> 0,
        0
    );
}

function createPayFastFields(order, event, user) {
    const pf = pfConfig();

    if (
        !pf.merchantId ||
        !pf.merchantKey ||
        !pf.returnUrl ||
        !pf.cancelUrl ||
        !pf.notifyUrl
    ) {
        throw new Error('PayFast is not fully configured.');
    }

    const nameParts = String(user.name || '').trim().split(/\s+/);
    const firstName = nameParts.shift() || '';
    const lastName = nameParts.join(' ');

    const fields = {
        merchant_id: pf.merchantId,
        merchant_key: pf.merchantKey,
        return_url: pf.returnUrl,
        cancel_url: pf.cancelUrl,
        notify_url: pf.notifyUrl,
        name_first: firstName,
        name_last: lastName,
        email_address: user.email,
        m_payment_id: `EB-${order.id}`,
        amount: Number(order.total).toFixed(2),
        item_name: String(event.title).slice(0, 100)
    };

    fields.signature = pfSignature(fields);

    return {
        payment_url: pf.paymentUrl,
        payment_fields: fields
    };
}
// --------------------------------------------------
// AUTHENTICATION
// --------------------------------------------------

async function auth(req, res, next) {
    try {
        const header = req.headers.authorization;

        if (!header || !header.startsWith('Bearer ')) {
            return res.status(401).json({
                error: 'Please log in first.'
            });
        }

        const token = header.slice(7);
        const decoded = jwt.verify(token, SECRET);

        // Always retrieve the actual account from MySQL.
        const [users] = await db.query(
            `SELECT id, name, email, account_type
             FROM eb_users
             WHERE id = ?
             LIMIT 1`,
            [decoded.id]
        );

        if (!users.length) {
            return res.status(401).json({
                error: 'Your account no longer exists.'
            });
        }

        req.user = users[0];

        next();

    } catch (error) {
        if (
            error.name === 'JsonWebTokenError' ||
            error.name === 'TokenExpiredError'
        ) {
            return res.status(401).json({
                error: 'Your session has expired. Please log in again.'
            });
        }

        next(error);
    }
}

function requireOrganiser(req, res, next) {
    if (req.user.account_type !== 'organiser') {
        return res.status(403).json({
            error: 'An organiser account is required.'
        });
    }

    next();
}

// --------------------------------------------------
// REGISTER
// --------------------------------------------------

app.post('/api/register', handle(async (req, res) => {
    const name = String(req.body.name || '').trim();
    const email = String(req.body.email || '').trim().toLowerCase();
    const password = String(req.body.password || '');
    const accountType = req.body.account_type;

    if (!name || !email || !password) {
        return res.status(400).json({
            error: 'Complete all required fields.'
        });
    }

    if (name.length > 120) {
        return res.status(400).json({
            error: 'Your name is too long.'
        });
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) || email.length > 190) {
        return res.status(400).json({
            error: 'Enter a valid email address.'
        });
    }

    if (password.length < 8) {
        return res.status(400).json({
            error: 'Password must contain at least 8 characters.'
        });
    }

    if (!['attendee', 'organiser'].includes(accountType)) {
        return res.status(400).json({
            error: 'Select a valid account type.'
        });
    }

    const passwordHash = await bcrypt.hash(password, 12);

    try {
        const [result] = await db.query(
            `INSERT INTO eb_users
            (name, email, password_hash, account_type)
            VALUES (?, ?, ?, ?)`,
            [name, email, passwordHash, accountType]
        );

        const user = {
            id: result.insertId,
            name,
            email,
            account_type: accountType
        };

        res.status(201).json(sign(user));

    } catch (error) {
        if (error.code === 'ER_DUP_ENTRY') {
            return res.status(409).json({
                error: 'An account with this email already exists.'
            });
        }

        throw error;
    }
}));

// --------------------------------------------------
// LOGIN
// --------------------------------------------------

app.post('/api/login', handle(async (req, res) => {
    const email = String(req.body.email || '').trim().toLowerCase();
    const password = String(req.body.password || '');

    if (!email || !password) {
        return res.status(400).json({
            error: 'Enter your email and password.'
        });
    }

    const [users] = await db.query(
        `SELECT id, name, email, password_hash, account_type
         FROM eb_users
         WHERE email = ?
         LIMIT 1`,
        [email]
    );

    if (!users.length) {
        return res.status(401).json({
            error: 'Incorrect email or password.'
        });
    }

    const user = users[0];

    const valid = await bcrypt.compare(
        password,
        user.password_hash
    );

    if (!valid) {
        return res.status(401).json({
            error: 'Incorrect email or password.'
        });
    }

    res.json(sign(user));
}));

// --------------------------------------------------
// CURRENT ACCOUNT
// --------------------------------------------------

app.get('/api/me', auth, (req, res) => {
    res.json({ user: req.user });
});

// --------------------------------------------------
// GET ACCOUNT DETAILS
// --------------------------------------------------

app.get('/api/account', auth, async (req, res) => {
    try {
        const [rows] = await db.query(
            `SELECT
                id,
                name,
                email,
                account_type,
                phone,
                location,
                organisation_name,
                organisation_description,
                website
             FROM eb_users
             WHERE id = ?
             LIMIT 1`,
            [req.user.id]
        );

        if (!rows.length) {
            return res.status(404).json({
                error: 'Account not found.'
            });
        }

        res.json({ user: rows[0] });

    } catch (error) {
        console.error('Account retrieval error:', error);

        res.status(500).json({
            error: 'Unable to load your account details.'
        });
    }
});

// --------------------------------------------------
// UPDATE ACCOUNT DETAILS
// --------------------------------------------------

app.put('/api/account', auth, async (req, res) => {
    try {
        const allowedFields = {
            name: 'name',
            phone: 'phone',
            location: 'location'
        };

        if (req.user.account_type === 'organiser') {
            Object.assign(allowedFields, {
                organisation_name: 'organisation_name',
                organisation_description: 'organisation_description',
                website: 'website'
            });
        }

        const updates = [];
        const values = [];

        for (const [field, column] of Object.entries(allowedFields)) {
            if (!Object.prototype.hasOwnProperty.call(req.body, field)) {
                continue;
            }

            let value = req.body[field];

            if (typeof value !== 'string') {
                return res.status(400).json({
                    error: `Invalid value for ${field}.`
                });
            }

            value = value.trim();

            if (field === 'name' && !value) {
                return res.status(400).json({
                    error: 'Your name cannot be empty.'
                });
            }

            if (field === 'name' && value.length > 120) {
                return res.status(400).json({
                    error: 'Your name is too long.'
                });
            }

            if (field === 'phone' && value.length > 30) {
                return res.status(400).json({
                    error: 'Your phone number is too long.'
                });
            }

            if (field === 'location' && value.length > 160) {
                return res.status(400).json({
                    error: 'Your location is too long.'
                });
            }

            if (field === 'organisation_name' && value.length > 160) {
                return res.status(400).json({
                    error: 'Your organisation name is too long.'
                });
            }

            if (field === 'website' && value.length > 255) {
                return res.status(400).json({
                    error: 'Your website address is too long.'
                });
            }

            updates.push(`\`${column}\` = ?`);
            values.push(value || null);
        }

        if (updates.length === 0) {
            return res.status(400).json({
                error: 'No account details were provided.'
            });
        }

        values.push(req.user.id);

        await db.query(
            `UPDATE eb_users
             SET ${updates.join(', ')}
             WHERE id = ?`,
            values
        );

        const [rows] = await db.query(
            `SELECT id, name, email, account_type,
                    phone, location, organisation_name,
                    organisation_description, website
             FROM eb_users
             WHERE id = ?`,
            [req.user.id]
        );

        if (!rows.length) {
            return res.status(404).json({
                error: 'Account not found.'
            });
        }

        res.json({
            message: 'Account details updated successfully.',
            user: rows[0]
        });

    } catch (error) {
        console.error('Account update error:', error);

        res.status(500).json({
            error: 'Unable to update your account details.'
        });
    }
});

// --------------------------------------------------
// PUBLIC EVENTS
// --------------------------------------------------

const LIST = `
    SELECT
        e.*,
        COALESCE(u.name, 'EasyBooking') AS organiser,
        (
            SELECT MIN(price)
            FROM eb_ticket_types
            WHERE event_id = e.id
        ) AS from_price
    FROM eb_events e
    LEFT JOIN eb_users u
        ON u.id = e.organiser_id
`;

app.get('/api/events', handle(async (req, res) => {
    const conditions = [
        'e.starts_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)'
    ];

    const values = [];

    if (req.query.q) {
        conditions.push(
            '(e.title LIKE ? OR e.venue LIKE ? OR e.city LIKE ?)'
        );

        const search = `%${req.query.q}%`;

        values.push(search, search, search);
    }

    if (req.query.category) {
        conditions.push('e.category = ?');
        values.push(req.query.category);
    }

    if (req.query.city) {
        conditions.push('e.city LIKE ?');
        values.push(`%${req.query.city}%`);
    }

    const [events] = await db.query(
        `${LIST}
         WHERE ${conditions.join(' AND ')}
         ORDER BY e.starts_at`,
        values
    );

    res.json(events);
}));

app.get('/api/events/:id', handle(async (req, res) => {
    const [events] = await db.query(
        `${LIST} WHERE e.id = ?`,
        [req.params.id]
    );

    if (!events.length) {
        return res.status(404).json({
            error: 'Event not found.'
        });
    }

    const [tickets] = await db.query(
        `SELECT
            id,
            name,
            price,
            quantity - sold AS remaining
         FROM eb_ticket_types
         WHERE event_id = ?`,
        [req.params.id]
    );

    res.json({
        ...events[0],
        tickets
    });
}));

// --------------------------------------------------
// CREATE EVENT - ORGANISERS ONLY
// --------------------------------------------------

app.post(
    '/api/events',
    auth,
    requireOrganiser,
    handle(async (req, res) => {
        const {
            title,
            category,
            venue,
            city,
            starts_at,
            description,
            tickets
        } = req.body;

        if (
            !title?.trim() ||
            !category ||
            !starts_at ||
            !Array.isArray(tickets) ||
            tickets.length < 1
        ) {
            return res.status(400).json({
                error: 'Complete the event details and add tickets.'
            });
        }

        if (tickets.length > 20) {
            return res.status(400).json({
                error: 'You can add up to 20 ticket types.'
            });
        }

        const connection = await db.getConnection();

        try {
            await connection.beginTransaction();

            // Ownership comes from the authenticated user.
            // Never accept organiser_id from the frontend.
            const [event] = await connection.query(
                `INSERT INTO eb_events
                (organiser_id, title, category, venue, city,
                 starts_at, description)
                 VALUES (?, ?, ?, ?, ?, ?, ?)`,
                [
                    req.user.id,
                    title.trim(),
                    category,
                    venue || '',
                    city || '',
                    starts_at.replace('T', ' '),
                    description || ''
                ]
            );

            for (const ticket of tickets) {
                const price = Number(ticket.price);
                const quantity = Number(ticket.quantity);

                if (
                    !ticket.name?.trim() ||
                    !Number.isFinite(price) ||
                    price < 0 ||
                    !Number.isInteger(quantity) ||
                    quantity < 1
                ) {
                    throw new Error('Check your ticket types, prices and quantities.');
                }

                await connection.query(
                    `INSERT INTO eb_ticket_types
                    (event_id, name, price, quantity)
                    VALUES (?, ?, ?, ?)`,
                    [
                        event.insertId,
                        ticket.name.trim(),
                        price,
                        quantity
                    ]
                );
            }

            await connection.commit();

            res.status(201).json({
                id: event.insertId,
                message: 'Event published successfully.'
            });

        } catch (error) {
            await connection.rollback();
            throw error;

        } finally {
            connection.release();
        }
    })
);

// --------------------------------------------------
// BOOK TICKETS - ATTENDEE ACCOUNT
// --------------------------------------------------

app.post('/api/orders', auth, handle(async (req, res) => {
    const eventId = Number(req.body.event_id);
    const items = req.body.items;

    if (
        !Number.isInteger(eventId) ||
        eventId < 1 ||
        !Array.isArray(items) ||
        !items.length
    ) {
        return res.status(400).json({
            error: 'Invalid booking request.'
        });
    }

    const connection = await db.getConnection();

    try {
        await connection.beginTransaction();

        const [events] = await connection.query(
            `SELECT id, title
             FROM eb_events
             WHERE id = ?
             LIMIT 1`,
            [eventId]
        );

        if (!events.length) {
            throw new Error('Event not found.');
        }

        const event = events[0];

        const picked = items.filter(item => Number(item.qty) > 0);

        if (!picked.length) {
            throw new Error('Choose at least one ticket.');
        }

        const ticketIds = picked.map(item =>
            Number(item.ticket_type_id)
        );

        if (
            ticketIds.some(id => !Number.isInteger(id) || id < 1) ||
            new Set(ticketIds).size !== ticketIds.length
        ) {
            throw new Error('Invalid ticket selection.');
        }

        let total = 0;
        const orderItems = [];

        for (const item of picked) {
            const qty = Number(item.qty);
            const ticketTypeId = Number(item.ticket_type_id);

            if (
                !Number.isInteger(qty) ||
                qty < 1 ||
                qty > 10
            ) {
                throw new Error(
                    'Choose between 1 and 10 tickets per type.'
                );
            }

            const [types] = await connection.query(
                `SELECT *
                 FROM eb_ticket_types
                 WHERE id = ? AND event_id = ?
                 FOR UPDATE`,
                [ticketTypeId, eventId]
            );

            if (!types.length) {
                throw new Error('Ticket type not found.');
            }

            const ticket = types[0];

            const available =
                Number(ticket.quantity) -
                Number(ticket.sold) -
                Number(ticket.reserved);

            if (available < qty) {
                throw new Error(
                    `Not enough tickets left for ${ticket.name}.`
                );
            }

            const price = Number(ticket.price);

            total += qty * price;

            orderItems.push({
                ticket_type_id: ticket.id,
                quantity: qty,
                unit_price: price
            });

            await connection.query(
                `UPDATE eb_ticket_types
                 SET reserved = reserved + ?
                 WHERE id = ?`,
                [qty, ticket.id]
            );
        }

        const [order] = await connection.query(
            `INSERT INTO eb_orders
             (user_id, event_id, total, payment_status, payment_ref)
             VALUES (?, ?, ?, 'pending', ?)`,
            [
                req.user.id,
                eventId,
                total.toFixed(2),
                crypto.randomUUID()
            ]
        );

        for (const item of orderItems) {
            await connection.query(
                `INSERT INTO eb_order_items
                 (order_id, ticket_type_id, quantity, unit_price)
                 VALUES (?, ?, ?, ?)`,
                [
                    order.insertId,
                    item.ticket_type_id,
                    item.quantity,
                    item.unit_price.toFixed(2)
                ]
            );
        }

        const payment = createPayFastFields(
            {
                id: order.insertId,
                total
            },
            event,
            req.user
        );

        await connection.commit();

        res.status(201).json({
            order_id: order.insertId,
            total: Number(total.toFixed(2)),
            ...payment
        });

    } catch (error) {
        await connection.rollback();

        res.status(400).json({
            error: error.message
        });

    } finally {
        connection.release();
    }
}));

// --------------------------------------------------
// PAYFAST ITN
// --------------------------------------------------

app.post('/api/payfast/notify', handle(async (req, res) => {
    const data = req.body;
    const pf = pfConfig();

    try {
        if (process.env.PAYFAST_MODE === 'live') {
            const forwarded = req.headers['x-forwarded-for'];
            const sourceIp = forwarded
                ? forwarded.split(',')[0].trim()
                : req.socket.remoteAddress;

            if (!isPayFastIp(sourceIp)) {
                return res.status(403).send('Invalid source IP');
            }
        }

        if (!data || !data.signature || !data.m_payment_id) {
            return res.status(400).send('Invalid notification');
        }

        // Verify merchant ID
        if (String(data.merchant_id) !== String(pf.merchantId)) {
            return res.status(400).send('Invalid merchant');
        }

        // Verify signature
        const signatureData = { ...data };
        delete signatureData.signature;

        const expectedSignature = pfSignature(signatureData);
        const receivedSignature = String(data.signature);

        if (
            expectedSignature.length !== receivedSignature.length ||
            !crypto.timingSafeEqual(
                Buffer.from(expectedSignature),
                Buffer.from(receivedSignature)
            )
        ) {
            return res.status(400).send('Invalid signature');
        }

        // Verify notification with PayFast server
        const validationUrl = process.env.PAYFAST_MODE === 'live'
            ? 'https://www.payfast.co.za/eng/query/validate'
            : 'https://sandbox.payfast.co.za/eng/query/validate';

        const validationBody = new URLSearchParams(data).toString();

        const validationResponse = await fetch(validationUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: validationBody
        });

        const validationResult = await validationResponse.text();

        if (validationResult.trim() !== 'VALID') {
            return res.status(400).send('PayFast validation failed');
        }

        const orderId = Number(
            String(data.m_payment_id).replace(/^EB-/, '')
        );

        if (!Number.isInteger(orderId) || orderId < 1) {
            return res.status(400).send('Invalid order reference');
        }

        const connection = await db.getConnection();

        try {
            await connection.beginTransaction();

            const [orders] = await connection.query(
                `SELECT *
                 FROM eb_orders
                 WHERE id = ?
                 FOR UPDATE`,
                [orderId]
            );

            if (!orders.length) {
                await connection.rollback();
                return res.status(404).send('Order not found');
            }

            const order = orders[0];

            // Idempotency: ignore duplicate notifications for paid orders
            if (order.payment_status !== 'pending') {
                await connection.rollback();
                return res.status(200).send('Order already processed');
            }

            // Verify payment amount against the stored order total
            const paidAmount = Number(data.amount_gross);
            const expectedAmount = Number(order.total);

            if (
                !Number.isFinite(paidAmount) ||
                Math.abs(paidAmount - expectedAmount) > 0.01
            ) {
                await connection.rollback();
                return res.status(400).send('Amount mismatch');
            }

            if (data.payment_status !== 'COMPLETE') {
                await connection.rollback();
                return res.status(200).send('Payment not complete');
            }

            const [orderItems] = await connection.query(
                `SELECT *
                 FROM eb_order_items
                 WHERE order_id = ?`,
                [orderId]
            );

            if (!orderItems.length) {
                await connection.rollback();
                return res.status(400).send('Order has no items');
            }

            // Lock ticket types and verify reservations
            for (const item of orderItems) {
                const [types] = await connection.query(
                    `SELECT *
                     FROM eb_ticket_types
                     WHERE id = ?
                     FOR UPDATE`,
                    [item.ticket_type_id]
                );

                if (!types.length || types[0].reserved < item.quantity) {
                    throw new Error('Ticket reservation missing');
                }
            }

            // Convert reservations to sold tickets
            for (const item of orderItems) {
                await connection.query(
                    `UPDATE eb_ticket_types
                     SET reserved = reserved - ?,
                         sold = sold + ?
                     WHERE id = ?`,
                    [
                        item.quantity,
                        item.quantity,
                        item.ticket_type_id
                    ]
                );

                // Issue tickets after verified payment
                for (let i = 0; i < item.quantity; i++) {
                    const code = 'EB-' +
                        crypto.randomBytes(6).toString('hex').toUpperCase();

                    await connection.query(
                        `INSERT INTO eb_tickets
                         (order_id, ticket_type_id, code)
                         VALUES (?, ?, ?)`,
                        [orderId, item.ticket_type_id, code]
                    );
                }
            }

            await connection.query(
                `UPDATE eb_orders
                 SET payment_status = 'paid',
                     pf_payment_id = ?,
                     paid_at = NOW()
                 WHERE id = ?`,
                [data.pf_payment_id, orderId]
            );

            await connection.commit();

            res.status(200).send('Payment processed');

        } catch (error) {
            await connection.rollback();
            throw error;

        } finally {
            connection.release();
        }

    } catch (error) {
        console.error('PayFast ITN error:', error.message);
        res.status(500).send('ITN processing error');
    }
}));

async function expirePendingOrders() {
    const connection = await db.getConnection();

    try {
        await connection.beginTransaction();

        const [orders] = await connection.query(
            `SELECT id
             FROM eb_orders
             WHERE payment_status = 'pending'
             AND created_at <= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
             FOR UPDATE`
        );

        for (const order of orders) {
            const [items] = await connection.query(
                `SELECT ticket_type_id, quantity
                 FROM eb_order_items
                 WHERE order_id = ?`,
                [order.id]
            );

            for (const item of items) {
                await connection.query(
                    `UPDATE eb_ticket_types
                     SET reserved = GREATEST(0, reserved - ?)
                     WHERE id = ?`,
                    [item.quantity, item.ticket_type_id]
                );
            }

            await connection.query(
                `UPDATE eb_orders
                 SET payment_status = 'cancelled'
                 WHERE id = ? AND payment_status = 'pending'`,
                [order.id]
            );
        }

        await connection.commit();

    } catch (error) {
        await connection.rollback();
        console.error('Reservation expiry error:', error.message);

    } finally {
        connection.release();
    }
}
// --------------------------------------------------
// MY TICKETS - CURRENT USER ONLY
// --------------------------------------------------

app.get('/api/my/tickets', auth, handle(async (req, res) => {
    const [tickets] = await db.query(
        `SELECT
            k.code,
            tt.name AS ticket,
            e.id AS event_id,
            e.title,
            e.venue,
            e.city,
            e.starts_at
         FROM eb_tickets k
         JOIN eb_orders o ON o.id = k.order_id
         JOIN eb_ticket_types tt ON tt.id = k.ticket_type_id
         JOIN eb_events e ON e.id = o.event_id
         WHERE o.user_id = ?
          AND o.payment_status = 'paid'
         ORDER BY e.starts_at`,
        [req.user.id]
    );

    res.json(tickets);
}));

// --------------------------------------------------
// ORGANISER DASHBOARD - CURRENT ORGANISER ONLY
// --------------------------------------------------

app.get(
    '/api/my/events',
    auth,
    requireOrganiser,
    handle(async (req, res) => {
        const [events] = await db.query(
            `SELECT
                e.id,
                e.title,
                e.starts_at,
                COALESCE(SUM(tt.sold), 0) AS sold,
                COALESCE(SUM(tt.quantity), 0) AS capacity,
                COALESCE(SUM(tt.sold * tt.price), 0) AS revenue
             FROM eb_events e
             LEFT JOIN eb_ticket_types tt
                ON tt.event_id = e.id
             WHERE e.organiser_id = ?
             GROUP BY e.id, e.title, e.starts_at
             ORDER BY e.starts_at`,
            [req.user.id]
        );

        res.json(events);
    })
);

// --------------------------------------------------
// PUBLIC STATISTICS
// --------------------------------------------------

app.get('/api/stats', handle(async (req, res) => {
    const [[events]] = await db.query(
        `SELECT
            COUNT(*) AS events,
            COUNT(DISTINCT CASE
                WHEN city <> 'Online' THEN city
            END) AS cities
         FROM eb_events`
    );

    const [[tickets]] = await db.query(
        'SELECT COUNT(*) AS tickets FROM eb_tickets'
    );

    const [[members]] = await db.query(
        'SELECT COUNT(*) AS members FROM eb_users'
    );

    res.json({
        events: events.events,
        cities: events.cities,
        tickets: tickets.tickets,
        members: members.members
    });
}));

// --------------------------------------------------
// SAMPLE EVENTS - NO DEMO USER
// --------------------------------------------------

async function seed() {
    const [[result]] = await db.query(
        'SELECT COUNT(*) AS total FROM eb_events'
    );

    if (Number(result.total) > 0) {
        return;
    }

    const day = 86400000;

    const at = days =>
        new Date(Date.now() + days * day)
            .toISOString()
            .slice(0, 19)
            .replace('T', ' ');

    const events = [
        [
            'Sunset Jazz on the Lawn',
            'Music',
            'Company Gardens',
            'Cape Town',
            7,
            'Live jazz, picnic baskets and golden-hour views.',
            [['General', 150, 200], ['VIP Picnic', 350, 30]]
        ],
        [
            'Cape Town Startup Mixer',
            'Business',
            'The Workshop',
            'Cape Town',
            10,
            'Meet founders, designers and developers over drinks.',
            [['Standard', 0, 120]]
        ],
        [
            'Community Beach Clean-up',
            'Charity',
            'Muizenberg Beach',
            'Cape Town',
            5,
            'Gloves and bags provided. Every hand helps.',
            [['Volunteer', 0, 80]]
        ],
        [
            'Street Food Festival',
            'Food & Drink',
            'Hope Street',
            'Cape Town',
            14,
            '30+ vendors, live DJs and a kids zone.',
            [['Entry', 60, 500], ['Tasting Pass', 180, 100]]
        ],
        [
            'Stand-Up Comedy Night',
            'Arts',
            'Obz Comedy Club',
            'Cape Town',
            3,
            'Five comedians, one unforgettable night.',
            [['Seat', 120, 90]]
        ],
        [
            'Trail Run Series',
            'Sports',
            'Table Mountain Park',
            'Cape Town',
            21,
            '10km and 21km routes with a post-run breakfast.',
            [['10km', 200, 150], ['21km', 280, 100]]
        ],
        [
            'Intro to Web Dev Workshop',
            'Workshops',
            'Online',
            'Online',
            9,
            'A hands-on beginner session building your first page.',
            [['Ticket', 100, 40]]
        ]
    ];

    for (const event of events) {
        const [created] = await db.query(
            `INSERT INTO eb_events
            (organiser_id, title, category, venue, city,
             starts_at, description)
             VALUES (NULL, ?, ?, ?, ?, ?, ?)`,
            [
                event[0],
                event[1],
                event[2],
                event[3],
                at(event[4]),
                event[5]
            ]
        );

        for (const ticket of event[6]) {
            await db.query(
                `INSERT INTO eb_ticket_types
                (event_id, name, price, quantity)
                VALUES (?, ?, ?, ?)`,
                [created.insertId, ...ticket]
            );
        }
    }

    console.log('Sample events created without a demo account.');
}

// --------------------------------------------------
// ERROR HANDLING
// --------------------------------------------------

app.use((error, req, res, next) => {
    console.error(error);

    if (res.headersSent) {
        return next(error);
    }

    res.status(500).json({
        error: 'An unexpected server error occurred.'
    });
});

async function ensureAccountColumns() {
    const columns = {
        phone: 'VARCHAR(30) NULL',
        location: 'VARCHAR(160) NULL',
        organisation_name: 'VARCHAR(160) NULL',
        organisation_description: 'TEXT NULL',
        website: 'VARCHAR(255) NULL'
    };

    for (const [column, definition] of Object.entries(columns)) {
        const [existing] = await db.query(
            `SELECT COLUMN_NAME
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
             AND TABLE_NAME = 'eb_users'
             AND COLUMN_NAME = ?`,
            [column]
        );

        if (existing.length === 0) {
            await db.query(
                `ALTER TABLE eb_users ADD COLUMN \`${column}\` ${definition}`
            );
        }
    }

    console.log('Account settings columns verified.');
}

// --------------------------------------------------
// START SERVER
// --------------------------------------------------

async function start() {
    db = mysql.createPool(dbConfig);

    await db.query(
        fs.readFileSync(
            path.join(__dirname, 'schema.sql'),
            'utf8'
        )
    );

    await ensureAccountColumns();

    await seed();

    setInterval(expirePendingOrders, 60 * 1000);

    app.listen(PORT, () => {
        console.log(`EasyBooking running at http://localhost:${PORT}`);
    });
}

start().catch(error => {
    console.error('Startup failed:', error);
    process.exit(1);
});
