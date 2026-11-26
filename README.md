# EasyBooking

**Discover events. Book experiences. Make memories.**

EasyBooking is a full-stack event discovery and ticket booking web application designed to connect event attendees with event organisers. Users can discover events, explore event details, select tickets and manage their bookings, while organisers can create and manage their own events.

The project was developed as a portfolio project to demonstrate full-stack development, authentication, database integration, event management, ticket reservation workflows and payment integration.

## Live Demo

**Hosted Application:** https://easybooking-1.onrender.com/#/

The application is deployed on Render and uses TiDB Cloud for its hosted database.

> This is a portfolio and demonstration project. Payments are configured using PayFast Sandbox and do not process real money.

---

## Features

### Event Discovery
- Browse available events.
- Explore event details, descriptions, dates, venues and locations.
- View available ticket types and prices.
- Discover events through the event listing interface.

### User Authentication
- User registration and login.
- Separate attendee and organiser account types.
- Password hashing using bcrypt.
- Token-based authentication using JWT.
- Protected API routes for authenticated users.

### Event Management
- Organiser accounts.
- Create and publish events.
- Add event descriptions, locations, dates and other event details.
- Configure ticket types and prices.
- Manage event ticket availability.

### Ticket Booking
- Select ticket types and quantities.
- Create booking orders.
- Reserve ticket inventory during the booking process.
- Track order and payment statuses.
- View booked tickets through the user's ticket section.

### Payment Integration
- PayFast hosted checkout integration.
- PayFast Sandbox payment testing.
- Payment notification handling through ITN (Instant Transaction Notification).
- Payment status tracking.
- Ticket issuance following successful payment processing.
- Pending reservation expiry and inventory release.

---

## Technology Stack

### Frontend
- HTML5
- CSS3
- JavaScript (Vanilla JS)

### Backend
- Node.js
- Express.js
- REST API

### Database
- MySQL-compatible database
- TiDB Cloud
- MySQL2

### Authentication & Security
- JSON Web Tokens (JWT)
- bcryptjs
- Environment variables for sensitive configuration

### Payment Integration
- PayFast Sandbox

### Hosting & Deployment
- Render — web application hosting
- TiDB Cloud — hosted database

---

## Architecture

EasyBooking follows a client-server architecture.

```text
                    USER
                     |
                     v
             FRONTEND APPLICATION
              HTML / CSS / JS
                     |
                     v
              EXPRESS SERVER
               NODE.JS API
                     |
          +----------+----------+
          |          |          |
          v          v          v
     AUTHENTICATION  EVENTS   BOOKINGS
          |          |          |
          +----------+----------+
                     |
                     v
                TIDB CLOUD
              DATABASE STORAGE
                     |
                     v
              PAYFAST SANDBOX
              PAYMENT CHECKOUT
```

### Frontend

The frontend is built using HTML, CSS and vanilla JavaScript. It handles page rendering, navigation, user interactions, event discovery, authentication interfaces and booking workflows. The application uses hash-based navigation.

### Backend

The backend runs on Node.js with Express.js. It provides API endpoints for registration, authentication, event management, ticket bookings, orders and payment notifications.

### Database

The application uses a relational database to store and manage application data, including:

- User accounts
- Events
- Ticket types
- Ticket inventory
- Orders
- Order items
- Tickets
- Payment information

### Payment Processing

PayFast is integrated through its hosted checkout workflow. The application redirects users to PayFast Sandbox for payment testing and handles payment notifications through the backend.

---

## Database & Hosting

### TiDB Cloud

The application uses **TiDB Cloud Starter** as its hosted MySQL-compatible database.

TiDB Cloud provides the remote database infrastructure used by the deployed application, allowing the backend hosted on Render to connect to the database without requiring a locally running MySQL server.

Database connectivity uses a secure TLS connection.

### Render

The Node.js application is hosted on **Render**.

Render runs the Express server and exposes the application through a public URL, making EasyBooking accessible online.

| Service | Purpose |
|---|---|
| Render | Hosts the Node.js/Express application |
| TiDB Cloud | Hosts the remote MySQL-compatible database |
| PayFast Sandbox | Simulates payment transactions |

---

## Getting Started Locally

### Prerequisites

Before running the application locally, you will need:

- Node.js
- npm
- MySQL server (for local development)
- Git

### 1. Clone the repository

```bash
git clone https://github.com/YolandaMbande/easybooking.git
```

Navigate into the project:

```bash
cd easybooking
```

### 2. Install dependencies

```bash
npm install
```

### 3. Create the database

Create a database in MySQL:

```sql
CREATE DATABASE easybooking;
```

The application uses the `schema.sql` file to initialise its database tables when the server starts.

### 4. Configure environment variables

Create a `.env` file in the root directory of the project.

```env
PORT=3000

JWT_SECRET=your_secure_jwt_secret

DB_HOST=localhost
DB_PORT=3306
DB_USER=root
DB_PASSWORD=
DB_NAME=easybooking

PAYFAST_MODE=sandbox
PAYFAST_MERCHANT_ID=your_sandbox_merchant_id
PAYFAST_MERCHANT_KEY=your_sandbox_merchant_key
PAYFAST_PASSPHRASE=your_sandbox_passphrase

PAYFAST_RETURN_URL=http://localhost:3000/#/payment-success
PAYFAST_CANCEL_URL=http://localhost:3000/#/payment-cancelled
PAYFAST_NOTIFY_URL=https://your-public-url/api/payfast/notify
```

Update the database credentials to match your local MySQL configuration.

PayFast Sandbox credentials are required if you want to test the checkout workflow. The notification endpoint must be publicly accessible for PayFast to send payment notifications.

**Never commit your `.env` file or expose your credentials publicly.**

### 5. Run the application

```bash
npm start
```

The application should be available at:

```text
http://localhost:3000
```

---

## Environment Variables

| Variable | Description |
|---|---|
| `PORT` | Port used by the Express server |
| `JWT_SECRET` | Secret used to sign authentication tokens |
| `DB_HOST` | Database hostname |
| `DB_PORT` | Database port |
| `DB_USER` | Database username |
| `DB_PASSWORD` | Database password |
| `DB_NAME` | Database name |
| `PAYFAST_MODE` | Payment environment |
| `PAYFAST_MERCHANT_ID` | PayFast Sandbox merchant ID |
| `PAYFAST_MERCHANT_KEY` | PayFast Sandbox merchant key |
| `PAYFAST_PASSPHRASE` | PayFast Sandbox passphrase |
| `PAYFAST_RETURN_URL` | URL users return to after checkout |
| `PAYFAST_CANCEL_URL` | URL users return to when checkout is cancelled |
| `PAYFAST_NOTIFY_URL` | Backend payment notification endpoint |

---

## Payment Testing

EasyBooking uses **PayFast Sandbox mode** for its payment integration.

The Sandbox environment is used to demonstrate the checkout and payment notification workflow without processing actual financial transactions.

The project is intended for demonstration and portfolio purposes. It is not configured to accept real payments.

---

## Project Goals

The main goals of this project were to:

- Build a complete full-stack web application.
- Implement user registration and authentication.
- Develop REST API endpoints using Express.js.
- Design and work with a relational database.
- Implement event creation and management functionality.
- Develop a ticket reservation and booking workflow.
- Integrate a third-party hosted payment service.
- Deploy a web application and database to cloud services.
- Gain practical experience troubleshooting deployment and database connectivity.

---

## Future Improvements

Potential improvements include:

- Enhanced event search and filtering.
- Improved organiser analytics and dashboards.
- Additional event categories.
- Email booking confirmations.
- QR-code ticket validation.
- Improved event discovery and recommendations.
- Additional automated testing.
- Production-ready payment configuration and security improvements.

---

## Author

**Yolanda Mbande**

Software Developer

GitHub: [@YolandaMbande](https://github.com/YolandaMbande)

---

## License

This project was developed as a personal portfolio project.

