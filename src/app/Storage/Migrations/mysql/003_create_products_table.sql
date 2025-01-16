CREATE TABLE IF NOT EXISTS products(
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price FLOAT NOT NULL,
    size VARCHAR(255),
    discount FLOAT,
    admin_id INT REFERENCES admins(admin_id),
    category_id INT REFERENCES categories(category_id),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)