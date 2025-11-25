create database Ride_sharing

use Ride_sharing

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(100) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(100) NOT NULL,
    us_typ ENUM('driver', 'passenger') NOT NULL,
    created_date DATETIME NOT NULL
);

CREATE TABLE Rides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_id INT NOT NULL,
    start_location VARCHAR(255) NOT NULL,
    end_location VARCHAR(255) NOT NULL,
    departure_time DATETIME NOT NULL,
    available_seats INT NOT NULL,
    price_per_seat DECIMAL NOT NULL,
    status ENUM('active', 'completed', 'cancelled') NOT NULL,
    FOREIGN KEY (driver_id) REFERENCES Users(id)
    ON DELETE CASCADE
);

CREATE TABLE Reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ride_id INT NOT NULL,
    passenger_id INT NOT NULL,
    reserved_seats INT NOT NULL,
    reservation_time DATETIME NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') NOT NULL,
    FOREIGN KEY (ride_id) REFERENCES Rides(id)
    ON DELETE CASCADE,
    FOREIGN KEY (passenger_id) REFERENCES Users(id)
    ON DELETE CASCADE
);

CREATE TABLE Reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ride_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    reviewed_user_id INT NOT NULL,
    rating INT NOT NULL,
    comment VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (ride_id) REFERENCES Rides(id)
    ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES Users(id)
    ON DELETE CASCADE,
    FOREIGN KEY (reviewed_user_id) REFERENCES Users(id)
    ON DELETE CASCADE
);

INSERT INTO Users (username, email, password_hash, full_name, phone_number, us_typ, created_date) VALUES
('john_doe', 'john.doe@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'John Doe', '0750500551', 'driver', '2025-07-29'),
('m_smith', 'michael.smith@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Michael Smith', '0797846383', 'driver', '2024-10-21'),
('sarah_j', 'sarah.johnson@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Sarah Johnson', '0716124731', 'driver', '2024-01-26'),
('d_wilson', 'david.wilson@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'David Wilson', '0780005009', 'driver', '2025-05-10'),
('l_roberts', 'lisa.roberts@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Lisa Roberts', '0763560161', 'driver', '2024-05-26'),
('james_b', 'james.brown@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'James Brown', '0791504536', 'driver', '2025-05-05'),
('emily_g', 'emily.garcia@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Emily Garcia', '0735476350', 'driver', '2025-02-01'),
('robert_m', 'robert.miller@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Robert Miller', '0746454407', 'driver', '2024-09-26'),
('amy_davis', 'amy.davis@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Amy Davis', '0737243815', 'driver', '2025-01-24'),
('t_rodriguez', 'thomas.rodriguez@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Thomas Rodriguez', '0773565553', 'driver', '2024-10-13'),
('sophia_l', 'sophia.lopez@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Sophia Lopez', '0703869764', 'driver', '2024-07-31'),
('daniel_h', 'daniel.hernandez@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Daniel Hernandez', '0752908219', 'driver', '2025-03-20'),
('olivia_c', 'olivia.clark@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Olivia Clark', '0759213270', 'driver', '2024-12-02'),
('william_t', 'william.taylor@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'William Taylor', '0784429661', 'driver', '2024-11-29'),
('ava_m', 'ava.martinez@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Ava Martinez', '0735944749', 'driver', '2024-11-12'),
('joseph_a', 'joseph.anderson@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Joseph Anderson', '0738880502', 'driver', '2024-04-30'),
('charlotte_w', 'charlotte.white@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Charlotte White', '0742713826', 'driver', '2025-08-07'),
('benjamin_lee', 'benjamin.lee@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Benjamin Lee', '0739145522', 'driver', '2024-06-06'),
('mia_harris', 'mia.harris@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Mia Harris', '0743710487', 'driver', '2024-08-17'),
('samuel_k', 'samuel.king@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Samuel King', '0739057140', 'driver', '2025-04-02'),
('alex_r', 'alex.ryan@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Alex Ryan', '0738345216', 'passenger', '2024-06-02'),
('s_jackson', 'sophie.jackson@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Sophie Jackson', '0729193285', 'passenger', '2025-08-02'),
('liam_p', 'liam.parker@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Liam Parker', '0749851632', 'passenger', '2025-03-02'),
('emma_w', 'emma.wright@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Emma Wright', '0728130945', 'passenger', '2024-12-18'),
('noah_s', 'noah.scott@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Noah Scott', '0752037746', 'passenger', '2025-08-05'),
('isabella_g', 'isabella.green@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Isabella Green', '0766923814', 'passenger', '2024-05-13'),
('ethan_b', 'ethan.baker@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Ethan Baker', '0753012249', 'passenger', '2024-02-27'),
('mia_adams', 'mia.adams@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Mia Adams', '0736962041', 'passenger', '2024-08-22'),
('jacob_n', 'jacob.nelson@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Jacob Nelson', '0742651482', 'passenger', '2024-04-05'),
('ava_carter', 'ava.carter@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Ava Carter', '0767193304', 'passenger', '2024-09-06'),
('logan_m', 'logan.mitchell@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Logan Mitchell', '0762473586', 'passenger', '2024-03-04'),
('amelia_p', 'amelia.perez@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Amelia Perez', '0752790443', 'passenger', '2024-10-14'),
('lucas_r', 'lucas.roberts@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Lucas Roberts', '0711362850', 'passenger', '2025-07-15'),
('harper_t', 'harper.turner@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Harper Turner', '0745263093', 'passenger', '2024-02-16'),
('aiden_p', 'aiden.phillips@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Aiden Phillips', '0705281497', 'passenger', '2025-06-09'),
('ella_c', 'ella.campbell@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Ella Campbell', '0727049138', 'passenger', '2024-03-03'),
('sebastian_r', 'sebastian.reed@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Sebastian Reed', '0738140295', 'passenger', '2024-12-16'),
('oliver_b', 'oliver.bryant@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Oliver Bryant', '0732439986', 'passenger', '2024-05-04'),
('sofia_m', 'sofia.morris@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Sofia Morris', '0729165783', 'passenger', '2024-11-20'),
('jackson_l', 'jackson.lopez@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Jackson Lopez', '0752590651', 'passenger', '2025-06-26'),
('grace_w', 'grace.watson@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Grace Watson', '0767394210', 'passenger', '2024-08-28'),
('chloe_s', 'chloe.sanders@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Chloe Sanders', '0740153429', 'passenger', '2025-02-04'),
('henry_k', 'henry.kim@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Henry Kim', '0795400612', 'passenger', '2024-03-08'),
('lily_j', 'lily.james@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Lily James', '0774678902', 'passenger', '2024-06-18'),
('mason_s', 'mason.stewart@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Mason Stewart', '0798571618', 'passenger', '2024-09-24'),
('victoria_r', 'victoria.reyes@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Victoria Reyes', '0718862048', 'passenger', '2025-04-28'),
('zachary_b', 'zachary.bryan@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Zachary Bryan', '0725333042', 'passenger', '2025-02-12'),
('nora_c', 'nora.coleman@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Nora Coleman', '0797138912', 'passenger', '2025-07-21'),
('caleb_w', 'caleb.walsh@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Caleb Walsh', '0790437589', 'passenger', '2025-01-11'),
('riley_s', 'riley.simmons@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Riley Simmons', '0702384330', 'passenger', '2025-08-20'),
('dylan_p', 'dylan.paul@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Dylan Paul', '0730506130', 'passenger', '2024-04-18'),
('hazel_b', 'hazel.bennett@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Hazel Bennett', '0761457836', 'passenger', '2024-03-29'),
('hunter_g', 'hunter.gomez@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Hunter Gomez', '0720735661', 'passenger', '2025-08-01'),
('penelope_r', 'penelope.ross@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Penelope Ross', '0794377531', 'passenger', '2024-11-18'),
('everett_m', 'everett.murphy@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Everett Murphy', '0737405052', 'passenger', '2024-02-08'),
('zoe_k', 'zoe.keller@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Zoe Keller', '0785634204', 'passenger', '2024-06-24'),
('carter_h', 'carter.hughes@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Carter Hughes', '0715450425', 'passenger', '2024-02-27'),
('nathan_b', 'nathan.bryce@email.com', '$2a$10$xJwL5vZsL3ZjJ5hJ5v5zL3', 'Nathan Bryce', '0797910371', 'passenger', '2024-12-11');


INSERT INTO Rides (driver_id, start_location, end_location, departure_time, available_seats, price_per_seat, status) VALUES
(1, 'Central Station, Downtown', 'University Campus', '2024-01-03 21:33:28', 1, 15.26, 'active'),
(2, 'Green Park Mall', 'Airport Terminal 1', '2024-09-10 15:21:33', 3, 16.92, 'completed'),
(3, 'City Library', 'Tech Park North', '2025-05-05 01:24:23', 1, 24.43, 'cancelled'),
(4, 'Riverside Apartments', 'Downtown Business District', '2024-07-20 20:53:50', 2, 23.71, 'completed'),
(5, 'Sunset Boulevard', 'Marina Bay', '2024-04-08 06:42:43', 3, 21.77, 'active'),
(6, 'North Hills Suburb', 'Central Hospital', '2024-10-04 01:58:22', 3, 10.02, 'completed'),
(7, 'Westwood Hills Suburb', 'Convention Center', '2024-09-07 14:32:28', 4, 16.78, 'completed'),
(8, 'Elmwood View Apartments', 'Financial District', '2024-01-18 16:02:18', 4, 10.56, 'cancelled'),
(9, 'Old Town Square', 'New Tech Hub', '2024-07-25 23:03:41', 1, 24.96, 'active'),
(10, 'Central Park West', 'University Medical Center', '2024-03-29 19:06:16', 2, 13.56, 'active'),
(11, 'Southside Station', 'Airport Terminal 2', '2025-04-17 06:27:36', 1, 12.71, 'completed'),
(12, 'Hillside Residence', 'Downtown Shopping District', '2024-12-22 21:00:55', 2, 20.82, 'cancelled'),
(13, 'Valley View Apartments', 'Tech Park South', '2024-03-13 23:53:34', 1, 11.8, 'cancelled'),
(14, 'Oceanfront Promenade', 'Financial District', '2025-08-09 20:09:51', 2, 17.0, 'active'),
(15, 'Maplewood Suburb', 'City Stadium', '2025-09-02 05:56:30', 2, 14.6, 'completed'),
(16, 'Highland Avenue', 'Sports Arena', '2025-02-13 00:56:03', 3, 22.37, 'completed'),
(17, 'Downtown Plaza', 'Marina Bay', '2025-02-11 20:01:18', 3, 16.26, 'active'),
(18, 'University Campus', 'Central Station, Downtown', '2025-08-30 04:51:42', 3, 17.05, 'cancelled'),
(19, 'Pine Street', 'Central Hospital', '2025-05-31 06:46:30', 2, 22.7, 'completed'),
(20, 'Hillcrest Park', 'Convention Center', '2024-01-20 01:44:22', 1, 21.74, 'completed');

INSERT INTO Reservations (ride_id, passenger_id, reserved_seats, reservation_time, status) VALUES
(1, 21, 1, '2023-05-28 14:30:00', 'confirmed'),
(2, 22, 1, '2023-05-28 15:45:00', 'confirmed'),
(2, 23, 1, '2023-05-29 18:00:00', 'confirmed'),
(2, 24, 1, '2023-05-29 14:15:00', 'confirmed'),
(3, 25, 1, '2023-05-30 10:00:00', 'confirmed'),
(3, 26, 1, '2023-05-30 14:30:00', 'confirmed'),
(3, 27, 1, '2023-05-30 12:15:00', 'confirmed'),
(3, 28, 1, '2023-05-30 16:45:00', 'confirmed'),
(4, 29, 2, '2023-05-31 18:45:00', 'confirmed'),
(4, 30, 1, '2023-05-31 19:00:00', 'confirmed'),
(5, 31, 2, '2023-06-01 12:15:00', 'confirmed'),
(6, 32, 1, '2023-06-01 14:30:00', 'confirmed'),
(6, 33, 1, '2023-06-02 08:00:00', 'confirmed'),
(6, 34, 1, '2023-06-02 08:45:00', 'confirmed'),
(7, 35, 2, '2023-06-02 09:15:00', 'confirmed'),
(7, 36, 1, '2023-06-02 09:45:00', 'confirmed'),
(8, 37, 1, '2023-06-02 14:30:00', 'confirmed'),
(8, 38, 2, '2023-06-03 07:00:00', 'confirmed'),
(8, 39, 1, '2023-06-03 11:00:00', 'confirmed'),
(9, 40, 1, '2023-06-03 13:30:00', 'confirmed'),
(9, 41, 1, '2023-06-04 08:45:00', 'confirmed'),
(10, 42, 2, '2023-06-04 10:00:00', 'confirmed');

INSERT INTO Reviews (ride_id, reviewer_id, reviewed_user_id, rating, comment, created_at) VALUES
(1, 21, 1, 5, 'Great driver, very punctual and safe!', '2023-06-01 09:30:00'),
(1, 22, 1, 4, 'Comfortable ride, would book again.', '2023-06-01 09:45:00'),
(1, 23, 1, 5, 'Excellent service, highly recommend!', '2023-06-01 10:00:00'),
(1, 24, 1, 4, 'Good conversation and safe driving.', '2023-06-01 10:30:00'),
(2, 25, 2, 5, 'Made it to the airport with time to spare!', '2023-06-01 11:15:00'),
(2, 26, 3, 5, 'Very professional and courteous driver.', '2023-06-01 11:45:00'),
(3, 27, 3, 4, 'Clean car and good music selection.', '2023-06-01 12:30:00'),
(3, 28, 4, 3, 'Got me to work on time but took a longer route.', '2023-06-01 08:30:00'),
(3, 29, 4, 4, 'Polite driver, comfortable seats.', '2023-06-01 08:45:00'),
(4, 30, 5, 5, 'Beautiful sunset ride along the coast!', '2023-06-01 18:45:00'),
(4, 31, 5, 4, 'Helped with my luggage, very kind.', '2023-06-01 19:00:00'),
(5, 32, 6, 5, 'Perfect for my hospital appointment.', '2023-06-02 09:15:00'),
(6, 33, 7, 3, 'A little late but good ride overall.', '2023-06-02 10:15:00'),
(6, 34, 7, 4, 'Comfortable and efficient.', '2023-06-02 10:30:00'),
(7, 35, 8, 5, 'Best ride to work I ever had!', '2023-06-02 12:00:00'),
(8, 36, 8, 4, 'Fun driver, made the trip enjoyable.', '2023-06-02 19:15:00'),
(9, 37, 9, 5, 'Went above and beyond to drop me closer to home.', '2023-06-02 19:30:00'),
(10, 42, 10, 4, 'Good price for the distance.', '2023-06-03 09:30:00');

CREATE USER 'ride_admin'@'Proiect BD' IDENTIFIED BY ' AdminPass123!';
GRANT ALL PRIVILEGES ON Ride_sharing.* TO  'ride_admin'@'Proiect BD' WITH GRANT OPTION;

CREATE USER 'ops_manager'@'Proiect BD' IDENTIFIED BY 'OpsPass456!';
GRANT SELECT, INSERT, UPDATE, DELETE ON Ride_sharing.* TO 'ops_manager'@'Proiect BD';

CREATE USER 'data_analyst'@'Proiect BD' IDENTIFIED BY 'AnalystPass789!';
GRANT SELECT ON Ride_sharing.* TO 'data_analyst'@'Proiect BD';

CREATE USER'support_agent'@'Proiect BD' IDENTIFIED BY 'SupportPass321!';
GRANT SELECT ON Ride_sharing.Users TO 'support_agent'@'Proiect BD';
GRANT SELECT, UPDATE ON Ride_sharing.Reservations TO 'support_agent'@'Proiect BD';
GRANT SELECT ON Ride_sharing.Rides TO 'support_agent'@'Proiect BD';

CREATE USER 'Passenger_app'@'Proiect BD' IDENTIFIED BY 'SimplePass123';
GRANT SELECT ON Ride_sharing.Rides TO 'Passenger_app'@'Proiect BD';
GRANT SELECT, INSERT, UPDATE ON Ride_sharing.Reservations TO 'Passenger_app'@'Proiect BD';
GRANT SELECT ON Ride_sharing.Users TO 'Passenger_app'@'Proiect BD';

UPDATE Users SET password_hash = SHA2(password_hash, 256);
UPDATE Users SET phone_number = SHA2(phone_number, 256);