CREATE TABLE IF NOT EXISTS categories(
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    admin_id INT REFERENCES admins(admin_id),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)