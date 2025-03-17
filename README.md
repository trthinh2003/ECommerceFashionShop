# 🛍️ Laravel E-Commerce System

## 📌 Project Overview
This project is a **Laravel-based E-Commerce System** designed for selling fashion products and snacks. The system includes features such as product management, order processing, customer authentication, supplier tracking, and promotional discounts. The database schema is structured for efficiency and scalability.

## 🏗️ Entity-Relationship Diagram (ERD)
Below is the ERD representation of the database schema:

![ERD Diagram](path/to/your/erd.png) <!-- Replace with actual ERD image path -->

## 📂 Database Structure
### **1. Product Management**
| Table | Description |
|--------|-------------|
| `product_categories` | Stores product categories. |
| `products` | Main product table with details like price, brand, description. |
| `product_descriptions` | Stores product variations (color, size, stock). |

### **2. Orders & Transactions**
| Table | Description |
|--------|-------------|
| `orders` | Stores customer orders and payment details. |
| `order_details` | Tracks individual items in an order. |
| `reviews` | Customer reviews and ratings. |

### **3. Customers & Employees**
| Table | Description |
|--------|-------------|
| `customers` | Stores registered users. |
| `employees` | Manages admin and staff accounts. |

### **4. Suppliers & Inventory**
| Table | Description |
|--------|-------------|
| `suppliers` | Stores supplier information. |
| `purchase_receipts` | Tracks incoming stock from suppliers. |

### **5. Promotions & Discounts**
| Table | Description |
|--------|-------------|
| `discounts` | Stores promotions and discount codes. |

## 🛠️ Tech Stack
- **Backend:** Laravel 10, PHP 8
- **Database:** MySQL
- **Frontend:** Vue.js (optional)
- **Authentication:** Laravel Breeze / Sanctum
- **ORM:** Eloquent

## 🚀 Installation Guide
### **Step 1: Clone the Repository**
```sh
  git clone https://github.com/your-repo/ecommerce-laravel.git
  cd ecommerce-laravel
```

### **Step 2: Install Dependencies**
```sh
  composer install
  npm install
```

### **Step 3: Configure Environment**
```sh
  cp .env.example .env
  php artisan key:generate
```

### **Step 4: Setup Database**
```sh
  php artisan migrate --seed
```

### **Step 5: Start Development Server**
```sh
  php artisan serve
```

## 🎯 Features
✅ Product Management  
✅ User Authentication (Customers & Admins)  
✅ Order & Checkout System  
✅ Discount & Promotion Management  
✅ Supplier & Inventory Tracking  
✅ Customer Reviews & Ratings  
✅ REST API Support for Frontend Integration  

## 📜 License
This project is licensed under the [MIT License](LICENSE).

---
**Developed by [Your Name](https://github.com/your-profile)** 🚀
