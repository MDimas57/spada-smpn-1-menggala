<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = [];
    protected $casts = ['date' => 'date'];

    public function student(){ return $this->belongsTo(Siswa::class,'student_id'); }
    public function schedule(){ return $this->belongsTo(JadwalPelajaran::class,'schedule_id'); }
    public function category(){ return $this->belongsTo(AttendanceCategory::class,'category_id'); }
}
