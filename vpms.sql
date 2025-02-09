DROP DATABASE IF EXISTS vpmsx;
CREATE DATABASE vpmsx;
USE vpmsx;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('a', 'u' , 'o' ) DEFAULT 'u'
);

CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_type VARCHAR(50) NOT NULL UNIQUE,
    price_by_hour DECIMAL(10, 2) NOT NULL
);


CREATE TABLE REGIS_VEHICLES (

    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    
    vehicle_type VARCHAR(50) NOT NULL,
    number_plate VARCHAR(50) UNIQUE NOT NULL,
    parking_status ENUM('y', 'n') DEFAULT 'n',
    
    start_time TIMESTAMP,
    end_time TIMESTAMP,

    CONSTRAINT fk_regis_vehicle_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    regis_vehicle_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    bill_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (regis_vehicle_id) REFERENCES REGIS_VEHICLES(id) ON DELETE CASCADE
);


CREATE TABLE feedback (

    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) DEFAULT NULL, 
    feedback TEXT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    role ENUM('a', 'u' , 'o' ) DEFAULT 'o'
);

-- Insert Vehicles
INSERT INTO vehicles (vehicle_type, price_by_hour) VALUES ('Motorcycle', 10);
INSERT INTO vehicles (vehicle_type, price_by_hour) VALUES ('Car', 20);
INSERT INTO vehicles (vehicle_type, price_by_hour) VALUES ('Van', 20);
INSERT INTO vehicles (vehicle_type, price_by_hour) VALUES ('Bus', 25);
INSERT INTO vehicles (vehicle_type, price_by_hour) VALUES ('Pickup Truck', 30);
INSERT INTO vehicles (vehicle_type, price_by_hour) VALUES ('Heavy Truck', 35);


-- Insert Users

insert into users (username, password, role) values ('admin', '123123', 'a');
insert into users (username, password, role) values ('user1', '123123', 'u');
insert into users (username, password, role) values ('user2', '123123', 'u');

-- Insert Register Vehicles

INSERT INTO REGIS_VEHICLES (user_id,  vehicle_type, number_plate, parking_status) 
VALUES (
    (SELECT id FROM users WHERE username = 'user1' LIMIT 1), 
    'Car', 'ABC123', 'n'
);

INSERT INTO REGIS_VEHICLES (user_id,  vehicle_type, number_plate, parking_status) 
VALUES (
    (SELECT id FROM users WHERE username = 'user2' LIMIT 1), 
    'Motorcycle', 'XYZ123', 'n'
);

INSERT INTO REGIS_VEHICLES (user_id,  vehicle_type, number_plate, parking_status) 
VALUES (
    (SELECT id FROM users WHERE username = 'user1' LIMIT 1), 
    'Van', 'DEF123', 'n'
);

INSERT INTO REGIS_VEHICLES (user_id,  vehicle_type, number_plate, parking_status) 
VALUES (
    (SELECT id FROM users WHERE username = 'user2' LIMIT 1), 
    'Bus', 'GHI123', 'n'
);

INSERT INTO REGIS_VEHICLES (user_id,  vehicle_type, number_plate, parking_status) 
VALUES (
    (SELECT id FROM users WHERE username = 'user1' LIMIT 1), 
    'Pickup Truck', 'JKL123', 'n'
);

-- Insert Feedback
insert into feedback (user_name, feedback, rating, role) values ('user1', 'Good Service', 5, 'u');
insert into feedback (user_name, feedback, rating, role) values ('user2', 'Bad Service', 1, 'u');
insert into feedback (feedback, rating, role) values ( 'Good Service', 5, 'o');
insert into feedback (feedback, rating, role) values ( 'Bad Service', 1, 'o');

