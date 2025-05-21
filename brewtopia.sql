-- Create database (you can skip this if already created)
CREATE DATABASE IF NOT EXISTS brewtopia;
USE brewtopia;

-- Drop existing tables to avoid duplicates on re-import
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS orders;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
);

-- Menu Items Table
CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(6,2) NOT NULL,
    image_url VARCHAR(255) DEFAULT 'placeholder.jpg',
    quantity INT DEFAULT 10
);

-- Orders Table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_email VARCHAR(100),
    item_id INT,
    quantity INT DEFAULT 1,
    status ENUM('pending', 'confirmed') DEFAULT 'pending',
    order_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES menu_items(id)
);

-- Optional: Sample Admin and User
INSERT INTO users (email, password, is_admin) VALUES
('admin', 'admin', TRUE),
('user@example.com', '1234', FALSE);

-- Optional: Sample Menu Items
INSERT INTO menu_items (name, description, price, image_url, quantity) VALUES
('Espresso', 'Strong and bold coffee.', 2.50, 'images/espresso.jpg', 15),
('Cappuccino', 'Coffee with steamed milk and foam.', 3.00, 'images/cappuccino.jpg', 10),
('Latte', 'Creamy latte with milk.', 3.50, 'images/latte.jpg', 12),
('Cold Brew', 'Chilled slow-brewed coffee.', 3.25, 'images/coldbrew.jpg', 8);
