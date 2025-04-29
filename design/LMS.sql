CREATE DATABASE library_management;
USE library_management;

CREATE TABLE books (
    book_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(13),
    publisher VARCHAR(255),
    publication_year INT,
    edition INT,
    availability TINYINT(1) DEFAULT 1, -- 1 for available, 0 for unavailable
    book_image_path VARCHAR(255),
    quantity INT NOT NULL DEFAULT 1, -- Add quantity field
    bookshelf_code VARCHAR(255) -- Add bookshelf code field
);

CREATE TABLE students (
    student_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    grade_level INT,
    email VARCHAR(255),
    birthday DATE,
    section VARCHAR(50),
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    picture_path VARCHAR(255),
    lrn VARCHAR(12) UNIQUE
);

CREATE TABLE librarians (
    librarian_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0 -- 1 for admin, 0 for regular librarian
);

CREATE TABLE loans (
    loan_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    book_id INT,
    loan_date DATE,
    due_date DATE,
    return_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (book_id) REFERENCES books(book_id) 

);