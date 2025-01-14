CREATE TABLE IF NOT EXISTS avaliations(
    avaliation_id SERIAL PRIMARY KEY,
    commentary TEXT,
    stars INTEGER,
    product_id INTEGER REFERENCES products(product_id),
    client_id INTEGER REFERENCES clients(client_id)
)