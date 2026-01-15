<x-app-layout>
    <div class="sticky top-0 z-20 bg-white border-b border-gray-200">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <a href="{{ $modul->course ? route('siswa.course.show', $modul->course) : route('siswa.my-courses') }}"
                           class="flex items-center gap-1 text-sm font-medium text-gray-400 transition-colors hover:text-teal-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </a>
                        <span class="text-gray-300">/</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-teal-50 text-teal-700 border border-teal-100">
                            {{ $modul->mapel->nama }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-800">{{ $modul->judul }}</h1>
                    <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="font-medium text-gray-700">{{ $modul->guru->user->name ?? 'Guru' }}</span>
                        </span>
                        <span class="hidden text-gray-300 sm:inline">|</span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $modul->created_at->format('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="min-h-screen py-8 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="flex items-center gap-3 px-4 py-3 mb-6 text-teal-700 border border-teal-200 shadow-sm rounded-xl bg-teal-50" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 px-4 py-3 mb-6 text-red-700 border border-red-200 shadow-sm rounded-xl bg-red-50" role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col gap-8 lg:flex-row">

                <div class="flex-1 space-y-8">

                    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                        <div class="p-6 md:p-8">
                            <h3 class="flex items-center gap-3 mb-4 text-lg font-bold text-gray-800">
                                <span class="p-2 text-teal-600 border border-teal-100 rounded-lg bg-teal-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                                Deskripsi Pembelajaran
                            </h3>
                            <div class="leading-relaxed prose text-gray-600 prose-slate prose-a:text-teal-600 hover:prose-a:text-teal-700 max-w-none">
                                {!! $modul->deskripsi !!}
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="flex items-center gap-3 text-lg font-bold text-gray-800">
                                <span class="p-2 border rounded-lg bg-cyan-50 text-cyan-600 border-cyan-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                </span>
                                Materi Pembelajaran
                            </h3>
                            <span class="px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-200 rounded-full">{{ $modul->materis->count() }} File</span>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($modul->materis as $materi)
                                <div class="flex flex-col justify-between gap-4 p-5 transition-colors sm:flex-row sm:items-center hover:bg-gray-50 group">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 pt-1 sm:pt-0">
                                            @if($materi->tipe == 'pdf')
                                                <div class="flex items-center justify-center w-10 h-10 text-red-600 bg-red-100 rounded-lg shadow-sm">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @elseif($materi->tipe == 'video')
                                                <div class="flex items-center justify-center w-10 h-10 text-white bg-red-500 rounded-lg shadow-sm">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
                                            @elseif($materi->tipe == 'link')
                                                <div class="flex items-center justify-center w-10 h-10 rounded-lg shadow-sm bg-sky-100 text-sky-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center w-10 h-10 text-gray-600 bg-gray-100 rounded-lg shadow-sm">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 transition-colors group-hover:text-teal-700">{{ $materi->judul }}</h4>
                                            <p class="mt-1 text-xs font-bold tracking-wide text-gray-500 uppercase">{{ $materi->tipe }}</p>
                                        </div>
                                    </div>

                                    <div class="flex w-full gap-2 mt-2 sm:w-auto sm:mt-0">
                                        {{-- @if($materi->tipe == 'pdf' || $materi->tipe == 'video' || $materi->tipe == 'link')
                        <button onclick="openPreview(@json($materi->tipe), @json($materi->tipe == 'link' ? $materi->url : Storage::url($materi->file_path)), @json($materi->judul))"
                                                    class="inline-flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium text-gray-700 transition-all bg-white border border-gray-200 rounded-lg shadow-sm sm:flex-none hover:bg-gray-50 hover:text-teal-600 hover:border-teal-200">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Preview
                                            </button>
                                        @endif --}}

                                        @if($materi->tipe == 'link')
                                            <a href="{{ $materi->url }}" target="_blank" class="inline-flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium transition-colors border rounded-lg shadow-sm sm:flex-none border-sky-200 text-sky-700 bg-sky-50 hover:bg-sky-100">
                                                Buka
                                                <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @elseif($materi->tipe == 'video' && $materi->url)
                                            <a href="{{ $materi->url }}" target="_blank" class="inline-flex items-center justify-center flex-1 px-4 py-2 text-sm font-medium text-red-700 transition-colors border border-red-200 rounded-lg shadow-sm sm:flex-none bg-red-50 hover:bg-red-100">
                                                Tonton
                                                <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </a>
                                        @else
                                            <a href="{{ Storage::url($materi->file_path) }}" target="_blank" class="inline-flex items-center justify-center flex-1 px-4 py-2 text-sm font-bold text-teal-700 transition-colors border border-teal-200 rounded-lg shadow-sm sm:flex-none bg-teal-50 hover:bg-teal-100">
                                                Download
                                                <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <p class="italic text-gray-400">Belum ada materi yang diunggah.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="flex items-center gap-3 text-lg font-bold text-gray-800">
                                <span class="p-2 text-teal-600 border border-teal-100 rounded-lg bg-teal-50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </span>
                                Tugas & Pengumpulan
                            </h3>
                            <span class="px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-200 rounded-full">{{ $modul->tugas->count() }} Tugas</span>
                        </div>

                        <div class="p-6 space-y-8">
                            @forelse($modul->tugas as $tugas)
                                @php
                                    $pengumpulan = $tugas->pengumpulan->first();
                                    $isSubmitted = $pengumpulan ? true : false;
                                @endphp
                                <div class="border rounded-2xl overflow-hidden {{ $isSubmitted ? 'border-teal-200 bg-teal-50/20' : 'border-gray-200 bg-white' }}">
                                    <div class="flex flex-col justify-between gap-4 p-5 border-b border-gray-100 sm:flex-row sm:items-center">
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-800">{{ $tugas->judul }}</h4>
                                            <div class="flex items-center gap-4 mt-1 text-sm">
                                                <span class="flex items-center gap-1 {{ $tugas->deadline && \Carbon\Carbon::parse($tugas->deadline)->isPast() && !$isSubmitted ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Deadline: {{ $tugas->deadline ? $tugas->deadline->format('d M Y, H:i') : 'Tidak ada' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @if($isSubmitted)
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold tracking-wider text-teal-700 uppercase bg-teal-100 rounded-lg">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Terkirim
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold tracking-wider text-gray-500 uppercase bg-gray-100 rounded-lg">
                                                    Belum Dikirim
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="p-5">
                                        <div class="p-4 prose-sm prose text-gray-600 border border-gray-100 rounded-xl prose-slate max-w-none bg-gray-50">
                                            {!! $tugas->instruksi !!}
                                        </div>
                                    </div>

                                    <div class="px-5 pt-2 pb-5">
                                        @if($isSubmitted)
                                            <div class="p-4 bg-white border border-teal-100 shadow-sm rounded-xl">
                                                <div class="flex items-start justify-between">
                                                    <div>
                                                        <h5 class="mb-1 text-sm font-semibold text-teal-800">Status Pengumpulan</h5>
                                                        <p class="text-xs text-teal-600">Dikirim pada: {{ $pengumpulan->tanggal_dikumpulkan }}</p>
                                                        <a href="{{ Storage::url($pengumpulan->file_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm font-medium text-teal-600 hover:text-teal-800 hover:underline">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                            Lihat File Anda
                                                        </a>
                                                    </div>
                                                    <div class="text-right">
                                                        @if($pengumpulan->nilai !== null)
                                                            <div class="inline-flex flex-col items-end">
                                                                <span class="text-3xl font-bold text-teal-600">{{ $pengumpulan->nilai }}</span>
                                                                <span class="text-xs font-bold text-gray-400 uppercase">Nilai</span>
                                                            </div>
                                                        @else
                                                            <span class="px-3 py-1 text-xs italic text-gray-400 bg-gray-100 rounded-full">Menunggu penilaian...</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($pengumpulan->komentar_guru)
                                                    <div class="pt-3 mt-3 border-t border-teal-100">
                                                        <p class="text-sm text-gray-700"><span class="font-semibold text-teal-700">Komentar Guru:</span> "{{ $pengumpulan->komentar_guru }}"</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <form action="{{ route('siswa.tugas.upload', $tugas->id) }}" method="POST" enctype="multipart/form-data"
                                                  class="p-8 text-center transition-all border-2 border-gray-300 border-dashed rounded-xl bg-gray-50 hover:bg-white hover:border-teal-400 hover:shadow-md group" id="uploadForm-{{ $tugas->id }}">
                                                @csrf
                                                <div class="space-y-4">
                                                    <div class="flex flex-col items-center">
                                                        <div class="p-3 mb-3 bg-white rounded-full shadow-sm">
                                                            <svg class="w-8 h-8 text-gray-400 transition-colors group-hover:text-teal-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </div>
                                                        <div class="flex items-center justify-center mt-2 text-sm text-gray-600">
                                                            <label for="file-upload-{{ $tugas->id }}" class="relative font-bold text-teal-600 bg-transparent rounded-md cursor-pointer hover:text-teal-500 focus-within:outline-none">
                                                                <span>Upload file jawaban</span>
                                                                <input id="file-upload-{{ $tugas->id }}" name="file" type="file" class="sr-only" required>
                                                            </label>
                                                            <p class="pl-1">atau drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500">PDF, DOC, JPG up to 10MB</p>
                                                    </div>

                                                    <!-- Preview Area (hidden until a file is chosen) -->
                                                    <div id="filePreview-{{ $tugas->id }}" class="hidden p-4 mt-3 text-left bg-white border border-gray-200 rounded-lg">
                                                        <div class="flex items-start gap-4">
                                                            <div id="fileThumb-{{ $tugas->id }}" class="flex items-center justify-center flex-shrink-0 w-20 h-20 overflow-hidden text-gray-400 rounded-md bg-gray-50">
                                                                <!-- thumbnail will be injected -->
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="flex items-center justify-between">
                                                                    <div>
                                                                        <div id="fileName-{{ $tugas->id }}" class="text-sm font-semibold text-gray-700"></div>
                                                                        <div id="fileMeta-{{ $tugas->id }}" class="mt-1 text-xs text-gray-500"></div>
                                                                    </div>
                                                                    <div class="flex items-center gap-2">
                                                                        <button type="button" id="viewFile-{{ $tugas->id }}" class="inline-flex items-center hidden px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700" target="_blank">
                                                                            Lihat
                                                                        </button>
                                                                        <button type="button" id="removeFile-{{ $tugas->id }}" class="inline-flex items-center px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
                                                                            Hapus
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <p id="fileNote-{{ $tugas->id }}" class="mt-2 text-xs text-gray-500"></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="p-3 mt-4 text-left bg-white border border-gray-200 rounded-lg">
                                                        <label class="block mb-1 text-sm font-medium text-gray-700">Catatan Tambahan (Opsional)</label>
                                                        <textarea name="catatan" rows="2" class="w-full text-gray-700 border-gray-300 rounded-md shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="Tulis catatan untuk guru jika ada..."></textarea>
                                                    </div>

                                                    <button type="submit" class="inline-flex justify-center w-full px-4 py-2.5 text-sm font-bold text-white bg-teal-600 border border-transparent rounded-lg shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all" id="submitBtn-{{ $tugas->id }}">
                                                        Kirim Tugas
                                                    </button>
                                                </div>
                                            </form>

                                            <script>
                                                (function(){
                                                    const input = document.getElementById('file-upload-{{ $tugas->id }}');
                                                    const previewWrap = document.getElementById('filePreview-{{ $tugas->id }}');
                                                    const thumb = document.getElementById('fileThumb-{{ $tugas->id }}');
                                                    const fileNameEl = document.getElementById('fileName-{{ $tugas->id }}');
                                                    const fileMetaEl = document.getElementById('fileMeta-{{ $tugas->id }}');
                                                    const viewBtn = document.getElementById('viewFile-{{ $tugas->id }}');
                                                    const removeBtn = document.getElementById('removeFile-{{ $tugas->id }}');
                                                    const fileNote = document.getElementById('fileNote-{{ $tugas->id }}');
                                                    let currentUrl = null;

                                                    function humanFileSize(size) {
                                                        const i = size == 0 ? 0 : Math.floor(Math.log(size) / Math.log(1024));
                                                        return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'KB', 'MB', 'GB'][i];
                                                    }

                                                    input.addEventListener('change', function(e){
                                                        const file = this.files && this.files[0];
                                                        if(!file) {
                                                            clearPreview();
                                                            return;
                                                        }

                                                        // revoke old url
                                                        if(currentUrl) {
                                                            URL.revokeObjectURL(currentUrl);
                                                            currentUrl = null;
                                                        }

                                                        fileNameEl.textContent = file.name;
                                                        fileMetaEl.textContent = file.type + ' • ' + humanFileSize(file.size);
                                                        fileNote.textContent = 'File akan dikirim saat Anda menekan "Kirim Tugas".';

                                                        // create preview URL
                                                        const url = URL.createObjectURL(file);
                                                        currentUrl = url;

                                                        // Reset thumb content
                                                        thumb.innerHTML = '';

                                                        if (file.type.startsWith('image/')) {
                                                            const img = document.createElement('img');
                                                            img.src = url;
                                                            img.className = 'object-cover w-full h-full';
                                                            img.alt = file.name;
                                                            thumb.appendChild(img);
                                                            viewBtn.classList.remove('hidden');
                                                            viewBtn.onclick = () => window.open(url, '_blank');
                                                        } else if (file.type === 'application/pdf') {
                                                            // show pdf icon and enable view
                                                            thumb.innerHTML = '<svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4 8v12a2 2 0 002 2h12a2 2 0 002-2V8l-8-6zM8 20v-8h8v8H8z"/></svg>';
                                                            viewBtn.classList.remove('hidden');
                                                            viewBtn.onclick = () => window.open(url, '_blank');
                                                        } else {
                                                            // generic file icon
                                                            thumb.innerHTML = '<svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7v10M17 7v10M3 7h18M3 17h18"/></svg>';
                                                            viewBtn.classList.add('hidden');
                                                            viewBtn.onclick = null;
                                                        }

                                                        previewWrap.classList.remove('hidden');
                                                    });

                                                    removeBtn.addEventListener('click', function(){
                                                        input.value = '';
                                                        clearPreview();
                                                    });

                                                    function clearPreview(){
                                                        if(currentUrl) {
                                                            URL.revokeObjectURL(currentUrl);
                                                            currentUrl = null;
                                                        }
                                                        thumb.innerHTML = '';
                                                        fileNameEl.textContent = '';
                                                        fileMetaEl.textContent = '';
                                                        fileNote.textContent = '';
                                                        viewBtn.classList.add('hidden');
                                                        viewBtn.onclick = null;
                                                        previewWrap.classList.add('hidden');
                                                    }

                                                    // Clean up when form is submitted to avoid leaked object URLs
                                                    const form = document.getElementById('uploadForm-{{ $tugas->id }}');
                                                    form.addEventListener('submit', function(){
                                                        if(currentUrl) {
                                                            URL.revokeObjectURL(currentUrl);
                                                            currentUrl = null;
                                                        }
                                                    });
                                                })();
                                            </script>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center">
                                    <p class="italic text-gray-400">Tidak ada tugas pada modul ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <div class="lg:w-1/3">
                    <div class="sticky space-y-6 top-24">

                        <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="flex items-center gap-3 font-bold text-gray-800">
                                    <span class="p-2 text-orange-600 border border-orange-100 rounded-lg bg-orange-50">
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

                                    <div class="border rounded-xl p-4 transition-all {{ $sudahDikerjakan ? 'border-teal-200 bg-teal-50' : 'border-gray-200 bg-white hover:border-teal-300 hover:shadow-md' }}">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="text-sm font-bold text-gray-800 line-clamp-2">{{ $kuis->judul }}</h4>
                                            @if($sudahDikerjakan)
                                                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2 mb-3 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $kuis->durasi_menit }} Menit
                                        </div>

                                        @if($sudahDikerjakan)
                                            @php
                                                // Kalkulasi Nilai (Sama dengan sebelumnya)
                                                $nilaiPG = null;
                                                if ($totalSoalPG > 0) {
                                                    $benar = $kuis->jawabanSiswa->where('soal.tipe', 'pilihan_ganda')->sum('skor');
                                                    $nilaiPG = round(($benar / $totalSoalPG) * 100);
                                                }

                                                $essayAnswers = $kuis->jawabanSiswa->filter(function($j) {
                                                    return optional($j->soal)->tipe === 'essay' && $j->skor !== null;
                                                });
                                                $avgEssay = null;
                                                if ($essayAnswers->isNotEmpty()) {
                                                    $avgEssay = round($essayAnswers->average('skor'));
                                                }

                                                $nilaiGabungan = null;
                                                if ($nilaiPG !== null && $avgEssay !== null) {
                                                    $nilaiGabungan = round(($nilaiPG + $avgEssay) / 2);
                                                } elseif ($nilaiPG !== null) {
                                                    $nilaiGabungan = $nilaiPG;
                                                } elseif ($avgEssay !== null) {
                                                    $nilaiGabungan = $avgEssay;
                                                }
                                            @endphp

                                            <div class="flex items-center justify-between pt-2 mt-2 border-t border-teal-200">
                                                <span class="text-xs font-bold text-teal-700 uppercase">Selesai</span>
                                                <div class="text-right">
                                                    @if($nilaiPG !== null)
                                                        <span class="block text-xs font-bold text-teal-600">PG: {{ $nilaiPG }}</span>
                                                    @endif
                                                    @if($avgEssay !== null)
                                                        <span class="block text-xs font-bold text-orange-600">Essay: {{ $avgEssay }}</span>
                                                    @elseif($hasEssay)
                                                        <span class="text-[10px] text-gray-500 italic block">Essay: Menunggu</span>
                                                    @endif

                                                    @if($nilaiGabungan !== null)
                                                        <span class="text-sm font-bold text-gray-800 block mt-0.5">Total: {{ $nilaiGabungan }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ route('siswa.kuis.kerjakan', $kuis->id) }}"
                                               class="block w-full px-4 py-2 text-xs font-bold text-center text-white transition-all bg-teal-600 rounded-lg hover:bg-teal-700 hover:shadow-lg hover:-translate-y-0.5">
                                                Mulai Kerjakan
                                            </a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-6 text-sm text-center text-gray-400 border border-gray-200 border-dashed bg-gray-50 rounded-xl">
                                        Tidak ada kuis aktif.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="relative p-6 overflow-hidden text-white shadow-xl bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl">
                            <div class="absolute top-0 right-0 w-20 h-20 -mt-2 -mr-2 bg-white rounded-full opacity-10 blur-xl"></div>
                            <div class="relative z-10">
                                <h4 class="mb-2 text-lg font-bold">Tetap Semangat! 🚀</h4>
                                <p class="text-sm leading-relaxed text-cyan-50">
                                    Selesaikan materi dan tugas tepat waktu agar nilaimu maksimal. Jangan lupa istirahat!
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="previewModal" class="fixed inset-0 z-50 hidden overflow-y-auto transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="relative w-full max-w-6xl transition-all transform bg-white shadow-2xl rounded-2xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 rounded-t-2xl">
                    <h3 id="previewTitle" class="text-lg font-bold text-gray-800">Preview Materi</h3>
                    <button onclick="closePreview()" class="p-1 text-gray-400 transition-colors rounded-full hover:text-gray-600 hover:bg-gray-200 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="previewContent" class="p-6 bg-gray-100 rounded-b-2xl" style="max-height: 80vh; overflow-y: auto;">
                    </div>
            </div>
        </div>
    </div>

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

            if (type === 'pdf') {
                // Simpler PDF preview using iframe — more robust across local setups
                content.innerHTML = `
                    <div class="flex flex-col w-full gap-4">
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-200 shadow-sm rounded-xl">
                            <div class="text-sm font-medium text-gray-700">PDF Preview</div>
                            <div>
                                <a id="pdf-download" href="${url}" target="_blank" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white transition-colors bg-teal-600 rounded-lg shadow-sm hover:bg-teal-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Download
                                </a>
                            </div>
                        </div>
                        <div class="w-full p-4 bg-gray-200 rounded-xl" style="min-height:500px;">
                            <iframe src="${url}" class="w-full h-[70vh] border-0 rounded-lg bg-white"></iframe>
                        </div>
                    </div>
                `;

            } else if (type === 'video') {
                content.innerHTML = `
                    <div class="w-full overflow-hidden bg-black shadow-lg rounded-xl">
                        <video controls class="w-full rounded-lg" style="max-height: 600px;">
                            <source src="${url}" type="video/mp4">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    </div>
                `;
            } else if (type === 'link') {
                const youtubeRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
                const match = url.match(youtubeRegex);

                if (match && match[1]) {
                    content.innerHTML = `
                        <div class="w-full overflow-hidden bg-black shadow-lg rounded-xl">
                            <div class="relative" style="padding-bottom: 56.25%; height: 0;">
                                <iframe src="https://www.youtube.com/embed/${match[1]}"
                                        class="absolute top-0 left-0 w-full h-full"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <a href="${url}" target="_blank" class="inline-flex items-center px-6 py-3 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-lg transform hover:-translate-y-0.5 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                Buka di YouTube
                            </a>
                        </div>
                    `;
                } else {
                    content.innerHTML = `
                        <div class="w-full overflow-hidden bg-white shadow-lg rounded-xl" style="height: 600px;">
                            <iframe src="${url}" class="w-full h-full border-0"></iframe>
                        </div>
                        <div class="mt-6 text-center">
                            <a href="${url}" target="_blank" class="inline-flex items-center px-6 py-3 text-sm font-bold text-white bg-sky-600 rounded-xl hover:bg-sky-700 shadow-lg transform hover:-translate-y-0.5 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            document.getElementById('previewContent').innerHTML = '';
        }

        document.getElementById('previewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePreview();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePreview();
            }
        });
    </script>
</x-app-layout>
