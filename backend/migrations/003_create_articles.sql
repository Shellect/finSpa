CREATE TABLE articles
(
    id      INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title   VARCHAR(100),
    content TEXT,
    userID  INT,
    FOREIGN KEY (userID) REFERENCES users (id)
);