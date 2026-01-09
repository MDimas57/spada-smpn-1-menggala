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

// Redirect dashboard berdasarkan Role
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

// --- RUTE PROFIL ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- GROUP ROUTE KHUSUS SISWA ---
Route::middleware(['auth', 'role:siswa'])->group(function () {

    // Dashboard Utama Siswa
    Route::get('/siswa/dashboard', [SiswaController::class, 'index'])->name('siswa.dashboard');

    // My Courses Siswa
    Route::get('/siswa/my-courses', [SiswaController::class, 'myCourses'])->name('siswa.my-courses');

    // [TAMBAHAN BARU] Route untuk AJAX toggle star
    // Saya sesuaikan URL-nya agar konsisten ada prefix '/siswa/'
    Route::post('/siswa/course/{id}/toggle-star', [SiswaController::class, 'toggleStar'])->name('course.toggle-star');

    // Detail Course
    Route::get('/siswa/course/{course}', [SiswaController::class, 'showCourse'])->name('siswa.course.show');

    // Halaman Detail Modul (Belajar)
    Route::get('/siswa/modul/{modul}', [SiswaController::class, 'show'])->name('siswa.modul.show');

    // Upload Tugas
    Route::post('/siswa/tugas/{tugas}/upload', [SiswaController::class, 'uploadTugas'])->name('siswa.tugas.upload');

    // --- FITUR KUIS ---

    // 1. Halaman Mengerjakan Kuis (GET)
    Route::get('/siswa/kuis/{kuis}/kerjakan', [SiswaController::class, 'kerjakanKuis'])
        ->name('siswa.kuis.kerjakan');

    // 2. Submit Jawaban Kuis (POST)
    Route::post('/siswa/kuis/{kuis}/submit', [SiswaController::class, 'submitKuis'])
        ->name('siswa.kuis.submit');

    // Route untuk Participants
    Route::get('/course/{course}/participants', [SiswaController::class, 'showParticipants'])
        ->name('siswa.course.participants');

    // Route untuk Grades
    Route::get('/course/{course}/grades', [SiswaController::class, 'showGrades'])
        ->name('siswa.course.grades');

    // Route untuk Competencies
    Route::get('/course/{course}/competencies', [SiswaController::class, 'showCompetencies'])
        ->name('siswa.course.competencies');

    // Route untuk Badges
    Route::get('/course/{course}/badges', [SiswaController::class, 'showBadges'])
        ->name('siswa.course.badges');
});

require __DIR__ . '/auth.php';
