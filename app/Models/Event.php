<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // Menggabungkan fillable lama dan atribut baru untuk Fitur 3
    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'description', 'date',
        'location', 'price', 'stock', 'quota', 'poster_path'
    ];

    public function category() 
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper untuk menghitung rata-rata rating event
    public function averageRating()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // ==========================================
    // PENAMBAHAN FITUR 3 (INI YANG BIKIN ERROR JIKA HILANG)
    // ==========================================

    // Relasi: Event dimiliki oleh satu Organizer (User)
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}