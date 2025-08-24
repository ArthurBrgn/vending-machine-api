# Employee Purchase System

This project is a **Laravel-based application** designed to automate employee purchases from self-service vending machines using their **NFC/RFID access cards**.  
Instead of real money, employees are allocated a **daily virtual balance (points)** according to their classification (e.g., Manager, Regular Staff). Purchases are validated in real time against daily quotas and balance rules.

The system provides:

-   A robust backend to handle vending machine API requests.
-   Rule-based daily balance management.
-   Classification-based purchase restrictions.
-   An **admin panel (Filament)** for managing employees, classifications, balances, vending machines, and reports.

You can read more details in the file **project_context.pdf**

---

## 🚀 Installation Guide

### 1. Clone the repository

```bash
git clone https://github.com/ArthurBrgn/vending-machine-api.git
cd vending-machine-api
```

### 2. Install dependencies

```bash
composer install
```

### 3. Environment setup

Copy the .env.example file and configure your local environment:

```bash
cp .env.example .env
```

Update database, cache, and other settings in .env as needed.

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Run migrations and seeders

```bash
php artisan migrate --seed
```

### 6. Run migrations and seeders

```bash
php artisan serve
```

By default, the application will be available at:
👉 http://localhost:8000

## 🧪 Running Tests

```bash
php artisan test
```

## 🔄 Recharging Employee Balances

The system uses a daily balance recharge mechanism based on employee classification.
You can manually trigger the recharge with the following command:

```bash
php artisan recharge-employee-daily-balance
```

## 🛠️ Admin Panel (Filament)

The admin panel is built with Filament and provides an interface for managing the system.

You can access it at:
👉 http://localhost:8000/admin

Default credentials (from seeders):

Email: admin@example.com

Password: password
