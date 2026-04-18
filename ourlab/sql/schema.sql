USE my_project_db;

CREATE TABLE  user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    tel VARCHAR(20),
    v_token VARCHAR(100) NULL UNIQUE,-- verification_token
    is_active BOOLEAN,
    expiry_time DATETIME NULL -- expiry date for verification
)ENGINE=InnoDB;


CREATE TABLE  projectlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    creator VARCHAR(50), 
    Date DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_list_creator   FOREIGN KEY (creator) REFERENCES user(username) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;



CREATE TABLE project (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    status VARCHAR(50),
    approved BOOLEAN DEFAULT NULL,
    datePublished DATETIME DEFAULT CURRENT_TIMESTAMP,
    creator VARCHAR(50),
    subject TEXT,
    list INT,
    description_file VARCHAR(255) DEFAULT NULL,
    solution_file VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_project_list FOREIGN KEY (list) REFERENCES projectlist(id) ON DELETE CASCADE,
    CONSTRAINT fk_project_creator FOREIGN KEY (creator) REFERENCES user(username) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;


CREATE TABLE list_permissions (
    list_id INT,
    username VARCHAR(50),
    PRIMARY KEY (list_id, username),
    FOREIGN KEY (list_id) REFERENCES projectlist(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (username) REFERENCES user(username) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE project_assignments(
    project_id INT,
    username VARCHAR(50),
    PRIMARY KEY (project_id, username),
    FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (username) REFERENCES user(username) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO `user` (firstName, lastName, username, password, email, tel, v_token, is_active, expiry_time)
VALUES
('Alice', 'Doe', 'alice_dev', '$2y$10$t50lJGsoZx3By4Sg76iO4Os2BVxS.Ev3yUI4S/UmsUBF9p2bie4lm', 'alice@example.com', '123456789', NULL, TRUE, NULL),
('BoB', 'Smith', 'bob1', '$2y$10$t50lJGsoZx3By4Sg76iO4Os2BVxS.Ev3yUI4S/UmsUBF9p2bie4lm', 'bob@example.com', '987654321', NULL, TRUE, NULL);