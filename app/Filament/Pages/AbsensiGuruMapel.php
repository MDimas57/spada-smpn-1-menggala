<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\JadwalPelajaran;
use App\Models\Siswa;
use App\Models\Attendance;
use App\Models\AttendanceCategory;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class AbsensiGuruMapel extends Page
{
    protected static ?string $navigationGroup = 'Absensi';
    protected static ?string $navigationLabel = 'Absensi Guru Mapel';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static string $view = 'filament.pages.absensi-guru-mapel';

    public $kelas_id;
    public $mapel_id;
    public $schedule_id;
    public $students = [];
    public $attendance = [];
    public $summary = [];
    public $selectedSchedule = null;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['admin', 'guru']);
    }

    public function mount()
    {
        $this->kelas_id = null;
        $this->mapel_id = null;
        $this->schedule_id = null;

        // Set locale ke Indonesia
        Carbon::setLocale('id');
    }

    /**
     * Ketika kelas dipilih, reset mapel & jadwal
     */
    public function updatedKelasId($value)
    {
        $this->mapel_id = null;
        $this->schedule_id = null;
        $this->students = [];
        $this->attendance = [];
        $this->selectedSchedule = null;
    }

    /**
     * Ketika mapel dipilih, reset jadwal
     */
    public function updatedMapelId($value)
    {
        $this->schedule_id = null;
        $this->students = [];
        $this->attendance = [];
        $this->selectedSchedule = null;
    }

    /**
     * Load siswa ketika jadwal dipilih
     */
    public function loadScheduleStudents($scheduleId)
    {
        $this->schedule_id = $scheduleId;
        $this->selectedSchedule = JadwalPelajaran::with(['kelas', 'mapel', 'guru.user'])->find($scheduleId);

        if (!$this->selectedSchedule) {
            Notification::make()
                ->danger()
                ->title('Jadwal tidak ditemukan')
                ->send();
            return;
        }

        // Ambil siswa dari kelas
        $this->students = Siswa::with('user')
            ->where('kelas_id', $this->selectedSchedule->kelas_id)
            ->orderBy('nis')
            ->get();

        // Set default status "Hadir" untuk semua siswa
        $defaultCat = AttendanceCategory::where('code', 'H')->first();
        $date = Carbon::now('Asia/Jakarta')->toDateString();

        foreach ($this->students as $s) {
            // Cek apakah sudah ada record absensi hari ini
            $existing = Attendance::where('student_id', $s->id)
                ->where('schedule_id', $scheduleId)
                ->where('date', $date)
                ->first();

            $this->attendance[$s->id] = $existing ? $existing->category_id : ($defaultCat ? $defaultCat->id : null);
        }

        $this->updateSummary();

        Notification::make()
            ->success()
            ->title('Siswa berhasil dimuat')
            ->body($this->students->count() . ' siswa ditemukan')
            ->send();
    }

    /**
     * Set status absensi siswa
     */
    public function setStatus($studentId, $categoryId)
    {
        $this->attendance[$studentId] = $categoryId;
        $this->updateSummary();
    }

    /**
     * Update ringkasan absensi (H: 25, S: 2, dst)
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
     * Simpan absensi ke database
     */
    public function save()
    {
        if (!$this->schedule_id) {
            Notification::make()
                ->danger()
                ->title('Pilih jadwal terlebih dahulu')
                ->send();
            return;
        }

        $date = Carbon::now('Asia/Jakarta')->toDateString();
        $saved = 0;

        foreach ($this->attendance as $studentId => $categoryId) {
            if ($categoryId) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'schedule_id' => $this->schedule_id,
                        'date' => $date
                    ],
                    [
                        'category_id' => $categoryId,
                        'note' => null
                    ]
                );
                $saved++;
            }
        }

        Notification::make()
            ->success()
            ->title('Absensi berhasil disimpan')
            ->body("{$saved} siswa telah diabsen")
            ->send();
    }

    /**
     * Dapatkan daftar mata pelajaran hari ini untuk kelas yang dipilih
     */
    public function getMapelsProperty()
    {
        // Dapatkan hari ini dalam bahasa Indonesia
        $today = Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd');

        $query = JadwalPelajaran::with('mapel')
            ->where('hari', $today);

        if ($this->kelas_id) {
            $query->where('kelas_id', $this->kelas_id);
        }

        // Jika bukan admin, filter hanya jadwal guru yang login
        if (!auth()->user()->hasRole('admin')) {
            $guruId = auth()->user()->guru->id ?? null;
            if ($guruId) {
                $query->where('guru_id', $guruId);
            } else {
                return collect([]);
            }
        }

        $schedules = $query->get();

        // Ambil mata pelajaran unik (distinct)
        return $schedules
            ->unique('mapel_id')
            ->pluck('mapel.nama', 'mapel_id');
    }

    /**
     * Dapatkan jadwal hari ini berdasarkan kelas & mapel yang dipilih
     */
    public function getSchedulesProperty()
    {
        if (!$this->mapel_id) {
            return collect([]);
        }

        $today = Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd');

        $query = JadwalPelajaran::with(['mapel', 'guru.user', 'kelas'])
            ->where('mapel_id', $this->mapel_id)
            ->where('hari', $today);

        if ($this->kelas_id) {
            $query->where('kelas_id', $this->kelas_id);
        }

        // Jika bukan admin, filter hanya jadwal guru yang login
        if (!auth()->user()->hasRole('admin')) {
            $guruId = auth()->user()->guru->id ?? null;
            if ($guruId) {
                $query->where('guru_id', $guruId);
            }
        }

        return $query->orderBy('jam_mulai')->get();
    }

    /**
     * Dapatkan semua kategori absensi
     */
    public function getCategoriesProperty()
    {
        return AttendanceCategory::orderBy('code')->get();
    }

    /**
     * Reset ke halaman jadwal
     */
    public function resetToScheduleList()
    {
        $this->schedule_id = null;
        $this->students = [];
        $this->attendance = [];
        $this->summary = [];
        $this->selectedSchedule = null;
    }

    /**
     * Dapatkan semua jadwal hari ini (untuk menampilkan pada empty state)
     */
    public function getTodaySchedulesProperty()
    {
        $today = Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd');

        $query = JadwalPelajaran::with(['mapel', 'guru.user', 'kelas'])
            ->where('hari', $today);

        // Jika bukan admin, filter hanya jadwal guru yang login
        if (!auth()->user()->hasRole('admin')) {
            $guruId = auth()->user()->guru->id ?? null;
            if ($guruId) {
                $query->where('guru_id', $guruId);
            } else {
                return collect([]);
            }
        }

        return $query->orderBy('jam_mulai')->get();
    }
}
