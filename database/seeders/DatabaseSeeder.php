<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],    
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'SuryaGilang@celoe.test'],
            [
                'name' => 'Surya Gilang Permana',
                'password' => Hash::make('gilang123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '102042500110',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // TODO 8: Tambahkan minimal 2 mata kuliah lagi dengan semester berbeda, 1 mahasiswa tambahan, 2 tugas, dan contoh data submission
    // Mahasiswa tambahan
$mahasiswa2 = User::updateOrCreate(
    ['email' => 'siti@celoe.test'],
    [
        'name' => 'Siti Nurhaliza',
        'password' => Hash::make('mahasiswa123'),
        'role' => 'mahasiswa',
        'nomor_induk' => '1301210002',
    ]
);

// Dua mata kuliah tambahan (semester dan SKS berbeda)
$course2 = MataKuliah::updateOrCreate(
    ['kode_mk' => 'IF204'],
    [
        'nama_mk' => 'Basis Data Relasional',
        'deskripsi' => 'Mata kuliah perancangan basis data relasional, normalisasi, ERD, dan optimasi query SQL.',
        'sks' => 3,
        'semester' => 'Ganjil 2026/2027',
        'dosen_id' => $dosen->id,
    ]
);

$course3 = MataKuliah::updateOrCreate(
    ['kode_mk' => 'IF402'],
    [
        'nama_mk' => 'Arsitektur Layanan Awan',
        'deskripsi' => 'Mata kuliah arsitektur cloud computing, containerization, dan layanan terdistribusi.',
        'sks' => 2,
        'semester' => 'Genap 2026/2027',
        'dosen_id' => $dosen->id,
    ]
);

// Pendaftaran mahasiswa ke kelas (status approved)
$course1->students()->syncWithoutDetaching([
    $mahasiswa2->id => ['status' => 'approved'],
]);
$course2->students()->syncWithoutDetaching([
    $mahasiswa1->id => ['status' => 'approved'],
]);

// Dua tugas perkuliahan
$assignment1 = Assignment::updateOrCreate(
    ['course_id' => $course1->id, 'judul' => 'Tugas 1: Implementasi Middleware & Eloquent ORM'],
    [
        'deskripsi' => 'Jelaskan cara kerja Route Middleware untuk proteksi peran pengguna serta implementasi relasi Eloquent pada studi kasus CeLOE LMS.',
        'deadline' => now()->addDays(7),
    ]
);

$assignment2 = Assignment::updateOrCreate(
    ['course_id' => $course2->id, 'judul' => 'Tugas 1: Normalisasi Basis Data'],
    [
        'deskripsi' => 'Lakukan normalisasi tabel transaksi penjualan hingga bentuk normal ketiga (3NF) dan jelaskan setiap tahapannya.',
        'deadline' => now()->addDays(10),
    ]
);

// Contoh pengumpulan tugas
// Ahmad, Tugas 1: sudah dinilai (Lulus)
Submission::updateOrCreate(
    ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa1->id],
    [
        'jawaban' => 'Middleware di Laravel berfungsi menyaring HTTP request sebelum mencapai controller. Pada CeLOE LMS, RoleMiddleware memeriksa apakah role pengguna sesuai dengan parameter route.',
        'nilai' => 88,
        'catatan_dosen' => 'Penjelasan sangat terstruktur dan tepat sasaran. Pertahankan!',
        'submitted_at' => now()->subDays(1),
    ]
);

// Siti, Tugas 1: sudah mengumpulkan, belum dinilai
Submission::updateOrCreate(
    ['assignment_id' => $assignment1->id, 'user_id' => $mahasiswa2->id],
    [
        'jawaban' => 'Relasi Eloquent menghubungkan antar model, misalnya MataKuliah hasMany Assignment dan Assignment belongsTo MataKuliah, sehingga data terkait dapat diakses tanpa query manual.',
        'nilai' => null,
        'catatan_dosen' => null,
        'submitted_at' => now()->subHours(5),
    ]
);

// Ahmad, Tugas 1: sudah dinilai (Perlu Diperbaiki)
Submission::updateOrCreate(
    ['assignment_id' => $assignment2->id, 'user_id' => $mahasiswa1->id],
    [
        'jawaban' => 'Normalisasi bertujuan mengurangi redundansi data. Tabel transaksi dipecah menjadi tabel pelanggan, produk, dan transaksi.',
        'nilai' => 65,
        'catatan_dosen' => 'Tahapan 1NF dan 2NF belum dijelaskan. Lengkapi sampai 3NF.',
        'submitted_at' => now()->subDays(2),
    ]
);
    
        }
}
