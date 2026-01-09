<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\AttendanceCategory;
use App\Models\HomeroomAttendance;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class AbsensiWaliKelas extends Page
{
    protected static ?string $navigationGroup = 'Absensi';
    protected static ?string $navigationLabel = 'Absensi Masuk/Pulang';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static string $view = 'filament.pages.absensi-wali-kelas';

    public $class_id;
    public $students = [];
    public $attendance = [];
    public $check_type = 'masuk';
    public $summary = [];
    public $isAutoMode = true; // Toggle untuk mode otomatis

    /**
     * Tentukan siapa yang bisa mengakses halaman ini
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();

        // Admin bisa akses semua
        if ($user->hasRole('admin')) {
            return true;
        }

        // Guru yang memiliki wali kelas bisa akses
        if ($user->hasRole('guru')) {
            return $user->guru && $user->guru->waliKelas;
        }

        return false;
    }

    /**
     * Inisialisasi saat halaman dimuat
     */
    public function mount()
    {
        Carbon::setLocale('id');

        $user = auth()->user();

        // Auto-detect tipe absensi berdasarkan jam
        if ($this->isAutoMode) {
            $this->check_type = $this->getAutoCheckType();
        }

        // Jika bukan admin, otomatis set kelas sesuai wali kelas
        if (!$user->hasRole('admin') && $user->guru && $user->guru->waliKelas) {
            $this->class_id = $user->guru->waliKelas->kelas_id;
            $this->loadStudents();
        }
    }

    /**
     * Auto-detect tipe absensi berdasarkan jam
     *
     * @return string "masuk" atau "pulang"
     */
    private function getAutoCheckType(): string
    {
        $hour = (int) Carbon::now('Asia/Jakarta')->format('H');

        // Logika:
        // 00:00 - 11:59 → Masuk
        // 12:00 - 23:59 → Pulang

        // Atau bisa lebih spesifik:
        // 06:00 - 11:00 → Masuk
        // 11:01 - 23:59 → Pulang

        if ($hour >= 6 && $hour < 12) {
            return 'masuk';
        } else {
            return 'pulang';
        }
    }

    /**
     * Toggle mode manual/otomatis
     */
    public function toggleAutoMode()
    {
        $this->isAutoMode = !$this->isAutoMode;

        if ($this->isAutoMode) {
            $this->check_type = $this->getAutoCheckType();
            $this->loadStudents();

            Notification::make()
                ->success()
                ->title('Mode Otomatis Aktif')
                ->body('Tipe absensi akan terdeteksi otomatis berdasarkan jam')
                ->send();
        } else {
            Notification::make()
                ->info()
                ->title('Mode Manual Aktif')
                ->body('Anda bisa memilih tipe absensi secara manual')
                ->send();
        }
    }

    /**
     * Ketika kelas dipilih (khusus untuk admin)
     */
    public function updatedClassId($value)
    {
        $this->loadStudents();
    }

    /**
     * Ketika tipe absensi berubah (masuk/pulang)
     */
    public function updatedCheckType($value)
    {
        // Jika manual mode, matikan auto
        if ($this->isAutoMode) {
            $this->isAutoMode = false;
        }

        $this->loadStudents();
    }

    /**
     * Load siswa berdasarkan kelas dan cek status absensi hari ini
     */
    public function loadStudents()
    {
        if (!$this->class_id) {
            $this->students = [];
            $this->attendance = [];
            $this->summary = [];
            return;
        }

        $this->students = Siswa::with('user')
            ->where('kelas_id', $this->class_id)
            ->orderBy('nis')
            ->get();

        // Load status absensi hari ini
        $date = Carbon::now('Asia/Jakarta')->toDateString();
        $defaultCat = AttendanceCategory::where('code', 'H')->first();

        foreach ($this->students as $student) {
            $existing = HomeroomAttendance::where('student_id', $student->id)
                ->where('date', $date)
                ->where('check_type', $this->check_type)
                ->first();

            $this->attendance[$student->id] = $existing
                ? $existing->category_id
                : ($defaultCat ? $defaultCat->id : null);
        }

        $this->updateSummary();
    }

    /**
     * Set status absensi untuk siswa tertentu
     */
    public function setStatus($studentId, $categoryId)
    {
        $this->attendance[$studentId] = $categoryId;
        $this->updateSummary();
    }

    /**
     * Update ringkasan absensi
     */
    public function updateSummary()
    {
        $this->summary = [];
        foreach ($this->attendance as $catId) {
            if ($catId) {
                $this->summary[$catId] = ($this->summary[$catId] ?? 0) + 1;
            }
        }
    }

    /**
     * Simpan absensi individual
     */
    public function saveCheck($studentId)
    {
        if (!$this->class_id) {
            Notification::make()
                ->danger()
                ->title('Pilih kelas terlebih dahulu')
                ->send();
            return;
        }

        $date = Carbon::now('Asia/Jakarta')->toDateString();
        $time = Carbon::now('Asia/Jakarta')->toTimeString();

        $categoryId = $this->attendance[$studentId] ?? null;

        if (!$categoryId) {
            Notification::make()
                ->danger()
                ->title('Pilih status absensi terlebih dahulu')
                ->send();
            return;
        }

        HomeroomAttendance::updateOrCreate(
            [
                'student_id' => $studentId,
                'date' => $date,
                'check_type' => $this->check_type
            ],
            [
                'class_id' => $this->class_id,
                'category_id' => $categoryId,
                'time' => $time,
                'note' => null
            ]
        );

        Notification::make()
            ->success()
            ->title('Absensi berhasil disimpan')
            ->send();
    }

    /**
     * Simpan semua absensi sekaligus
     */
    public function saveAll()
    {
        if (!$this->class_id) {
            Notification::make()
                ->danger()
                ->title('Pilih kelas terlebih dahulu')
                ->send();
            return;
        }

        $date = Carbon::now('Asia/Jakarta')->toDateString();
        $time = Carbon::now('Asia/Jakarta')->toTimeString();
        $saved = 0;

        foreach ($this->attendance as $studentId => $categoryId) {
            if ($categoryId) {
                HomeroomAttendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'date' => $date,
                        'check_type' => $this->check_type
                    ],
                    [
                        'class_id' => $this->class_id,
                        'category_id' => $categoryId,
                        'time' => $time,
                        'note' => null
                    ]
                );
                $saved++;
            }
        }

        Notification::make()
            ->success()
            ->title('Absensi berhasil disimpan')
            ->body("{$saved} siswa telah diabsen {$this->check_type}")
            ->send();
    }

    /**
     * Dapatkan daftar kelas (hanya untuk admin)
     */
    public function getAvailableClassesProperty()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return Kelas::orderBy('nama')->get();
        }

        // Jika wali kelas, hanya kelasnya sendiri
        if ($user->guru && $user->guru->waliKelas) {
            return Kelas::where('id', $user->guru->waliKelas->kelas_id)->get();
        }

        return collect([]);
    }

    /**
     * Dapatkan semua kategori absensi
     */
    public function getCategoriesProperty()
    {
        return AttendanceCategory::orderBy('code')->get();
    }

    /**
     * Cek apakah user adalah admin
     */
    public function getIsAdminProperty()
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * Get nama kelas untuk wali kelas
     */
    public function getWaliKelasInfoProperty()
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && $user->guru && $user->guru->waliKelas) {
            return $user->guru->waliKelas->kelas;
        }

        return null;
    }

    /**
     * Get current hour untuk display
     */
    public function getCurrentHourProperty()
    {
        return (int) Carbon::now('Asia/Jakarta')->format('H');
    }
}
