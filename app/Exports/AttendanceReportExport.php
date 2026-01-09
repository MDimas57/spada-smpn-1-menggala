<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\AttendanceCategory;

class AttendanceReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    WithStyles
{
    protected $from;
    protected $to;
    protected $kelas;
    protected $mapel;
    protected $type;
    protected $source;
    protected $rowNumber = 0;

    public function __construct($from, $to, $kelas = null, $mapel = null, $type = 'summary', $source = 'all')
    {
        $this->from = $from;
        $this->to = $to;
        $this->kelas = $kelas;
        $this->mapel = $mapel;
        $this->type = $type;
        $this->source = $source;
    }

    /**
     * Get collection based on report type
     */
    public function collection()
    {
        if ($this->type == 'detail') {
            return $this->getDetailCollection();
        } elseif ($this->type == 'student') {
            return $this->getStudentCollection();
        } else {
            return $this->getSummaryCollection();
        }
    }

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
        return 'A';
    }

    /**
     * Summary Collection (By Category)
     */
    protected function getSummaryCollection()
    {
        $results = collect([]);

        // Absensi Mapel
        if ($this->source == 'mapel' || $this->source == 'all') {
            $mapelData = DB::table('attendances')
                ->join('attendance_categories', 'attendances.category_id', '=', 'attendance_categories.id')
                ->join('siswas', 'attendances.student_id', '=', 'siswas.id')
                ->join('jadwal_pelajarans', 'attendances.schedule_id', '=', 'jadwal_pelajarans.id')
                ->when($this->kelas, function($q) {
                    $q->where('siswas.kelas_id', $this->kelas);
                })
                ->when($this->mapel, function($q) {
                    $q->where('jadwal_pelajarans.mapel_id', $this->mapel);
                })
                ->whereBetween('attendances.date', [$this->from, $this->to])
                ->select(
                    DB::raw('"Mapel" as source'),
                    'attendance_categories.code',
                    'attendance_categories.name',
                    DB::raw('count(*) as total')
                )
                ->groupBy('attendance_categories.id', 'attendance_categories.code', 'attendance_categories.name')
                ->orderBy('attendance_categories.code')
                ->get();

            $results = $results->merge($mapelData);
        }

        // Absensi Wali Kelas (Harian)
        if ($this->source == 'homeroom' || $this->source == 'all') {
            $dailyAttendances = DB::table('homeroom_attendances as ha_masuk')
                ->leftJoin('homeroom_attendances as ha_pulang', function($join) {
                    $join->on('ha_masuk.student_id', '=', 'ha_pulang.student_id')
                         ->on('ha_masuk.date', '=', 'ha_pulang.date')
                         ->where('ha_pulang.check_type', '=', 'pulang');
                })
                ->join('siswas', 'ha_masuk.student_id', '=', 'siswas.id')
                ->join('attendance_categories as cat_masuk', 'ha_masuk.category_id', '=', 'cat_masuk.id')
                ->leftJoin('attendance_categories as cat_pulang', 'ha_pulang.category_id', '=', 'cat_pulang.id')
                ->where('ha_masuk.check_type', '=', 'masuk')
                ->when($this->kelas, function($q) {
                    $q->where('ha_masuk.class_id', $this->kelas);
                })
                ->whereBetween('ha_masuk.date', [$this->from, $this->to])
                ->select(
                    'cat_masuk.code as masuk_code',
                    'cat_pulang.code as pulang_code'
                )
                ->get();

            $summary = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];

            foreach ($dailyAttendances as $att) {
                $status = $this->calculateDailyStatus($att->masuk_code, $att->pulang_code);
                if (isset($summary[$status])) {
                    $summary[$status]++;
                }
            }

            $categories = AttendanceCategory::all()->keyBy('code');
            foreach ($summary as $code => $total) {
                if ($total > 0 && isset($categories[$code])) {
                    $cat = $categories[$code];
                    $results->push((object)[
                        'source' => 'Harian',
                        'code' => $code,
                        'name' => $cat->name,
                        'total' => $total
                    ]);
                }
            }
        }

        return $results;
    }

    /**
     * Detail Collection (By Student)
     */
    protected function getDetailCollection()
    {
        $results = collect([]);

        // Absensi Mapel
        if ($this->source == 'mapel' || $this->source == 'all') {
            $mapelData = DB::table('siswas')
                ->leftJoin('attendances', 'siswas.id', '=', 'attendances.student_id')
                ->leftJoin('attendance_categories', 'attendances.category_id', '=', 'attendance_categories.id')
                ->leftJoin('jadwal_pelajarans', 'attendances.schedule_id', '=', 'jadwal_pelajarans.id')
                ->leftJoin('users', 'siswas.user_id', '=', 'users.id')
                ->leftJoin('kelas', 'siswas.kelas_id', '=', 'kelas.id')
                ->when($this->kelas, function($q) {
                    $q->where('siswas.kelas_id', $this->kelas);
                })
                ->when($this->mapel, function($q) {
                    $q->where('jadwal_pelajarans.mapel_id', $this->mapel);
                })
                ->whereBetween('attendances.date', [$this->from, $this->to])
                ->select(
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

            $results = $results->merge($mapelData);
        }

        // Absensi Wali Kelas (Harian)
        if ($this->source == 'homeroom' || $this->source == 'all') {
            $siswaQuery = Siswa::with('user', 'kelas')
                ->when($this->kelas, function($q) {
                    $q->where('kelas_id', $this->kelas);
                });

            $siswas = $siswaQuery->get();

            foreach ($siswas as $siswa) {
                $dailyAttendances = DB::table('homeroom_attendances as ha_masuk')
                    ->leftJoin('homeroom_attendances as ha_pulang', function($join) {
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

                $summary = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];

                foreach ($dailyAttendances as $att) {
                    $status = $this->calculateDailyStatus($att->masuk_code, $att->pulang_code);
                    if (isset($summary[$status])) {
                        $summary[$status]++;
                    }
                }

                $total = array_sum($summary);

                if ($total > 0) {
                    $results->push((object)[
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
        }

        return $results;
    }

    /**
     * Student Collection (Individual History)
     */
    protected function getStudentCollection()
    {
        $results = collect([]);

        // Absensi Mapel
        if ($this->source == 'mapel' || $this->source == 'all') {
            $mapelData = DB::table('attendances')
                ->join('siswas', 'attendances.student_id', '=', 'siswas.id')
                ->join('users', 'siswas.user_id', '=', 'users.id')
                ->join('attendance_categories', 'attendances.category_id', '=', 'attendance_categories.id')
                ->join('jadwal_pelajarans', 'attendances.schedule_id', '=', 'jadwal_pelajarans.id')
                ->join('mapels', 'jadwal_pelajarans.mapel_id', '=', 'mapels.id')
                ->when($this->kelas, function($q) {
                    $q->where('siswas.kelas_id', $this->kelas);
                })
                ->when($this->mapel, function($q) {
                    $q->where('jadwal_pelajarans.mapel_id', $this->mapel);
                })
                ->whereBetween('attendances.date', [$this->from, $this->to])
                ->select(
                    'users.name as nama_siswa',
                    'siswas.nis',
                    'attendances.date',
                    DB::raw('"Mapel" as tipe'),
                    'mapels.nama as keterangan',
                    'jadwal_pelajarans.jam_mulai',
                    'jadwal_pelajarans.jam_selesai',
                    'attendance_categories.code',
                    'attendance_categories.name as status',
                    'attendances.note'
                )
                ->orderBy('attendances.date', 'desc')
                ->orderBy('users.name')
                ->get();

            $results = $results->merge($mapelData);
        }

        // Absensi Wali Kelas (Harian)
        if ($this->source == 'homeroom' || $this->source == 'all') {
            $dates = DB::table('homeroom_attendances')
                ->when($this->kelas, function($q) {
                    $q->where('class_id', $this->kelas);
                })
                ->whereBetween('date', [$this->from, $this->to])
                ->distinct()
                ->pluck('date');

            $siswaQuery = Siswa::with('user')
                ->when($this->kelas, function($q) {
                    $q->where('kelas_id', $this->kelas);
                });

            $siswas = $siswaQuery->get();
            $categories = AttendanceCategory::all()->keyBy('code');

            foreach ($siswas as $siswa) {
                foreach ($dates as $date) {
                    $masuk = DB::table('homeroom_attendances')
                        ->join('attendance_categories', 'homeroom_attendances.category_id', '=', 'attendance_categories.id')
                        ->where('student_id', $siswa->id)
                        ->where('date', $date)
                        ->where('check_type', 'masuk')
                        ->select('attendance_categories.code', 'homeroom_attendances.time', 'homeroom_attendances.note')
                        ->first();

                    $pulang = DB::table('homeroom_attendances')
                        ->join('attendance_categories', 'homeroom_attendances.category_id', '=', 'attendance_categories.id')
                        ->where('student_id', $siswa->id)
                        ->where('date', $date)
                        ->where('check_type', 'pulang')
                        ->select('attendance_categories.code', 'homeroom_attendances.time', 'homeroom_attendances.note')
                        ->first();

                    if ($masuk || $pulang) {
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

                        $results->push((object)[
                            'nama_siswa' => $siswa->user->name ?? $siswa->nama,
                            'nis' => $siswa->nis,
                            'date' => $date,
                            'tipe' => 'Harian',
                            'keterangan' => $keterangan,
                            'jam_mulai' => $masuk->time ?? '-',
                            'jam_selesai' => $pulang->time ?? '-',
                            'code' => $status,
                            'status' => $cat->name ?? '-',
                            'note' => $masuk->note ?? $pulang->note ?? '-'
                        ]);
                    }
                }
            }
        }

        return $results;
    }

    /**
     * Define headings based on report type
     */
    public function headings(): array
    {
        if ($this->type == 'detail') {
            return ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Tipe Absensi', 'Hadir', 'Sakit', 'Izin', 'Alpha', 'Total'];
        } elseif ($this->type == 'student') {
            return ['No', 'Nama Siswa', 'NIS', 'Tanggal', 'Tipe', 'Keterangan', 'Jam Mulai', 'Jam Selesai', 'Kode', 'Status', 'Catatan'];
        } else {
            return ['No', 'Tipe Absensi', 'Kode', 'Kategori', 'Total'];
        }
    }

    /**
     * Map data for export
     */
    public function map($row): array
    {
        $this->rowNumber++;

        if ($this->type == 'detail') {
            return [
                $this->rowNumber,
                $row->nis ?? '-',
                $row->nama ?? '-',
                $row->kelas ?? '-',
                $row->tipe ?? '-',
                $row->hadir ?? 0,
                $row->sakit ?? 0,
                $row->izin ?? 0,
                $row->alpha ?? 0,
                $row->total ?? 0,
            ];
        } elseif ($this->type == 'student') {
            return [
                $this->rowNumber,
                $row->nama_siswa ?? '-',
                $row->nis ?? '-',
                $row->date ?? '-',
                $row->tipe ?? '-',
                $row->keterangan ?? '-',
                $row->jam_mulai ?? '-',
                $row->jam_selesai ?? '-',
                $row->code ?? '-',
                $row->status ?? '-',
                $row->note ?? '-',
            ];
        } else {
            return [
                $this->rowNumber,
                $row->source ?? '-',
                $row->code ?? '-',
                $row->name ?? '-',
                $row->total ?? 0,
            ];
        }
    }

    /**
     * Sheet title
     */
    public function title(): string
    {
        if ($this->type == 'detail') {
            return 'Rekap Detail';
        } elseif ($this->type == 'student') {
            return 'Riwayat Individual';
        } else {
            return 'Rekap Ringkasan';
        }
    }

    /**
     * Apply styles to the sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ]
            ],
        ];
    }
}
