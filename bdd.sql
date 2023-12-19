CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lastName VARCHAR(255) NOT NULL,
    firstName VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    job VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    telephone VARCHAR(20) NOT NULL;
)