-- Create the admin_credentials table if it doesn't exist
CREATE TABLE IF NOT EXISTS admin_credentials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert default admin credentials
-- Username: admin
-- Email: admin@rentflow.com
-- Password: admin123
INSERT INTO admin_credentials (admin_name, email, password) 
VALUES ('admin', 'admin@rentflow.com', 'admin123')
ON DUPLICATE KEY UPDATE 
    email = 'admin@rentflow.com',
    password = 'admin123'; 