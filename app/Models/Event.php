<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Event extends Model
{
    //
    protected $fillable = [
    'category_id', 'title', 'description', 'date',
    'location', 'price', 'stock', 'poster_path'
    ];


    public function category() {
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
}