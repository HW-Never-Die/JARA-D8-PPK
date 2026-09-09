# JARA - Task Management System

Projek PPK D8 - Aplikasi manajemen tugas kolaboratif.

## Tim

| Nama    | Tugas                                                        |
| ------- | ------------------------------------------------------------ |
| Moses   | Database Design, Authentication, API Task & Collaboration    |
| Dewa    | Halaman Auth, Dashboard User, Task Management UI, Collab UI  |
| Sulthon | Admin Dashboard, Progress Monitoring, Integration, Testing   |

## Tech Stack

- **Backend:** Laravel 13
- **Database:** MySQL
- **Frontend:** Blade + Vite

## Setup

```bash
# Clone repo
git clone https://github.com/HW-Never-Die/JARA-D8-PPK.git
cd JARA-D8-PPK

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Buat database 'jara' di MySQL, lalu:
php artisan migrate --seed

# Jalankan server
composer dev
```

## Branch Strategy

| Branch                  | Fungsi                          |
| ----------------------- | ------------------------------- |
| `main`                  | Production-ready                |
| `dev`                   | Development integration         |
| `feature/nama-fitur`    | Branch per fitur                |

### Workflow

1. Checkout dari `dev`: `git checkout -b feature/nama-fitur dev`
2. Kerjakan fitur, commit
3. Push & buat Pull Request ke `dev`
4. Setelah di-review, merge ke `dev`
5. `dev` di-merge ke `main` saat rilis

## Default Accounts (Seeder)

| Email              | Role  | Password |
| ------------------ | ----- | -------- |
| admin@jara.test    | admin | password |
| test@example.com   | owner | password |
