<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Category;
use App\Models\Event;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        
        User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'], // Cari berdasarkan email ini
            [
                'name' => 'Admin Amikom',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
            
        
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
                'category_id' => $category2->id,
                'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',
                'date' => '2026-05-10 19:00:00',
                'location' => 'Amikom Baru',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => 'posters/event-1.png',
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
                'poster_path' => 'posters/event-2.png',
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
                'poster_path' => 'posters/event-3.png',
            ]
        );
    }
}