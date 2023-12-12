-- Create a database
CREATE DATABASE IF NOT EXISTS event_management;

-- Use the database
USE event_management;

-- Table for users
CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  user_img VARCHAR(255) DEFAULT NULL,
  user_role VARCHAR(50) NOT NULL DEFAULT 'user'
);

-- Table for events
CREATE TABLE IF NOT EXISTS events (
  event_id INT AUTO_INCREMENT PRIMARY KEY,
  event_name VARCHAR(255) NOT NULL,
  event_date DATE NOT NULL,
  event_type ENUM('conférence', 'forum', 'formation', 'voyage') NOT NULL,
  event_details TEXT,
  event_img VARCHAR(255),
  event_latitude DECIMAL(10, 8) DEFAULT NULL,
  event_longitude DECIMAL(11, 8) DEFAULT NULL,
  organizer_id INT,
  FOREIGN KEY (organizer_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Table for event registrations
CREATE TABLE IF NOT EXISTS registrations (
  registration_id INT AUTO_INCREMENT PRIMARY KEY,
  event_id INT,
  user_id INT,
  registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Insert some fake users data
INSERT INTO users (username, email, password, user_img, user_role)
VALUES ('Mouad Ananouch', 'mouad.ananouch@uit.ac.ma', 'secret', 'images/m.jpg', 'user'),
('Mouad-P7', 'mouadananouch7@gmail.com', 'secret', 'images/smile-2.jpg', 'user'),
('club anaruz', 'anaruz@gmail.com', 'secret', 'images/anaruz.jpg', 'admin');

-- Insert some fake events data
INSERT INTO events (organizer_id, event_name, event_date, event_details, event_img, event_latitude, event_longitude, event_type)
VALUES (3, '"Arabian Nights" in Morocco', '2023-12-30', 'Morocco Arabian Nights event offers an enchanting experience with vibrant decor, traditional music, dance, and a feast of Moroccan cuisine, immersing guests in the magical tales.', 'images/morocco.jpg', 31.59078450242524, -7.97583585657646, 'voyage'),
(3, 'ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum', '2023-12-31', 'ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023 ENSAK Forum 2023', 'images/ensak.jpg', 34.25257320, -6.53506217, 'conférence'),
(3, 'Caravan', '2024-01-06', 'description.', 'images/caravan.jpg', 31.59078450242524, -7.97583585657646, 'voyage');

-- Insert some fake registrations data
INSERT INTO registrations (event_id, user_id)
VALUES (1, 1), (1, 2);
