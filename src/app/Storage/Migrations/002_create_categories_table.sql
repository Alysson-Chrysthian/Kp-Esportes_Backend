CREATE TABLE IF NOT EXISTS categories(
    category_id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    admin_id INTEGER REFERENCES admins(admin_id),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)