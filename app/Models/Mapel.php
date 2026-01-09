<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel');
    }

    public function moduls()
    {
        return $this->hasMany(Modul::class);
    }

    /**
     * RELASI BARU (WAJIB ADA)
     * Untuk filter di CourseResource
     * Menghubungkan Mapel ke jadwal_pelajarans
     */
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'mapel_id');
    }
}
