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