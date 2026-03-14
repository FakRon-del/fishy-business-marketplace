Step 1 — Clone the Project
git clone https://github.com/yourname/fishy-business.git
Step 2 — Move Project to XAMPP

Put the folder inside:

xampp/htdocs/

Example:

htdocs/fishy-business
Step 3 — Import Database

Open:

http://localhost/phpmyadmin

Create database:

fishy_business

Then click:

Import → choose fishy_business.sql
Step 4 — Update Database Connection

Open:

config/db.php

Make sure this matches their setup:

$conn = new mysqli("localhost","root","","fishy_business");
Step 5 — Run the Website


==============================================================================================
# Fishy Business 🐟

Fishy Business is a PHP + MySQL marketplace system for buying and selling fish and aquarium supplies.

## Features

* User registration and login
* Product browsing and search
* Shopping cart system
* Checkout and order system
* Order cancellation
* Multi-shop support
* Admin dashboard
* Product management (CRUD)
* Order management
* Ocean themed UI

## Installation

1. Clone the repository

git clone https://github.com/YOURNAME/fishy-business.git

2. Move the folder into your XAMPP htdocs directory.

3. Create a database in phpMyAdmin called:

fishy_business

4. Import the provided SQL file.

5. Open config/db.php and confirm the database connection:

$conn = new mysqli("localhost","root","","fishy_business");

6. Run the project:

http://localhost/fishy-business

## Admin Access

Access the admin panel:

http://localhost/fishy-business/admin/dashboard.php

## Technologies Used

* PHP
* MySQL
* HTML
* CSS
* XAMPP





Open browser:

http://localhost/fishy-business
