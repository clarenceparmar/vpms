# 🚗 Vehicle Parking Management System

## 📌 Overview
**Vehicle Parking Management System** is a simple yet powerful PHP-based vehicle parking management system. It allows users to register, log in, add vehicles, generate bills, and manage their accounts. The admin panel provides complete control over vehicles, users, and billing operations.

---

## 🚀 Features
✅ **User Management** (Registration, Login, Dashboard, Settings)  
✅ **Admin Panel** (Manage Users, Vehicles, and Bills)  
✅ **Vehicle Management** (Add, View, Delete Vehicles)  
✅ **Billing System** (Users can generate and view bills)  
✅ **Authentication & Security** (Login system with access control)  
✅ **Responsive UI** (Designed for easy navigation)  

---

## 📂 Project Structure
```
X:.
│   index.php
│   vpms.sql
│
├───admin
│       admin_bills.php
│       admin_dashboard.php
│       admin_feedback.php
│       admin_settings.php
│       manage_regis_vehicle.php        
│       manage_users.php
│       manage_vehicle.php
│
├───assets
│       admin_header.html
│       footer.html
│       public_header.html
│       user_header.html
│
├───common
│       auth.php
│       logout.php
│
├───css
│       style.css
│
├───public
│       about.php
│       contact.php
│       login.php
│       registration.php
│
└───user
        user_bills.php
        user_dashboard.php
        user_settings.php
        user_vehicle.php
```
---

## 🛠️ Installation Guide
### 🔹 Prerequisites
- **WAMP Server** (Windows, Apache, MySQL, PHP) Installed
- Basic knowledge of PHP & MySQL

### 🔹 Setup Instructions
1. **Clone the Repository**
   ```bash
   git clone https://github.com/clarenceparmar/vpms.git
   cd vpms
   ```
2. **Import Database**
   - Open **phpMyAdmin**
   - Create a new database (e.g., `vpms`)
   - Import `vpms.sql` from the project folder

3. **Configure Database Connection**
   - Open `common/auth.php`
   - Update database credentials:
   ```php
   $conn = new mysqli("localhost", "root", "", "vpms");
   ```

4. **Run the Project**
   - Start Apache & MySQL in WAMP
   - Open your browser and go to:
     ```
     http://localhost/vechaon-parlo-management
     ```
---

## 🎮 Usage
- **User:** Register/Login → Add Vehicles → Generate/View Bills
- **Admin:** Manage Users → Approve Vehicles → View/Delete Bills

---

## 🎨 UI Preview
*(Add screenshots or GIFs here to showcase the project UI)*

---

## 🤝 Contributing
Feel free to contribute to this project. If you find any bugs or have suggestions, open an issue or submit a pull request. 🚀

---

## 📜 License
This project is **open-source** and available for educational purposes. 🎓

---

## 📬 Contact
💡 Have questions? Reach out to me:  
📧 Email: git.clarence@gmail.com  
🔗 GitHub: [yourusername](https://github.com/clarenceparmar)
