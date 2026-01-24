````md
# Account Management System

A centralized **Account Management System** consisting of a RESTful backend API and a modern frontend application.  
This repository is structured as a **mono-repository** containing both backend and frontend projects for streamlined development and maintenance.

---

## 📦 Repository Structure

```text
account_management/
├── bank-api/        # Backend API (Laravel)
│   ├── app/
│   ├── routes/
│   ├── database/
│   ├── public/
│   ├── composer.json
│   └── .env
│
├── bank-gui/        # Frontend Application (Vue.js)
│   ├── src/
│   ├── public/
│   ├── package.json
│   ├── vite.config.js
│   └── index.html
│
└── README.md        # Project documentation
````

---

## 🏗 Architecture Overview

* **Backend (`bank-api`)**

  * Laravel-based REST API
  * Handles business logic, authentication, and database operations

* **Frontend (`bank-gui`)**

  * Vue 3 single-page application
  * Consumes backend APIs for data visualization and interaction

* **Repository Pattern**

  * Single Git repository
  * Independent backend and frontend deployments

---

## 🧰 Technology Stack

### Backend

* PHP 8+
* Laravel Framework
* MySQL / MariaDB
* RESTful APIs
* Authentication & Authorization
* Pagination, Filtering, Sorting

### Frontend

* Vue 3 (Composition API)
* Pinia (State Management)
* Tailwind CSS
* Axios
* Vite

---

## ⚙️ Backend Setup (bank-api)

### Installation

```bash
cd bank-api
composer install
cp .env.example .env
php artisan key:generate
```

### Environment Configuration

Update database credentials in `.env`:

```env
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```

### Database Migration

```bash
php artisan migrate --seed
```

### Run Development Server

```bash
php artisan serve
```

Backend API will be available at:

```
http://127.0.0.1:8000
```

---

## ⚙️ Frontend Setup (bank-gui)

### Installation

```bash
cd bank-gui
npm install
```

### Run Development Server

```bash
npm run dev
```

Frontend application will be available at:

```
http://localhost:5173
```

---

## 🔗 API Communication

The frontend communicates with the backend using **Axios**.

Example API base URL:

```js
http://127.0.0.1:8000/api
```

---

## ✨ Key Features

* Bank, Branch & Account Management
* Transaction Management (Deposit / Withdraw)
* Search, Filter & Pagination
* Sorting (ASC / DESC)
* Status Management (Active / Inactive)
* Soft Delete & Restore
* Reusable UI Components
* Robust Validation & Error Handling

---

## 🛠 Development Guidelines

* Backend and frontend are **loosely coupled**
* Follow **PSR standards** for backend code
* Use **LF line endings** for cross-platform consistency
* Environment files (`.env`) should never be committed

---

## 🚀 Deployment Notes

* Backend can be deployed on **Apache / Nginx**
* Frontend can be built and served via **Nginx**
* CI/CD pipelines can be configured for independent deployment

---

## 👤 Author

**Sharif Uddin**
Web Engineer | Full Stack Developer

---

## 📄 License

This project is proprietary and intended for internal development and controlled distribution.

``` 