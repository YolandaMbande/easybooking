
CREATE TABLE IF NOT EXISTS eb_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(100) NOT NULL,
    account_type ENUM('attendee', 'organiser')
        NOT NULL DEFAULT 'attendee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS eb_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    organiser_id INT NULL,
    title VARCHAR(200) NOT NULL,
    category VARCHAR(40) NOT NULL,
    venue VARCHAR(200),
    city VARCHAR(100),
    starts_at DATETIME NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_eb_events_organiser
        FOREIGN KEY (organiser_id)
        REFERENCES eb_users(id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS eb_ticket_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    quantity INT NOT NULL,
    sold INT NOT NULL DEFAULT 0,
    FOREIGN KEY (event_id)
        REFERENCES eb_events(id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS eb_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)
        REFERENCES eb_users(id),
    FOREIGN KEY (event_id)
        REFERENCES eb_events(id)
);

CREATE TABLE IF NOT EXISTS eb_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    ticket_type_id INT NOT NULL,
    code VARCHAR(20) NOT NULL UNIQUE,
    FOREIGN KEY (order_id)
        REFERENCES eb_orders(id),
    FOREIGN KEY (ticket_type_id)
        REFERENCES eb_ticket_types(id)
);
