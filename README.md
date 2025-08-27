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

git clone https://github.com/harryzafar/emp_dept.git

cd emp_dept


### 2. Install Dependencies

composer install


### 3. Configure Environment
Copy `.env.example` to `.env` and update your DB credentials:

cp .env.example .env


Update the following keys in `.env`:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=emp_dept
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key
php artisan key:generate
```

### 5. Run Migrations & Seeders

php artisan migrate --seed
```

This will create the schema and seed a default user:
```
Email: test@example.com
Password: password
```

### 6. Run the Development Server

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
Success Response
Code: 201 Created
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "access_token": "1|ABCXYZ123TOKEN"
}

Error Response:
Code: 422 Unprocessable Entity
{
  "message": "The email has already been taken.",
  "errors": {
    "email": [
      "The email has already been taken."
    ]
  }
}




### Login
`POST /api/login`  
**Body:**
{
  "email": "john@example.com",
  "password": "password"
}

Success Response:
Code: 200 OK
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "access_token": "1|ABCXYZ123TOKEN"
}

Error Response:
Code: 401 Unauthorized
{
  "message": "Invalid credentials"
}

### Profile
`GET /api/user`  
Headers:
Authorization: Bearer <token>

Success Response:
Code: 200 OK

{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "created_at": "2025-08-20T12:34:56.000000Z",
  "updated_at": "2025-08-20T12:34:56.000000Z"
}

Error Response:
Code: 401 Unauthorized
{
  "message": "Unauthenticated."
}

### logout
Endpoint: POST /api/logout
Headers:
Authorization: Bearer <token>

Success Response:
Code: 200 OK

{
  "message": "Logged out successfully"
}

Error Response:
Code: 401 Unauthorized

{
  "message": "Unauthenticated."
}

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
### 👥 Employees
1. List Employees

GET /api/employees

Success Response
[
  {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "zafarr.doe@example.com",
    "designation": "Software Engineer",
    "department": "IT"
  }
]

2. Create Employee

POST /api/employees

Request Body
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "zafarr.doe@example.com",
  "date_of_birth": "1990-05-15",
  "designation": "Software Engineer",
  "department_id": 3,
  "phone_numbers": [
    {
      "phone": "9876543210",
      "label": "mobile",
      "is_primary": true
    },
    {
      "phone": "0123456789",
      "label": "home",
      "is_primary": false
    }
  ],
  "addresses": [
    {
      "line1": "123 Main Street",
      "line2": "Apt 4B",
      "city": "New Delhi",
      "state": "Delhi",
      "country": "India",
      "postal_code": "110001",
      "label": "home",
      "is_primary": true
    },
    {
      "line1": "456 Corporate Tower",
      "line2": "Floor 7",
      "city": "Gurgaon",
      "state": "Haryana",
      "country": "India",
      "postal_code": "122001",
      "label": "office",
      "is_primary": false
    }
  ]
}

Success Response
{
  "message": "Employee created successfully",
  "employee": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "zafarr.doe@example.com",
    "designation": "Software Engineer"
  }
}

Validation Rules

first_name, last_name → required, string

email → required, unique, email

date_of_birth → required, date

designation → required, string

department_id → required, exists:departments

phone_numbers → array, each phone required, max:15 digits

addresses → array, each address requires line1, city, state, country, postal_code

Error Response
{
  "errors": {
    "email": ["The email has already been taken."]
  }
}

3. Get Employee

GET /api/employees/{id}

Success Response
{
  "id": 1,
  "first_name": "John",
  "last_name": "Doe",
  "email": "zafarr.doe@example.com",
  "designation": "Software Engineer",
  "department": "IT",
  "phone_numbers": [...],
  "addresses": [...]
}

Error Response
{
  "message": "Employee not found"
}

4. Update Employee

PUT /api/employees/{id}

(Same body as create)

Success Response
{
  "message": "Employee updated successfully"
}

5. Delete Employee

DELETE /api/employees/{id}

Success Response
{
  "message": "Employee deleted successfully"
}

### 🏢 Departments
1. List Departments

GET /api/departments

[
  {
    "id": 1,
    "name": "IT"
  }
]

2. Create Department

POST /api/departments

Request Body
{
  "name": "Finance"
}

Success Response
{
  "message": "Department created successfully",
  "department": {
    "id": 2,
    "name": "Finance"
  }
}

Error Response
{
  "errors": {
    "name": ["The name field is required."]
  }
}

3. Get Department

GET /api/departments/{id}

Success Response
{
  "id": 1,
  "name": "IT"
}

4. Update Department

PUT /api/departments/{id}

Request Body
{
  "name": "Human Resources"
}

Success Response
{
  "message": "Department updated successfully"
}

5. Delete Department

DELETE /api/departments/{id}

Success Response
{
  "message": "Department deleted successfully"
}

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