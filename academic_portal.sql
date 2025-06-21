-- academic_portal.sql
CREATE DATABASE IF NOT EXISTS academic_portal;
USE academic_portal;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'lecturer', 'student') NOT NULL
);

INSERT INTO users (name, email, password, role) VALUES
(
  'Administrator',
  'admin@gmail.com',
  '$2y$10$e0NR/vS4v7vl5ZXxSb/ckOLaw64OXxPN2hl0cjXZ3vWw97tbnd8yW',
  'admin'
);

CREATE TABLE IF NOT EXISTS calendar (
  id INT AUTO_INCREMENT PRIMARY KEY,
  week_number INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  description VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS notices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  type ENUM('General', 'Exam', 'Event') NOT NULL,
  file VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
