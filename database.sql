-- Create the database
CREATE DATABASE school_online_exam;

-- Use the newly created database
USE school_online_exam;

-- Create Users table
CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('Admin', 'Teacher', 'Student') NOT NULL
);

-- Create Exams table
CREATE TABLE Exams (
    exam_id INT PRIMARY KEY AUTO_INCREMENT,
    exam_name VARCHAR(100) NOT NULL,
    exam_description TEXT,
    duration INT NOT NULL,
    start_time DATETIME,
    end_time DATETIME,
    is_active BOOLEAN DEFAULT TRUE
);

-- Create Questions table
CREATE TABLE Questions (
    question_id INT PRIMARY KEY AUTO_INCREMENT,
    exam_id INT,
    question_text TEXT NOT NULL,
    question_type ENUM('Text', 'Multiple Choice') NOT NULL,
    option_1 VARCHAR(255),
    option_2 VARCHAR(255),
    option_3 VARCHAR(255),
    correct_answer VARCHAR(255),
    FOREIGN KEY (exam_id) REFERENCES Exams(exam_id)
);

-- Create Choices table
CREATE TABLE Choices (
    choice_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    question_id INT,
    chosen_answer VARCHAR(255) NOT NULL,
    correct_answer VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (question_id) REFERENCES Questions(question_id)
);

-- Create Responses table
CREATE TABLE Responses (
    response_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    exam_id INT,
    question_id INT,
    chosen_answer VARCHAR(255),
    response_time DATETIME,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (exam_id) REFERENCES Exams(exam_id),
    FOREIGN KEY (question_id) REFERENCES Questions(question_id)
);

-- Create student_results table
CREATE TABLE student_results (
    user_id INT NOT NULL,
    username VARCHAR(50) DEFAULT NULL,
    correct_count INT DEFAULT NULL,
    incorrect_count INT DEFAULT NULL,
    total_points INT DEFAULT NULL,
    exam_time DATETIME DEFAULT NULL
);

-- Create index on username column in Users table
CREATE INDEX idx_username ON Users(username);
