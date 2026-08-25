<?php

namespace Database\Seeders;

// Import semua model yang digunakan di sini
use App\Models\User;
use App\Models\Category;
use App\Models\Event;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin & Organizers
        User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name' => 'Admin Amikom',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'organizer.hima@test.com'],
            [
                'name' => 'Organizer HIMA',
                'password' => bcrypt('password'),
                'role' => 'organizer',
            ]
        );

        User::firstOrCreate(
            ['email' => 'organizer.bem@test.com'],
            [
                'name' => 'Organizer BEM',
                'password' => bcrypt('password'),
                'role' => 'organizer',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'User Test',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );
            
        // 2. Insert Kategori Event
        // Saya ubah keduanya pakai firstOrCreate biar aman kalau di-seed ulang
        $category = Category::firstOrCreate(
            ['slug' => 'seminar-it'],
            ['name' => 'Seminar IT']
        );
                
        $category2 = Category::firstOrCreate(
            ['slug' => 'entertaiment'],
            ['name' => 'Entertaiment']
        );
            
        // 3. Insert Sampel Events (menggunakan firstOrCreate agar tidak duplikat)
        Event::firstOrCreate(
            ['title' => 'Jazz Night 2025'],
            [
                'category_id' => $category2->id,
                'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',
                'date' => '2026-05-10 19:00:00',
                'location' => 'Amikom Baru',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => null,
            ]
        );
            
        Event::firstOrCreate(
            ['title' => 'Hackaton - Unleash Your Inner Developer'],
            [
                'category_id' => $category->id,
                'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan!',
                'date' => '2026-05-05 10:00:00',
                'location' => 'Inkubator Amikom',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => null,
            ]
        );
                    
        Event::firstOrCreate(
            ['title' => 'AI & FUTURE TECH SUMMIT 2026'],
            [
                'category_id' => $category->id,
                'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',
                'date' => '2026-05-01 13:00:00',
                'location' => 'Cinema Unit 6',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => null,
            ]
        );
    }
}