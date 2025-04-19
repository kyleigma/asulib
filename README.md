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
   

### 2. Install required dependencies:
npm install


### 3. Set up the database:

- Ensure that your database server is running (e.g., MySQL, PostgreSQL).
- Create a new database for the system.
- Import the provided SQL schema to create tables.

### 4. Configure environment variables:

- Set up a `.env` file with the necessary environment variables (database credentials, server settings).

### 5. Run the application:
npm start


## Technologies Used

- **Frontend:** HTML, CSS, JavaScript, Bootstrap
- **Backend:** Node.js, Express.js
- **Database:** MySQL / PostgreSQL
- **Authentication:** JWT (JSON Web Tokens)

## Database Schema

The database contains the following tables:

- **Users:** Stores information about students and librarians.
- **Books:** Contains book details such as title, author, and ISBN.
- **Transactions:** Tracks book borrowings and returns.
- **Overdue_Fees:** Calculates fines for overdue books.

## Usage

1. **User Login:**  
   Users (students and librarians) can log in to access the system using their credentials.

2. **Book Borrowing:**  
   After logging in, students can browse available books and borrow them. The system tracks borrowing dates and return deadlines.

3. **Returning Books:**  
   Users can return books on the due date or after the deadline (with penalties for overdue books).

4. **Admin Features:**  
   Admins can manage user accounts, view borrowing records, and generate reports on library usage.

## Contributing

If you'd like to contribute to this project, follow these steps:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -am 'Add new feature'`).
5. Push to your branch (`git push origin feature-branch`).
6. Create a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Special thanks to the developers and contributors of open-source libraries used in this project.
- Thank you to Aklan State University for the opportunity to create this system.


