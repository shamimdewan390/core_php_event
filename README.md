# Event Management System (Core PHP)

## 📌 Project Overview
The **Event Management System** is a web-based application that allows users to create, manage, and view events. It also enables attendees to register for events and provides event reports for administrators.

## 🚀 Live Demo
🔗 **[Live Project](https://shamim.ecomership.com/)**  
📧 **Admin Credentials:**
- **Email:** admin@gmail.com
- **Password:** adminadmin

## 📂 Features
### 1️⃣ Core Functionalities
- **User Authentication**: Secure login and registration with password hashing.
- **Event Management**: Users can create, update, view, and delete events.
- **Attendee Registration**: Prevents registration beyond the maximum event capacity.
- **Event Dashboard**: Paginated, sortable, and filterable event listing.
- **Event Reports**: Admins can download event attendee lists in CSV format.

### 2️⃣ Technical Aspects
- Developed using **pure PHP (no frameworks).**
- **MySQL database** for data storage.
- **Secure authentication** with password hashing.
- **Prepared statements** to prevent SQL injection.
- **Bootstrap-based responsive UI**.

## 🛠️ Installation Guide (Local Setup)

### Step 1: Clone the Repository
```sh
 git clone https://github.com/shamimdewan390/core_php_event.git
```

### Step 2: Configure the Database Connection
Edit `classes/Database.php` and update the database credentials:
```php
private $host = "localhost";
private $user = "localhost_user";
private $password = "12345678";
private $dbName = "localhost";
```

### Step 3: Set Base URL
Update `config.php`:
```php
$base_url = "http://localhost:9000/";
```

### Step 4: Create Database Tables
Run the following command in the project root:
```sh
php create_database.php
```

### Step 5: Start the Local Server
```sh
php -S localhost:9000
```
Now, open `http://localhost:9000/` in your browser.


## 🎯 Bonus Features (Optional Enhancements)
- **Search functionality** for events.

## 🔎 Evaluation Criteria
✔️ **Code Quality**: Well-structured and readable.
✔️ **Functionality**: Full implementation of features with edge case handling.
✔️ **Security**: Password hashing, input validation, and SQL injection prevention.
✔️ **Database Design**: Efficient and relational schema.
✔️ **Documentation**: Clear setup instructions.
✔️ **Hosting**: Live demo link with credentials.

## 📜 License
This project is licensed under the **MIT License**.

---
Developed by [Shamim Dewan](https://github.com/shamimdewan390) ✨

