CREATE TABLE IF NOT EXISTS products(
    product_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price FLOAT NOT NULL,
    size VARCHAR(255),
    image VARCHAR(255),
    discount FLOAT,
    admin_id INTEGER REFERENCES admins(admin_id),
    category_id INTEGER REFERENCES categories(category_id),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)