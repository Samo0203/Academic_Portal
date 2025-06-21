# 🎓 Academic_Portal
### 🎓 Academic Calendar and Notice Board System

A web-based application for universities to manage and display academic calendars, publish notices, and provide a centralized dashboard for students, lecturers, and administrators.

---

## 📌 Features

- ✅ **User Authentication** (Student, Lecturer, Admin)
- ✅ **Admin-only Calendar Generator**
  - Based on the semester start date and week count
  - Mid-semester break, study leave, and exam weeks are auto-included
- ✅ **Notice Board System**
  - Post notices with attachments
  - Filtered by type: General, Exam, Event
  - Admins and Lecturers can manage posts
- ✅ **User Management**
  - Admin can view, add, and edit users
- ✅ **Secure Password Handling**
  - Passwords hashed with `password_hash()`
  - Validation: minimum 8 characters + at least 1 number
- ✅ **Responsive UI**
  - Clean and modern design using HTML/CSS
  - Primary color theme: **Maroon**

---

## 🛠 Technologies

- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Server: XAMPP

---
## ⚙️ Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/academic-calendar-notice-board.git
   cd academic-calendar-notice-board
   ```
2. **Import the database**
  Open phpMyAdmin
  Create a database named academic_portal
  Import the provided academic_portal.sql file

3. **Configure Database Connection**
  Open config.php and ensure credentials match your local setup:
```php
$conn = new mysqli("localhost", "root", "", "academic_portal");
```

4. **Run the project**
  Start your local server (e.g., XAMPP)
  Visit: http://localhost/academic-calendar-notice-board/login.php
---

## 👩‍💼Default Admin Credentials
Email: admin@gmail.com <br>
Password: admin123

---
## 📁 Folder Structure

academic-calendar-notice-board/ <br>
│ <br>
├── config.php <br>
├── login.php <br>
├── register.php <br>
├── dashboard.php <br>
├── calendar.php <br>
├── notices.php <br>
├── users.php <br>
├── logout.php <br>
├── style.css <br>
├── upload/         ← Notice attachments <br>
└── academic_portal.sql

---
## 🧑‍💻 Credits

Developed by:
IT Undergraduate Students (Group 10 - 2021/2022 Batch) <br>
University of Vavuniya <br>
For academic and educational purposes.
