<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Mapel;
use App\Models\AttendanceCategory;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class RekapAbsensi extends Page
{
    protected static ?string $navigationGroup = 'Absensi';
    protected static ?string $navigationLabel = 'Rekap Absensi';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.rekap-absensi';
    protected static ?int $navigationSort = 3;

    // ========== PROPERTIES ==========
    // Filter
    public $kelas_id = '';
    public $mapel_id = '';
    public $siswa_id = '';
    public $guru_id = '';
    public $wali_kelas_id = '';
    public $from;
    public $to;

    // Tipe Rekap
    public $report_type = 'summary';
    public $attendance_source = 'all';

    // Results
    public $results = [];
    public $detailResults = [];
    public $studentResults = [];

    // Stats
    public $totalAbsensi = 0;
    public $totalSiswa = 0;

    // ========== LIFECYCLE ==========
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['admin', 'guru']);
    }

    public function mount()
    {
        $this->from = Carbon::now('Asia/Jakarta')->startOfMonth()->toDateString();
        $this->to = Carbon::now('Asia/Jakarta')->toDateString();
    }

    // ========== FILTER UPDATES ==========
    /**
     * Reset saat kelas berubah
     * - Guru akan di-refresh dengan filter kelas baru
     * - Mapel akan direset
     */
    public function updatedKelasId()
    {
        $this->guru_id = '';
        $this->mapel_id = '';
        $this->siswa_id = '';
        $this->resetResults();
    }

    /**
     * Reset saat guru berubah
     * - Mapel akan di-refresh dengan filter guru baru
     */
    public function updatedGuruId()
    {
        $this->mapel_id = '';
        $this->resetResults();
    }

    public function updatedMapelId()
    {
        $this->resetResults();
    }

    public function updatedWaliKelasId()
    {
        $this->kelas_id = '';
        $this->resetResults();
    }

    public function updatedReportType()
    {
        $this->resetResults();
    }

    public function updatedAttendanceSource()
    {
        $this->resetResults();
        if ($this->attendance_source === 'homeroom') {
            $this->mapel_id = '';
        }
    }

    private function resetResults()
    {
        $this->results = [];
        $this->detailResults = [];
        $this->studentResults = [];
    }

    // ========== FILTER PROPERTIES (DYNAMIC) ==========
    /**
     * Get available classes
     */
    public function getKelasListProperty()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return Kelas::orderBy('nama')->get();
        }

        if ($user->hasRole('guru') && $user->guru) {
            $kelasIds = collect();
            $kelasIds = $kelasIds->merge($user->guru->kelas->pluck('id'));

            if ($user->guru->waliKelas) {
                $kelasIds->push($user->guru->waliKelas->kelas_id);
            }

            return Kelas::whereIn('id', $kelasIds->unique())->orderBy('nama')->get();
        }

        return collect([]);
    }

    /**
     * Get available gurus - FILTER DINAMIS berdasarkan kelas_id
     * * Logika:
     * 1. Jika admin memilih kelas -> tampilkan guru yang mengajar di kelas itu
     * 2. Jika belum pilih kelas -> tampilkan semua guru
     */
    public function getGuruListProperty()
    {
        // Hanya admin yang bisa filter guru
        if (!auth()->user()->hasRole('admin')) {
            return collect([]);
        }

        // Jika kelas dipilih, tampilkan guru yang mengajar di kelas tersebut
        if ($this->kelas_id) {
            return Guru::with('user')
                ->whereHas('kelas', function ($q) {
                    $q->where('kelas.id', $this->kelas_id);
                })
                // --- PERBAIKAN DI BAWAH INI (tambah huruf 's') ---
                ->orWhereHas('jadwalPelajarans', function ($q) {
                    $q->where('jadwal_pelajarans.kelas_id', $this->kelas_id);
                })
                ->distinct()
                ->orderBy('id')
                ->get()
                ->map(fn($guru) => (object) [
                    'id' => $guru->id,
                    'name' => $guru->user->name ?? 'Guru'
                ]);
        }

        // Tampilkan semua guru jika tidak ada filter kelas
        return Guru::with('user')
            ->orderBy('id')
            ->get()
            ->map(fn($guru) => (object) [
                'id' => $guru->id,
                'name' => $guru->user->name ?? 'Guru'
            ]);
    }

    /**
     * Get available mapels - FILTER DINAMIS berdasarkan guru_id dan kelas_id
     * * Logika:
     * 1. Jika guru dipilih -> tampilkan mapel yang diampu guru (di kelas yg dipilih jika ada)
     * 2. Jika hanya kelas dipilih -> tampilkan semua mapel di kelas
     * 3. Jika tidak ada filter -> tampilkan semua mapel
     */
    public function getMapelListProperty()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Jika guru dipilih, filter mapel yang diampu guru di kelas tersebut
            if ($this->guru_id) {
                $query = JadwalPelajaran::where('guru_id', $this->guru_id);

                if ($this->kelas_id) {
                    $query->where('kelas_id', $this->kelas_id);
                }

                return $query->distinct()
                    ->pluck('mapel_id')
                    ->map(fn($id) => Mapel::find($id))
                    ->filter()
                    ->sortBy('nama')
                    ->values();
            }

            // Jika hanya kelas dipilih, tampilkan semua mapel di kelas
            if ($this->kelas_id) {
                return JadwalPelajaran::where('kelas_id', $this->kelas_id)
                    ->distinct()
                    ->pluck('mapel_id')
                    ->map(fn($id) => Mapel::find($id))
                    ->filter()
                    ->sortBy('nama')
                    ->values();
            }

            // Tampilkan semua mapel
            return Mapel::orderBy('nama')->get();
        }

        // Untuk guru non-admin
        if ($user->hasRole('guru') && $user->guru) {
            return $user->guru->mapels()->orderBy('nama')->get();
        }

        return collect([]);
    }

    /**
     * Get available wali kelas
     */
    public function getWaliKelasListProperty()
    {
        if (auth()->user()->hasRole('admin')) {
            return WaliKelas::with('guru.user', 'kelas')
                ->orderBy('id')
                ->get()
                ->map(fn($wk) => (object) [
                    'id' => $wk->id,
                    'name' => ($wk->guru->user->name ?? 'Guru') . ' - ' . ($wk->kelas->nama ?? 'Kelas')
                ]);
        }

        return collect([]);
    }

    /**
     * Get available students (filtered by class)
     */
    public function getSiswaListProperty()
    {
        $query = Siswa::with('user');

        if ($this->kelas_id) {
            $query->where('kelas_id', $this->kelas_id);
        } elseif ($this->wali_kelas_id) {
            $waliKelas = WaliKelas::find($this->wali_kelas_id);
            if ($waliKelas) {
                $query->where('kelas_id', $waliKelas->kelas_id);
            }
        } else {
            $user = auth()->user();
            if (!$user->hasRole('admin') && $user->hasRole('guru') && $user->guru) {
                $kelasIds = collect();
                $kelasIds = $kelasIds->merge($user->guru->kelas->pluck('id'));

                if ($user->guru->waliKelas) {
                    $kelasIds->push($user->guru->waliKelas->kelas_id);
                }

                if ($kelasIds->isNotEmpty()) {
                    $query->whereIn('kelas_id', $kelasIds->unique());
                }
            }
        }

        return $query->orderBy('nis')->get();
    }

    /**
     * Get available attendance sources
     */
    public function getAvailableSourcesProperty()
    {
        $user = auth()->user();

        $sources = [
            'mapel' => '📚 Absensi Mapel'
        ];

        if ($user->hasRole('admin')) {
            $sources['homeroom'] = '🚌 Absensi Masuk/Pulang';
            $sources = ['all' => '📄 Semua (Mapel + Masuk/Pulang)'] + $sources;
        } elseif ($user->hasRole('guru') && $user->guru && $user->guru->waliKelas) {
            $sources['homeroom'] = '🚌 Absensi Masuk/Pulang';
            $sources = ['all' => '📄 Semua (Mapel + Masuk/Pulang)'] + $sources;
        }

        return $sources;
    }

    /**
     * Get categories
     */
    public function getCategoriesProperty()
    {
        return AttendanceCategory::orderBy('code')->get();
    }

    /**
     * Check if user is admin
     */
    public function getIsAdminProperty()
    {
        return auth()->user()->hasRole('admin');
    }

    // ========== HELPER METHODS ==========
    /**
     * Kalkulasi status harian berdasarkan absensi masuk dan pulang
     */
    private function calculateDailyStatus($masukCode, $pulangCode)
    {
        if ($masukCode == 'S' || $pulangCode == 'S') {
            return 'S';
        }

        if ($masukCode == 'I' || $pulangCode == 'I') {
            return 'I';
        }

        if ($masukCode == 'H' && $pulangCode == 'H') {
            return 'H';
        }

        if ($masukCode == 'H' && ($pulangCode == 'A' || $pulangCode == null)) {
            return 'A';
        }

        if ($masukCode == null && $pulangCode != null) {
            return 'A';
        }

        if ($masukCode == 'A' && $pulangCode == 'A') {
            return 'A';
        }

        return 'A';
    }

    // ========== MAIN SEARCH ==========
    /**
     * Main search function - Router
     */
    public function search()
    {
        if ($this->report_type == 'summary') {
            $this->searchSummary();
        } elseif ($this->report_type == 'detail') {
            $this->searchDetail();
        } elseif ($this->report_type == 'student') {
            $this->searchStudent();
        }
    }

    // ========== SEARCH SUMMARY ==========
    /**
     * REKAP RINGKASAN (Summary by Category)
     */
    public function searchSummary()
    {
        $this->results = collect();

        // Validasi untuk guru non-admin
        if (!auth()->user()->hasRole('admin') && $this->attendance_source === 'all') {
            $guru = auth()->user()->guru;
            $hasWaliKelas = $guru && $guru->waliKelas;

            if (!$hasWaliKelas) {
                $this->attendance_source = 'mapel';

                Notification::make()
                    ->warning()
                    ->title('Filter disesuaikan')
                    ->body('Anda bukan wali kelas, menampilkan absensi mapel saja')
                    ->send();
            }
        }

        if ($this->attendance_source === 'mapel') {
            $this->results = $this->getMapelSummary();
        } elseif ($this->attendance_source === 'homeroom') {
            if (!auth()->user()->hasRole('admin')) {
                $guru = auth()->user()->guru;
                $hasWaliKelas = $guru && $guru->waliKelas;

                if (!$hasWaliKelas) {
                    Notification::make()
                        ->danger()
                        ->title('Akses Ditolak')
                        ->body('Anda bukan wali kelas. Tidak dapat melihat absensi harian.')
                        ->send();
                    return;
                }
            }

            $this->results = $this->getHomeroomSummary();
        } else {
            $homeroomData = $this->getHomeroomSummary();
            $mapelData = $this->getMapelSummary();
            $this->results = $homeroomData->merge($mapelData);
        }

        $this->totalAbsensi = $this->results->sum('total');

        Notification::make()
            ->success()
            ->title('Rekap berhasil dimuat')
            ->body($this->totalAbsensi . ' total absensi')
            ->send();
    }

    /**
     * Get Mapel Summary dengan filter dinamis
     */
    private function getMapelSummary()
    {
        $query = DB::table('attendances')
            ->join('attendance_categories', 'attendances.category_id', '=', 'attendance_categories.id')
            ->join('siswas', 'attendances.student_id', '=', 'siswas.id')
            ->join('jadwal_pelajarans', 'attendances.schedule_id', '=', 'jadwal_pelajarans.id')
            ->when($this->kelas_id, function ($q) {
                $q->where('siswas.kelas_id', $this->kelas_id);
            })
            ->when($this->mapel_id, function ($q) {
                $q->where('jadwal_pelajarans.mapel_id', $this->mapel_id);
            })
            ->when($this->guru_id, function ($q) {
                $q->where('jadwal_pelajarans.guru_id', $this->guru_id);
            })
            ->whereBetween('attendances.date', [$this->from, $this->to]);

        if (!auth()->user()->hasRole('admin')) {
            $guruId = auth()->user()->guru->id ?? null;
            if ($guruId) {
                $query->where('jadwal_pelajarans.guru_id', $guruId);

                if (!$this->kelas_id) {
                    $kelasIds = collect();
                    $guru = auth()->user()->guru;

                    $kelasIds = $kelasIds->merge($guru->kelas->pluck('id'));

                    if ($guru->waliKelas) {
                        $kelasIds->push($guru->waliKelas->kelas_id);
                    }

                    if ($kelasIds->isNotEmpty()) {
                        $query->whereIn('siswas.kelas_id', $kelasIds->unique()->toArray());
                    }
                }
            }
        }

        return $query->select(
            DB::raw('"Mapel" as source'),
            'attendance_categories.code',
            'attendance_categories.name',
            'attendance_categories.color',
            DB::raw('count(*) as total')
        )
            ->groupBy('attendance_categories.id', 'attendance_categories.code', 'attendance_categories.name', 'attendance_categories.color')
            ->orderBy('attendance_categories.code')
            ->get();
    }

    /**
     * Get Homeroom Summary (Harian)
     */
    private function getHomeroomSummary()
    {
        $query = DB::table('homeroom_attendances as ha_masuk')
            ->leftJoin('homeroom_attendances as ha_pulang', function ($join) {
                $join->on('ha_masuk.student_id', '=', 'ha_pulang.student_id')
                    ->on('ha_masuk.date', '=', 'ha_pulang.date')
                    ->where('ha_pulang.check_type', '=', 'pulang');
            })
            ->join('siswas', 'ha_masuk.student_id', '=', 'siswas.id')
            ->join('attendance_categories as cat_masuk', 'ha_masuk.category_id', '=', 'cat_masuk.id')
            ->leftJoin('attendance_categories as cat_pulang', 'ha_pulang.category_id', '=', 'cat_pulang.id')
            ->where('ha_masuk.check_type', '=', 'masuk')
            ->when($this->kelas_id, function ($q) {
                $q->where('ha_masuk.class_id', $this->kelas_id);
            })
            ->when($this->wali_kelas_id, function ($q) {
                $waliKelas = WaliKelas::find($this->wali_kelas_id);
                if ($waliKelas) {
                    $q->where('ha_masuk.class_id', $waliKelas->kelas_id);
                }
            })
            ->whereBetween('ha_masuk.date', [$this->from, $this->to]);

        if (!auth()->user()->hasRole('admin')) {
            $guruId = auth()->user()->guru->id ?? null;
            if ($guruId) {
                $waliKelas = WaliKelas::where('guru_id', $guruId)->first();
                if ($waliKelas) {
                    $query->where('ha_masuk.class_id', $waliKelas->kelas_id);
                }
            }
        }

        $dailyAttendances = $query->select(
            'ha_masuk.date',
            'ha_masuk.student_id',
            'cat_masuk.code as masuk_code',
            'cat_pulang.code as pulang_code'
        )
            ->get();

        $summary = [
            'H' => 0,
            'S' => 0,
            'I' => 0,
            'A' => 0
        ];

        foreach ($dailyAttendances as $att) {
            $status = $this->calculateDailyStatus($att->masuk_code, $att->pulang_code);
            if (isset($summary[$status])) {
                $summary[$status]++;
            }
        }

        $categories = AttendanceCategory::all()->keyBy('code');
        $results = collect();

        foreach ($summary as $code => $total) {
            if ($total > 0 && isset($categories[$code])) {
                $cat = $categories[$code];
                $results->push((object) [
                    'source' => 'Harian',
                    'code' => $code,
                    'name' => $cat->name,
                    'color' => $cat->color,
                    'total' => $total
                ]);
            }
        }

        return $results;
    }

    // ========== SEARCH DETAIL ==========
    /**
     * REKAP DETAIL (By Student)
     */
    public function searchDetail()
    {
        $this->detailResults = collect();

        if (!auth()->user()->hasRole('admin') && $this->attendance_source === 'all') {
            $guru = auth()->user()->guru;
            $hasWaliKelas = $guru && $guru->waliKelas;

            if (!$hasWaliKelas) {
                $this->attendance_source = 'mapel';

                Notification::make()
                    ->warning()
                    ->title('Filter disesuaikan')
                    ->body('Anda bukan wali kelas, menampilkan absensi mapel saja')
                    ->send();
            }
        }

        if ($this->attendance_source === 'mapel') {
            $this->detailResults = $this->getMapelDetail();
        } elseif ($this->attendance_source === 'homeroom') {
            if (!auth()->user()->hasRole('admin')) {
                $guru = auth()->user()->guru;
                $hasWaliKelas = $guru && $guru->waliKelas;

                if (!$hasWaliKelas) {
                    Notification::make()
                        ->danger()
                        ->title('Akses Ditolak')
                        ->body('Anda bukan wali kelas. Tidak dapat melihat absensi harian.')
                        ->send();
                    return;
                }
            }

            $this->detailResults = $this->getHomeroomDetail();
        } else {
            $homeroomData = $this->getHomeroomDetail();
            $mapelData = $this->getMapelDetail();
            $this->detailResults = $homeroomData->merge($mapelData);
        }

        $this->totalSiswa = $this->detailResults->count();

        Notification::make()
            ->success()
            ->title('Rekap detail berhasil dimuat')
            ->body($this->totalSiswa . ' record')
            ->send();
    }

    /**
     * Get Mapel Detail
     */
    private function getMapelDetail()
    {
        $query = DB::table('siswas')
            ->leftJoin('attendances', 'siswas.id', '=', 'attendances.student_id')
            ->leftJoin('attendance_categories', 'attendances.category_id', '=', 'attendance_categories.id')
            ->leftJoin('jadwal_pelajarans', 'attendances.schedule_id', '=', 'jadwal_pelajarans.id')
            ->leftJoin('users', 'siswas.user_id', '=', 'users.id')
            ->leftJoin('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->when($this->kelas_id, function ($q) {
                $q->where('siswas.kelas_id', $this->kelas_id);
            })
            ->when($this->mapel_id, function ($q) {
                $q->where('jadwal_pelajarans.mapel_id', $this->mapel_id);
            })
            ->when($this->guru_id, function ($q) {
                $q->where('jadwal_pelajarans.guru_id', $this->guru_id);
            })
            ->whereBetween('attendances.date', [$this->from, $this->to]);

        if (!auth()->user()->hasRole('admin')) {
            $guruId = auth()->user()->guru->id ?? null;
            if ($guruId) {
                $query->where('jadwal_pelajarans.guru_id', $guruId);

                if (!$this->kelas_id) {
                    $kelasIds = collect();
                    $guru = auth()->user()->guru;

                    $kelasIds = $kelasIds->merge($guru->kelas->pluck('id'));

                    if ($guru->waliKelas) {
                        $kelasIds->push($guru->waliKelas->kelas_id);
                    }

                    if ($kelasIds->isNotEmpty()) {
                        $query->whereIn('siswas.kelas_id', $kelasIds->unique()->toArray());
                    }
                }
            }
        }

        return $query->select(
            'siswas.id',
            'siswas.nis',
            'users.name as nama',
            'kelas.nama as kelas',
            DB::raw('"Mapel" as tipe'),
            DB::raw('SUM(CASE WHEN attendance_categories.code = "H" THEN 1 ELSE 0 END) as hadir'),
            DB::raw('SUM(CASE WHEN attendance_categories.code = "S" THEN 1 ELSE 0 END) as sakit'),
            DB::raw('SUM(CASE WHEN attendance_categories.code = "I" THEN 1 ELSE 0 END) as izin'),
            DB::raw('SUM(CASE WHEN attendance_categories.code = "A" THEN 1 ELSE 0 END) as alpha'),
            DB::raw('COUNT(attendances.id) as total')
        )
            ->groupBy('siswas.id', 'siswas.nis', 'users.name', 'kelas.nama')
            ->orderBy('users.name')
            ->get();
    }

    /**
     * Get Homeroom Detail (Harian)
     */
    private function getHomeroomDetail()
    {
        $siswaQuery = Siswa::with('user', 'kelas')
            ->when($this->kelas_id, function ($q) {
                $q->where('kelas_id', $this->kelas_id);
            })
            ->when($this->wali_kelas_id, function ($q) {
                $waliKelas = WaliKelas::find($this->wali_kelas_id);
                if ($waliKelas) {
                    $q->where('kelas_id', $waliKelas->kelas_id);
                }
            });

        if (!auth()->user()->hasRole('admin')) {
            $guruId = auth()->user()->guru->id ?? null;
            if ($guruId) {
                $waliKelas = WaliKelas::where('guru_id', $guruId)->first();
                if ($waliKelas) {
                    $siswaQuery->where('kelas_id', $waliKelas->kelas_id);
                }
            }
        }

        $siswas = $siswaQuery->get();
        $results = collect();

        foreach ($siswas as $siswa) {
            $dailyAttendances = DB::table('homeroom_attendances as ha_masuk')
                ->leftJoin('homeroom_attendances as ha_pulang', function ($join) {
                    $join->on('ha_masuk.student_id', '=', 'ha_pulang.student_id')
                        ->on('ha_masuk.date', '=', 'ha_pulang.date')
                        ->where('ha_pulang.check_type', '=', 'pulang');
                })
                ->join('attendance_categories as cat_masuk', 'ha_masuk.category_id', '=', 'cat_masuk.id')
                ->leftJoin('attendance_categories as cat_pulang', 'ha_pulang.category_id', '=', 'cat_pulang.id')
                ->where('ha_masuk.student_id', $siswa->id)
                ->where('ha_masuk.check_type', '=', 'masuk')
                ->whereBetween('ha_masuk.date', [$this->from, $this->to])
                ->select(
                    'cat_masuk.code as masuk_code',
                    'cat_pulang.code as pulang_code'
                )
                ->get();

            $summary = [
                'H' => 0,
                'S' => 0,
                'I' => 0,
                'A' => 0
            ];

            foreach ($dailyAttendances as $att) {
                $status = $this->calculateDailyStatus($att->masuk_code, $att->pulang_code);
                if (isset($summary[$status])) {
                    $summary[$status]++;
                }
            }

            $total = array_sum($summary);

            if ($total > 0) {
                $results->push((object) [
                    'id' => $siswa->id,
                    'nis' => $siswa->nis,
                    'nama' => $siswa->user->name ?? $siswa->nama,
                    'kelas' => $siswa->kelas->nama ?? '-',
                    'tipe' => 'Harian',
                    'hadir' => $summary['H'],
                    'sakit' => $summary['S'],
                    'izin' => $summary['I'],
                    'alpha' => $summary['A'],
                    'total' => $total
                ]);
            }
        }

        return $results;
    }

    // ========== SEARCH STUDENT ==========
    /**
     * REKAP PER SISWA (Individual Student History)
     */
    public function searchStudent()
    {
        if (!$this->siswa_id) {
            Notification::make()
                ->warning()
                ->title('Pilih siswa terlebih dahulu')
                ->send();
            return;
        }

        $this->studentResults = collect();

        if (!auth()->user()->hasRole('admin') && $this->attendance_source === 'all') {
            $guru = auth()->user()->guru;
            $hasWaliKelas = $guru && $guru->waliKelas;

            if (!$hasWaliKelas) {
                $this->attendance_source = 'mapel';

                Notification::make()
                    ->warning()
                    ->title('Filter disesuaikan')
                    ->body('Anda bukan wali kelas, menampilkan absensi mapel saja')
                    ->send();
            }
        }

        if ($this->attendance_source === 'mapel') {
            $this->studentResults = $this->getStudentMapelHistory($this->siswa_id);
        } elseif ($this->attendance_source === 'homeroom') {
            if (!auth()->user()->hasRole('admin')) {
                $guru = auth()->user()->guru;
                $hasWaliKelas = $guru && $guru->waliKelas;

                if (!$hasWaliKelas) {
                    Notification::make()
                        ->danger()
                        ->title('Akses Ditolak')
                        ->body('Anda bukan wali kelas. Tidak dapat melihat absensi harian.')
                        ->send();
                    return;
                }
            }

            $this->studentResults = $this->getStudentHomeroomHistory($this->siswa_id);
        } else {
            $homeroomData = $this->getStudentHomeroomHistory($this->siswa_id);
            $mapelData = $this->getStudentMapelHistory($this->siswa_id);
            $this->studentResults = $homeroomData->merge($mapelData);
        }

        $this->studentResults = $this->studentResults->sortByDesc('date')->values();

        Notification::make()
            ->success()
            ->title('Riwayat absensi berhasil dimuat')
            ->body($this->studentResults->count() . ' record')
            ->send();
    }

    /**
     * Get Student Mapel History
     */
    private function getStudentMapelHistory($siswaId)
    {
        $query = DB::table('attendances')
            ->join('attendance_categories', 'attendances.category_id', '=', 'attendance_categories.id')
            ->join('jadwal_pelajarans', 'attendances.schedule_id', '=', 'jadwal_pelajarans.id')
            ->join('mapels', 'jadwal_pelajarans.mapel_id', '=', 'mapels.id')
            ->where('attendances.student_id', $siswaId)
            ->when($this->mapel_id, function ($q) {
                $q->where('jadwal_pelajarans.mapel_id', $this->mapel_id);
            })
            ->when($this->guru_id, function ($q) {
                $q->where('jadwal_pelajarans.guru_id', $this->guru_id);
            })
            ->whereBetween('attendances.date', [$this->from, $this->to]);

        if (!auth()->user()->hasRole('admin')) {
            $guruId = auth()->user()->guru->id ?? null;
            if ($guruId) {
                $query->where('jadwal_pelajarans.guru_id', $guruId);
            }
        }

        return $query->select(
            'attendances.date',
            DB::raw('"Mapel" as tipe'),
            'mapels.nama as keterangan',
            'jadwal_pelajarans.jam_mulai',
            'jadwal_pelajarans.jam_selesai',
            'attendance_categories.code',
            'attendance_categories.name',
            'attendance_categories.color',
            'attendances.note'
        )
            ->orderBy('attendances.date', 'desc')
            ->orderBy('jadwal_pelajarans.jam_mulai')
            ->get();
    }

    /**
     * Get Student Homeroom History (Harian)
     */
    private function getStudentHomeroomHistory($siswaId)
    {
        $dates = DB::table('homeroom_attendances')
            ->where('student_id', $siswaId)
            ->whereBetween('date', [$this->from, $this->to])
            ->distinct()
            ->pluck('date');

        $results = collect();
        $categories = AttendanceCategory::all()->keyBy('code');

        foreach ($dates as $date) {
            $masuk = DB::table('homeroom_attendances')
                ->join('attendance_categories', 'homeroom_attendances.category_id', '=', 'attendance_categories.id')
                ->where('student_id', $siswaId)
                ->where('date', $date)
                ->where('check_type', 'masuk')
                ->select('attendance_categories.code', 'homeroom_attendances.time', 'homeroom_attendances.note')
                ->first();

            $pulang = DB::table('homeroom_attendances')
                ->join('attendance_categories', 'homeroom_attendances.category_id', '=', 'attendance_categories.id')
                ->where('student_id', $siswaId)
                ->where('date', $date)
                ->where('check_type', 'pulang')
                ->select('attendance_categories.code', 'homeroom_attendances.time', 'homeroom_attendances.note')
                ->first();

            $status = $this->calculateDailyStatus(
                $masuk->code ?? null,
                $pulang->code ?? null
            );

            $cat = $categories[$status] ?? null;

            $keterangan = sprintf(
                'Masuk: %s | Pulang: %s',
                $masuk ? $masuk->code : '-',
                $pulang ? $pulang->code : '-'
            );

            $results->push((object) [
                'date' => $date,
                'tipe' => 'Harian',
                'keterangan' => $keterangan,
                'jam_mulai' => $masuk->time ?? '-',
                'jam_selesai' => $pulang->time ?? '-',
                'code' => $status,
                'name' => $cat->name ?? '-',
                'color' => $cat->color ?? '',
                'note' => $masuk->note ?? $pulang->note ?? '-'
            ]);
        }

        return $results;
    }

    // ========== EXPORT ==========
    /**
     * Export Excel
     */
    public function exportExcel()
    {
        return redirect()->route('admin.rekap-attendance.export', [
            'from' => $this->from,
            'to' => $this->to,
            'kelas' => $this->kelas_id,
            'mapel' => $this->mapel_id,
            'guru' => $this->guru_id,
            'wali_kelas' => $this->wali_kelas_id,
            'type' => $this->report_type,
            'source' => $this->attendance_source
        ]);
    }
}
