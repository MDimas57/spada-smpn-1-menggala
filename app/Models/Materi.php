<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Materi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function modul()
    {
        return $this->belongsTo(Modul::class);
    }

    protected static function booted()
    {
        static::deleting(function ($materi) {

            if (!empty($materi->file_path) && $materi->tipe !== 'link') {
                Storage::disk('public')->delete($materi->file_path);
            }
        });
    }
}