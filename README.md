# Aklan State University - Kalibo Library Borrowing Management System

This project is a **Library Borrowing Management System** designed for **Aklan State University - Kalibo** to help manage book lending and returning processes. The system helps both students and librarians track borrowed books, manage inventory, and ensure timely returns.

## Features

- **User Registration and Login**  
  Users can register and log in to access the library system.
  
- **Book Management**  
  Librarians can add, update, or remove books from the library catalog.

- **Borrowing System**  
  Students can borrow books based on availability, and the system keeps track of borrowed books and due dates.

- **Return Management**  
  The system allows users to return books, and it tracks overdue books for penalty management.

- **Book Search**  
  Users can search for books by title, author, or category.

- **Admin Dashboard**  
  Admins can manage library users, check borrowing records, and generate reports.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/username/aklan-library-management.git

Install required dependencies:

bash
Copy
Edit
npm install
Set up the database:

Ensure that your database server is running (e.g., MySQL, PostgreSQL).

Create a new database for the system.

Import the provided SQL schema to create tables.

Configure environment variables:

Set up a .env file with the necessary environment variables (database credentials, server settings).

Run the application:

bash
Copy
Edit
npm start
Technologies Used
Frontend: HTML, CSS, JavaScript, Bootstrap

Backend: Node.js, Express.js

Database: MySQL / PostgreSQL

Authentication: JWT (JSON Web Tokens)

Database Schema
The database contains the following tables:

Users: Stores information about students and librarians.

Books: Contains book details such as title, author, and ISBN.

Transactions: Tracks book borrowings and returns.

Overdue_Fees: Calculates fines for overdue books.

Usage
User Login:
Users (students and librarians) can log in to access the system using their credentials.

Book Borrowing:
After logging in, students can browse available books and borrow them. The system tracks borrowing dates and return deadlines.

Returning Books:
Users can return books on the due date or after the deadline (with penalties for overdue books).

Admin Features:
Admins can manage user accounts, view borrowing records, and generate reports on library usage.

Contributing
If you'd like to contribute to this project, follow these steps:

Fork the repository.

Create a new branch (git checkout -b feature-branch).

Make your changes.

Commit your changes (git commit -am 'Add new feature').

Push to your branch (git push origin feature-branch).

Create a pull request.

License
This project is licensed under the MIT License - see the LICENSE file for details.

Acknowledgments
Special thanks to the developers and contributors of open-source libraries used in this project.

Thank you to Aklan State University for the opportunity to create this system.
