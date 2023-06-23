CREATE TABLE user_roles
(
   userID INT,
   roleID INT,
   PRIMARY KEY (userID, roleID),
   FOREIGN KEY (userID) REFERENCES users(id),
   FOREIGN KEY (roleID) REFERENCES roles(id)
);