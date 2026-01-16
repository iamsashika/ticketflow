-- ========================================
-- Event Registration and Ticketing System
-- Database Setup Script
-- ========================================

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS event_ticketing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE event_ticketing;

-- ========================================
-- Table: users
-- Stores user account information
-- ========================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Table: categories
-- Stores event categories
-- ========================================
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Table: events
-- Stores event information
-- ========================================
CREATE TABLE IF NOT EXISTS events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    venue VARCHAR(200) NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    ticket_price DECIMAL(10, 2) DEFAULT 0.00,
    capacity INT NOT NULL,
    available_seats INT NOT NULL,
    image VARCHAR(255),
    status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_event_date (event_date),
    INDEX idx_status (status),
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Table: ticket_types
-- Stores different ticket tiers for each event
-- ========================================
CREATE TABLE IF NOT EXISTS ticket_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL,
    available_seats INT NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event (event_id),
    INDEX idx_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Table: registrations
-- Stores event registrations and tickets
-- ========================================
CREATE TABLE IF NOT EXISTS registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    ticket_type_id INT,
    quantity INT DEFAULT 1,
    ticket_number VARCHAR(50) UNIQUE NOT NULL,
    ticket_price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (ticket_type_id) REFERENCES ticket_types(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_event (event_id),
    INDEX idx_ticket_type (ticket_type_id),
    INDEX idx_ticket (ticket_number),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Insert Sample Categories
-- ========================================
INSERT INTO categories (name, description) VALUES
('Conference', 'Professional conferences and seminars'),
('Concert', 'Music concerts and shows'),
('Workshop', 'Educational workshops and training sessions'),
('Sports', 'Sporting events and competitions'),
('Festival', 'Cultural festivals and celebrations'),
('Exhibition', 'Art exhibitions and displays');

-- ========================================
-- Insert Default Admin User
-- Password: admin123 (hashed with PASSWORD_DEFAULT)
-- ========================================
INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES
('Admin', 'User', 'admin@example.com', '0771234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- ========================================
-- Insert Sample Events
-- ========================================
INSERT INTO events (category_id, title, description, venue, event_date, event_time, ticket_price, capacity, available_seats, status, created_by) VALUES
(1, 'Tech Conference 2026', 'Annual technology conference featuring latest innovations and trends in tech industry.', 'Colombo Conference Center', '2026-02-15', '09:00:00', 5000.00, 500, 500, 'upcoming', 1),
(2, 'Music Festival Sri Lanka', 'Three-day music festival featuring local and international artists.', 'Galle Face Green', '2026-03-20', '18:00:00', 2500.00, 1000, 1000, 'upcoming', 1),
(3, 'Digital Marketing Workshop', 'Hands-on workshop on digital marketing strategies and tools.', 'Hotel Galadari, Colombo', '2026-02-10', '10:00:00', 3000.00, 100, 100, 'upcoming', 1),
(4, 'Cricket Tournament', 'Inter-school cricket tournament finals.', 'R. Premadasa Stadium', '2026-02-25', '14:00:00', 1000.00, 5000, 5000, 'upcoming', 1),
(5, 'Vesak Festival Celebration', 'Traditional Vesak festival celebration with cultural performances.', 'Gangaramaya Temple', '2026-05-12', '17:00:00', 0.00, 2000, 2000, 'upcoming', 1);

-- ========================================
-- Insert Sample Ticket Types
-- ========================================
INSERT INTO ticket_types (event_id, name, price, capacity, available_seats, display_order) VALUES
(1, 'VVIP', 10000.00, 50, 50, 1),
(1, 'VIP', 7500.00, 100, 100, 2),
(1, 'Standard', 5000.00, 350, 350, 3),

(2, 'Stage Front', 5000.00, 200, 200, 1),
(2, 'General Admission', 2500.00, 800, 800, 2),

(3, 'Workshop Entry', 3000.00, 100, 100, 1),

(4, 'Grand Stand', 2000.00, 500, 500, 1),
(4, 'Normal Stand', 1000.00, 4500, 4500, 2),

(5, 'Free Entry', 0.00, 2000, 2000, 1);


-- ========================================
-- Verification Queries
-- ========================================
-- SELECT * FROM users;
-- SELECT * FROM categories;
-- SELECT * FROM events;
-- SELECT * FROM registrations;
