<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Exports\AttendanceReportExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('admin') || $user->hasRole('guru')) {
        return redirect('/admin');
    }

    return redirect()->route('siswa.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/rekap-attendance/export', function (\Illuminate\Http\Request $req) {
    $from = $req->query('from');
    $to = $req->query('to');
    $kelas = $req->query('kelas');
    $fileName = "rekap-absensi-{$from}-{$to}.xlsx";
    return Excel::download(new \App\Exports\AttendanceReportExport($from, $to, $kelas), $fileName);
})->name('admin.rekap-attendance.export');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:siswa'])->group(function () {

    Route::get('/siswa/dashboard', [SiswaController::class, 'index'])->name('siswa.dashboard');

    Route::get('/siswa/my-courses', [SiswaController::class, 'myCourses'])->name('siswa.my-courses');

    Route::post('/siswa/course/{id}/toggle-star', [SiswaController::class, 'toggleStar'])->name('course.toggle-star');

    Route::get('/siswa/course/{course}', [SiswaController::class, 'showCourse'])->name('siswa.course.show');

    Route::get('/siswa/modul/{modul}', [SiswaController::class, 'show'])->name('siswa.modul.show');

    Route::post('/siswa/tugas/{tugas}/upload', [SiswaController::class, 'uploadTugas'])->name('siswa.tugas.upload');

    Route::get('/siswa/kuis/{kuis}/kerjakan', [SiswaController::class, 'kerjakanKuis'])
        ->name('siswa.kuis.kerjakan');

    Route::post('/siswa/kuis/{kuis}/submit', [SiswaController::class, 'submitKuis'])
        ->name('siswa.kuis.submit');

    Route::get('/course/{course}/participants', [SiswaController::class, 'showParticipants'])
        ->name('siswa.course.participants');

    Route::get('/course/{course}/grades', [SiswaController::class, 'showGrades'])
        ->name('siswa.course.grades');

    Route::get('/course/{course}/competencies', [SiswaController::class, 'showCompetencies'])
        ->name('siswa.course.competencies');

    Route::get('/course/{course}/badges', [SiswaController::class, 'showBadges'])
        ->name('siswa.course.badges');
});

require __DIR__ . '/auth.php';