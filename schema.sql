-- Schema for User and Contact Management System
CREATE TABLE IF NOT EXISTS USERS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('administrator','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Index on email for faster lookups
CREATE INDEX idx_email ON USERS(email);
CREATE TABLE IF NOT EXISTS Contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    Title VARCHAR(100),
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    company VARCHAR(100),
    type ENUM('Sales Lead','Support') NOT NULL,
    assigned_to INT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES USERS(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES USERS(id) ON DELETE CASCADE,
    CHECK (email LIKE '%_@__%.__%')
);

-- Index on assigned_to for faster lookups
CREATE INDEX idx_assigned_to ON Contacts(assigned_to);
CREATE TABLE IF NOT EXISTS Notes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    contact_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES Contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES USERS(id) ON DELETE CASCADE,
    CHECK (CHAR_LENGTH(comment) <= 2000)
);

-- Index on contact_id for faster lookups
CREATE INDEX idx_contact_id ON Notes(contact_id);
CREATE TABLE IF NOT EXISTS Audit_Log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    table_name VARCHAR(50) NOT NULL,
    action ENUM('INSERT','UPDATE','DELETE') NOT NULL,
    record_id INT NOT NULL,
    changed_by INT,
    change_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default administrator user
INSERT INTO USERS (firstname, lastname, password, email, role)
VALUES (
    'Admin',
    'User',
    '$2y$10$YwXT.cS1LZxTSFeJD5zZxODO8ppOgRsgvswAXIZYyER1S4X0aX/Te',
    'admin@project2.com',
    'administrator'
);

DELIMITER //

-- Sanitize USER input
CREATE TRIGGER sanitize_user_input
BEFORE INSERT ON USERS
FOR EACH ROW
BEGIN
    SET NEW.firstname = TRIM(NEW.firstname);
    SET NEW.lastname = TRIM(NEW.lastname);
    SET NEW.email = TRIM(NEW.email);
END;
//

-- Prevent duplicate CONTACT
CREATE TRIGGER prevent_duplicate_contact
BEFORE INSERT ON Contacts
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM Contacts
        WHERE firstname = NEW.firstname
          AND lastname = NEW.lastname
          AND email = NEW.email
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate contact not allowed';
    END IF;
END;
//

-- Sanitize CONTACT input
CREATE TRIGGER sanitize_contact_input
BEFORE INSERT ON Contacts
FOR EACH ROW
BEGIN
    SET NEW.firstname = TRIM(NEW.firstname);
    SET NEW.lastname = TRIM(NEW.lastname);
    SET NEW.email = TRIM(NEW.email);
    SET NEW.company = TRIM(NEW.company);
    SET NEW.Title = TRIM(NEW.Title);
END;
//

--  Audit INSERT on USERS
CREATE TRIGGER log_user_insert
AFTER INSERT ON USERS
FOR EACH ROW
BEGIN
    INSERT INTO Audit_Log(table_name, action, record_id, changed_by)
    VALUES ('USERS', 'INSERT', NEW.id, NULL);
END;
//

-- Audit UPDATE on CONTACTS
CREATE TRIGGER log_contact_update
AFTER UPDATE ON Contacts
FOR EACH ROW
BEGIN
    INSERT INTO Audit_Log(table_name, action, record_id, changed_by)
    VALUES ('Contacts', 'UPDATE', NEW.id, NULL);
END;
//

DELIMITER ;
