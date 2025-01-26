CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(50) NOT NULL;
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    status ENUM('In Stock', 'Low Stock', 'Out of Stock') NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON
    deleted BOOLEAN NOT NULL DEFAULT FALSE;
    ADD CONSTRAINT unique_name UNIQUE (name);
    ADD CONSTRAINT check_quantity CHECK (quantity >= 0 AND quantity <= 1000);
    ADD CONSTRAINT check_price CHECK (price > 0 AND price <= 10000);
    ADD CONSTRAINT check_price_quantity CHECK ((price > 100 AND quantity >= 10) OR price <= 100);
    ADD CONSTRAINT check_promo_price CHECK ( (name LIKE '%promo%' AND price < 50) OR name NOT LIKE '%promo%' );
    
);

-- Products with "no promo" restriction 
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

-- Products with "promo" in the name (price < 50)
INSERT INTO products (name, quantity, price, status)
VALUES
('Promo T-shirt', 20, 25.99, 'In Stock'),
('Winter Promo Jacket', 15, 39.99, 'Low Stock'),
('Promo Socks Pack', 50, 15.00, 'In Stock'),
('Flash Sale Promo Sneakers', 30, 49.99, 'In Stock');


-- Violate unique_name (Duplicate name)
INSERT INTO products (name, quantity, price, status)
VALUES
('4K Monitor', 15, 349.99, 'Low Stock'),  -- Violates UNIQUE constraint

-- Violate check_quantity (Quantity < 0)
INSERT INTO products (name, quantity, price, status)
VALUES
('Old Book', -5, 15.99, 'In Stock');  -- Violates check_quantity constraint (quantity < 0)

-- Violate check_price (Price <= 0)
INSERT INTO products (name, quantity, price, status)
VALUES
('Broken Toy', 10, 0, 'Out of Stock');  -- Violates check_price constraint (price <= 0)

-- Violate check_price (Price > 10,000)
INSERT INTO products (name, quantity, price, status)
VALUES
('Luxury Car', 2, 20000, 'In Stock');  -- Violates check_price constraint (price > 10,000)

-- Violate check_price_quantity (Price > 100 and Quantity < 10)
INSERT INTO products (name, quantity, price, status)
VALUES
('Expensive Vase', 5, 150.00, 'In Stock');  -- Violates check_price_quantity constraint (price > 100 and quantity < 10)

-- Violate check_promo_price (Price >= 50 for "promo" items)
INSERT INTO products (name, quantity, price, status)
VALUES
('Promo T-shirt', 30, 55.00, 'In Stock');  -- Violates check_promo_price constraint (price must be < 50 for promo items)
