CREATE TABLE IF NOT EXISTS sales(
    sale_id SERIAL PRIMARY KEY,
    size VARCHAR(255),
    client_id INTEGER REFERENCES clients(client_id),
    product_id INTEGER REFERENCES products(product_id)
)