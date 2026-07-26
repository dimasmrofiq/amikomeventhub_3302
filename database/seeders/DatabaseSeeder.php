<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Wajib ditambahkan untuk membuat slug otomatis

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insert Akun Superadmin
        $superadmin = User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'], // Cari berdasarkan email ini
            [
                'name'     => 'Admin Amikom',
                'password' => bcrypt('password'),
                'role'     => 'superadmin', // Diubah dari 'admin' menjadi 'superadmin'
            ]
        );

        // (Opsional) Insert Akun Organizer untuk testing Fitur 3
        $organizer = User::firstOrCreate(
            ['email' => 'hima@amikom.ac.id'], 
            [
                'name'     => 'HIMA Amikom',
                'password' => bcrypt('password'),
                'role'     => 'organizer',
            ]
        );
            
        // 2. Insert Kategori
        $category = Category::firstOrCreate(
            ['slug' => 'seminar-it'],
            ['name' => 'Seminar IT']
        );
                
        $category2 = Category::firstOrCreate(
            ['slug' => 'entertaiment'], // typo asli dipertahankan, atau bisa diganti 'entertainment'
            ['name' => 'Entertaiment']
        );
            
        // 3. Insert Sampel Events
        Event::firstOrCreate(
            ['title' => 'Jazz Night 2025'], // Cari berdasarkan judul agar tidak duplikat
            [
                'user_id'     => $superadmin->id, // Mengaitkan event dengan pembuatnya
                'category_id' => $category2->id,
                'slug'        => Str::slug('Jazz Night 2025'), // Membuat slug otomatis
                'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',
                'date'        => '2026-05-10 19:00:00',
                'location'    => 'Amikom Baru',
                'price'       => 50000,
                'stock'       => 100,
                'quota'       => 100, // Tambahan Fitur 3
                'poster_path' => 'posters/event-1.png',
            ]
        );
            
        Event::firstOrCreate(
            ['title' => 'Hackaton - Unleash Your Inner Developer'],
            [
                'user_id'     => $organizer->id, // Contoh event yang dibuat oleh organizer
                'category_id' => $category->id,
                'slug'        => Str::slug('Hackaton - Unleash Your Inner Developer'),
                'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan!',
                'date'        => '2026-05-05 10:00:00',
                'location'    => 'Inkubator Amikom',
                'price'       => 50000,
                'stock'       => 100,
                'quota'       => 100,
                'poster_path' => 'posters/event-2.png',
            ]
        );
                    
        Event::firstOrCreate(
            ['title' => 'AI & FUTURE TECH SUMMIT 2026'],
            [
                'user_id'     => $superadmin->id,
                'category_id' => $category->id,
                'slug'        => Str::slug('AI & FUTURE TECH SUMMIT 2026'),
                'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',
                'date'        => '2026-05-01 13:00:00',
                'location'    => 'Cinema Unit 6',
                'price'       => 50000,
                'stock'       => 100,
                'quota'       => 100,
                'poster_path' => 'posters/event-3.png',
            ]
        );
    }
}