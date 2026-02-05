<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas'; // Memaksa nama tabel (laravel biasanya mencari 'tugases')

    protected $guarded = [];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    /**
     * Delete stored file when a Tugas record is deleted.
     */
    protected static function booted()
    {
        static::deleting(function ($tugas) {
            // First, delete all student submissions (PengumpulanTugas) so their
            // model deleting events run and remove uploaded files.
            $tugas->pengumpulan()->get()->each(function ($peng) {
                try {
                    $peng->delete();
                } catch (\Exception $e) {
                    // swallow exceptions to avoid blocking the parent delete
                }
            });

            // Then delete the tugas file itself
            if (!empty($tugas->file_path)) {
                Storage::disk('public')->delete($tugas->file_path);
            }
        });
    }
}
