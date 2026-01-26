<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\JawabanKuis;
use App\Models\Kuis;
use App\Models\Modul;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        if (!$user->siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        $kelasId = $user->siswa->kelas_id;

        // Get timeline tugas dan kuis
        $tugasTimeline = $this->getTimelineData($kelasId);

        return view('siswa.dashboard', compact('user', 'tugasTimeline'));
    }

    /**
     * Tampilkan daftar Course (Mata Pelajaran) untuk kelas siswa
     * [DIPERBARUI] Menambahkan logic untuk mengambil data starred courses
     */
    public function myCourses()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        if (!$user->siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        $kelasId = $user->siswa->kelas_id;

        // Pastikan kelas_id tidak null
        if (!$kelasId) {
            return view('siswa.my-courses', [
                'courses' => collect([]),
                'user' => $user,
                'starredCourseIds' => [] // Kirim array kosong agar view tidak error
            ]);
        }

        // Ambil semua Course untuk kelas siswa
        $courses = Course::with(['mapel', 'guru.user'])
            ->where('kelas_id', $kelasId)
            ->where('status', 'published')
            ->withCount('moduls') // Hitung jumlah modul per course
            ->latest('created_at')
            ->get();

        // [TAMBAHAN BARU] Ambil ID course yang di-star oleh user
        // pluck('course_id') mengambil kolom ID saja dan menjadikannya array sederhana [1, 5, 8]
        // Pastikan relasi 'starredCourses' sudah ada di model User
        $starredCourseIds = $user->starredCourses()->pluck('course_id')->toArray();

        // Tambahkan 'starredCourseIds' ke compact
        return view('siswa.my-courses', compact('courses', 'user', 'starredCourseIds'));
    }

    /**
     * [METHOD BARU] Untuk Handle Toggle Star (AJAX)
     * Dipanggil saat user mengklik ikon bintang di halaman My Courses
     */
    public function toggleStar(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // Toggle: Jika relasi sudah ada maka dihapus (unstar), jika belum ada maka dibuat (star)
        // Fungsi toggle() otomatis menangani logic ini di tabel pivot
    // Tell analyzer that Auth::user() returns App\Models\User (for IDEs that don't infer it)
    /** @var \App\Models\User $authUser */
    $authUser = Auth::user();
    $authUser->starredCourses()->toggle($course->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Star status updated'
        ]);
    }

    /**
     * Tampilkan daftar Modul dari Course tertentu
     */
    public function showCourse(Course $course)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        if (!$user->siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        // Validasi: Course harus untuk kelas siswa
        if ($course->kelas_id !== $user->siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        // Load modul-modul yang published dari course ini dengan relasi
        $moduls = Modul::with(['mapel', 'guru.user', 'materis', 'tugas', 'kuis'])
            ->where('course_id', $course->id)
            ->where('status', 'published')
            ->latest('publish_at')
            ->get();

        return view('siswa.course-detail', compact('course', 'moduls', 'user'));
    }

    /**
     * Mengambil timeline data tugas dan kuis untuk siswa
     */
    private function getTimelineData($kelasId)
    {
        $siswa = Auth::user()->siswa;
        /** @var \App\Models\Siswa $siswa */

        // Get semua tugas dari modul di kelas ini
        $tugas = Tugas::with(['modul.mapel', 'modul.guru.user'])
            ->whereHas('modul', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->get();

        // Get semua kuis dari modul di kelas ini
        $kuis = Kuis::with(['modul.mapel', 'modul.guru.user'])
            ->whereHas('modul', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->get();

        // Merge dan sort berdasarkan deadline/created_at
        $timeline = collect();

        foreach ($tugas as $item) {
            $item->mapel = $item->modul->mapel;
            $item->guru = $item->modul->guru;

            // Set due_date dengan benar
            if ($item->deadline) {
                // Deadline sudah dari database dalam format datetime
                $item->due_date = $item->deadline;
            } else {
                $item->due_date = $item->created_at;
            }

            $item->item_type = 'tugas';
            // Cek apakah siswa sudah mengumpulkan tugas ini
            $item->is_done = PengumpulanTugas::where('tugas_id', $item->id)
                ->where('siswa_id', $siswa->id)
                ->exists();
            $timeline->push($item);
        }

        foreach ($kuis as $item) {
            $item->mapel = $item->modul->mapel;
            $item->guru = $item->modul->guru;

            // Set due_date dengan benar
            if ($item->deadline) {
                // Deadline sudah dari database dalam format datetime
                $item->due_date = $item->deadline;
            } else {
                $item->due_date = $item->created_at;
            }

            $item->item_type = 'kuis';
            // Cek apakah siswa sudah mengerjakan kuis ini
            $item->is_done = JawabanKuis::where('kuis_id', $item->id)
                ->where('siswa_id', $siswa->id)
                ->exists();
            $timeline->push($item);
        }

        // Sort by due_date (descending - yang terdekat dulu)
        $timeline = $timeline->sortBy(function ($item) {
            return $item->due_date instanceof \Carbon\Carbon
                ? $item->due_date->timestamp
                : \Carbon\Carbon::parse($item->due_date)->timestamp;
        })->reverse();

        return $timeline;
    }

    public function show(Modul $modul)
    {
        $siswa = Auth::user()->siswa;
        /** @var \App\Models\Siswa $siswa */
        if ($modul->kelas_id !== $siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke modul ini.');
        }

        $modul->load([
            'materis',
            'tugas.pengumpulan' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            },
            'kuis.jawabanSiswa' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id)->with('soal');
            },
            'guru.user'
        ]);

        return view('siswa.modul.show', compact('modul'));
    }

    public function uploadTugas(Request $request, Tugas $tugas)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,jpg,png|max:10240',
            'catatan' => 'nullable|string',
        ]);

    $siswa = Auth::user()->siswa;
    /** @var \App\Models\Siswa $siswa */

        if ($tugas->deadline && now()->greaterThan($tugas->deadline)) {
            return back()->with('error', 'Maaf, waktu pengumpulan tugas sudah habis.');
        }

        $path = $request->file('file')->store('tugas-siswa', 'public');

        PengumpulanTugas::updateOrCreate(
            [
                'tugas_id' => $tugas->id,
                'siswa_id' => $siswa->id,
            ],
            [
                'file_path' => $path,
                'catatan_siswa' => $request->catatan,
                'tanggal_dikumpulkan' => now(),
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function kerjakanKuis(Kuis $kuis)
    {
        $siswa = Auth::user()->siswa;
        /** @var \App\Models\Siswa $siswa */

        if ($kuis->modul->kelas_id !== $siswa->kelas_id) {
            abort(403, 'Akses ditolak.');
        }

        $sudahMengerjakan = JawabanKuis::where('kuis_id', $kuis->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahMengerjakan) {
            return redirect()->route('siswa.modul.show', $kuis->modul_id)
                ->with('error', 'Anda sudah mengerjakan kuis ini sebelumnya.');
        }

        return view('siswa.kuis.kerjakan', compact('kuis', 'siswa'));
    }

    public function submitKuis(Request $request, Kuis $kuis)
    {
        $siswa = Auth::user()->siswa;
        /** @var \App\Models\Siswa $siswa */

        $request->validate([
            'jawaban' => 'array',
        ]);

        $jawabanInput = $request->input('jawaban', []);
        $totalSkor = 0;
        $totalSoalPG = 0;
        $hasEssay = false;

        foreach ($kuis->soals as $soal) {
            $jawabanSiswa = $jawabanInput[$soal->id] ?? null;
            $skor = 0;

            if ($soal->tipe === 'pilihan_ganda') {
                $totalSoalPG++;
                if ($jawabanSiswa && $jawabanSiswa == $soal->kunci_jawaban) {
                    $skor = 1;
                }
            } elseif ($soal->tipe === 'essay') {
                $hasEssay = true;
                // Essay disimpan tanpa skor otomatis, guru akan menilai
                $skor = null;
            }

            JawabanKuis::create([
                'kuis_id' => $kuis->id,
                'siswa_id' => $siswa->id,
                'soal_id' => $soal->id,
                'jawaban_siswa' => $jawabanSiswa ?? '-',
                'skor' => $skor,
            ]);

            if ($skor !== null) {
                $totalSkor += $skor;
            }
        }

        // Pesan yang ditampilkan
        $message = 'Kuis berhasil dikumpulkan!';

        if ($totalSoalPG > 0) {
            $nilaiAkhir = round(($totalSkor / $totalSoalPG) * 100);
            $message .= ' Nilai Pilihan Ganda Anda: ' . $nilaiAkhir;
        }

        if ($hasEssay) {
            $message .= ' Essay akan dinilai oleh guru.';
        }

        return redirect()->route('siswa.modul.show', $kuis->modul_id)
            ->with('success', $message);
    }

    public function showParticipants(Course $course)
    {
        $user = Auth::user();

        if (!$user->siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        // Validasi: Course harus untuk kelas siswa
        if ($course->kelas_id !== $user->siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        // Query dasar
        $query = \App\Models\Siswa::with(['user', 'kelas'])
            ->where('kelas_id', $course->kelas_id)
            ->whereHas('user');

        // Filter berdasarkan first name
        if (request()->has('first_name') && request('first_name') !== 'All') {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'LIKE', request('first_name') . '%');
            });
        }

        // Filter berdasarkan last name (jika ada kolom last_name terpisah)
        // Jika nama lengkap dalam satu kolom, kita filter berdasarkan kata terakhir
        if (request()->has('last_name') && request('last_name') !== 'All') {
            $query->whereHas('user', function ($q) {
                $letter = request('last_name');
                // Filter nama yang kata terakhirnya dimulai dengan huruf tertentu
                $q->where('name', 'LIKE', '% ' . $letter . '%')
                    ->orWhere('name', 'LIKE', $letter . '%');
            });
        }

        // Filter search dari form (opsional - untuk fitur search nanti)
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        // Sorting
        $sortBy = request('sort_by', 'name');
        $sortOrder = request('sort_order', 'asc');

        if ($sortBy === 'name') {
            $query->join('users', 'siswas.user_id', '=', 'users.id')
                ->orderBy('users.name', $sortOrder)
                ->select('siswas.*');
        }

        // Ambil data siswa
        $participants = $query->paginate(15)->appends(request()->query());

        // Set last_access untuk setiap participant
        foreach ($participants as $participant) {
            $participant->last_access = null;
        }

        return view('siswa.course-participants', compact('course', 'participants', 'user'));
    }

    public function showGrades(Course $course)
    {
        $user = Auth::user();

        if (!$user->siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        // Validasi: Course harus untuk kelas siswa
        if ($course->kelas_id !== $user->siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        $siswa = $user->siswa;
        $grades = [];

        // Statistik
        $totalTugas = 0;
        $tugasDikumpulkan = 0;
        $totalKuis = 0;
        $kuisDikerjakan = 0;
        $totalNilai = 0;
        $jumlahNilai = 0;

        // Ambil semua modul dari course ini
        $moduls = Modul::with([
            'tugas.pengumpulan' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            },
            'kuis.jawabanSiswa' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            }
        ])
            ->where('course_id', $course->id)
            ->where('status', 'published')
            ->orderBy('publish_at', 'asc')
            ->get();

        foreach ($moduls as $modul) {
            $modulGrades = [];

            // Proses Tugas
            foreach ($modul->tugas as $tugas) {
                $totalTugas++;
                $pengumpulan = $tugas->pengumpulan->first();

                if ($pengumpulan) {
                    $tugasDikumpulkan++;

                    if ($pengumpulan->nilai !== null) {
                        $modulGrades[] = [
                            'judul' => $tugas->judul,
                            'tipe' => 'tugas',
                            'nilai' => $pengumpulan->nilai,
                            'feedback' => $pengumpulan->feedback_guru,
                        ];

                        $totalNilai += $pengumpulan->nilai;
                        $jumlahNilai++;
                    }
                }
            }

            // Proses Kuis
            foreach ($modul->kuis as $kuis) {
                $totalKuis++;
                $jawabanSiswa = $kuis->jawabanSiswa;

                if ($jawabanSiswa->isNotEmpty()) {
                    $kuisDikerjakan++;

                    // Hitung nilai kuis
                    $totalSoal = $kuis->soals->count();
                    $benar = 0;

                    foreach ($jawabanSiswa as $jawaban) {
                        if ($jawaban->skor !== null && $jawaban->skor > 0) {
                            $benar++;
                        }
                    }

                    if ($totalSoal > 0) {
                        $nilai = ($benar / $totalSoal) * 100;

                        $modulGrades[] = [
                            'judul' => $kuis->pertanyaan,
                            'tipe' => 'kuis',
                            'nilai' => $nilai,
                            'feedback' => null,
                        ];

                        $totalNilai += $nilai;
                        $jumlahNilai++;
                    }
                }
            }

            // Tambahkan ke grades jika ada nilai
            if (!empty($modulGrades)) {
                $grades[$modul->judul] = $modulGrades;
            }
        }

        // Hitung rata-rata
        $rataRata = $jumlahNilai > 0 ? $totalNilai / $jumlahNilai : 0;

        return view('siswa.course-grades', compact(
            'course',
            'grades',
            'totalTugas',
            'tugasDikumpulkan',
            'totalKuis',
            'kuisDikerjakan',
            'rataRata',
            'user'
        ));
    }

    public function showCompetencies(Course $course)
    {
        $user = Auth::user();

        if (!$user->siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        if ($course->kelas_id !== $user->siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        $siswa = $user->siswa;
        $competenciesByModule = [];

        // Statistik keseluruhan
        $totalCompetencies = 0;
        $completedCompetencies = 0;
        $inProgressCompetencies = 0;
        $notStartedCompetencies = 0;

        // Ambil semua modul dari course ini
        $moduls = Modul::with([
            'tugas.pengumpulan' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            },
            'kuis.jawabanSiswa' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            }
        ])
            ->where('course_id', $course->id)
            ->where('status', 'published')
            ->orderBy('publish_at', 'asc')
            ->get();

        foreach ($moduls as $modul) {
            $moduleCompetencies = [];

            // Kompetensi 1: Penyelesaian Materi
            $totalMateri = $modul->materis->count();
            $materiProgress = $totalMateri > 0 ? 100 : 0; // Asumsi semua materi bisa diakses = selesai

            if ($totalMateri > 0) {
                $status = $materiProgress >= 100 ? 'completed' : ($materiProgress > 0 ? 'in_progress' : 'not_started');
                if ($status === 'completed')
                    $completedCompetencies++;
                elseif ($status === 'in_progress')
                    $inProgressCompetencies++;
                else
                    $notStartedCompetencies++;
                $totalCompetencies++;

                $moduleCompetencies[] = [
                    'title' => 'Pemahaman Materi',
                    'description' => 'Menyelesaikan dan memahami semua materi pembelajaran dalam modul ini',
                    'progress' => $materiProgress,
                    'status' => $status,
                    'activities' => []
                ];
            }

            // Kompetensi 2: Pengumpulan Tugas
            $totalTugas = $modul->tugas->count();
            if ($totalTugas > 0) {
                $tugasDikumpulkan = 0;
                $tugasActivities = [];

                foreach ($modul->tugas as $tugas) {
                    $pengumpulan = $tugas->pengumpulan->first();
                    if ($pengumpulan) {
                        $tugasDikumpulkan++;
                    }
                    $tugasActivities[] = [
                        'name' => $tugas->judul,
                        'type' => 'tugas',
                        'completed' => $pengumpulan ? true : false
                    ];
                }

                $tugasProgress = ($tugasDikumpulkan / $totalTugas) * 100;
                $status = $tugasProgress >= 100 ? 'completed' : ($tugasProgress > 0 ? 'in_progress' : 'not_started');

                if ($status === 'completed')
                    $completedCompetencies++;
                elseif ($status === 'in_progress')
                    $inProgressCompetencies++;
                else
                    $notStartedCompetencies++;
                $totalCompetencies++;

                $moduleCompetencies[] = [
                    'title' => 'Penyelesaian Tugas',
                    'description' => 'Mengumpulkan semua tugas yang diberikan dalam modul ini',
                    'progress' => round($tugasProgress),
                    'status' => $status,
                    'activities' => $tugasActivities
                ];
            }

            // Kompetensi 3: Penguasaan Kuis
            $totalKuis = $modul->kuis->count();
            if ($totalKuis > 0) {
                $kuisDikerjakan = 0;
                $totalNilaiKuis = 0;
                $kuisActivities = [];

                foreach ($modul->kuis as $kuis) {
                    $jawabanSiswa = $kuis->jawabanSiswa;
                    $isDone = $jawabanSiswa->isNotEmpty();

                    if ($isDone) {
                        $kuisDikerjakan++;
                        // Hitung nilai
                        $totalSoal = $kuis->soals->count();
                        $benar = 0;
                        foreach ($jawabanSiswa as $jawaban) {
                            if ($jawaban->skor !== null && $jawaban->skor > 0) {
                                $benar++;
                            }
                        }
                        if ($totalSoal > 0) {
                            $totalNilaiKuis += ($benar / $totalSoal) * 100;
                        }
                    }

                    $kuisActivities[] = [
                        'name' => $kuis->pertanyaan,
                        'type' => 'kuis',
                        'completed' => $isDone
                    ];
                }

                $kuisProgress = $kuisDikerjakan > 0 ? ($totalNilaiKuis / $kuisDikerjakan) : 0;
                $status = $kuisProgress >= 70 ? 'completed' : ($kuisProgress > 0 ? 'in_progress' : 'not_started');

                if ($status === 'completed')
                    $completedCompetencies++;
                elseif ($status === 'in_progress')
                    $inProgressCompetencies++;
                else
                    $notStartedCompetencies++;
                $totalCompetencies++;

                $moduleCompetencies[] = [
                    'title' => 'Penguasaan Kuis',
                    'description' => 'Mencapai nilai minimal 70% pada semua kuis dalam modul ini',
                    'progress' => round($kuisProgress),
                    'status' => $status,
                    'activities' => $kuisActivities
                ];
            }

            if (!empty($moduleCompetencies)) {
                $competenciesByModule[$modul->judul] = $moduleCompetencies;
            }
        }

        // Hitung overall progress
        $overallProgress = $totalCompetencies > 0 ? ($completedCompetencies / $totalCompetencies) * 100 : 0;

        return view('siswa.course-competencies', compact(
            'course',
            'competenciesByModule',
            'overallProgress',
            'totalCompetencies',
            'completedCompetencies',
            'inProgressCompetencies',
            'notStartedCompetencies',
            'user'
        ));
    }

    /**
     * Menampilkan halaman Badges untuk course tertentu
     */
    public function showBadges(Course $course)
    {
        $user = Auth::user();

        if (!$user->siswa) {
            abort(403, 'Profil siswa tidak ditemukan.');
        }

        if ($course->kelas_id !== $user->siswa->kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        $siswa = $user->siswa;

        // Definisi badges yang bisa didapatkan
        $availableBadges = [
            [
                'id' => 'first_assignment',
                'name' => 'First Step',
                'description' => 'Mengumpulkan tugas pertama',
                'icon' => 'assignment',
                'color' => 'blue',
                'criteria' => 'Kumpulkan 1 tugas',
                'earned' => false,
                'earned_date' => null
            ],
            [
                'id' => 'assignment_master',
                'name' => 'Assignment Master',
                'description' => 'Mengumpulkan semua tugas tepat waktu',
                'icon' => 'star',
                'color' => 'amber',
                'criteria' => 'Kumpulkan semua tugas sebelum deadline',
                'earned' => false,
                'earned_date' => null
            ],
            [
                'id' => 'quiz_champion',
                'name' => 'Quiz Champion',
                'description' => 'Mendapat nilai sempurna di kuis',
                'icon' => 'trophy',
                'color' => 'emerald',
                'criteria' => 'Raih nilai 100 di minimal 1 kuis',
                'earned' => false,
                'earned_date' => null
            ],
            [
                'id' => 'perfect_score',
                'name' => 'Perfect Score',
                'description' => 'Mendapat nilai sempurna di semua kuis',
                'icon' => 'medal',
                'color' => 'purple',
                'criteria' => 'Raih nilai 100 di semua kuis',
                'earned' => false,
                'earned_date' => null
            ],
            [
                'id' => 'early_bird',
                'name' => 'Early Bird',
                'description' => 'Selalu mengumpulkan tugas lebih awal',
                'icon' => 'clock',
                'color' => 'rose',
                'criteria' => 'Kumpulkan 5 tugas minimal 1 hari sebelum deadline',
                'earned' => false,
                'earned_date' => null
            ],
            [
                'id' => 'course_completion',
                'name' => 'Course Completer',
                'description' => 'Menyelesaikan seluruh course',
                'icon' => 'graduation',
                'color' => 'indigo',
                'criteria' => 'Selesaikan semua modul, tugas, dan kuis',
                'earned' => false,
                'earned_date' => null
            ]
        ];

        // Logic untuk cek badge yang sudah didapat (bisa dikembangkan)
        // Di sini hanya contoh sederhana
        $moduls = Modul::with([
            'tugas.pengumpulan' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            },
            'kuis.jawabanSiswa' => function ($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            }
        ])
            ->where('course_id', $course->id)
            ->where('status', 'published')
            ->get();

        $totalTugasDikumpulkan = 0;
        $totalKuis = 0;
        $kuisNilai100 = 0;

        foreach ($moduls as $modul) {
            foreach ($modul->tugas as $tugas) {
                if ($tugas->pengumpulan->first()) {
                    $totalTugasDikumpulkan++;
                }
            }

            foreach ($modul->kuis as $kuis) {
                $jawabanSiswa = $kuis->jawabanSiswa;
                if ($jawabanSiswa->isNotEmpty()) {
                    $totalKuis++;
                    $totalSoal = $kuis->soals->count();
                    $benar = 0;
                    foreach ($jawabanSiswa as $jawaban) {
                        if ($jawaban->skor !== null && $jawaban->skor > 0) {
                            $benar++;
                        }
                    }
                    if ($totalSoal > 0 && $benar === $totalSoal) {
                        $kuisNilai100++;
                    }
                }
            }
        }

        // Update badge status
        if ($totalTugasDikumpulkan >= 1) {
            $availableBadges[0]['earned'] = true;
            $availableBadges[0]['earned_date'] = now();
        }

        if ($kuisNilai100 >= 1) {
            $availableBadges[2]['earned'] = true;
            $availableBadges[2]['earned_date'] = now();
        }

        $earnedBadges = collect($availableBadges)->where('earned', true);
        $totalBadges = count($availableBadges);
        $earnedCount = $earnedBadges->count();
        $progressPercentage = $totalBadges > 0 ? ($earnedCount / $totalBadges) * 100 : 0;

        return view('siswa.course-badges', compact(
            'course',
            'availableBadges',
            'earnedCount',
            'totalBadges',
            'progressPercentage',
            'user'
        ));
    }
}
