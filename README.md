![CI](https://github.com/jamoussir1/virtual-queue-system/actions/workflows/ci.yml/badge.svg)
# 🎟 Virtual Queue Management System
### ESPRIT School of Business — 2 LBC-BIS — 2025/2026

---

## ✅ Requirements
- PHP >= 8.1
- Composer
- MySQL (via XAMPP or Laragon)
- Git (optional)

---

## 🚀 Installation Steps

### 1. Place the project
Copy the `virtual-queue-system` folder to:
- **XAMPP**: `C:/xampp/htdocs/virtual-queue-system`
- **Laragon**: `C:/laragon/www/virtual-queue-system`

### 2. Install dependencies
Open a terminal inside the project folder:
```bash
composer install
```

### 3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Create the database
- Open **phpMyAdmin** → create a database named `virtual_queue_db`
- Edit `.env` if your MySQL password is different:
```
DB_DATABASE=virtual_queue_db
DB_USERNAME=root
DB_PASSWORD=        ← leave blank for XAMPP default
```

### 5. Run migrations & seed data
```bash
php artisan migrate --seed
```

### 6. Start the server
```bash
php artisan serve
```
Then open: **http://localhost:8000**

---

## 🔑 Demo Login Accounts
| Role     | Email                    | Password |
|----------|--------------------------|----------|
| Admin    | admin@virtualqueue.tn    | password |
| Agent    | yasmine@virtualqueue.tn  | password |
| Agent    | karim@virtualqueue.tn    | password |
| Customer | rafaa.jamoussi@esprit.tn | password |
| Customer | aziz@mail.com            | password |
| Customer | omar@mail.com            | password |
| Customer | rami@mail.com            | password |

---

## 🗂 Features by Role

### 👤 Customer
- Register / Login
- View open queues with capacity bar
- Join a queue → receive position + QR code
- Real-time ticket tracking (auto-refresh)
- Cancel ticket
- View notifications
- View ticket history

### 🖥 Agent
- Login
- See waiting queue list
- Call next customer (one click)
- Mark customer as Served or Absent
- Auto-refresh every 20s

### ⚙️ Admin
- Full dashboard with live stats
- **Queue CRUD**: create, edit, delete queues
- **Service Window CRUD**: assign agents to windows
- **User CRUD**: manage all accounts + roles
- **Statistics**: serve rates, daily activity, per-queue analytics

---

## 🗃 Database Schema
```
users           → id, name, email, password, role
queues          → id, name, status, max_capacity, created_by
service_windows → id, label, is_active, agent_id, queue_id
tickets         → id, qr_code, position, status, customer_id, queue_id, window_id
notifications   → id, type, message, is_read, ticket_id, user_id
statistics      → id, period, total_served, avg_wait_time, queue_id
```

---

## ⚡ Troubleshooting

**Blank page / 500 error:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**Permission error on storage:**
```bash
chmod -R 775 storage bootstrap/cache
```

**Database error:**
- Make sure MySQL is running in XAMPP/Laragon
- Check `.env` DB credentials match

---

*Built with Laravel 10 · MySQL · Blade Templates*
*ESPRIT School of Business — Integrated Project 2025-2026*
