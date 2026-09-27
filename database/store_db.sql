CREATE DATABASE IF NOT EXISTS store_db;
USE store_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data Awal (Dummy Data)
INSERT INTO products (name, category, price, stock) VALUES
('Canon IXY Digital 930 IS', 'Digicam', 1850000, 3),
('Sony Cyber-shot DSC-W350', 'Digicam', 1450000, 2),
('Olympus OM-1 MD Film Camera', 'Analog', 2750000, 1),
('Fujifilm FinePix Z33WP', 'Digicam', 1200000, 4);
