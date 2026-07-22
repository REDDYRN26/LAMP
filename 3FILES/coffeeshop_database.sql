-- Coffee Shop Database Setup
-- Create database
CREATE DATABASE IF NOT EXISTS coffeeshop;
USE coffeeshop;

-- Users/Customers Table
CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  phone VARCHAR(20),
  password VARCHAR(255) NOT NULL,
  address TEXT,
  city VARCHAR(50),
  zip_code VARCHAR(10),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products/Menu Table
CREATE TABLE IF NOT EXISTS products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  category VARCHAR(50) NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  image VARCHAR(255),
  stock INT DEFAULT 50,
  is_available BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  total_amount DECIMAL(10, 2) NOT NULL,
  status ENUM('pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
  payment_method VARCHAR(50),
  delivery_address TEXT,
  special_instructions TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Cart Table (Temporary)
CREATE TABLE IF NOT EXISTS cart (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Sample Coffee Products
INSERT INTO products (name, description, category, price, image) VALUES
('Espresso', 'Strong, concentrated black coffee', 'Black Coffee', 2.50, 'espresso.jpg'),
('Cappuccino', 'Espresso with steamed milk and foam', 'Milk Coffee', 3.50, 'cappuccino.jpg'),
('Latte', 'Espresso with more milk and less foam', 'Milk Coffee', 3.75, 'latte.jpg'),
('Americano', 'Espresso diluted with hot water', 'Black Coffee', 2.75, 'americano.jpg'),
('Mocha', 'Espresso, milk, and chocolate', 'Specialty', 4.25, 'mocha.jpg'),
('Macchiato', 'Espresso "marked" with milk foam', 'Milk Coffee', 3.25, 'macchiato.jpg'),
('Iced Coffee', 'Chilled coffee with ice', 'Cold Coffee', 3.00, 'iced-coffee.jpg'),
('Flat White', 'Espresso with velvety steamed milk', 'Milk Coffee', 4.00, 'flat-white.jpg'),
('Cortado', 'Equal parts espresso and steamed milk', 'Milk Coffee', 3.00, 'cortado.jpg'),
('Affogato', 'Espresso poured over ice cream', 'Specialty', 4.75, 'affogato.jpg');
