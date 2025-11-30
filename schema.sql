CREATE TABLE USERS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    password VARCHAR(255),
    email VARCHAR(100),
    role VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    Title VARCHAR(100),
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    email VARCHAR(100),
    telephone VARCHAR(20),
    company VARCHAR(100),
    type VARCHAR(50),
    assigned_to INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE Notes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    contact_id INT,
    comment TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO
    Users (
        firstname,
        lastname,
        password,
        email,
        role
    )
VALUES (
        'Admin',
        'User',
        '$2y$10$YwXT.cS1LZxTSFeJD5zZxODO8ppOgRsgvswAXIZYyER1S4X0aX/Te',
        'admin@project2.com',
        'administrator'
    );