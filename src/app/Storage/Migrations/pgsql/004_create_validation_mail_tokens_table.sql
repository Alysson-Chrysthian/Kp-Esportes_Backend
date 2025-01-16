CREATE TABLE IF NOT EXISTS validation_mail_tokens(
    validation_mail_token_id SERIAL PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    user_info VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    expires_at TIMESTAMP
)