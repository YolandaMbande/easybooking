const CURRENCY = 'R';
const CONTACT_EMAIL = 'hello@easybooking.com'; // change to your real email
const ABOUT = {
    founderName: 'Ziyanda Testing',
    founderRole: 'Founder, EasyBooking'
}; // edit these two
const app = document.getElementById('app'),
    menu = document.getElementById('menu');
let token = localStorage.getItem('eb_token');
let user = null;
let ALL = null;

// Read the cached user safely. The server will refresh this on startup.
try {
    user = JSON.parse(localStorage.getItem('eb_user') || 'null');
} catch {
    localStorage.removeItem('eb_user');
}
const PH = {
    Music: '1470229722913-7c0e2dbbafd3',
    Business: '1511795409834-ef04bbd61622',
    Charity: '1559027615-cd4628902d4a',
    'Food & Drink': '1555939594-58d7cb561ad1',
    Arts: '1503095396549-807759245b35',
    Sports: '1452626038306-9aae5e071dd3',
    Workshops: '1522202176988-66273c2fd55f'
};
const U = id => `https://images.unsplash.com/photo-${id}?auto=format&fit=crop&w=900&q=70`;
const pic = (cat, cls = '') => `<img class="${cls}" loading="lazy" src="${U(PH[cat]||PH.Music)}" alt="" onerror="this.style.visibility='hidden'">`;
const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;'
} [c]));
const money = n => +n === 0 ? 'Free' : CURRENCY + Number(n).toFixed(2);
const dt = d => new Date(d.replace(' ', 'T'));
const when = d => dt(d).toLocaleString('en-ZA', {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
});
const api = async (p, o = {}) => {
    const options = {
        method: o.method || 'GET',
        headers: {
            ...(o.body ? { 'Content-Type': 'application/json' } : {}),
            ...(token ? { Authorization: 'Bearer ' + token } : {})
        },
        body: o.body ? JSON.stringify(o.body) : undefined
    };

    const response = await fetch('/api' + p, options);

    let data;
    try {
        data = await response.json();
    } catch {
        data = {};
    }

    if (response.status === 401 && token) {
        token = null;
        user = null;
        localStorage.removeItem('eb_token');
        localStorage.removeItem('eb_user');
    }

    if (!response.ok) {
        const error = new Error(data.error || 'Something went wrong.');
        error.status = response.status;
        throw error;
    }

    return data;
};
const setUser = d => {
    token = d.token;
    user = d.user;
    localStorage.setItem('eb_token', token);
    localStorage.setItem('eb_user', JSON.stringify(user))
};

function logout(silent = false) {
    token = null;
    user = null;

    localStorage.removeItem('eb_token');
    localStorage.removeItem('eb_user');

    if (!silent) {
        location.hash = '#/';
    }

    render();
}

const filt = {
    cat: '',
    free: false,
    when: '',
    where: 'Cape Town',
    q: '',
    tile: 'Nearby'
};
const TILES = [
    ['Nearby', '🗺', {
        where: 'Cape Town'
    }],
    ['For You', '🗂', {}],
    ['Trending', '🔥', {}],
    ['Free Things To Do', '💲', {
        free: true
    }],
    ['This Weekend', '🕐', {
        when: 'weekend'
    }],
    ['Music', '🎵', {
        cat: 'Music'
    }],
    ["What's On Today", '🕐', {
        when: 'today'
    }],
    ['Food & Drink', '🍴', {
        cat: 'Food & Drink'
    }],
    ['Arts', '🎭', {
        cat: 'Arts'
    }],
    ['Business', '💼', {
        cat: 'Business'
    }],
    ['Charity', '💚', {
        cat: 'Charity'
    }],
    ['Sports', '🏃', {
        cat: 'Sports'
    }],
    ['Workshops', '🛠', {
        cat: 'Workshops'
    }]
];

function match(e) {
    const d = dt(e.starts_at),
        now = new Date(),
        days = (d - now) / 864e5,
        city = (e.city || '').toLowerCase();
    if (filt.cat && e.category !== filt.cat) return false;
    if (filt.free && +e.from_price !== 0) return false;
    if (filt.where && city !== 'online' && !city.includes(filt.where.toLowerCase())) return false;
    if (filt.when === 'today' && d.toDateString() !== now.toDateString()) return false;
    if (filt.when === 'weekend' && !(days < 7 && [5, 6, 0].includes(d.getDay()))) return false;
    if (filt.when === 'month' && days > 31) return false;
    if (filt.q && !(e.title + e.venue + e.city).toLowerCase().includes(filt.q.toLowerCase())) return false;
    return true
}
const card = e => `<a class="card" href="#/event/${e.id}"><div class="ph">${pic(e.category)}</div><div class="when">${when(e.starts_at)}</div><h3>${esc(e.title)}</h3><div class="meta">${esc(e.venue||'')}${e.city?', '+esc(e.city):''}</div><div class="price">${e.from_price==null?'':(+e.from_price===0?'Free':'From '+money(e.from_price))}</div></a>`;


function renderChrome() {
    const isOrganiser = user?.account_type === 'organiser';

    menu.innerHTML = `
        <div class="mtop">
            <a class="logo" href="#/">Easy<b>Booking</b></a>
            <button class="mx" aria-label="Close menu">✕</button>
        </div>

        <div class="mgrid">

            <div>
                <h4>Explore</h4>
                <a href="#/">Find events</a>
                <a href="#/?cat=Music">Music</a>
                <a href="#/?cat=Food & Drink">Food & Drink</a>
                <a href="#/?cat=Charity">Charity events</a>
            </div>

            <div>
                <h4>My account</h4>

                ${user ? `
                    <p class="meta">
                        Signed in as <b>${esc(user.name)}</b>
                    </p>

                    <p class="meta">
                        ${isOrganiser ? 'Organiser account' : 'Attendee account'}
                    </p>

                    ${isOrganiser ? `
                        <a href="#/dashboard">My organiser dashboard</a>
                        <a href="#/create">Create an event</a>
                    ` : `
                        <a href="#/account">Account settings</a>
                    `}

                    <a href="#/tickets">My tickets</a>
                    <a href="#" id="lo">Log out</a>

                ` : `
                    <a href="#/login">Log in</a>
                    <a href="#/register">Create an account</a>
                `}
            </div>

            <div>
                <h4>Company</h4>
                <a href="#/about">About us</a>
                <a href="#/careers">Careers</a>
                <a href="#/contact">Contact us</a>
                <a href="#/faqs">FAQs</a>
            </div>

        </div>
    `;

    const logoutLink = document.getElementById('lo');

    if (logoutLink) {
        logoutLink.onclick = event => {
            event.preventDefault();
            logout();
        };
    }

    const headerLink = document.getElementById('hl');

    if (headerLink) {
        headerLink.textContent = user
            ? (isOrganiser ? 'My dashboard ↗' : 'My tickets ↗')
            : 'Log in ↗';

        headerLink.href = user
            ? (isOrganiser ? '#/dashboard' : '#/tickets')
            : '#/login';
    }

    const fi = document.getElementById('fi');

    if (fi) fi.innerHTML = `
        <option value="">All Events</option>
        ${Object.keys(PH).map(category => `
            <option ${category === filt.cat ? 'selected' : ''}>
                ${category}
            </option>
        `).join('')}
    `;

    const fw = document.getElementById('fw');
    const fn = document.getElementById('fn');

    if (fw) fw.value = filt.where;
    if (fn) fn.value = filt.when;
}


const closeMenu = () => {
    menu.classList.remove('open');
    document.body.classList.remove('lock')
};
document.getElementById('burger').onclick = () => {
    menu.classList.add('open');
    document.body.classList.add('lock')
};
menu.onclick = e => {
    if (e.target.closest('a') || e.target.closest('.mx')) closeMenu()
};
addEventListener('keydown', e => {
    if (e.key === 'Escape') closeMenu()
});
document.getElementById('pill').onsubmit = e => {
    e.preventDefault();
    Object.assign(filt, {
        cat: fi.value,
        where: fw.value.trim(),
        when: fn.value,
        free: false,
        q: '',
        tile: ''
    });
    if (location.hash.replace('#', '') === '' || location.hash === '#/') render();
    else location.hash = '#/';
    setTimeout(() => document.getElementById('events')?.scrollIntoView({
        behavior: 'smooth'
    }), 50)
};

async function home() {
    if (!ALL) ALL = await api('/events');
    app.innerHTML = `<div class="hero"><div><small>FEATURED</small><h1>Events made easy, not expensive</h1><p>Host your next event with EasyBooking. Set up tickets in minutes and start selling today.</p><a class="btn peach" href="#/create">Host events</a><a class="btn line" href="#events" id="browse">Browse events</a></div></div>
 <div class="loc"><span>Showing events in <b id="lw"></b></span><button id="loc">⌖ Use my location</button></div>
 <div class="tilewrap"><div class="tiles" id="tiles"></div><button class="arrow" id="nx">›</button></div>
 <section class="s" id="events"><h2 class="h" id="ttl"></h2><div class="grid" id="list"></div></section>
 <div class="promo"><div class="t"><h2>Want to see your event here?</h2><p>Sell tickets in minutes on a simple platform built for hosts of every size, from backyard gigs to big festivals.</p><a class="btn peach" href="#/create">Learn more</a><a class="btn dark" href="#/register">Create account</a></div>
 <div class="collage"><img class="c1" src="${U('1492684223066-81342ee5ff30')}" alt="" onerror="this.style.visibility='hidden'"><img class="c2" src="${U('1533174072545-7a4b6ad7a6c3')}" alt="" onerror="this.style.visibility='hidden'"><img class="c3" src="${U('1510915361894-db8b60106cb1')}" alt="" onerror="this.style.visibility='hidden'"><span class="star">✳</span></div></div>
 <section class="s"><h2 class="h">Popular places</h2><div class="places">${['Cape Town','Johannesburg','Durban','Pretoria','Gqeberha','Online'].map(c=>`<a href="#/?where=${c}">${c}</a>`).join('')}</div></section>`;
    const tiles = document.getElementById('tiles');
    const draw = () => {
        document.getElementById('lw').textContent = filt.where || 'All locations';
        tiles.innerHTML = TILES.map(t => `<div class="tile ${filt.tile===t[0]?'on':''}" data-k="${t[0]}"><i>${t[1]}</i>${t[0]}</div>`).join('');
        tiles.querySelectorAll('.tile').forEach(el => el.onclick = () => {
            const t = TILES.find(x => x[0] === el.dataset.k);
            Object.assign(filt, {
                cat: '',
                free: false,
                when: '',
                q: '',
                tile: t[0]
            }, t[2]);
            renderChrome();
            draw();
            list()
        });
    };
    const list = () => {
        const ev = ALL.filter(match);
        document.getElementById('ttl').textContent = filt.tile && filt.tile !== 'Nearby' ? filt.tile : (filt.cat || 'Upcoming events');
        document.getElementById('list').innerHTML = ev.map(card).join('') || '<p class="meta">No events match yet. Try another place or category.</p>'
    };
    document.getElementById('nx').onclick = () => tiles.scrollBy({
        left: 420,
        behavior: 'smooth'
    });
    document.getElementById('browse').onclick = e => {
        e.preventDefault();
        document.getElementById('events').scrollIntoView({
            behavior: 'smooth'
        })
    };
    document.getElementById('loc').onclick = () => {
        Object.assign(filt, {
            where: 'Cape Town',
            tile: 'Nearby',
            cat: '',
            free: false,
            when: ''
        });
        renderChrome();
        draw();
        list()
    };
    draw();
    list()
}


async function eventPage(id) {
    const e = await api('/events/' + id);

    app.innerHTML = `
        <div class="event-detail-page">

            <div class="event-detail-hero">
                <div class="event-detail-image">
                    ${pic(e.category, 'bigimg')}
                </div>

                <div class="event-detail-hero-content">
                    <span class="event-detail-category">
                        ${esc(e.category)}
                    </span>

                    <h1>${esc(e.title)}</h1>

                    <p class="event-detail-organiser">
                        Organised by <b>${esc(e.organiser)}</b>
                    </p>

                    <div class="event-detail-hero-meta">
                        <div>
                            <span class="event-meta-label">DATE & TIME</span>
                            <b>${when(e.starts_at)}</b>
                        </div>

                        <div>
                            <span class="event-meta-label">LOCATION</span>
                            <b>${esc(e.venue || 'Venue to be announced')}${e.city ? ', ' + esc(e.city) : ''}</b>
                        </div>
                    </div>
                </div>
            </div>

            <div class="event-detail-layout">

                <main class="event-detail-main">

                    <section class="event-detail-section">
                        <span class="event-section-eyebrow">THE EXPERIENCE</span>
                        <h2>About this event</h2>
                        <p class="event-description">
                            ${esc(e.description || 'More information about this event will be available soon.')}
                        </p>
                    </section>

                    <section class="event-detail-section event-location-section">
                        <span class="event-section-eyebrow">WHEN & WHERE</span>
                        <h2>Event information</h2>

                        <div class="event-info-card">
                            <div class="event-info-item">
                                <div class="event-info-icon">D</div>
                                <div>
                                    <span>Date & time</span>
                                    <b>${when(e.starts_at)}</b>
                                </div>
                            </div>

                            <div class="event-info-item">
                                <div class="event-info-icon">L</div>
                                <div>
                                    <span>Venue</span>
                                    <b>${esc(e.venue || 'To be announced')}</b>
                                    <small>${esc(e.city || '')}</small>
                                </div>
                            </div>

                            <div class="event-info-item">
                                <div class="event-info-icon">O</div>
                                <div>
                                    <span>Organiser</span>
                                    <b>${esc(e.organiser)}</b>
                                </div>
                            </div>
                        </div>
                    </section>

                </main>

                <aside class="event-booking-card">

                    <div class="event-booking-heading">
                        <span class="event-section-eyebrow">MAKE IT HAPPEN</span>
                        <h2>Choose your tickets</h2>
                        <p>Select the tickets you'd like to book.</p>
                    </div>

                    <div class="event-ticket-list">
                        ${e.tickets.map(t => `
                            <div class="event-ticket-option ${t.remaining < 1 ? 'sold-out' : ''}">
                                <div class="event-ticket-info">
                                    <b>${esc(t.name)}</b>
                                    <strong>${money(t.price)}</strong>
                                    <span>
                                        ${t.remaining > 0
                                            ? t.remaining + ' tickets remaining'
                                            : 'Sold out'}
                                    </span>
                                </div>

                                <div class="event-ticket-quantity">
                                    <label for="ticket-${t.id}">Qty</label>
                                    <input
                                        id="ticket-${t.id}"
                                        type="number"
                                        min="0"
                                        max="${Math.min(10, Math.max(0, t.remaining))}"
                                        step="1"
                                        value="0"
                                        data-id="${t.id}"
                                        data-p="${t.price}"
                                        ${t.remaining < 1 ? 'disabled' : ''}
                                    >
                                </div>
                            </div>
                        `).join('')}
                    </div>

                    <div class="event-booking-summary">
                        <div>
                            <span>Selected tickets</span>
                            <b id="event-ticket-count">0</b>
                        </div>

                        <div class="event-total">
                            <span>Total</span>
                            <strong id="tot">${money(0)}</strong>
                        </div>
                    </div>

                    <div id="msg" class="event-booking-message"></div>

                    <button class="event-book-button" id="buy">
                        Book tickets
                        <span>→</span>
                    </button>

                    <p class="event-booking-note">
                        Your booking details will be available in My Tickets.
                    </p>

                </aside>

            </div>
        </div>
    `;

    const inputs = [
        ...app.querySelectorAll('.event-ticket-quantity input')
    ];

    const totalElement = document.getElementById('tot');
    const countElement = document.getElementById('event-ticket-count');
    const buyButton = document.getElementById('buy');

    const updateTotal = () => {
        let total = 0;
        let count = 0;

        inputs.forEach(input => {
            const quantity = Math.max(
                0,
                Math.min(
                    Math.floor(Number(input.value) || 0),
                    Number(input.max) || 0
                )
            );

            input.value = quantity;

            count += quantity;
            total += quantity * Number(input.dataset.p);
        });

        totalElement.textContent = money(total);
        countElement.textContent = count;

        buyButton.disabled = count === 0;
    };

    inputs.forEach(input => {
        input.addEventListener('input', updateTotal);
        input.addEventListener('change', updateTotal);
    });

    updateTotal();

buyButton.onclick = async () => {
    const message = document.getElementById('msg');

    if (!user) {
        location.hash = '#/login';
        return;
    }

    const items = inputs.map(input => ({
        ticket_type_id: Number(input.dataset.id),
        qty: Number(input.value)
    })).filter(item => item.qty > 0);

    if (!items.length) {
        message.innerHTML = `
            <div class="err">Please select at least one ticket.</div>
        `;
        return;
    }

    buyButton.disabled = true;
    buyButton.textContent = 'Preparing payment...';

    try {
        const result = await api('/orders', {
            method: 'POST',
            body: {
                event_id: id,
                items
            }
        });

        if (!result.payment_url || !result.payment_fields) {
            throw new Error('Unable to prepare payment. Please try again.');
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = result.payment_url;

        Object.entries(result.payment_fields).forEach(([name, value]) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();

    } catch (error) {
        message.innerHTML = `
            <div class="err">${esc(error.message)}</div>
        `;

        buyButton.disabled = false;
        buyButton.innerHTML = 'Book tickets <span>→</span>';
    }
};
}



async function dashboard() {
    const events = await api('/my/events');

    const params = new URLSearchParams(location.hash.split('?')[1] || '');
    let tab = params.get('tab') || 'home';

    const name = (user?.name || 'Organiser').trim();
    const firstName = name.split(/\s+/)[0];

    const totalEvents = events.length;
    const totalSold = events.reduce((sum, e) => sum + Number(e.sold || 0), 0);
    const totalCapacity = events.reduce((sum, e) => sum + Number(e.capacity || 0), 0);
    const totalRevenue = events.reduce((sum, e) => sum + Number(e.revenue || 0), 0);
    const upcoming = events.filter(e => dt(e.starts_at) >= new Date());
    const fillRate = totalCapacity > 0
        ? Math.round((totalSold / totalCapacity) * 100)
        : 0;

    const tabs = [
        ['home', 'Home'],
        ['events', 'Events'],
        ['reports', 'Reports'],
        ['promote', 'Promote'],
        ['campaigns', 'Email campaigns']
    ];

const nav = `
    <div class="org-nav">

        <div class="org-nav-links">
            ${tabs.map(([id, label]) => `
                <a
                    href="#/dashboard?tab=${id}"
                    class="${tab === id ? 'active' : ''}"
                >${label}</a>
            `).join('')}
        </div>

        <div class="org-nav-actions">

            <a class="org-new-event" href="#/create">
                <span>+</span> New event
            </a>

            <details class="org-user-menu">
                <summary class="org-account">
                    <span class="org-user-avatar">
                        ${esc(firstName.charAt(0).toUpperCase())}
                    </span>
                    <span>${esc(firstName)}</span>
                    <span class="org-dropdown-arrow">⌄</span>
                </summary>

                <div class="org-user-dropdown">
                    <div class="org-dropdown-header">
                        <strong>${esc(name)}</strong>
                        <small>Organiser account</small>
                    </div>

                    <a href="#/account">
                        <span>Account details</span>
                    </a>

                    <a href="#/tickets">
                        <span>My tickets</span>
                    </a>

                    <a href="#/contact">
                        <span>Help & support</span>
                    </a>

                    <div class="org-dropdown-divider"></div>

                    <button type="button" data-logout>
                        Log out
                    </button>
                </div>
            </details>

        </div>
    </div>
`;

    const stats = `
        <div class="org-stats">
            <div class="org-stat">
                <span class="org-stat-label">Total events</span>
                <strong>${totalEvents}</strong>
                <small>${upcoming.length} upcoming</small>
            </div>

            <div class="org-stat">
                <span class="org-stat-label">Tickets sold</span>
                <strong>${totalSold.toLocaleString('en-ZA')}</strong>
                <small>Across your events</small>
            </div>

            <div class="org-stat">
                <span class="org-stat-label">Total revenue</span>
                <strong>${money(totalRevenue)}</strong>
                <small>Recorded ticket revenue</small>
            </div>

            <div class="org-stat">
                <span class="org-stat-label">Capacity filled</span>
                <strong>${fillRate}%</strong>
                <small>${totalSold} of ${totalCapacity} tickets</small>
            </div>
        </div>
    `;

    const eventRows = events.length
        ? events.map(e => `
            <tr>
                <td>
                    <div class="org-event-name">
                        <b>${esc(e.title)}</b>
                        <small>${esc(e.venue || 'Venue not specified')}${e.city ? ' · ' + esc(e.city) : ''}</small>
                    </div>
                </td>

                <td>${when(e.starts_at)}</td>
                <td>${Number(e.sold || 0)} / ${Number(e.capacity || 0)}</td>
                <td>${money(e.revenue || 0)}</td>

                <td>
                    <a class="org-table-link" href="#/event/${e.id}">
                        View event →
                    </a>
                </td>
            </tr>
        `).join('')
        : `<tr><td colspan="5" class="org-empty">You haven't created an event yet.</td></tr>`;

    const eventTable = `
        <div class="org-table-wrap">
            <table class="org-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Tickets sold</th>
                        <th>Revenue</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>${eventRows}</tbody>
            </table>
        </div>
    `;

    const emptyState = `
        <div class="org-empty-state">
            <div class="org-empty-icon">+</div>
            <h3>Your first event starts here</h3>
            <p>Create an event, add your ticket types and share it with your audience.</p>
            <a class="org-new-event" href="#/create">Create your first event</a>
        </div>
    `;

    const homeContent = `
        <section class="org-welcome">
            <div>
                <span class="org-eyebrow">YOUR ORGANISER SPACE</span>
                <h1>Welcome, ${esc(firstName)}. We're glad you're here.</h1>
                <p>Start creating experiences people will remember.</p>
            </div>
        </section>

        ${stats}

        <section class="org-setup">
            <div class="org-setup-image"></div>

            <div class="org-setup-content">
                <span class="org-eyebrow">LET'S GET YOU STARTED</span>
                <h2>Make your first event happen.</h2>

                <div class="org-step">
                    <span class="org-step-number">1</span>
                    <div>
                        <b>Draft the basics</b>
                        <p>Tell attendees when, where and what your event is about.</p>
                    </div>
                </div>

                <div class="org-step">
                    <span class="org-step-number">2</span>
                    <div>
                        <b>Set up your tickets</b>
                        <p>Choose ticket names, prices and how many are available.</p>
                    </div>
                </div>

                <div class="org-step">
                    <span class="org-step-number">3</span>
                    <div>
                        <b>Publish and share</b>
                        <p>Make your event live and invite people to join you.</p>
                    </div>
                </div>

                <a class="org-new-event org-setup-button" href="#/create">
                    Create event
                </a>
            </div>
        </section>

        <section class="org-section">
            <div class="org-section-heading">
                <div>
                    <span class="org-eyebrow">YOUR ACTIVITY</span>
                    <h2>Recent events</h2>
                </div>
                <a href="#/dashboard?tab=events">View all events →</a>
            </div>

            ${events.length ? eventTable : emptyState}
        </section>

        <section class="org-quick-actions">
            <a href="#/create">
                <span>+</span>
                <div><b>Create an event</b><small>Start something new</small></div>
            </a>

            <a href="#/dashboard?tab=reports">
                <span>↗</span>
                <div><b>View reports</b><small>Understand your sales</small></div>
            </a>

            <a href="#/dashboard?tab=promote">
                <span>↗</span>
                <div><b>Promote events</b><small>Reach more people</small></div>
            </a>
        </section>
    `;

    const eventsContent = `
        <section class="org-page-heading">
            <div>
                <span class="org-eyebrow">EVENT MANAGEMENT</span>
                <h1>Your events</h1>
                <p>Create, view and keep track of everything you're hosting.</p>
            </div>

            <a class="org-new-event" href="#/create">+ New event</a>
        </section>

        ${stats}

        <section class="org-section">
            <div class="org-section-heading">
                <div>
                    <h2>All events</h2>
                    <p>${totalEvents} events in your organiser account</p>
                </div>
            </div>

            ${events.length ? eventTable : emptyState}
        </section>
    `;

    const reportsContent = `
        <section class="org-page-heading">
            <div>
                <span class="org-eyebrow">PERFORMANCE</span>
                <h1>Your reports</h1>
                <p>A snapshot of how your events are performing.</p>
            </div>
        </section>

        ${stats}

        <section class="org-section">
            <div class="org-section-heading">
                <div>
                    <h2>Event performance</h2>
                    <p>Ticket sales and revenue by event.</p>
                </div>
            </div>

            ${eventTable}
        </section>

        <section class="org-report-grid">
            <div class="org-report-card">
                <span>Average tickets per event</span>
                <strong>${totalEvents ? Math.round(totalSold / totalEvents) : 0}</strong>
                <small>Based on all your events</small>
            </div>

            <div class="org-report-card">
                <span>Average revenue per event</span>
                <strong>${money(totalEvents ? totalRevenue / totalEvents : 0)}</strong>
                <small>Based on all your events</small>
            </div>

            <div class="org-report-card">
                <span>Remaining ticket capacity</span>
                <strong>${Math.max(0, totalCapacity - totalSold)}</strong>
                <small>Across all your events</small>
            </div>
        </section>
    `;

    const promoteContent = `
        <section class="org-page-heading">
            <div>
                <span class="org-eyebrow">GET THE WORD OUT</span>
                <h1>Promote your events</h1>
                <p>Share your event pages and make it easier for people to find you.</p>
            </div>
        </section>

        <div class="org-promo-intro">
            <div>
                <h2>Great events deserve an audience.</h2>
                <p>Copy an event link and share it on your website, social media or with your community.</p>
            </div>
            <div class="org-promo-mark">EB</div>
        </div>

        <section class="org-section">
            <div class="org-section-heading">
                <div>
                    <h2>Choose an event to share</h2>
                    <p>Your event links are ready to copy.</p>
                </div>
            </div>

            ${events.length ? `
                <div class="org-share-list">
                    ${events.map(e => `
                        <div class="org-share-item">
                            <div>
                                <b>${esc(e.title)}</b>
                                <small>${when(e.starts_at)} · ${esc(e.city || '')}</small>
                            </div>

                            <button
                                class="org-copy-button"
                                type="button"
                                data-copy="${location.origin}/#/event/${e.id}"
                            >Copy event link</button>
                        </div>
                    `).join('')}
                </div>
            ` : emptyState}
        </section>
    `;

    const campaignsContent = `
        <section class="org-page-heading">
            <div>
                <span class="org-eyebrow">AUDIENCE & OUTREACH</span>
                <h1>Email campaigns</h1>
                <p>Prepare an announcement for your event audience.</p>
            </div>
        </section>

        <div class="org-campaign-notice">
            <b>Campaign preparation</b>
            <p>You can draft an announcement here and open it in your email application. Bulk email delivery and subscriber management aren't connected yet.</p>
        </div>

        <section class="org-campaign-layout">
            <form class="org-campaign-form" id="campaignForm">
                <h2>Draft an announcement</h2>

                <label>
                    Choose an event
                    <select name="event_id" required>
                        <option value="">Select an event</option>
                        ${events.map(e => `<option value="${e.id}">${esc(e.title)}</option>`).join('')}
                    </select>
                </label>

                <label>
                    Email subject
                    <input name="subject" placeholder="Something exciting is coming..." required>
                </label>

                <label>
                    Message
                    <textarea name="message" rows="7" placeholder="Tell people why they should attend..." required></textarea>
                </label>

                <button class="org-new-event" type="submit">
                    Prepare email
                </button>

                <div id="campaignMessage"></div>
            </form>

            <div class="org-campaign-aside">
                <span class="org-eyebrow">A FEW IDEAS</span>
                <h3>What could you send?</h3>
                <div><b>Event announcement</b><p>Introduce your event and let people know tickets are available.</p></div>
                <div><b>Last chance reminder</b><p>Remind your audience when an event is coming up.</p></div>
                <div><b>Thank you message</b><p>Thank attendees for being part of your event.</p></div>
            </div>
        </section>
    `;

    const content = {
        home: homeContent,
        events: eventsContent,
        reports: reportsContent,
        promote: promoteContent,
        campaigns: campaignsContent
    };

    if (!content[tab]) tab = 'home';

    app.innerHTML = `
        <div class="organiser-dashboard">
            ${nav}
            <main class="org-main">
                ${content[tab]}
            </main>
            <footer class="org-footer">
                <span>EasyBooking Organiser</span>
                <a href="#/">Back to public events</a>
            </footer>
        </div>
    `;

    app.querySelectorAll('[data-copy]').forEach(button => {
        button.onclick = async () => {
            const value = button.dataset.copy;

            try {
                await navigator.clipboard.writeText(value);
                button.textContent = 'Copied!';
            } catch {
                window.prompt('Copy this event link:', value);
            }
        };
    });

    const campaignForm = document.getElementById('campaignForm');

    if (campaignForm) {
        campaignForm.onsubmit = e => {
            e.preventDefault();

            const form = Object.fromEntries(new FormData(campaignForm));
            const selectedEvent = events.find(
                event => String(event.id) === String(form.event_id)
            );

            if (!selectedEvent) return;

            const eventUrl = `${location.origin}/#/event/${selectedEvent.id}`;

            const body = `${form.message}

${selectedEvent.title}
${eventUrl}

Sent via EasyBooking`;

            const mailto = `mailto:?subject=${encodeURIComponent(form.subject)}&body=${encodeURIComponent(body)}`;

            document.getElementById('campaignMessage').innerHTML = `
                <p class="org-campaign-success">
                    Your email draft is ready. Your email application should open now.
                </p>
            `;

            location.href = mailto;
        };
    }
}


async function tickets() {
    const t = await api('/my/tickets');

    app.innerHTML = `
        <div class="tickets-page">

            <div class="tickets-header">
                <div>
                    <span class="tickets-eyebrow">YOUR EASYBOOKING WALLET</span>
                    <h1>My tickets<span>.</span></h1>
                    <p>All your event bookings, together in one place.</p>
                </div>

                <div class="tickets-count">
                    <strong>${t.length}</strong>
                    <span>${t.length === 1 ? 'Ticket' : 'Tickets'} booked</span>
                </div>
            </div>

            ${t.length ? `
                <div class="tickets-list">
                    ${t.map((k, index) => `
                        <article class="digital-ticket">

                            <div class="digital-ticket-main">
                                <div class="digital-ticket-top">
                                    <span class="ticket-number">
                                        BOOKING ${String(index + 1).padStart(2, '0')}
                                    </span>
                                    <span class="ticket-status">
                                        CONFIRMED
                                    </span>
                                </div>

                                <h2>${esc(k.title)}</h2>

                                <div class="digital-ticket-details">
                                    <div>
                                        <span>DATE & TIME</span>
                                        <b>${when(k.starts_at)}</b>
                                    </div>

                                    <div>
                                        <span>TICKET TYPE</span>
                                        <b>${esc(k.ticket)}</b>
                                    </div>

                                    <div>
                                        <span>VENUE</span>
                                        <b>${esc(k.venue || 'To be announced')}${k.city ? ', ' + esc(k.city) : ''}</b>
                                    </div>
                                </div>
                            </div>

                            <div class="digital-ticket-code">
                                <div class="ticket-code-inner">
                                    <span>YOUR TICKET CODE</span>
                                    <strong>${esc(k.code)}</strong>
                                    <div class="ticket-code-decoration"></div>
                                    <small>Keep this code safe</small>
                                </div>
                            </div>

                        </article>
                    `).join('')}
                </div>
            ` : `
                <div class="tickets-empty">
                    <div class="empty-ticket-icon">
                        <span></span>
                    </div>

                    <span class="tickets-eyebrow">NOTHING HERE YET</span>
                    <h2>Your next experience awaits.</h2>
                    <p>
                        You haven't booked any tickets yet.
                        Discover something exciting and your tickets will appear here.
                    </p>
                    <a href="#/" class="tickets-explore-button">
                        Explore events <span>→</span>
                    </a>
                </div>
            `}

        </div>
    `;
}


function create() {
    const steps = [
        ['information', 'Event information', 'Name, category, location and date'],
        ['content', 'Page content', 'Tell people what your event is about'],
        ['tickets', 'Ticket types', 'Set your ticket prices and quantities'],
        ['review', 'Review & publish', 'Check everything before going live']
    ];

    let currentStep = 0;

    app.innerHTML = `
        <div class="create-event-page">

            <aside class="create-sidebar">
                <div class="create-sidebar-heading">
                    <div>
                        <span class="create-sidebar-eyebrow">EVENT BUILDER</span>
                        <h2>Create event</h2>
                    </div>
                    <a href="#/dashboard" class="create-close" title="Back to dashboard">×</a>
                </div>

                <div class="create-step-list">
                    ${steps.map((step, index) => `
                        <button
                            type="button"
                            class="create-step ${index === 0 ? 'active' : ''}"
                            data-step="${index}"
                        >
                            <span class="create-step-number">${index + 1}</span>
                            <span class="create-step-copy">
                                <b>${step[1]}</b>
                                <small>${step[2]}</small>
                            </span>
                            <span class="create-step-check">✓</span>
                        </button>
                    `).join('')}
                </div>

                <div class="create-sidebar-bottom">
                    <div class="create-help-card">
                        <span>NEED A HAND?</span>
                        <b>We're here to help.</b>
                        <p>Questions about setting up your event?</p>
                        <a href="#/contact">Contact support →</a>
                    </div>

                    <a href="#/dashboard" class="create-back-link">
                        ← Back to dashboard
                    </a>
                </div>
            </aside>

            <main class="create-main">
                <div class="create-main-top">
                    <div>
                        <span class="create-eyebrow">YOUR NEXT BIG THING STARTS HERE</span>
                        <h1>Let's create something.</h1>
                        <p>Take it one step at a time. You can review everything before publishing.</p>
                    </div>

                    <span class="create-draft-label">
                        <span></span> New event
                    </span>
                </div>

                <div class="create-progress">
                    <div class="create-progress-track">
                        <span id="createProgressBar"></span>
                    </div>
                    <small id="createProgressText">STEP 1 OF 4</small>
                </div>

                <form id="createEventForm" class="create-form">

                    <!-- STEP 1: EVENT INFORMATION -->
                    <section class="create-section active" id="create-information">
                        <div class="create-section-heading">
                            <div>
                                <span class="create-section-number">01 / THE BASICS</span>
                                <h2>Tell us about your event.</h2>
                                <p>Start with the details people need to know.</p>
                            </div>
                            <div class="create-section-icon">01</div>
                        </div>

                        <div class="create-fields">
                            <label class="create-field full">
                                <span>Event name <i>*</i></span>
                                <input
                                    name="title"
                                    type="text"
                                    placeholder="e.g. Cape Town Summer Festival"
                                    maxlength="150"
                                    required
                                >
                                <small>Give your event a name people will remember.</small>
                            </label>

                            <label class="create-field">
                                <span>Event category <i>*</i></span>
                                <select name="category" required>
                                    ${Object.keys(PH).map(c => `
                                        <option value="${esc(c)}">${esc(c)}</option>
                                    `).join('')}
                                </select>
                            </label>

                            <label class="create-field">
                                <span>Event date and time <i>*</i></span>
                                <input name="starts_at" type="datetime-local" required>
                            </label>

                            <label class="create-field">
                                <span>Venue</span>
                                <input
                                    name="venue"
                                    type="text"
                                    placeholder="e.g. The Old Biscuit Mill"
                                >
                            </label>

                            <label class="create-field">
                                <span>City</span>
                                <input
                                    name="city"
                                    type="text"
                                    value="Cape Town"
                                    placeholder="e.g. Cape Town"
                                >
                            </label>
                        </div>

                        <div class="create-tip">
                            <span>TIP</span>
                            <p>Make sure your event date and location are accurate. Attendees use these details to plan their visit.</p>
                        </div>
                    </section>

                    <!-- STEP 2: PAGE CONTENT -->
                    <section class="create-section" id="create-content">
                        <div class="create-section-heading">
                            <div>
                                <span class="create-section-number">02 / THE STORY</span>
                                <h2>Give people a reason to come.</h2>
                                <p>Tell your audience what makes this event worth attending.</p>
                            </div>
                            <div class="create-section-icon">02</div>
                        </div>

                        <label class="create-field full">
                            <span>Event description</span>
                            <textarea
                                name="description"
                                rows="8"
                                maxlength="5000"
                                placeholder="What's happening? Who is it for? What can attendees look forward to?"
                            ></textarea>
                            <small>Include useful details about the experience, activities and what attendees can expect.</small>
                        </label>

                        <div class="create-description-tips">
                            <h3>What makes a great description?</h3>
                            <div>
                                <span>01</span>
                                <p><b>Set the scene</b> Explain what your event is about.</p>
                            </div>
                            <div>
                                <span>02</span>
                                <p><b>Share the highlights</b> Tell people what they'll experience.</p>
                            </div>
                            <div>
                                <span>03</span>
                                <p><b>Make it personal</b> Give people a reason to be there.</p>
                            </div>
                        </div>
                    </section>

                    <!-- STEP 3: TICKETS -->
                    <section class="create-section" id="create-tickets">
                        <div class="create-section-heading">
                            <div>
                                <span class="create-section-number">03 / THE TICKETS</span>
                                <h2>Let's talk tickets.</h2>
                                <p>Choose what attendees can book and how many are available.</p>
                            </div>
                            <div class="create-section-icon">03</div>
                        </div>

                        <div class="create-ticket-intro">
                            <div>
                                <b>Your ticket types</b>
                                <p>You can offer free admission or set your own prices.</p>
                            </div>

                            <button type="button" class="create-add-ticket" id="addTicket">
                                + Add ticket type
                            </button>
                        </div>

                        <div id="createTicketList"></div>

                        <div class="create-ticket-summary">
                            <div>
                                <small>TOTAL TICKET CAPACITY</small>
                                <strong id="createCapacity">0</strong>
                            </div>
                            <div>
                                <small>TICKET TYPES</small>
                                <strong id="createTicketCount">0</strong>
                            </div>
                        </div>

                        <div class="create-tip">
                            <span>TIP</span>
                            <p>Use different ticket types for general admission, VIP access or early-bird offers.</p>
                        </div>
                    </section>

                    <!-- STEP 4: REVIEW -->
                    <section class="create-section" id="create-review">
                        <div class="create-section-heading">
                            <div>
                                <span class="create-section-number">04 / ALMOST THERE</span>
                                <h2>Looking good?</h2>
                                <p>Give your event one last check before publishing.</p>
                            </div>
                            <div class="create-section-icon">04</div>
                        </div>

                        <div class="create-review-card">
                            <div class="create-review-banner">
                                <span id="reviewCategory">EVENT PREVIEW</span>
                                <h3 id="reviewTitle">Your event name</h3>
                            </div>

                            <div class="create-review-details">
                                <div>
                                    <small>DATE & TIME</small>
                                    <b id="reviewDate">Not set</b>
                                </div>
                                <div>
                                    <small>LOCATION</small>
                                    <b id="reviewLocation">Not set</b>
                                </div>
                                <div>
                                    <small>CATEGORY</small>
                                    <b id="reviewCategoryText">Not set</b>
                                </div>
                            </div>

                            <div class="create-review-description">
                                <small>ABOUT THIS EVENT</small>
                                <p id="reviewDescription">Your event description will appear here.</p>
                            </div>

                            <div class="create-review-tickets">
                                <small>YOUR TICKETS</small>
                                <div id="reviewTickets"></div>
                            </div>
                        </div>

                        <div class="create-publish-note">
                            <b>Ready to go live?</b>
                            <p>Publishing will create your event and make it available on EasyBooking for attendees to discover.</p>
                        </div>
                    </section>

                    <div id="createMessage"></div>

                    <div class="create-form-footer">
                        <button type="button" id="createPrevious" class="create-previous" disabled>
                            ← Back
                        </button>

                        <div>
                            <span id="createFooterStep">Step 1 of 4</span>
                            <button type="button" id="createContinue" class="create-continue">
                                Continue →
                            </button>
                            <button type="submit" id="createPublish" class="create-publish" hidden>
                                Publish event →
                            </button>
                        </div>
                    </div>

                </form>
            </main>
        </div>
    `;

    const form = document.getElementById('createEventForm');
    const ticketList = document.getElementById('createTicketList');
    const message = document.getElementById('createMessage');

    const sections = [...app.querySelectorAll('.create-section')];
    const stepButtons = [...app.querySelectorAll('.create-step')];

    const previous = document.getElementById('createPrevious');
    const next = document.getElementById('createContinue');
    const publish = document.getElementById('createPublish');

    function addTicket() {
        const row = document.createElement('div');
        row.className = 'create-ticket-row';

        row.innerHTML = `
            <div class="create-ticket-index"></div>

            <label>
                <span>Ticket name</span>
                <input
                    type="text"
                    class="create-ticket-name"
                    placeholder="e.g. General admission"
                    required
                >
            </label>

            <label>
                <span>Price (R)</span>
                <input
                    type="number"
                    class="create-ticket-price"
                    min="0"
                    step="0.01"
                    placeholder="0.00"
                    required
                >
            </label>

            <label>
                <span>Quantity</span>
                <input
                    type="number"
                    class="create-ticket-quantity"
                    min="1"
                    step="1"
                    placeholder="100"
                    required
                >
            </label>

            <button type="button" class="create-remove-ticket" title="Remove ticket type">
                ×
            </button>
        `;

        ticketList.appendChild(row);

        row.querySelector('.create-remove-ticket').onclick = () => {
            if (ticketList.children.length <= 1) {
                message.innerHTML = '<div class="err">You need at least one ticket type.</div>';
                return;
            }

            row.remove();
            updateTicketSummary();
        };

        row.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', updateTicketSummary);
        });

        updateTicketSummary();
    }

    function updateTicketSummary() {
        const rows = [...ticketList.children];

        let capacity = 0;

        rows.forEach((row, index) => {
            row.querySelector('.create-ticket-index').textContent =
                String(index + 1).padStart(2, '0');

            capacity += Math.max(
                0,
                Number(row.querySelector('.create-ticket-quantity').value || 0)
            );
        });

        document.getElementById('createCapacity').textContent =
            capacity.toLocaleString('en-ZA');

        document.getElementById('createTicketCount').textContent =
            rows.length;
    }

    function updateReview() {
        const data = Object.fromEntries(new FormData(form));

        document.getElementById('reviewTitle').textContent =
            data.title || 'Your event name';

        document.getElementById('reviewCategory').textContent =
            (data.category || 'EVENT') + ' EVENT';

        document.getElementById('reviewCategoryText').textContent =
            data.category || 'Not set';

        document.getElementById('reviewDate').textContent =
            data.starts_at
                ? new Date(data.starts_at).toLocaleString('en-ZA', {
                    dateStyle: 'medium',
                    timeStyle: 'short'
                })
                : 'Not set';

        document.getElementById('reviewLocation').textContent =
            [data.venue, data.city].filter(Boolean).join(', ') || 'Not set';

        document.getElementById('reviewDescription').textContent =
            data.description || 'No description added yet.';

        document.getElementById('reviewTickets').innerHTML =
            [...ticketList.children].map(row => {
                const name = row.querySelector('.create-ticket-name').value;
                const price = row.querySelector('.create-ticket-price').value;
                const quantity = row.querySelector('.create-ticket-quantity').value;

                return `
                    <div class="create-review-ticket">
                        <b>${esc(name || 'Unnamed ticket')}</b>
                        <span>${money(price || 0)}</span>
                        <small>${esc(quantity || '0')} available</small>
                    </div>
                `;
            }).join('');
    }

    function showStep(index) {
        currentStep = Math.max(0, Math.min(index, steps.length - 1));

        sections.forEach((section, i) => {
            section.classList.toggle('active', i === currentStep);
        });

        stepButtons.forEach((button, i) => {
            button.classList.toggle('active', i === currentStep);
            button.classList.toggle('completed', i < currentStep);
        });

        document.getElementById('createProgressBar').style.width =
            `${((currentStep + 1) / steps.length) * 100}%`;

        document.getElementById('createProgressText').textContent =
            `STEP ${currentStep + 1} OF ${steps.length}`;

        document.getElementById('createFooterStep').textContent =
            `Step ${currentStep + 1} of ${steps.length}`;

        previous.disabled = currentStep === 0;
        next.hidden = currentStep === steps.length - 1;
        publish.hidden = currentStep !== steps.length - 1;

        if (currentStep === 3) updateReview();

        message.innerHTML = '';

        document.querySelector('.create-main').scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function validateCurrentStep() {
        const currentSection = sections[currentStep];
        const fields = [...currentSection.querySelectorAll('input, select, textarea')];

        for (const field of fields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return false;
            }
        }

        return true;
    }

    document.getElementById('addTicket').onclick = addTicket;

    next.onclick = () => {
        if (!validateCurrentStep()) return;
        showStep(currentStep + 1);
    };

    previous.onclick = () => {
        showStep(currentStep - 1);
    };

    stepButtons.forEach(button => {
        button.onclick = () => {
            const target = Number(button.dataset.step);

            if (target > currentStep && !validateCurrentStep()) return;

            showStep(target);
        };
    });

    form.addEventListener('input', () => {
        if (currentStep === 3) updateReview();
    });

    addTicket();

    form.onsubmit = async event => {
        event.preventDefault();

        if (!validateCurrentStep()) return;

        const button = publish;
        button.disabled = true;
        button.textContent = 'Publishing...';
        message.innerHTML = '';

        const data = Object.fromEntries(new FormData(form));

        data.starts_at = data.starts_at.replace('T', ' ') + ':00';

        data.tickets = [...ticketList.children].map(row => ({
            name: row.querySelector('.create-ticket-name').value,
            price: row.querySelector('.create-ticket-price').value,
            quantity: row.querySelector('.create-ticket-quantity').value
        }));

        try {
            const result = await api('/events', {
                method: 'POST',
                body: data
            });

            ALL = null;
            location.hash = '#/event/' + result.id;

        } catch (error) {
            message.innerHTML = `
                <div class="err">${esc(error.message)}</div>
            `;

            button.disabled = false;
            button.textContent = 'Publish event →';
        }
    };

    showStep(0);
}


const phead = (t, s) => `<div class="phead"><h1>${t}</h1><p>${s}</p></div>`;


async function about() {
    let st = null;

    try {
        st = await api('/stats');
    } catch {}

    const im = (id, className = '') => `
        <img
            class="about-image ${className}"
            src="${U(id)}"
            alt=""
            loading="lazy"
            onerror="this.style.visibility='hidden'"
        >
    `;

    const n = x => Number(x).toLocaleString('en-ZA');

    app.innerHTML = `
        <div class="about-page">

            <!-- HERO -->
            <section class="about-hero">
                <div class="about-hero-content">
                    <span class="about-eyebrow">A LITTLE ABOUT US</span>

                    <h1>
                        Bringing people
                        <span>together.</span>
                    </h1>

                    <p>
                        Great events create great memories.
                        We're here to make finding them, hosting them
                        and being part of them a whole lot easier.
                    </p>

                    <a href="#/" class="about-primary-link">
                        Explore events <span>→</span>
                    </a>

                    <div class="about-hero-caption">
                        <span></span>
                        Built in Cape Town, South Africa
                    </div>
                </div>

                <div class="about-hero-visual">
                    ${im('1527529482837-4698179dc6ce', 'about-hero-photo')}

                    <div class="about-image-note">
                        <span>MORE THAN TICKETS</span>
                        <b>It's about being there.</b>
                    </div>

                    <div class="about-floating-card">
                        <span class="about-floating-mark">E</span>
                        <div>
                            <b>Find your people.</b>
                            <small>Find your next experience.</small>
                        </div>
                    </div>
                </div>
            </section>

            <!-- MISSION -->
            <section class="about-mission">
                <div class="about-mission-label">
                    <span class="about-eyebrow">WHY WE EXIST</span>
                    <span class="about-mission-line"></span>
                </div>

                <h2>
                    We believe the best moments happen
                    <span>when people come together.</span>
                </h2>

                <p>
                    Our mission is to make hosting and attending events simple,
                    so more people come together and fewer great events go unnoticed.
                </p>
            </section>

            <!-- STORY -->
            <section class="about-story">
                <div class="about-story-visual">
                    ${im('1528605248644-14dd04022da1', 'about-story-photo')}

                    <div class="about-story-stamp">
                        <span>MADE WITH</span>
                        <b>Community<br>in mind.</b>
                    </div>
                </div>

                <div class="about-story-content">
                    <span class="about-eyebrow">HOW IT STARTED</span>
                    <h2>Our story<span>.</span></h2>

                    <p>
                        Every community has events worth showing up for:
                        a Sunday jazz picnic, a beach clean-up, a founders'
                        mixer, a trail run.
                    </p>

                    <p>
                        Yet too many hosts still juggle WhatsApp groups,
                        spreadsheets and cash at the door, and too many
                        people never hear about what's happening ten
                        minutes from home.
                    </p>

                    <p>
                        EasyBooking began as a fix for that. One place
                        where hosts can publish an event and sell tickets
                        in minutes, and where guests can discover what's on,
                        book in a few taps and keep every ticket safe in
                        their account.
                    </p>

                    <p>
                        We're a small, independent team based in Cape Town,
                        and we build with local hosts and venues in mind,
                        from backyard gigs to full festivals.
                    </p>
                </div>
            </section>

            <!-- STATS -->
            ${st ? `
                <section class="about-stats">
                    <div class="about-stats-heading">
                        <span class="about-eyebrow">THE COMMUNITY SO FAR</span>
                        <h2>Growing, one event at a time.</h2>
                    </div>

                    <div class="about-stats-grid">
                        <div>
                            <strong>${n(st.events)}</strong>
                            <span>Events listed</span>
                        </div>

                        <div>
                            <strong>${n(st.tickets)}</strong>
                            <span>Tickets booked</span>
                        </div>

                        <div>
                            <strong>${n(st.members)}</strong>
                            <span>Community members</span>
                        </div>

                        <div>
                            <strong>${n(st.cities)}</strong>
                            <span>${st.cities == 1 ? 'City' : 'Cities'}</span>
                        </div>
                    </div>
                </section>
            ` : ''}

            <!-- WHAT WE DO -->
            <section class="about-offer">
                <div class="about-section-heading">
                    <span class="about-eyebrow">ONE PLATFORM, TWO SIDES</span>
                    <h2>Made for the people<br>who make events happen.</h2>
                    <p>
                        Whether you're planning an event or looking for
                        something to do, we've got a place for you.
                    </p>
                </div>

                <div class="about-offer-grid">
                    <article class="about-offer-card about-guests">
                        ${im('1511795409834-ef04bbd61622', 'about-offer-image')}

                        <div class="about-offer-card-content">
                            <span>01 / FOR GUESTS</span>
                            <h3>Find something<br>worth showing up for.</h3>

                            <ul>
                                <li>Discover events by category, place and date</li>
                                <li>Book tickets in just a few steps</li>
                                <li>Get a unique code for every ticket</li>
                                <li>Keep all your bookings in one place</li>
                            </ul>

                            <a href="#/">Explore events <span>→</span></a>
                        </div>
                    </article>

                    <article class="about-offer-card about-hosts">
                        ${im('1517248135467-4c7edcad34c4', 'about-offer-image')}

                        <div class="about-offer-card-content">
                            <span>02 / FOR HOSTS</span>
                            <h3>Bring your idea<br>to life.</h3>

                            <ul>
                                <li>Create and publish events</li>
                                <li>Offer different ticket types</li>
                                <li>Manage ticket availability</li>
                                <li>Track sales from your dashboard</li>
                            </ul>

                            <a href="#/create">Host an event <span>→</span></a>
                        </div>
                    </article>
                </div>
            </section>

            <!-- HOW IT WORKS -->
            <section class="about-how">
                <div class="about-section-heading">
                    <span class="about-eyebrow">NO COMPLICATED STEPS</span>
                    <h2>From discovery<br>to the big day.</h2>
                </div>

                <div class="about-how-grid">
                    <article>
                        <span class="about-step-number">01</span>
                        <h3>Discover</h3>
                        <p>Browse what's happening near you or search for something specific.</p>
                    </article>

                    <article>
                        <span class="about-step-number">02</span>
                        <h3>Book</h3>
                        <p>Choose your tickets and confirm your booking in a few simple steps.</p>
                    </article>

                    <article>
                        <span class="about-step-number">03</span>
                        <h3>Show up</h3>
                        <p>Your ticket codes live in your account, ready when you arrive.</p>
                    </article>
                </div>
            </section>

            <!-- VALUES -->
            <section class="about-values">
                <div class="about-values-heading">
                    <span class="about-eyebrow">WHAT MATTERS TO US</span>
                    <h2>Our values, in everything we build.</h2>
                </div>

                <div class="about-values-grid">
                    <article>
                        <span>01</span>
                        <h3>Simple by default</h3>
                        <p>If something takes more than a few taps, we haven't finished designing it.</p>
                    </article>

                    <article>
                        <span>02</span>
                        <h3>Honest with everyone</h3>
                        <p>Clear prices, real availability and no surprises for guests or hosts.</p>
                    </article>

                    <article>
                        <span>03</span>
                        <h3>Local first</h3>
                        <p>Strong communities start with strong local events, and we want to back them.</p>
                    </article>

                    <article>
                        <span>04</span>
                        <h3>Open to all</h3>
                        <p>Free events are welcome, small hosts are welcome, and everyone should feel at home here.</p>
                    </article>
                </div>
            </section>

            <!-- FOUNDER -->
            <section class="about-founder">
                <div class="about-founder-mark">“</div>

                <div class="about-founder-content">
                    <span class="about-eyebrow">A NOTE FROM OUR FOUNDER</span>

                    <blockquote>
                        I built EasyBooking because organising an event
                        should feel exciting, not exhausting. If you host,
                        we want you spending your time on the experience,
                        not the admin. If you're coming along, we want
                        getting your ticket to be the easiest part of
                        your day. Thank you for being part of it.
                    </blockquote>

                    <div class="about-founder-person">
                        <span class="about-founder-avatar">
                            ${esc(ABOUT.founderName.trim().charAt(0).toUpperCase() || 'E')}
                        </span>

                        <div>
                            <b>${esc(ABOUT.founderName)}</b>
                            <span>${esc(ABOUT.founderRole)}</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="about-final-cta">
                <div>
                    <span class="about-eyebrow">YOUR NEXT EXPERIENCE STARTS HERE</span>
                    <h2>There's a whole world<br>of events waiting.</h2>
                    <p>
                        Find your next event, or put your own in front
                        of the right people.
                    </p>
                </div>

                <div class="about-final-actions">
                    <a class="about-cta-primary" href="#/">Find events <span>→</span></a>
                    <a class="about-cta-secondary" href="#/create">Host an event</a>
                    <a class="about-cta-contact" href="#/contact">Have a question? Get in touch.</a>
                </div>
            </section>

        </div>
    `;
}


const PERKS = [
    ['🌍', 'Work from anywhere', 'Remote-friendly, with a Cape Town base for team days.'],
    ['⏰', 'Flexible hours', 'We care about the work you do, not when you log on.'],
    ['📚', 'Room to grow', 'Learning time and mentoring are part of every role.'],
    ['🎟️', 'Free event tickets', 'Come along to the events you help bring to life.'],
    ['🚀', 'Small team, real impact', 'Your ideas ship in days, not quarters.'],
    ['💬', 'Open and kind', 'Honest feedback, no ego and plenty of laughs.']
];

const ROLES = [{
        title: 'Full-stack Developer',
        team: 'Engineering',
        loc: 'Remote / Cape Town',
        type: 'Full-time',
        about: 'Help us build the booking experience thousands of people will use every week. You will work across the whole product, from the Node.js and MySQL backend to the interface guests and hosts see.',
        do: ['Build and ship new features end to end', 'Keep bookings fast, reliable and safe from overselling', 'Improve performance, accessibility and mobile experience', 'Work closely with hosts to turn feedback into product'],
        need: ['Strong JavaScript, HTML and CSS', 'Experience with Node.js and a SQL database such as MySQL', 'A habit of writing clear, simple, maintainable code', 'Care about the details people notice when something just works']
    },
    {
        title: 'Product Designer',
        team: 'Design',
        loc: 'Remote',
        type: 'Contract',
        about: 'Shape how EasyBooking looks and feels, from the first search to the ticket in your hand. You will design clean, friendly screens that work beautifully on a phone.',
        do: ['Design flows for discovering, booking and hosting events', 'Build and maintain our visual style and components', 'Run quick tests with real hosts and guests', 'Hand over designs that developers love to build'],
        need: ['A portfolio showing thoughtful, mobile-first work', 'Fluency in Figma or a similar tool', 'A good eye for typography, spacing and colour', 'Comfort explaining your design decisions']
    },
    {
        title: 'Community & Partnerships Manager',
        team: 'Community',
        loc: 'Cape Town',
        type: 'Full-time',
        about: 'Be the face of EasyBooking to venues, promoters and local organisers. You will bring great events onto the platform and help hosts get the most from it.',
        do: ['Find and onboard venues, promoters and community groups', 'Help new hosts set up their first event', 'Build relationships across the local events scene', 'Share what you hear with the team so we improve'],
        need: ['Strong communication and relationship-building skills', 'Knowledge of the Cape Town events and hospitality scene', 'Self-driven, organised and happy to be out and about', 'Enthusiasm for helping others succeed']
    },
    {
        title: 'Customer Support Specialist',
        team: 'Support',
        loc: 'Remote',
        type: 'Part-time',
        about: 'Make sure every guest and host feels looked after. You will answer questions, solve problems and turn tricky moments into happy ones.',
        do: ['Reply to guest and host questions quickly and kindly', 'Solve booking and ticket issues end to end', 'Spot patterns and suggest fixes to the product', 'Keep our help content clear and up to date'],
        need: ['Excellent written English and a warm tone', 'Patience, empathy and attention to detail', 'Comfort with web apps and learning new tools', 'Reliable, organised and calm under pressure']
    }
];
function careers() {
    const teams = ['All', ...new Set(ROLES.map(r => r.team))];
    let sel = 'All';

    const mail = r =>
        `mailto:${CONTACT_EMAIL}?subject=${encodeURIComponent('Application: ' + r)}`;

    const ph = (id, cls = '') => `
        <img
            class="${cls}"
            src="${U(id)}"
            alt=""
            loading="lazy"
            onerror="this.style.visibility='hidden'"
        >
    `;

    app.innerHTML = `
        <div class="careers-page">

            <section class="careers-hero">
                <div class="careers-hero-content">
                    <span class="careers-eyebrow">CAREERS AT EASYBOOKING</span>
                    <h1>Build something that brings people together.</h1>
                    <p>
                        We're making it easier for people to create experiences,
                        discover events and connect with their communities.
                        Come build it with us.
                    </p>
                    <a class="careers-primary-btn" href="#roles" id="seeroles">
                        Explore opportunities <span>→</span>
                    </a>
                    <div class="careers-hero-note">
                        <span></span> Building from Cape Town, South Africa
                    </div>
                </div>

                <div class="careers-hero-image">
                    ${ph('1528605248644-14dd04022da1')}
                    <div class="careers-image-caption">
                        <span>GOOD PEOPLE.</span>
                        <span>MEANINGFUL WORK.</span>
                    </div>
                </div>
            </section>

            <section class="careers-intro">
                <span class="careers-section-label">WHY JOIN US</span>
                <div class="careers-intro-grid">
                    <h2>Small team.<br><span>Big possibilities.</span></h2>
                    <p>
                        We're creating something that makes a real difference
                        to how people experience events. At EasyBooking, you'll
                        have room to contribute, experiment, learn and see the
                        impact of the work you do.
                    </p>
                </div>
            </section>

            <section class="careers-perks-section">
                <div class="careers-perks-heading">
                    <div>
                        <span class="careers-section-label">THE EXPERIENCE</span>
                        <h2>Why EasyBooking?</h2>
                    </div>
                    <p>
                        A place to do good work, share ideas and grow
                        alongside a team building something worthwhile.
                    </p>
                </div>

                <div class="careers-perks">
                    ${PERKS.map((p, i) => `
                        <article class="careers-perk">
                            <span class="careers-perk-number">
                                ${String(i + 1).padStart(2, '0')}
                            </span>
                            <span class="careers-perk-icon">${p[0]}</span>
                            <h3>${p[1]}</h3>
                            <p>${p[2]}</p>
                        </article>
                    `).join('')}
                </div>
            </section>

            <section class="careers-roles-section" id="roles">
                <div class="careers-roles-heading">
                    <div>
                        <span class="careers-section-label">COME WORK WITH US</span>
                        <h2>Find your place here.</h2>
                        <p>Explore the opportunities currently available at EasyBooking.</p>
                    </div>
                    <span class="careers-role-count">
                        ${ROLES.length} ${ROLES.length === 1 ? 'OPEN POSITION' : 'OPEN POSITIONS'}
                    </span>
                </div>

                <div class="careers-filters" id="tf"></div>
                <div class="careers-role-list" id="rl"></div>
            </section>

            <section class="careers-hiring">
                <div class="careers-hiring-image">
                    ${ph('1517048676732-d65bc937f952')}
                    <div class="careers-hiring-overlay">
                        <span>OUR APPROACH</span>
                        <h2>Good work starts with good conversations.</h2>
                    </div>
                </div>

                <div class="careers-hiring-content">
                    <span class="careers-section-label">THE PROCESS</span>
                    <h2>How we hire.</h2>
                    <p class="careers-hiring-intro">
                        No unnecessary hoops. Just a straightforward process
                        that gives us a chance to get to know each other.
                    </p>

                    <div class="careers-step">
                        <span>01</span>
                        <div>
                            <h3>Apply</h3>
                            <p>Send us your CV, portfolio and a little about yourself.</p>
                        </div>
                    </div>

                    <div class="careers-step">
                        <span>02</span>
                        <div>
                            <h3>Get to know us</h3>
                            <p>We'll have a conversation about your experience and what you're looking for.</p>
                        </div>
                    </div>

                    <div class="careers-step">
                        <span>03</span>
                        <div>
                            <h3>Working session</h3>
                            <p>Depending on the role, we'll work through a relevant task together.</p>
                        </div>
                    </div>

                    <div class="careers-step">
                        <span>04</span>
                        <div>
                            <h3>Next steps</h3>
                            <p>We'll share our decision and discuss what comes next.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="careers-open">
                <div class="careers-open-image">
                    ${ph('1517248135467-4c7edcad34c4')}
                </div>
                <div class="careers-open-content">
                    <span class="careers-section-label">YOUR NEXT CHAPTER</span>
                    <h2>Don't see the right opportunity?</h2>
                    <p>
                        The right person doesn't always fit into an existing
                        job description. If you believe you could bring
                        something valuable to EasyBooking, we'd love to hear
                        from you.
                    </p>
                    <a class="careers-primary-btn" href="mailto:${CONTACT_EMAIL}?subject=Open%20application">
                        Introduce yourself <span>→</span>
                    </a>
                </div>
            </section>

            <div class="careers-equal">
                EasyBooking is an equal opportunity employer. We welcome
                applications from people of all backgrounds and are happy
                to make reasonable adjustments to our recruitment process.
            </div>

        </div>
    `;

    const drawRoles = () => {
        document.getElementById('tf').innerHTML = teams.map(t => `
            <button class="careers-filter ${t === sel ? 'active' : ''}" data-t="${esc(t)}">
                ${esc(t)}
            </button>
        `).join('');

        document.querySelectorAll('#tf .careers-filter').forEach(b => {
            b.onclick = () => {
                sel = b.dataset.t;
                drawRoles();
            };
        });

        const filtered = ROLES.filter(r =>
            sel === 'All' || r.team === sel
        );

        document.getElementById('rl').innerHTML = filtered.map((r, i) => `
            <details class="careers-role">
                <summary>
                    <span class="careers-role-index">
                        ${String(i + 1).padStart(2, '0')}
                    </span>

                    <div class="careers-role-main">
                        <h3>${esc(r.title)}</h3>
                        <div class="careers-role-meta">
                            <span>${esc(r.team)}</span>
                            <span>${esc(r.loc)}</span>
                            <span>${esc(r.type)}</span>
                        </div>
                    </div>

                    <span class="careers-role-toggle">+</span>
                </summary>

                <div class="careers-role-body">
                    <p class="careers-role-about">${esc(r.about)}</p>

                    <div class="careers-role-details">
                        <div>
                            <h4>What you'll do</h4>
                            <ul>${r.do.map(x => `<li>${esc(x)}</li>`).join('')}</ul>
                        </div>

                        <div>
                            <h4>What we're looking for</h4>
                            <ul>${r.need.map(x => `<li>${esc(x)}</li>`).join('')}</ul>
                        </div>
                    </div>

                    <a class="careers-apply-btn" href="${mail(r.title)}">
                        Apply for this position →
                    </a>
                </div>
            </details>
        `).join('') || `
            <div class="careers-no-roles">
                <h3>No open positions in this team right now.</h3>
                <p>Check back soon or send us an open application.</p>
                <a href="mailto:${CONTACT_EMAIL}?subject=Open%20application">
                    Send an open application →
                </a>
            </div>
        `;
    };

    drawRoles();

    document.getElementById('seeroles').onclick = e => {
        e.preventDefault();
        document.getElementById('roles').scrollIntoView({
            behavior: 'smooth'
        });
    };
}

const RESPONSE_TIME = '1 to 2 business days'; // edit to what you can really deliver

const CONTACT_TOPICS = {
    attendee: [
        ['🎟️', 'Find my tickets', 'Every ticket you book is saved with its own code under My tickets.', ['Open my tickets', '#/tickets']],
        ['🛠️', 'A problem with my booking', 'Wrong quantity, a missing ticket or something not working? Tell us what happened.'],
        ['📅', 'Event details or changes', 'Dates, venues and cancellations are set by the host. Check the event page first, then tell us the event name.'],
        ['👤', 'Account help', 'Trouble logging in, or need your name or email changed.']
    ],
    host: [
        ['🚀', 'Setting up my event', 'Need a hand creating your event, adding details or publishing it?', ['Create an event', '#/create']],
        ['💲', 'Ticket types and pricing', 'Questions about free or paid tickets, quantities or sold-out events.'],
        ['📊', 'Sales and dashboard', 'See tickets sold, capacity and revenue for each of your events.', ['Open dashboard', '#/dashboard']],
        ['✏️', 'Changing or cancelling an event', 'Need to update details or take an event down? Let us know.'],
        ['🤝', 'Partnerships and venues', 'Running a venue, festival or community group? Let\'s work together.']
    ]
};

const CONTACT_TIPS = {
    attendee: ['Include the event name and the email you booked with.', 'Quote your ticket code if you have it.', 'Tell us what you expected and what happened.'],
    host: ['Include your event name and the date.', 'Tell us what you were trying to do when it went wrong.', 'For sales questions, mention the ticket type.']
};


function contact() {
    let who = 'attendee',
        topic = '';

    const draw = () => {
        const host = who === 'host',
            T = CONTACT_TOPICS[who];

        if (!T.some(t => t[1] === topic)) topic = T[0][1];

        const links = host ? [
            ['Create an event', '#/create'],
            ['Organiser dashboard', '#/dashboard'],
            ['FAQs', '#/faqs']
        ] : [
            ['My tickets', '#/tickets'],
            ['Find events', '#/'],
            ['FAQs', '#/faqs']
        ];

        app.innerHTML = `
            <div class="contact-page">

                <section class="contact-hero">
                    <div class="contact-hero-content">
                        <span class="contact-eyebrow">WE'RE HERE TO HELP</span>
                        <h1>Let's get things <span>sorted.</span></h1>
                        <p>
                            Have a question, need a hand or something you'd
                            like us to know? Tell us what's going on and
                            we'll help you find the right answer.
                        </p>
                        <div class="contact-hero-note">
                            <span></span> Real help, from real people.
                        </div>
                    </div>

                    <div class="contact-hero-image">
                        <img
                            src="${U('1521737711867-e3b97375f902')}"
                            alt=""
                            onerror="this.style.visibility='hidden'"
                        >
                        <div class="contact-image-caption">
                            HERE WHEN YOU NEED US.
                        </div>
                    </div>
                </section>

                <section class="contact-content">

                    <div class="contact-intro">
                        <span class="contact-section-label">LET'S START HERE</span>
                        <h2>How can we help?</h2>
                        <p>
                            First, tell us which side of EasyBooking you're on.
                            We'll point you in the right direction.
                        </p>
                    </div>

                    <div class="contact-who">
                        <button type="button"
                            class="contact-who-card ${host ? '' : 'active'}"
                            data-w="attendee">
                            <span class="contact-who-number">01</span>
                            <span class="contact-who-icon">ATTENDEE</span>
                            <b>I'm attending an event</b>
                            <small>Bookings, tickets and my account</small>
                            <span class="contact-who-arrow">→</span>
                        </button>

                        <button type="button"
                            class="contact-who-card ${host ? 'active' : ''}"
                            data-w="host">
                            <span class="contact-who-number">02</span>
                            <span class="contact-who-icon">ORGANISER</span>
                            <b>I'm hosting an event</b>
                            <small>Creating, selling and managing events</small>
                            <span class="contact-who-arrow">→</span>
                        </button>
                    </div>

                    <div class="contact-help-heading">
                        <div>
                            <span class="contact-section-label">SUPPORT TOPICS</span>
                            <h2>${host ? 'Organiser support' : 'Attendee support'}</h2>
                            <p>Select a topic to tell us what you need help with.</p>
                        </div>
                    </div>

                    <div class="contact-topics">
                        ${T.map((t, i) => `
                            <button type="button"
                                class="contact-topic ${t[1] === topic ? 'active' : ''}"
                                data-t="${esc(t[1])}">
                                <span class="contact-topic-number">
                                    ${String(i + 1).padStart(2, '0')}
                                </span>
                                <span class="contact-topic-icon">${t[0]}</span>
                                <span class="contact-topic-content">
                                    <b>${esc(t[1])}</b>
                                    <small>${esc(t[2])}</small>
                                    ${t[3] ? `
                                        <a href="${t[3][1]}" class="contact-topic-link">
                                            ${esc(t[3][0])} →
                                        </a>
                                    ` : ''}
                                </span>
                                <span class="contact-topic-arrow">→</span>
                            </button>
                        `).join('')}
                    </div>

                    <section class="contact-form-section">
                        <div class="contact-form-intro">
                            <span class="contact-section-label">GET IN TOUCH</span>
                            <h2>Send us a message.</h2>
                            <p>
                                Give us a little detail and we'll get back to you.
                            </p>

                            <div class="contact-direct">
                                <span class="contact-direct-label">PREFER EMAIL?</span>
                                <a href="mailto:${CONTACT_EMAIL}">${CONTACT_EMAIL} →</a>
                            </div>
                        </div>

                        <form class="contact-form" id="cf">
                            <div class="contact-form-top">
                                <span>YOUR MESSAGE</span>
                                <span>${host ? 'ORGANISER' : 'ATTENDEE'} SUPPORT</span>
                            </div>

                            <label class="contact-field">
                                <span>What do you need help with?</span>
                                <select id="ct">
                                    ${T.map(t => `
                                        <option value="${esc(t[1])}" ${t[1] === topic ? 'selected' : ''}>
                                            ${esc(t[1])}
                                        </option>
                                    `).join('')}
                                </select>
                            </label>

                            <div class="contact-field-row">
                                <label class="contact-field">
                                    <span>Your name</span>
                                    <input name="name" placeholder="Full name" required>
                                </label>

                                <label class="contact-field">
                                    <span>Your email</span>
                                    <input name="email" type="email" placeholder="you@example.com" required>
                                </label>
                            </div>

                            <label class="contact-field">
                                <span>${host ? 'Your event (optional)' : 'Event you booked (optional)'}</span>
                                <input name="event" placeholder="Event name">
                            </label>

                            <label class="contact-field">
                                <span>Your message</span>
                                <textarea name="message" rows="6"
                                    placeholder="Tell us a little more about how we can help..."
                                    required></textarea>
                            </label>

                            <div id="msg"></div>

                            <div class="contact-submit-row">
                                <small>We'll aim to respond within ${esc(RESPONSE_TIME)}.</small>
                                <button type="submit" class="contact-submit">
                                    Send message <span>→</span>
                                </button>
                            </div>
                        </form>
                    </section>

                    <section class="contact-bottom">
                        <div>
                            <span class="contact-section-label">NEED A QUICK ANSWER?</span>
                            <h2>Some things are just a click away.</h2>
                        </div>

                        <div class="contact-quick-links">
                            ${links.map((l, i) => `
                                <a href="${l[1]}">
                                    <span>0${i + 1}</span>
                                    <b>${esc(l[0])}</b>
                                    <span>→</span>
                                </a>
                            `).join('')}
                        </div>
                    </section>

                </section>

                <div class="contact-footer-note">
                    We're committed to making EasyBooking a welcoming place
                    for everyone. If you need an adjustment to our support
                    process, just let us know.
                </div>

            </div>
        `;

        app.querySelectorAll('.contact-who-card').forEach(b => {
            b.onclick = () => {
                who = b.dataset.w;
                topic = '';
                draw();
            };
        });

        app.querySelectorAll('.contact-topic').forEach(c => {
            c.onclick = e => {
                if (e.target.closest('a')) return;

                topic = c.dataset.t;

                app.querySelectorAll('.contact-topic').forEach(x =>
                    x.classList.toggle('active', x === c)
                );

                document.getElementById('ct').value = topic;
            };
        });

        document.getElementById('ct').onchange = e => {
            topic = e.target.value;

            app.querySelectorAll('.contact-topic').forEach(x =>
                x.classList.toggle('active', x.dataset.t === topic)
            );
        };

        document.getElementById('cf').onsubmit = e => {
            e.preventDefault();

            const f = Object.fromEntries(new FormData(e.target));

            const subject = `[${host ? 'Organiser' : 'Attendee'}] ${topic}`;

            const body = `Name: ${f.name}
Email: ${f.email}
${host ? 'Event' : 'Event booked'}: ${f.event || '-'}

${f.message}`;

            location.href =
                `mailto:${CONTACT_EMAIL}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;

            document.getElementById('msg').innerHTML = `
                <div class="contact-success">
                    Your email app should open with your message ready to send.
                    If it doesn't, email us directly at ${CONTACT_EMAIL}.
                </div>
            `;
        };
    };

    draw();
}


function faqs() {
    const Q = [
        ['How do I book tickets?', 'Open an event, choose how many of each ticket you want and press Book tickets. You need a free account to book.'],
        ['Where do I find my tickets?', 'Log in and open My tickets from the menu. Every ticket has its own unique code.'],
        ['How do I host an event?', 'Create an account, choose Create an event, add your details and ticket types, then publish. Your event appears on the home page straight away.'],
        ['Where can I see how many tickets I have sold?', 'Your organiser dashboard shows tickets sold against capacity, plus revenue, for each of your events.']
    ];

    app.innerHTML = phead('FAQs', 'Quick answers to common questions.') + `
        <div class="page faqs-page">
            <div class="faqs-intro">
                <span class="faqs-label">NEED A HAND?</span>
                <h2>Frequently asked questions</h2>
                <p>Find quick answers about bookings, tickets and hosting events.</p>
            </div>

            <div class="faqs-list">
                ${Q.map((q, i) => `
                    <details class="faqs-item">
                        <summary>
                            <span class="faqs-number">0${i + 1}</span>
                            <b>${q[0]}</b>
                            <span class="faqs-toggle">+</span>
                        </summary>
                        <p>${q[1]}</p>
                    </details>
                `).join('')}
            </div>

            <div class="faqs-contact">
                <div>
                    <span class="faqs-label">STILL NEED HELP?</span>
                    <h2>Rather speak to a human?</h2>
                    <p>Can't find what you're looking for? Our team is happy to help.</p>
                </div>
                <div class="faqs-actions">
                    <a class="btn dark" href="#/contact">Contact us</a>
                    <a class="btn ghost" href="mailto:${CONTACT_EMAIL}">Email us</a>
                </div>
            </div>
        </div>
    `;

    const items = app.querySelectorAll('.faqs-item');

    items.forEach(item => {
        item.addEventListener('toggle', () => {
            if (item.open) {
                items.forEach(other => {
                    if (other !== item) other.open = false;
                });
            }
        });
    });
}


// Refresh the account from MySQL before rendering. This also updates older
// cached sessions that do not yet contain account_type.
async function initialiseApp() {
    if (token) {
        try {
            const result = await api('/me');
            user = result.user;
            localStorage.setItem('eb_user', JSON.stringify(user));

        } catch (error) {
            token = null;
            user = null;

            localStorage.removeItem('eb_token');
            localStorage.removeItem('eb_user');

            const currentRoute = (location.hash || '#/').slice(1).split('?')[0];

            if (['/tickets', '/dashboard', '/create'].includes(currentRoute)) {
                location.hash = '#/login';
            }
        }

    } else {
        user = null;
        localStorage.removeItem('eb_user');
    }

    render();
}

addEventListener('hashchange', render);

initialiseApp();
function faqs() {
    const Q = [
        ['How do I book tickets?', 'Open an event, choose how many of each ticket you want and press Book tickets. You need a free account to book.'],
        ['Where do I find my tickets?', 'Log in and open My tickets from the menu. Every ticket has its own unique code.'],
        ['How do I host an event?', 'Create an account, choose Create an event, add your details and ticket types, then publish. Your event appears on the home page straight away.'],
        ['Where can I see how many tickets I have sold?', 'Your organiser dashboard shows tickets sold against capacity, plus revenue, for each of your events.']
    ];

    app.innerHTML = phead('FAQs', 'Quick answers to common questions.') + `<div class="page">${Q.map(q=>`<details><summary>${q[0]}</summary><p>${q[1]}</p></details>`).join('')}
  <div class="ctaband"><h2 class="h" style="margin-bottom:8px">Wanna speak to a human?</h2><p style="margin-bottom:22px;font-size:19px">Can't find what you're looking for? Our team is happy to help.</p><a class="btn dark" href="#/contact">Contact us</a> <a class="btn ghost" href="mailto:${CONTACT_EMAIL}">Email us</a></div></div>`
}

function authPage(mode = 'login') {
    const isLogin = mode === 'login';

    app.innerHTML = `
        <section class="auth-layout ${isLogin ? 'is-login' : 'is-register'}">

            <div class="auth-panel">

                <a href="#/" class="auth-logo">
                    Easy<span>Booking</span>
                </a>

                <div class="auth-content">

                    <div class="auth-heading">
                        <span class="auth-eyebrow">
                            ${isLogin ? 'WELCOME BACK' : 'GET STARTED'}
                        </span>

                        <h1>
                            ${isLogin ? 'Welcome back.' : 'Make it happen.'}
                        </h1>

                        <p>
                            ${isLogin
                                ? 'Sign in to pick up where you left off.'
                                : 'Create your account and start discovering experiences worth remembering.'}
                        </p>
                    </div>

                    <form id="authForm" class="auth-form">

                        ${!isLogin ? `
                            <div class="auth-field">
                                <label for="authName">Full name</label>
                                <input
                                    type="text"
                                    id="authName"
                                    name="name"
                                    placeholder="Your full name"
                                    maxlength="120"
                                    required
                                >
                            </div>
                        ` : ''}

                        <div class="auth-field">
                            <label for="authEmail">Email address</label>
                            <input
                                type="email"
                                id="authEmail"
                                name="email"
                                placeholder="you@example.com"
                                required
                            >
                        </div>

                        <div class="auth-field">
                            <label for="authPassword">Password</label>
                            <input
                                type="password"
                                id="authPassword"
                                name="password"
                                placeholder="Enter your password"
                                minlength="${isLogin ? 1 : 8}"
                                required
                            >
                        </div>

                        ${!isLogin ? `
                            <div class="auth-field">
                                <label for="authAccountType">I want to</label>
                                <select
                                    id="authAccountType"
                                    name="account_type"
                                    required
                                >
                                    <option value="attendee">Attend events</option>
                                    <option value="organiser">Organise events</option>
                                </select>
                            </div>
                        ` : ''}

                        <div id="authError" class="auth-error" hidden></div>

                        <button type="submit" class="auth-submit" id="authSubmit">
                            ${isLogin ? 'Sign in' : 'Create account'}
                        </button>

                    </form>

                    <p class="auth-switch">
                        ${isLogin
                            ? `Don't have an account? <a href="#/register">Create one</a>`
                            : `Already have an account? <a href="#/login">Sign in</a>`
                        }
                    </p>

                </div>

                <a href="#/" class="auth-back">
                    Back to EasyBooking
                </a>

            </div>

            <div class="auth-visual">
                <div class="auth-visual-content">
                    <span class="auth-visual-eyebrow">
                        ${isLogin ? 'YOUR NEXT EXPERIENCE AWAITS' : 'MAKE ROOM FOR SOMETHING NEW'}
                    </span>

                    <h2>
                        ${isLogin
                            ? 'Good times are better when shared.'
                            : 'Every great experience starts somewhere.'}
                    </h2>

                    <p>
                        ${isLogin
                            ? 'Find your people. Discover your next favourite event.'
                            : 'From intimate gatherings to unforgettable events, make it happen with EasyBooking.'}
                    </p>
                </div>
            </div>

        </section>
    `;

    const form = document.getElementById('authForm');
    const errorBox = document.getElementById('authError');
    const submitButton = document.getElementById('authSubmit');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        errorBox.hidden = true;
        errorBox.textContent = '';

        submitButton.disabled = true;
        submitButton.textContent = 'Please wait...';

        const formData = new FormData(form);

        const payload = {
            email: formData.get('email').trim(),
            password: formData.get('password')
        };

        if (!isLogin) {
            payload.name = formData.get('name').trim();
            payload.account_type = formData.get('account_type');
        }

        try {
            const result = await api(
                isLogin ? '/login' : '/register',
                {
                    method: 'POST',
                    body: payload
                }
            );

            token = result.token;
            user = result.user;

            localStorage.setItem('eb_token', token);
            localStorage.setItem('eb_user', JSON.stringify(user));

            location.hash = user.account_type === 'organiser'
                ? '#/dashboard'
                : '#/';

            render();

        } catch (error) {
            errorBox.textContent = error.message;
            errorBox.hidden = false;

            submitButton.disabled = false;
            submitButton.textContent = isLogin
                ? 'Sign in'
                : 'Create account';
        }
    });
}


async function accountPage() {
    const result = await api('/account');
    const account = result.user;
    const isOrganiser = account.account_type === 'organiser';

    app.innerHTML = `
        <section class="account-page">

            <aside class="account-sidebar">

                <div>
                    <div class="account-sidebar-heading">
                        <div>
                            <span class="account-eyebrow">EASYBOOKING</span>
                            <h2>Account settings</h2>
                        </div>

                        <a href="${isOrganiser ? '#/dashboard' : '#/'}"
                           class="account-close"
                           aria-label="Close account settings">
                            &times;
                        </a>
                    </div>

                    <p class="account-sidebar-description">
                        Manage your account and preferences.
                    </p>

                    <div class="account-step-list">

                        <button type="button"
                                class="account-step active"
                                data-account-section="account-personal">
                            <span class="account-step-number">01</span>
                            <span class="account-step-copy">
                                <b>Personal information</b>
                                <small>Your name and account details</small>
                            </span>
                        </button>

                        <button type="button"
                                class="account-step"
                                data-account-section="account-contact">
                            <span class="account-step-number">02</span>
                            <span class="account-step-copy">
                                <b>Contact information</b>
                                <small>Your contact details</small>
                            </span>
                        </button>

                        ${isOrganiser ? `
                            <button type="button"
                                    class="account-step"
                                    data-account-section="account-organisation">
                                <span class="account-step-number">03</span>
                                <span class="account-step-copy">
                                    <b>Organisation</b>
                                    <small>Your public organiser details</small>
                                </span>
                            </button>
                        ` : ''}

                    </div>
                </div>

                <div class="account-sidebar-footer">
                    <span class="account-footer-mark">E</span>
                    <div>
                        <b>EasyBooking</b>
                        <small>Account management</small>
                    </div>
                </div>

            </aside>

            <main class="account-main">

                <div class="account-container">

                    <div class="account-header">
                        <div>
                            <span class="account-eyebrow">YOUR ACCOUNT</span>
                            <h1>Account settings</h1>
                            <p>
                                Keep your information up to date.
                            </p>
                        </div>
                    </div>

                    <form id="accountForm">

                        <section class="account-section"
                                 id="account-personal">

                            <div class="account-section-heading">
                                <span class="account-section-number">01</span>
                                <div>
                                    <h2>Personal information</h2>
                                    <p>Your basic account details.</p>
                                </div>
                            </div>

                            <div class="account-grid">

                                <div class="account-field">
                                    <label for="accountName">Full name</label>
                                    <input
                                        id="accountName"
                                        name="name"
                                        type="text"
                                        value="${esc(account.name || '')}"
                                        maxlength="120"
                                        required
                                    >
                                </div>

                                <div class="account-field">
                                    <label for="accountEmail">Email address</label>
                                    <input
                                        id="accountEmail"
                                        type="email"
                                        value="${esc(account.email || '')}"
                                        disabled
                                    >
                                    <small>Email address cannot currently be changed.</small>
                                </div>

                            </div>
                        </section>

                        <section class="account-section"
                                 id="account-contact">

                            <div class="account-section-heading">
                                <span class="account-section-number">02</span>
                                <div>
                                    <h2>Contact information</h2>
                                    <p>How we can reach you.</p>
                                </div>
                            </div>

                            <div class="account-grid">

                                <div class="account-field">
                                    <label for="accountPhone">Phone number</label>
                                    <input
                                        id="accountPhone"
                                        name="phone"
                                        type="tel"
                                        value="${esc(account.phone || '')}"
                                        placeholder="Your phone number"
                                    >
                                </div>

                                <div class="account-field">
                                    <label for="accountLocation">Location</label>
                                    <input
                                        id="accountLocation"
                                        name="location"
                                        type="text"
                                        value="${esc(account.location || '')}"
                                        placeholder="City or town"
                                    >
                                </div>

                            </div>
                        </section>

                        ${isOrganiser ? `
                            <section class="account-section"
                                     id="account-organisation">

                                <div class="account-section-heading">
                                    <span class="account-section-number">03</span>
                                    <div>
                                        <h2>Organisation information</h2>
                                        <p>Tell attendees about your organisation.</p>
                                    </div>
                                </div>

                                <div class="account-grid">

                                    <div class="account-field">
                                        <label for="organisationName">
                                            Organisation name
                                        </label>
                                        <input
                                            id="organisationName"
                                            name="organisation_name"
                                            type="text"
                                            value="${esc(account.organisation_name || '')}"
                                            maxlength="160"
                                            placeholder="Your organisation"
                                        >
                                    </div>

                                    <div class="account-field">
                                        <label for="organisationWebsite">
                                            Website
                                        </label>
                                        <input
                                            id="organisationWebsite"
                                            name="website"
                                            type="url"
                                            value="${esc(account.website || '')}"
                                            maxlength="255"
                                            placeholder="https://example.com"
                                        >
                                    </div>

                                    <div class="account-field account-field-full">
                                        <label for="organisationDescription">
                                            About your organisation
                                        </label>
                                        <textarea
                                            id="organisationDescription"
                                            name="organisation_description"
                                            rows="5"
                                            placeholder="Tell attendees about your organisation..."
                                        >${esc(account.organisation_description || '')}</textarea>
                                    </div>

                                </div>
                            </section>
                        ` : ''}

                        <div id="accountMessage"
                             class="account-message"
                             hidden></div>

                        <div class="account-actions">
                            <button type="submit"
                                    id="accountSave"
                                    class="account-save">
                                Save changes
                            </button>
                        </div>

                    </form>

                </div>

            </main>

        </section>
    `;

    // Sidebar navigation
    const steps = document.querySelectorAll('.account-step');

    steps.forEach(step => {
        step.addEventListener('click', () => {
            const target = document.getElementById(
                step.dataset.accountSection
            );

            if (!target) return;

            steps.forEach(item => item.classList.remove('active'));
            step.classList.add('active');

            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    // Account form submission
    const form = document.getElementById('accountForm');
    const saveButton = document.getElementById('accountSave');
    const message = document.getElementById('accountMessage');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        saveButton.disabled = true;
        saveButton.textContent = 'Saving...';

        message.hidden = true;

        const formData = new FormData(form);
        const payload = {};

        for (const [key, value] of formData.entries()) {
            payload[key] = value.trim();
        }

        try {
            const response = await api('/account', {
                method: 'PUT',
                body: payload
            });

            user = response.user;

            localStorage.setItem(
                'eb_user',
                JSON.stringify(user)
            );

            message.textContent =
                'Your account details have been saved.';

            message.className = 'account-message success';
            message.hidden = false;

        } catch (error) {
            message.textContent = error.message;
            message.className = 'account-message error';
            message.hidden = false;

        } finally {
            saveButton.disabled = false;
            saveButton.textContent = 'Save changes';
        }
    });
}


async function render() {
    closeMenu();

    const [path, qs] = (location.hash || '#/').slice(1).split('?');
    const [, route, id] = path.split('/');

    window.scrollTo(0, 0);

    if (qs) {
        const params = new URLSearchParams(qs);

        Object.assign(filt, {
            cat: params.get('cat') || '',
            where: params.get('where') || filt.where,
            free: false,
            when: '',
            q: '',
            tile: ''
        });
    }

    renderChrome();

    const protectedRoutes = [
        'tickets',
        'dashboard',
        'create',
        'account'
    ];

    if (protectedRoutes.includes(route) && !user) {
        location.hash = '#/login';
        return;
    }

    const organiserRoutes = [
        'dashboard',
        'create'
    ];

    if (
        organiserRoutes.includes(route) &&
        user?.account_type !== 'organiser'
    ) {
        app.innerHTML = `
            <div class="page">
                <div class="err">
                    This page is only available to organiser accounts.
                </div>
                <p style="margin-top:20px">
                    <a href="#/tickets">Go to my tickets</a>
                </p>
            </div>
        `;
        return;
    }

    try {
        if (route === 'event') {
            await eventPage(id);
        } else if (route === 'login') {
            authPage('login');
        } else if (route === 'register') {
            authPage('register');
        } else if (route === 'account') {
            await accountPage();
        } else if (route === 'tickets') {
            await tickets();
        } else if (route === 'dashboard') {
            await dashboard();
        } else if (route === 'create') {
            create();
        } else if (route === 'about') {
            about();
        } else if (route === 'careers') {
            careers();
        } else if (route === 'contact') {
            contact();
        } else if (route === 'faqs') {
            faqs();
        } else if (route === 'payment-success') {
            app.innerHTML = `
                <div class="page">
                    <h1>Payment submitted</h1>
                    <p>Your payment is being verified. Your tickets will appear in My Tickets once confirmed.</p>
                    <a class="btn dark" href="#/tickets">View my tickets</a>
                </div>
            `;
        } else if (route === 'payment-cancelled') {
            app.innerHTML = `
                <div class="page">
                    <h1>Payment cancelled</h1>
                    <p>Your payment wasn't completed. You can return to the event and try again.</p>
                    <a class="btn dark" href="#/">Back to events</a>
                </div>
            `;
        } else {
            await home();
        }

    } catch (error) {
        if (error.status === 401 && route !== 'login' && route !== 'register') {
            location.hash = '#/login';
            return;
        }

        app.innerHTML = `
            <div class="page">
                <div class="err">${esc(error.message)}</div>
            </div>
        `;
    }
}

// Refresh the account from MySQL before rendering. This also updates older
// cached sessions that do not yet contain account_type.
async function initialiseApp() {
    if (token) {
        try {
            const result = await api('/me');
            user = result.user;
            localStorage.setItem('eb_user', JSON.stringify(user));

        } catch (error) {
            token = null;
            user = null;

            localStorage.removeItem('eb_token');
            localStorage.removeItem('eb_user');

            const currentRoute = (location.hash || '#/').slice(1).split('?')[0];

            if (['/tickets', '/dashboard', '/create'].includes(currentRoute)) {
                location.hash = '#/login';
            }
        }

    } else {
        user = null;
        localStorage.removeItem('eb_user');
    }

    render();
}

addEventListener('hashchange', render);

initialiseApp();