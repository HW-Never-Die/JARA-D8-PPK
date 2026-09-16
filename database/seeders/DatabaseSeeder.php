<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@jara.test'],
            [
                'name' => 'Admin Jara',
                'password' => 'password',
                'role' => 'admin',
            ]
        );

        // 2. Owner (Dewa / Test User)
        $owner = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Dewangga Ramadhan',
                'password' => 'password',
                'role' => 'owner',
            ]
        );

        // 3. Collaborators (Moses & Sulthon)
        $moses = User::firstOrCreate(
            ['email' => 'moses@jara.test'],
            [
                'name' => 'Moses Christian',
                'password' => 'password',
                'role' => 'member',
            ]
        );

        $sulthon = User::firstOrCreate(
            ['email' => 'sulthon@jara.test'],
            [
                'name' => 'Sulthon Aulia',
                'password' => 'password',
                'role' => 'member',
            ]
        );

        // Clear existing task lists if re-seeding
        $owner->ownedTaskLists()->delete();

        // 4. List 1: Project Website (Total 10, Done 7 -> 70% Progress)
        $projectWeb = $owner->ownedTaskLists()->create([
            'name' => 'Project Website JARA',
            'description' => 'Pengembangan antarmuka kolaboratif dan integrasi backend Laravel 13.',
        ]);

        $projectWeb->members()->syncWithoutDetaching([$moses->id, $sulthon->id]);

        $tasksWeb = [
            ['title' => 'Inisialisasi repo & konfigurasi Laravel 13', 'desc' => 'Setup base project dan environment.', 'status' => 'done', 'priority' => 'high', 'deadline' => now()->subDays(3)->format('Y-m-d'), 'assignees' => [$owner->id, $moses->id]],
            ['title' => 'Perancangan skema database & relasi Eloquent', 'desc' => 'Migrasi users, task_lists, tasks, members, assignments.', 'status' => 'done', 'priority' => 'high', 'deadline' => now()->subDays(2)->format('Y-m-d'), 'assignees' => [$moses->id]],
            ['title' => 'Desain arsitektur UI & token tema Slate Iris', 'desc' => 'Palette #0C0D10, Royal Iris, crisp 1px borders.', 'status' => 'done', 'priority' => 'medium', 'deadline' => now()->subDays(2)->format('Y-m-d'), 'assignees' => [$owner->id]],
            ['title' => 'Halaman autentikasi Login & Register', 'desc' => 'Form auth lengkap dengan validasi dan CSRF.', 'status' => 'done', 'priority' => 'high', 'deadline' => now()->subDay()->format('Y-m-d'), 'assignees' => [$owner->id]],
            ['title' => 'Implementasi User Profile & edit data', 'desc' => 'Ubah profil dan credentials pengguna.', 'status' => 'done', 'priority' => 'medium', 'deadline' => now()->subDay()->format('Y-m-d'), 'assignees' => [$owner->id]],
            ['title' => 'Dashboard ringkasan progress metriks', 'desc' => 'KPI Total task, done, percentage visual monitor.', 'status' => 'done', 'priority' => 'high', 'deadline' => now()->format('Y-m-d'), 'assignees' => [$owner->id, $sulthon->id]],
            ['title' => 'Sistem assign & unassign kolaborator task', 'desc' => 'Pivot task_assignments terhubung dengan members.', 'status' => 'done', 'priority' => 'medium', 'deadline' => now()->format('Y-m-d'), 'assignees' => [$owner->id, $moses->id]],
            ['title' => 'Testing interaktivitas Kanban drag-to-status', 'desc' => 'Verifikasi transisi TODO -> IN_PROGRESS -> DONE.', 'status' => 'in_progress', 'priority' => 'high', 'deadline' => now()->addDays(2)->format('Y-m-d'), 'assignees' => [$owner->id, $sulthon->id]],
            ['title' => 'Review integrasi API Task dengan tim backend', 'desc' => 'Sinkronisasi endpoint payload dan format JSON.', 'status' => 'in_progress', 'priority' => 'medium', 'deadline' => now()->addDays(3)->format('Y-m-d'), 'assignees' => [$moses->id]],
            ['title' => 'Automated test suite & UAT final deployment', 'desc' => 'Smoke testing route dan validasi form submission.', 'status' => 'todo', 'priority' => 'low', 'deadline' => now()->addDays(5)->format('Y-m-d'), 'assignees' => [$sulthon->id]],
        ];

        foreach ($tasksWeb as $t) {
            $task = $projectWeb->tasks()->create([
                'title' => $t['title'],
                'description' => $t['desc'],
                'status' => $t['status'],
                'priority' => $t['priority'],
                'deadline' => $t['deadline'],
            ]);
            if (! empty($t['assignees'])) {
                $task->assignees()->sync($t['assignees']);
            }
        }

        // 5. List 2: Tugas Kuliah PPK
        $tugasKuliah = $owner->ownedTaskLists()->create([
            'name' => 'Tugas Kuliah PPK',
            'description' => 'Praktikum dan laporan implementasi web application kelompok D8.',
        ]);
        $tugasKuliah->members()->syncWithoutDetaching([$moses->id]);

        $t1 = $tugasKuliah->tasks()->create([
            'title' => 'Penyusunan dokumen URS & Use Case',
            'description' => 'Format SRS standar praktikum SMT 5.',
            'status' => 'done',
            'priority' => 'high',
            'deadline' => now()->subDay()->format('Y-m-d'),
        ]);
        $t1->assignees()->sync([$owner->id]);

        $t2 = $tugasKuliah->tasks()->create([
            'title' => 'Pembuatan slide presentasi progres sprint 1',
            'description' => 'Presentasi arsitektur dan demo antarmuka.',
            'status' => 'in_progress',
            'priority' => 'medium',
            'deadline' => now()->addDays(2)->format('Y-m-d'),
        ]);
        $t2->assignees()->sync([$moses->id]);

        $t3 = $tugasKuliah->tasks()->create([
            'title' => 'Pengujian beban endpoint dan benchmarking',
            'description' => 'Simulasi concurrent requests ke database MySQL.',
            'status' => 'todo',
            'priority' => 'low',
            'deadline' => now()->addDays(4)->format('Y-m-d'),
        ]);

        // 6. List 3: Pekerjaan Freelance / Side Project
        $pekerjaan = $owner->ownedTaskLists()->create([
            'name' => 'Pekerjaan Client Portal',
            'description' => 'Sistem monitoring tiket dan reporting bulanan klien.',
        ]);
        $pekerjaan->tasks()->create([
            'title' => 'Audit keamanan form input dan sanitasi XSS',
            'description' => 'Pastikan seluruh input menggunakan CSRF dan validasi ketat.',
            'status' => 'done',
            'priority' => 'high',
            'deadline' => now()->subDays(1)->format('Y-m-d'),
        ]);
        $pekerjaan->tasks()->create([
            'title' => 'Setup CI/CD pipeline GitHub Actions',
            'description' => 'Build automated testing dan asset compiling.',
            'status' => 'todo',
            'priority' => 'medium',
            'deadline' => now()->addDays(6)->format('Y-m-d'),
        ]);
    }
}
