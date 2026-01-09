<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeroomAttendance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime'
    ];

    /**
     * Relasi ke Siswa
     */
    public function student()
    {
        return $this->belongsTo(Siswa::class, 'student_id');
    }

    /**
     * Relasi ke Kelas
     */
    public function class()
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    /**
     * Relasi ke Kategori Absensi
     */
    public function category()
    {
        return $this->belongsTo(AttendanceCategory::class, 'category_id');
    }
}
