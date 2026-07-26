<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Kolom role ditambahkan di sini agar aman saat registrasi/update data
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==========================================
    // PENAMBAHAN FITUR 3
    // ==========================================

    // Relasi: Satu Organisasi/User dapat memiliki banyak Event
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Helper untuk pengecekan role superadmin
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    // Helper untuk pengecekan role organizer (Superadmin juga dianggap valid)
    public function isOrganizer()
    {
        return $this->role === 'organizer' || $this->role === 'superadmin';
    }
}