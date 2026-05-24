<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    protected $fillable = ['name', 'category_id', 'logo_path'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}