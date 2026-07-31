<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DemoOrganizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Organizer HIMA
        $organizerHima = User::firstOrCreate(
            ['email' => 'organizer.hima@test.com'],
            [
                'name' => 'Organizer HIMA',
                'password' => bcrypt('password'),
                'role' => 'organizer',
            ]
        );

        // 2. Buat Organizer PRX
        $organizerPrx = User::firstOrCreate(
            ['email' => 'organizer.prx@test.com'],
            [
                'name' => 'Organizer PRX',
                'password' => bcrypt('password'),
                'role' => 'organizer',
            ]
        );

        // Ambil kategori yang tersedia
        $categoryIT = Category::where('slug', 'seminar-it')->first();
        $categoryEnt = Category::where('slug', 'entertaiment')->first();

        // Fallback jika tidak ditemukan
        $catITId = $categoryIT ? $categoryIT->id : 1;
        $catEntId = $categoryEnt ? $categoryEnt->id : 2;

        // 3. Buat Event HIMA (dimiliki oleh Organizer HIMA)
        Event::firstOrCreate(
            [
                'title' => 'Festival Musik HIMA 2026',
                'user_id' => $organizerHima->id,
            ],
            [
                'category_id' => $catEntId,
                'description' => 'Festival musik tahunan Himpunan Mahasiswa Amikom dengan bintang tamu lokal menarik.',
                'date' => '2026-09-15 18:00:00',
                'location' => 'Lobby Gedung 3 Amikom',
                'price' => 25000,
                'stock' => 150,
                'poster_path' => null,
            ]
        );

        // 4. Buat Event PRX (dimiliki oleh Organizer PRX)
        Event::firstOrCreate(
            [
                'title' => 'PRX Tech Workshop 2026',
                'user_id' => $organizerPrx->id,
            ],
            [
                'category_id' => $catITId,
                'description' => 'Workshop pemrograman web modern dan arsitektur microservices bersama expert.',
                'date' => '2026-10-20 09:00:00',
                'location' => 'Ruang Cinema Unit 6 Amikom',
                'price' => 35000,
                'stock' => 80,
                'poster_path' => null,
            ]
        );
    }
}
