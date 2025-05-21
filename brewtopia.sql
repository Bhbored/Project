-- Create database (if not already exists)
CREATE DATABASE IF NOT EXISTS brewtopia;
USE brewtopia;

-- Drop old tables for a clean reimport
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS items;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
);

-- Items Table (your main product list)
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(6,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255) DEFAULT 'images/placeholder.jpg'
);

-- Insert Coffee Items
INSERT INTO items (name, description, price, stock, image_url) VALUES
('Espresso', 'Strong and black coffee.', 3.50, 10, 'images/espresso.jpg'),
('Cappuccino', 'Coffee with steamed milk and foam.', 4.00, 15, 'images/cappuccino.jpg'),
('Latte', 'Coffee with lots of milk.', 4.50, 12, 'images/latte.jpg'),
('Mocha', 'Coffee with chocolate.', 5.00, 8, 'images/mocha.jpg'),
('Americano', 'Espresso with hot water.', 3.00, 20, 'images/americano.jpg'),
('Macchiato', 'Espresso with a dash of milk.', 3.75, 10, 'images/macchiato.jpg'),

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(100),
    item_id INT,
    quantity INT DEFAULT 1,
    status ENUM('pending', 'confirmed') DEFAULT 'pending',
    order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(id)
);

-- Insert Sample Users
INSERT INTO users (email, password, is_admin) VALUES
('admin', 'admin', TRUE),
('user@example.com', '1234', FALSE);
