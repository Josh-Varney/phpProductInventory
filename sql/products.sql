CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    status ENUM('In Stock', 'Low Stock', 'Out of Stock') NOT NULL
);

INSERT INTO products (name, quantity, price, status)
VALUES
    ('Wireless Mouse', 120, 19.99, 'In Stock'),
    ('Mechanical Keyboard', 35, 89.99, 'Low Stock'),
    ('Gaming Headset', 5, 129.99, 'Low Stock'),
    ('USB-C Cable', 300, 9.99, 'In Stock'),
    ('Bluetooth Speaker', 0, 59.99, 'Out of Stock'),
    ('Laptop Stand', 60, 25.99, 'In Stock'),
    ('Smartphone Charger', 150, 14.99, 'In Stock'),
    ('4K Monitor', 15, 349.99, 'Low Stock'),
    ('Wireless Earbuds', 0, 79.99, 'Out of Stock'),
    ('Portable SSD', 50, 119.99, 'In Stock');
