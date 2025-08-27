# Employee Management API (Laravel 11)

This project is a **RESTful API** built with **Laravel 11** that manages departments and employees.  
It demonstrates clean architecture, authentication, relationships, validations, and automated tests.  

---

## 🚀 Features

- User Authentication (Register, Login, Logout) using **Laravel Sanctum**
- CRUD operations for:
  - Departments
  - Employees
- Employees can have:
  - Multiple phone numbers
  - Multiple addresses
- Request validation using **Form Requests**
- Resource responses for consistent API output
- Feature tests covering authentication, employees, and departments
- Default seeded user for quick testing

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL (or any supported DB)
- **Auth:** Laravel Sanctum
- **Testing:** PHPUnit & Laravel Test utilities

---

## 📦 Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/harryzafar/emp_dept.git
cd employee-management-api
```

### 2. Install Dependencies

composer install


### 3. Configure Environment
Copy `.env.example` to `.env` and update your DB credentials:

cp .env.example .env


Update the following keys in `.env`:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_management
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key
```bash
php artisan key:generate
```

### 5. Run Migrations & Seeders
```bash
php artisan migrate --seed
```

This will create the schema and seed a default user:
```
Email: test@example.com
Password: password
```

### 6. Run the Development Server
```bash
php artisan serve
```

API will be available at:  
👉 `http://127.0.0.1:8000/api`

---

## 🔑 Authentication

This project uses **token-based authentication** with Laravel Sanctum.  

### Register
`POST /api/register`  
**Body (form-data / JSON):**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

### Login
`POST /api/login`  
**Body:**
```json
{
  "email": "john@example.com",
  "password": "password"
}
```

Response contains an `access_token` that must be sent in the `Authorization` header:  
```
Authorization: Bearer <token>
```

---

## 📘 API Endpoints

### Departments
- `GET    /api/departments` → List all departments  
- `POST   /api/departments` → Create department  
- `GET    /api/departments/{id}` → Show department  
- `PUT    /api/departments/{id}` → Update department  
- `DELETE /api/departments/{id}` → Delete department  

### Employees
- `GET    /api/employees` → List all employees  
- `POST   /api/employees` → Create employee (with phone numbers & addresses)  
- `GET    /api/employees/{id}` → Show employee  
- `PUT    /api/employees/{id}` → Update employee  
- `DELETE /api/employees/{id}` → Delete employee  

---

## 🧪 Running Tests

Run the test suite with:
```bash
php artisan test
```

Covers:
- Authentication (login, register, unauthorized access)
- Department CRUD
- Employee CRUD
- Negative test cases (unauthorized access without token)

---

## 📂 Project Structure (Important Parts)

```
app/
 ├── Http/
 │   ├── Controllers/
 │   │   ├── AuthController.php
 │   │   ├── DepartmentController.php
 │   │   └── EmployeeController.php
 │   ├── Requests/
 │   │   ├── StoreDepartmentRequest.php
 │   │   ├── StoreEmployeeRequest.php
 │   │   └── UpdateEmployeeRequest.php
 │   │   └── UpdateDepartmentRequest.php
 │   └── Resources/
 │       ├── DepartmentResource.php
 │       └── EmployeeResource.php
 ├── Models/
 │   ├── User.php
 │   ├── Department.php
 │   └── Employee.php
database/
 ├── factories/
 ├── migrations/
 └── seeders/
tests/
 └── Feature/
     ├── AuthTest.php
     ├── DepartmentTest.php
     └── EmployeeTest.php
```

---

## 👤 Default Test User

For convenience, after seeding you can login using:  
```
Email: test@example.com
Password: password
```

Use the token received from login to access protected routes.

---
🛠 Best Practices Implemented

Form Requests – Used Laravel FormRequest classes for validation instead of inline validation, making controllers cleaner and reusable.

API Resources – Used Resource classes to format JSON responses consistently.

Authentication – Implemented Laravel Sanctum for token-based authentication.

Seeder & Factory – Used factories and seeders for generating realistic test data.

Feature Tests – Wrote PHPUnit feature tests for authentication, departments, and employees.

RESTful Standards – Followed REST conventions for API routes and structure.

Error Handling – Used structured error responses for validation failures and unauthorized access.