<x-app-layout>
    <div class="border-b bg-slate-50 border-slate-200">
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <a href="{{ $modul->course ? route('siswa.course.show', $modul->course) : route('siswa.my-courses') }}" class="transition-colors text-slate-400 hover:text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700">
                            {{ $modul->mapel->nama }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-800">{{ $modul->judul }}</h1>
                    <p class="flex items-center gap-2 mt-2 text-sm text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Pengajar: <span class="font-medium text-slate-700">{{ $modul->guru->user->name ?? 'Guru' }}</span>
                        <span class="text-slate-300">|</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $modul->created_at->format('d F Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="min-h-screen py-8 bg-slate-50/50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="flex items-center gap-2 px-4 py-3 mb-6 border rounded-lg shadow-sm bg-emerald-50 border-emerald-200 text-emerald-700" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-2 px-4 py-3 mb-6 border rounded-lg shadow-sm bg-rose-50 border-rose-200 text-rose-700" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col gap-8 lg:flex-row">

                <div class="flex-1 space-y-8">

                    <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="p-6 md:p-8">
                            <h3 class="flex items-center gap-2 mb-4 text-lg font-bold text-slate-800">
                                <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                                Deskripsi Pembelajaran
                            </h3>
                            <div class="leading-relaxed prose prose-slate max-w-none text-slate-600">
                                {!! $modul->deskripsi !!}
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800">
                                <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </span>
                                Materi Pembelajaran
                            </h3>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-slate-200 text-slate-600">{{ $modul->materis->count() }} File</span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($modul->materis as $materi)
                                <div class="flex items-center justify-between p-4 transition-colors hover:bg-slate-50 group">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0">
                                            @if($materi->tipe == 'pdf')
                                                <div class="flex items-center justify-center w-10 h-10 text-red-600 bg-red-100 rounded-lg">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @elseif($materi->tipe == 'video')
                                                <div class="flex items-center justify-center w-10 h-10 text-white bg-red-500 rounded-lg">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                            @elseif($materi->tipe == 'link')
                                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-sky-100 text-sky-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100 text-slate-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-slate-800">{{ $materi->judul }}</h4>
                                            <p class="text-xs text-slate-500 uppercase font-medium mt-0.5">{{ $materi->tipe }}</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <!-- Preview Button -->
                                        @if($materi->tipe == 'pdf' || $materi->tipe == 'video' || $materi->tipe == 'link')
                                            <button onclick="openPreview('{{ $materi->tipe }}', '{{ $materi->tipe == 'link' ? $materi->url : Storage::url($materi->file_path) }}', '{{ $materi->judul }}')"
                                                    class="inline-flex items-center px-3 py-1.5 border border-indigo-200 text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Preview
                                            </button>
                                        @endif

                                        <!-- Download/Open Button -->
                                        @if($materi->tipe == 'link')
                                            <a href="{{ $materi->url }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-sky-200 text-sm font-medium rounded-lg text-sky-700 bg-sky-50 hover:bg-sky-100 transition-colors">
                                                Buka Link
                                                <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @elseif($materi->tipe == 'video' && $materi->url)
                                            <a href="{{ $materi->url }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-red-200 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                                                Tonton
                                                <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </a>
                                        @else
                                            <a href="{{ Storage::url($materi->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-slate-200 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-colors shadow-sm">
                                                Download
                                                <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <p class="italic text-slate-500">Belum ada materi yang diunggah.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="flex items-center gap-2 text-lg font-bold text-slate-800">
                                <span class="bg-violet-100 text-violet-600 p-1.5 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </span>
                                Tugas & Pengumpulan
                            </h3>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-slate-200 text-slate-600">{{ $modul->tugas->count() }} Tugas</span>
                        </div>

                        <div class="p-6 space-y-8">
                            @forelse($modul->tugas as $tugas)
                                @php
                                    $pengumpulan = $tugas->pengumpulan->first();
                                    $isSubmitted = $pengumpulan ? true : false;
                                @endphp
                                <div class="border rounded-xl overflow-hidden {{ $isSubmitted ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-white' }}">
                                    <div class="flex flex-col justify-between gap-4 p-5 border-b border-slate-100 sm:flex-row sm:items-center">
                                        <div>
                                            <h4 class="text-lg font-bold text-slate-800">{{ $tugas->judul }}</h4>
                                            <div class="flex items-center gap-4 mt-1 text-sm">
                                                <span class="flex items-center gap-1 {{ $tugas->deadline && \Carbon\Carbon::parse($tugas->deadline)->isPast() && !$isSubmitted ? 'text-rose-600 font-medium' : 'text-slate-500' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Deadline: {{ $tugas->deadline ? $tugas->deadline->format('d M Y, H:i') : 'Tidak ada' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @if($isSubmitted)
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold tracking-wider uppercase rounded-full bg-emerald-100 text-emerald-700">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Terkirim
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold tracking-wider uppercase rounded-full bg-slate-100 text-slate-500">
                                                    Belum Dikirim
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="p-5">
                                        <div class="p-4 prose-sm prose border rounded-lg prose-slate max-w-none bg-slate-50 border-slate-100 text-slate-600">
                                            {!! $tugas->instruksi !!}
                                        </div>
                                    </div>

                                    <div class="px-5 pt-2 pb-5">
                                        @if($isSubmitted)
                                            <div class="p-4 bg-white border rounded-lg shadow-sm border-emerald-100">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <h5 class="mb-1 text-sm font-semibold text-emerald-800">Status Pengumpulan</h5>
                                                        <p class="text-xs text-emerald-600">Dikirim pada: {{ $pengumpulan->tanggal_dikumpulkan }}</p>
                                                        <a href="{{ Storage::url($pengumpulan->file_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                            Lihat File Anda
                                                        </a>
                                                    </div>
                                                    <div class="text-right">
                                                        @if($pengumpulan->nilai !== null)
                                                            <div class="inline-flex flex-col items-end">
                                                                <span class="text-3xl font-bold text-indigo-600">{{ $pengumpulan->nilai }}</span>
                                                                <span class="text-xs font-bold uppercase text-slate-400">Nilai</span>
                                                            </div>
                                                        @else
                                                            <span class="text-xs italic text-slate-400">Menunggu penilaian...</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($pengumpulan->komentar_guru)
                                                    <div class="pt-3 mt-3 border-t border-emerald-100">
                                                        <p class="text-sm text-slate-700"><span class="font-semibold text-emerald-700">Komentar Guru:</span> "{{ $pengumpulan->komentar_guru }}"</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <form action="{{ route('siswa.tugas.upload', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="p-6 text-center transition-colors border border-dashed rounded-lg bg-slate-50 border-slate-300 hover:bg-white hover:border-indigo-300">
                                                @csrf
                                                <div class="space-y-4">
                                                    <div class="flex flex-col items-center">
                                                        <svg class="w-12 h-12 mx-auto text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="flex justify-center mt-2 text-sm text-slate-600">
                                                            <label for="file-upload-{{ $tugas->id }}" class="relative font-medium text-indigo-600 bg-white rounded-md cursor-pointer hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                                <span>Upload file jawaban</span>
                                                                <input id="file-upload-{{ $tugas->id }}" name="file" type="file" class="sr-only" required>
                                                            </label>
                                                            <p class="pl-1">atau drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-slate-500">PDF, DOC, JPG up to 10MB</p>
                                                    </div>

                                                    <div class="mt-4 text-left">
                                                        <label class="block mb-1 text-sm font-medium text-slate-700">Catatan Tambahan (Opsional)</label>
                                                        <textarea name="catatan" rows="2" class="w-full rounded-md shadow-sm border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Tulis catatan untuk guru jika ada..."></textarea>
                                                    </div>

                                                    <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Kirim Tugas
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center">
                                    <p class="italic text-slate-500">Tidak ada tugas pada modul ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <div class="lg:w-1/3">
                    <div class="sticky space-y-6 top-24">

                        <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="flex items-center gap-2 font-bold text-slate-800">
                                    <span class="p-1 rounded bg-amber-100 text-amber-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </span>
                                    Kuis & Ujian
                                </h3>
                            </div>
                            <div class="p-4 space-y-3">
                                @forelse($modul->kuis as $kuis)
                                    @php
                                        $sudahDikerjakan = $kuis->jawabanSiswa->isNotEmpty();
                                        $nilai = 0;
                                        $hasEssay = $kuis->soals->where('tipe', 'essay')->count() > 0;
                                        $totalSoalPG = $kuis->soals->where('tipe', 'pilihan_ganda')->count();

                                        if($sudahDikerjakan) {
                                            $benar = $kuis->jawabanSiswa->where('skor', '!=', null)->sum('skor');
                                            $nilai = ($totalSoalPG > 0) ? round(($benar / $totalSoalPG) * 100) : 0;
                                        }
                                    @endphp

                                    <div class="border rounded-xl p-4 transition-all {{ $sudahDikerjakan ? 'border-emerald-200 bg-emerald-50' : 'border-slate-200 hover:border-indigo-300 hover:shadow-md' }}">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="text-sm font-bold text-slate-800">{{ $kuis->judul }}</h4>
                                            @if($sudahDikerjakan)
                                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2 mb-3 text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $kuis->durasi_menit }} Menit
                                        </div>

                                        @if($sudahDikerjakan)
                                            @php
                                                // Hitung nilai PG (persentase)
                                                $nilaiPG = null;
                                                if ($totalSoalPG > 0) {
                                                    $benar = $kuis->jawabanSiswa->where('soal.tipe', 'pilihan_ganda')->sum('skor');
                                                    $nilaiPG = round(($benar / $totalSoalPG) * 100);
                                                }

                                                // Hitung rata-rata skor essay (diasumsikan guru memberi skor 0-100 per soal essay)
                                                $essayAnswers = $kuis->jawabanSiswa->filter(function($j) {
                                                    return optional($j->soal)->tipe === 'essay' && $j->skor !== null;
                                                });
                                                $avgEssay = null;
                                                if ($essayAnswers->isNotEmpty()) {
                                                    $avgEssay = round($essayAnswers->average('skor'));
                                                }

                                                // Gabungkan jika kedua komponen ada — rata-rata sederhana
                                                $nilaiGabungan = null;
                                                if ($nilaiPG !== null && $avgEssay !== null) {
                                                    $nilaiGabungan = round(($nilaiPG + $avgEssay) / 2);
                                                } elseif ($nilaiPG !== null) {
                                                    $nilaiGabungan = $nilaiPG;
                                                } elseif ($avgEssay !== null) {
                                                    $nilaiGabungan = $avgEssay;
                                                }
                                            @endphp

                                            <div class="flex items-center justify-between pt-2 mt-2 border-t border-emerald-200">
                                                <span class="text-xs font-semibold text-emerald-700">Selesai</span>
                                                <div class="text-right">
                                                    @if($nilaiPG !== null)
                                                        <span class="text-sm font-bold text-emerald-600 block">Nilai PG: {{ $nilaiPG }}</span>
                                                    @endif
                                                    @if($avgEssay !== null)
                                                        <span class="text-sm font-bold text-amber-600 block">Nilai Essay: {{ $avgEssay }}</span>
                                                    @elseif($hasEssay)
                                                        <span class="text-xs text-slate-500 italic">Essay: Menunggu penilaian</span>
                                                    @endif

                                                    @if($nilaiGabungan !== null)
                                                        <span class="text-sm font-bold text-indigo-600 block">Nilai Akhir: {{ $nilaiGabungan }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ route('siswa.kuis.kerjakan', $kuis->id) }}"
                                               class="block w-full px-4 py-2 text-xs font-bold text-center text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-700">
                                                Mulai Kerjakan
                                            </a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-4 text-sm text-center text-slate-400">
                                        Tidak ada kuis aktif.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="p-6 text-white shadow-lg bg-gradient-to-br from-violet-600 to-indigo-700 rounded-2xl">
                            <h4 class="mb-1 text-lg font-bold">Tetap Semangat! 🚀</h4>
                            <p class="text-sm text-indigo-100 opacity-90">
                                Selesaikan materi dan tugas tepat waktu agar nilaimu maksimal.
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Preview -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-75">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative w-full max-w-6xl bg-white rounded-2xl shadow-2xl">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                    <h3 id="previewTitle" class="text-lg font-bold text-slate-800">Preview Materi</h3>
                    <button onclick="closePreview()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div id="previewContent" class="p-6" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <!-- PDF.js from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.228/pdf.min.js" integrity="sha512-+o2qSx0k1i0Y+5m6WQkqz1q1G6fK0m5Y9s4Yb6rj1Z2Yc9VvKc1Ykz8y2h6q7mN0G0Q9Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Configure PDF.js worker
        if (window.pdfjsLib) {
            window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.228/pdf.worker.min.js';
        }
        function openPreview(type, url, title) {
            const modal = document.getElementById('previewModal');
            const content = document.getElementById('previewContent');
            const titleEl = document.getElementById('previewTitle');

            titleEl.textContent = title;

            if (type === 'pdf' && window.pdfjsLib) {
                // PDF.js viewer with loading indicator and fallback
                content.innerHTML = `
                    <div class="w-full flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <button id="pdf-prev" class="px-3 py-1 bg-slate-100 rounded">Prev</button>
                                <button id="pdf-next" class="ml-2 px-3 py-1 bg-slate-100 rounded">Next</button>
                                <span id="pdf-page-info" class="ml-3 text-sm text-slate-600"></span>
                            </div>
                            <div>
                                <a id="pdf-download" href="${url}" target="_blank" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white rounded">Download</a>
                            </div>
                        </div>
                        <div id="pdf-loading" class="w-full text-center py-8 text-slate-500">Memuat dokumen...</div>
                        <div class="w-full flex justify-center items-center" style="min-height:400px;">
                            <canvas id="pdf-canvas" class="rounded-lg shadow-lg hidden" style="max-width:100%;"></canvas>
                        </div>
                    </div>
                `;

                const canvas = document.getElementById('pdf-canvas');
                const ctx = canvas.getContext('2d');
                const loadingEl = document.getElementById('pdf-loading');
                let pdfDoc = null;
                let currentPage = 1;
                let totalPages = 0;

                const renderPage = (num) => {
                    pdfDoc.getPage(num).then(function(page) {
                        const viewport = page.getViewport({ scale: 1 });
                        // scale to fit width
                        const containerWidth = Math.min(window.innerWidth * 0.8, 1000);
                        const scale = containerWidth / viewport.width;
                        const scaledViewport = page.getViewport({ scale });
                        canvas.height = scaledViewport.height;
                        canvas.width = scaledViewport.width;
                        canvas.classList.remove('hidden');

                        const renderContext = {
                            canvasContext: ctx,
                            viewport: scaledViewport,
                        };
                        page.render(renderContext).promise.then(function() {
                            document.getElementById('pdf-page-info').textContent = `Halaman ${num} / ${totalPages}`;
                        });
                    }).catch(function(err) {
                        loadingEl.textContent = 'Gagal merender halaman: ' + err.message;
                    });
                };

                // Attempt to fetch and render PDF; if fails, fallback to iframe link
                (async function() {
                    try {
                        const res = await fetch(encodeURI(url));
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        const data = await res.arrayBuffer();
                        const loadingTask = pdfjsLib.getDocument({ data });
                        pdfDoc = await loadingTask.promise;
                        totalPages = pdfDoc.numPages;
                        loadingEl.style.display = 'none';
                        renderPage(currentPage);

                        document.getElementById('pdf-prev').addEventListener('click', function() {
                            if (currentPage <= 1) return;
                            currentPage--;
                            renderPage(currentPage);
                        });
                        document.getElementById('pdf-next').addEventListener('click', function() {
                            if (currentPage >= totalPages) return;
                            currentPage++;
                            renderPage(currentPage);
                        });
                    } catch (err) {
                        // Fallback: show iframe or direct link if fetch/pdf.js fails
                        loadingEl.innerHTML = `<div class="text-rose-600">Tidak dapat memuat preview. <a href="${url}" target="_blank" class="text-indigo-600 underline">Buka di tab baru</a></div>`;
                        console.error('PDF preview error:', err);
                        // Try to show iframe as fallback (may be blocked by headers)
                        try {
                            const iframe = document.createElement('iframe');
                            iframe.src = url;
                            iframe.className = 'w-full h-96 border-0 rounded-lg';
                            loadingEl.parentNode.appendChild(iframe);
                        } catch (e) {
                            // ignore
                        }
                    }
                })();

            } else if (type === 'video') {
                content.innerHTML = `
                    <div class="w-full">
                        <video controls class="w-full rounded-lg shadow-lg" style="max-height: 600px;">
                            <source src="${url}" type="video/mp4">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    </div>
                `;
            } else if (type === 'link') {
                // Check if it's a YouTube link
                const youtubeRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
                const match = url.match(youtubeRegex);

                if (match && match[1]) {
                    content.innerHTML = `
                        <div class="w-full">
                            <div class="relative" style="padding-bottom: 56.25%; height: 0;">
                                <iframe src="https://www.youtube.com/embed/${match[1]}"
                                        class="absolute top-0 left-0 w-full h-full rounded-lg"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="${url}" target="_blank" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                Buka di YouTube
                            </a>
                        </div>
                    `;
                } else {
                    content.innerHTML = `
                        <div class="w-full" style="height: 600px;">
                            <iframe src="${url}" class="w-full h-full border-0 rounded-lg"></iframe>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="${url}" target="_blank" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-sky-600 rounded-lg hover:bg-sky-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Buka di Tab Baru
                            </a>
                        </div>
                    `;
                }
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePreview() {
            const modal = document.getElementById('previewModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';

            // Clear content
            document.getElementById('previewContent').innerHTML = '';
        }

        // Close modal when clicking outside
        document.getElementById('previewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreview();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePreview();
            }
        });
    </script>
</x-app-layout>
