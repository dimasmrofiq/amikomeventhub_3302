<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    // Mengizinkan seeder/form mengisi kolom name dan slug
    protected $fillable = [
        'name',
        'slug',
    ];

    // Relasi ke tabel events (Satu kategori punya banyak event)
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}