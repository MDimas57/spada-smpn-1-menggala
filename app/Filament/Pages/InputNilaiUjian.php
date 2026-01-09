<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\NilaiUjian;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class InputNilaiUjian extends Page
{
    protected static ?string $navigationGroup = 'Pengelolaan Nilai Siswa';
    protected static ?string $navigationLabel = 'Input Nilai Massal';
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.input-nilai-ujian';

    // ========== PROPERTIES ==========
    // Filter
    public $mapel_id = '';
    public $jenis_ujian = '';
    public $tahun_ajaran_id = '';
    public $kelas_id = '';

    // Nilai List
    public $nilai_list = [];

    // Stats
    public $totalSiswa = 0;
    public $selectedCount = 0;

    // ========== LIFECYCLE ==========
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(['admin', 'guru']);
    }

    public function mount()
    {
        // Set default tahun ajaran aktif jika ada
        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        if ($tahunAjaranAktif) {
            $this->tahun_ajaran_id = $tahunAjaranAktif->id;
        }
    }

    // ========== FILTER UPDATES ==========
    public function updatedKelasId()
    {
        $this->loadSiswaList();
    }

    public function updatedMapelId()
    {
        $this->loadSiswaList();
    }

    public function updatedJenisUjian()
    {
        $this->loadSiswaList();
    }

    public function updatedTahunAjaranId()
    {
        $this->loadSiswaList();
    }

    private function loadSiswaList()
    {
        if (!$this->kelas_id) {
            $this->nilai_list = [];
            $this->totalSiswa = 0;
            $this->selectedCount = 0;
            return;
        }

        $siswaList = Siswa::with(['user', 'kelas'])
            ->where('kelas_id', $this->kelas_id)
            ->join('users', 'siswas.user_id', '=', 'users.id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->select(
                'siswas.id as siswa_id',
                'users.name as nama_siswa',
                'siswas.nis as nis',
                'kelas.nama as kelas_nama'
            )
            ->orderBy('users.name')
            ->get();

        $this->nilai_list = $siswaList->map(function ($siswa) {
            // Cek apakah sudah ada nilai untuk siswa ini
            $existingNilai = null;
            if ($this->mapel_id && $this->jenis_ujian && $this->tahun_ajaran_id) {
                $existingNilai = NilaiUjian::where('siswa_id', $siswa->siswa_id)
                    ->where('mapel_id', $this->mapel_id)
                    ->where('jenis_ujian', $this->jenis_ujian)
                    ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
                    ->first();
            }

            return [
                'siswa_id' => $siswa->siswa_id,
                'nama_siswa' => $siswa->nama_siswa,
                'nis' => $siswa->nis,
                'kelas_nama' => $siswa->kelas_nama,
                'nilai' => $existingNilai ? $existingNilai->nilai : null,
                'selected' => $existingNilai ? true : false, // Auto-select jika sudah ada nilai
            ];
        })->toArray();

        $this->totalSiswa = count($this->nilai_list);
        $this->updateSelectedCount();
    }

    // ========== FILTER PROPERTIES (DYNAMIC) ==========
    public function getKelasList()
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

    public function getMapelList()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return Mapel::orderBy('nama')->get();
        }

        if ($user->hasRole('guru') && $user->guru) {
            return $user->guru->mapels()->orderBy('nama')->get();
        }

        return collect([]);
    }

    public function getTahunAjaranList()
    {
        return TahunAjaran::orderBy('tahun', 'desc')->get();
    }

    // ========== SAVE NILAI ==========
    public function simpanNilai()
    {
        // Validasi filter wajib
        if (!$this->mapel_id || !$this->jenis_ujian || !$this->tahun_ajaran_id || !$this->kelas_id) {
            Notification::make()
                ->danger()
                ->title('Filter Tidak Lengkap')
                ->body('Pastikan Mata Pelajaran, Jenis Ujian, Tahun Ajaran, dan Kelas sudah dipilih.')
                ->send();
            return;
        }

        if (empty($this->nilai_list)) {
            Notification::make()
                ->warning()
                ->title('Tidak Ada Data')
                ->body('Tidak ada siswa yang akan disimpan nilainya.')
                ->send();
            return;
        }

        $saved = 0;
        $updated = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($this->nilai_list as $item) {
                // Skip jika tidak dipilih
                if (!$item['selected']) {
                    $skipped++;
                    continue;
                }

                // Skip jika nilai kosong
                if ($item['nilai'] === null || $item['nilai'] === '') {
                    $skipped++;
                    continue;
                }

                // Validasi nilai 0-100
                if ($item['nilai'] < 0 || $item['nilai'] > 100) {
                    $skipped++;
                    continue;
                }

                // Cek apakah sudah ada
                $existing = NilaiUjian::where('siswa_id', $item['siswa_id'])
                    ->where('mapel_id', $this->mapel_id)
                    ->where('jenis_ujian', $this->jenis_ujian)
                    ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
                    ->first();

                if ($existing) {
                    $existing->update(['nilai' => $item['nilai']]);
                    $updated++;
                } else {
                    NilaiUjian::create([
                        'siswa_id' => $item['siswa_id'],
                        'mapel_id' => $this->mapel_id,
                        'jenis_ujian' => $this->jenis_ujian,
                        'tahun_ajaran_id' => $this->tahun_ajaran_id,
                        'nilai' => $item['nilai'],
                    ]);
                    $saved++;
                }
            }

            DB::commit();

            $message = [];
            if ($saved > 0) $message[] = "{$saved} nilai baru disimpan";
            if ($updated > 0) $message[] = "{$updated} nilai diupdate";
            if ($skipped > 0) $message[] = "{$skipped} dilewati";

            Notification::make()
                ->success()
                ->title('Nilai Berhasil Disimpan')
                ->body(implode(', ', $message))
                ->send();

            // Reload data untuk menampilkan nilai yang baru disimpan
            $this->loadSiswaList();

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->danger()
                ->title('Gagal Menyimpan')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->send();
        }
    }

    // ========== HELPER ==========
    public function selectAll()
    {
        $this->nilai_list = array_map(function ($item) {
            $item['selected'] = true;
            return $item;
        }, $this->nilai_list);
        $this->updateSelectedCount();
    }

    public function deselectAll()
    {
        $this->nilai_list = array_map(function ($item) {
            $item['selected'] = false;
            return $item;
        }, $this->nilai_list);
        $this->updateSelectedCount();
    }

    public function updatedNilaiList()
    {
        $this->updateSelectedCount();
    }

    private function updateSelectedCount()
    {
        $this->selectedCount = collect($this->nilai_list)
            ->filter(fn($item) => $item['selected'])
            ->count();
    }
}
