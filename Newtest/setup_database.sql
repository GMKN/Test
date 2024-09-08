CREATE DATABASE visit_counter;

USE visit_counter;

CREATE TABLE visit_count (
    id INT AUTO_INCREMENT PRIMARY KEY,
    count INT NOT NULL
);

-- Initialize the visit count to 0
INSERT INTO visit_count (count) VALUES (0);
