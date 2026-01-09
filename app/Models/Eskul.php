<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Eskul extends Model
{
    use HasFactory;

    protected $table = 'eskuls';
    protected $guarded = [];

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'eskul_siswa', 'eskul_id', 'siswa_id')
            ->withTimestamps();
    }

    public function pembinas(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'pembina_eskul', 'eskul_id', 'guru_id')
            ->using(PembinaEskul::class)
            ->withTimestamps();
    }
}
