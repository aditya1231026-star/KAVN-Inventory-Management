# KAVN - Inventory Management System

## 📌 Project Overview

KAVN is a PHP and MySQL-based web application designed to manage inventory and stock operations through a centralized dashboard.

The application provides user authentication and allows users to view and manage products, track stock quantities, perform stock transactions, manage warehouse information, and maintain a transaction ledger.

## ✨ Features

- 🔐 User registration and login
- 🔑 Session-based authentication
- 🔒 Password hashing and verification
- 📊 Inventory overview dashboard
- 📦 Product management
- ➕ Add products
- 🗑️ Delete products
- 📋 Product and stock information
- 📥 Stock-in transactions
- 📤 Stock-out transactions
- 🔄 Warehouse transfer functionality
- ⚙️ Stock adjustment functionality
- 📝 Transaction/ledger tracking
- 📈 Inventory data visualization using Chart.js
- 📱 Clean and responsive dashboard interface

## 🛠️ Technologies Used

- **PHP**
- **MySQL**
- **HTML**
- **CSS**
- **JavaScript**
- **Chart.js**
- **Phosphor Icons**
- **XAMPP / WAMP**

## 🔐 Authentication

KAVN includes an authentication system with:

- User registration
- User login
- Session management
- Logout functionality
- Password hashing using PHP's password hashing functions
- Protected dashboard and API access

Users who are not authenticated are redirected to the login page.

## 📦 Inventory Management

The application allows users to manage inventory information including:

- Product name
- SKU
- Category
- Warehouse
- Stock quantity

Users can add new products, view inventory information, and delete products.

## 🔄 Stock Transactions

KAVN supports multiple inventory transaction types:

### Stock In
Increases the available quantity of a product.

### Stock Out
Decreases the available quantity of a product.

### Transfer
Updates the warehouse associated with a product.

### Adjustment
Updates stock based on an inventory adjustment.

Transactions are recorded in a ledger with information such as transaction type, product, quantity, details, and date.

## 📊 Dashboard

The dashboard provides an overview of inventory information and stock operations.

Chart.js is used to visualize inventory data and provide a clearer view of stock information.

## 🗄️ Database

The application uses **MySQL** for storing user, product, inventory, and ledger information.

The current PHP configuration expects a local database named:

```text
kavn_inventory
```

The database connection is configured in:

```text
db.php
```

## 📁 Project Structure

```text
KAVN-Inventory-Management/
│
├── api.php
├── db.php
├── index.php
├── login.php
├── logout.php
├── signup.php
└── README.md
```

## ▶️ How to Run Locally

### Requirements

- XAMPP or WAMP
- PHP
- MySQL
- Web browser

### Setup

1. Install XAMPP.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Place the project folder inside the XAMPP `htdocs` directory.
4. Create a MySQL database named:

```text
kavn_inventory
```

5. Create the required tables for users, products, and ledger records.
6. Check the database settings in `db.php`.
7. Start Apache and MySQL.
8. Open the application through the local Apache server.
9. Create an account using the signup page.
10. Log in to access the inventory dashboard.

## 📌 Project Type

**Web Development | PHP | MySQL | Inventory Management**

## 💡 Skills Demonstrated

- PHP web development
- MySQL database connectivity
- User authentication
- Session management
- Password hashing
- CRUD operations
- Prepared SQL statements
- Inventory management
- Stock transaction management
- PHP API endpoints
- Dashboard development
- Data visualization
- HTML, CSS and JavaScript

## ⚠️ Configuration Note

The project is configured for local development using XAMPP/WAMP.

Before deploying the application to a production server, update the database configuration and apply appropriate production security practices.
