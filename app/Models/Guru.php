<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'guru_mapel');
    }

    public function moduls()
    {
        return $this->hasMany(Modul::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'guru_kelas', 'guru_id', 'kelas_id');
    }

    public function waliKelas()
    {
        return $this->hasOne(WaliKelas::class);
    }

    /**
     * Relasi ke JadwalPelajaran
     * Ditambahkan untuk memperbaiki error "Call to undefined method"
     */
    public function jadwalPelajarans()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    /**
     * Display name with academic titles (gelar depan & belakang) combined with user name.
     * Example: "Dr. Nama Guru, M.Si"
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->user?->name ?? '';
        $front = $this->gelar_depan ? trim($this->gelar_depan) . ' ' : '';
        $back = $this->gelar_belakang ? ', ' . trim($this->gelar_belakang) : '';

        return trim($front . $name) . ($name ? $back : '');
    }
}
