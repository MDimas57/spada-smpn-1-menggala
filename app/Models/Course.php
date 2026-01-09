<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi ke Mapel
    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Relasi ke Modul (1 Course punya banyak Modul)
    public function moduls()
    {
        return $this->hasMany(Modul::class)->orderBy('created_at');
    }
    public function starredByUsers()
{
    return $this->belongsToMany(User::class, 'course_user_stars', 'course_id', 'user_id');
}
}
