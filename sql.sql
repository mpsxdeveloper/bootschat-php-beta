CREATE DATABASE bootschat;

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(45) NOT NULL,
    nickname VARCHAR(17) NOT NULL,
    password VARCHAR(60) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE(email, nickname)
);

CREATE TABLE invites (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(45) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE(email)
);

CREATE TABLE rooms (
    id INT NOT NULL AUTO_INCREMENT,
    description VARCHAR(25) NOT NULL,
    PRIMARY KEY(id),
    UNIQUE(description)
);

CREATE TABLE users_rooms (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE messages (
    id INT NOT NULL AUTO_INCREMENT,
    room_id INT NOT NULL,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    datemsg DATETIME NOT NULL,
    msg VARCHAR(100) NOT NULL,
    type ENUM('text', 'image', 'welcome', 'bye') NOT NULL DEFAULT 'text',
    PRIMARY KEY(id)
);