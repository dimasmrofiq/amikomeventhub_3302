<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;

    /**
     * SOLUSI UTS: Menggunakan guarded kosong agar semua kolom minimum 
     * (id, name, logo_url, category_id) bisa disimpan tanpa halangan mass assignment.
     */
    protected $guarded = [];

    /**
     * RELASI: Banyak Partner dimiliki oleh Satu Kategori
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}