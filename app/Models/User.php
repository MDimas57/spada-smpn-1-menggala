<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Kontrol akses ke Filament Panel
     * Izinkan Admin DAN Guru masuk ke Dashboard Filament
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin') || $this->hasRole('guru');
    }

    /**
     * RELASI: User ke Guru (One-to-One)
     * Untuk mengakses data guru dari user
     * Contoh: $user->guru->nip
     */
    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    /**
     * RELASI: User ke Siswa (One-to-One)
     * Untuk mengakses data siswa dari user
     * Contoh: $user->siswa->nis
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    public function starredCourses()
{
    return $this->belongsToMany(Course::class, 'course_user_stars', 'user_id', 'course_id');
}
}
