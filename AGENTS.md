<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

# AI_RULES & STANDAR PENGEMBANGAN JARA

## A. Standar Variabel Utama
Gunakan penamaan ini untuk mencegah bentrok antar developer:
- ID Pengguna: `$userId`
- ID Daftar Tugas: `$taskListId`
- ID Tugas: `$taskId`
- Objek Model: `$user`, `$taskList`, `$task`
- Payload Request: `$validatedData`

## B. Standar Commit Message
Format wajib: `tipe(fitur): pesan commit`
Contoh: 
- `feat(task-list): tambah fungsi hapus atomic`
- `fix(auth): perbaiki validasi token`
- `chore(db): update seeder user`

## C. Database, Keamanan & Transaksi (Wajib)
1. **Anti SQL Injection**: Dilarang keras menggunakan string concatenation pada query. WAJIB menggunakan parameter binding bawaan Eloquent/Query Builder.
   - SALAH: `DB::select("SELECT * FROM tasks WHERE id = " . $id)`
   - BENAR: `Task::where('id', $taskId)->get()` atau `DB::select("SELECT * FROM tasks WHERE id = ?", [$taskId])`
2. **Transaksi Atomic**: Operasi yang mengubah >1 tabel WAJIB dibungkus `DB::transaction()`.
3. **Validasi**: Seluruh input wajib divalidasi menggunakan Laravel Form Requests.
4. **Otorisasi**: Gunakan Laravel Policies/Gates. Tolak akses (403) jika bukan pemilik/berwenang.

## D. Aturan Git & Workflow
1. JANGAN PERNAH `git push` tanpa instruksi/izin eksplisit.
2. Selalu jalankan local test/check sebelum commit.
3. Jika test gagal atau terjadi error saat development, lakukan rollback kode dan beri laporan peringatan.
