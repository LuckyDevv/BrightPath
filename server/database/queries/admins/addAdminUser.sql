-- admin_add.sql
INSERT INTO `admin_users` (
    `login`,
    `password_hash`,
    `role`,
    `is_locked`,
    `totp_secret`,
    `password_updated_at`
) VALUES (
    :login,
    :password_hash,
    :role,
    :is_locked,
    :totp_secret,
    NOW()
);