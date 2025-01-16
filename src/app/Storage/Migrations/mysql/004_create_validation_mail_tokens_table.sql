CREATE TABLE IF NOT EXISTS validation_mail_tokens(
    validation_mail_token_id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    user_info VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    expires_at TIMESTAMP
)