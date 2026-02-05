<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PengumpulanTugas extends Model
{
    use HasFactory;

    protected $table = 'pengumpulan_tugas';

    protected $guarded = [];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Delete uploaded file when a PengumpulanTugas record is deleted.
     */
    protected static function booted()
    {
        static::deleting(function ($pengumpulan) {
            if (!empty($pengumpulan->file_path)) {
                Storage::disk('public')->delete($pengumpulan->file_path);
            }
        });
    }
}