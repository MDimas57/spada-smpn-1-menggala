<x-app-layout>
    <div class="min-h-screen py-12 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('siswa.my-courses') }}"
                           class="inline-flex items-center text-sm font-medium text-gray-500 transition-colors hover:text-teal-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            My Courses
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-teal-600 md:ml-2">{{ $course->nama }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Header Course --}}
            <div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                @php
                    // Gradient Teal ke Cyan
                    $headerGradient = 'from-teal-600 to-cyan-700';
                @endphp

                <div class="relative p-8 bg-gradient-to-br {{ $headerGradient }}">
                    <div class="absolute inset-0 opacity-20">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="header-hexagons" x="0" y="0" width="56" height="100" patternUnits="userSpaceOnUse">
                                    <polygon points="28,0 56,16.66 56,50 28,66.66 0,50 0,16.66" fill="white" fill-opacity="0.3" />
                                    <polygon points="28,66.66 56,83.32 56,116.66 28,133.32 0,116.66 0,83.32" fill="white" fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#header-hexagons)" />
                        </svg>
                    </div>

                    <div class="absolute top-0 right-0 w-64 h-64 -mt-16 -mr-16 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 -mb-10 -ml-10 rounded-full bg-teal-400/20 blur-2xl"></div>

                    <div class="relative">
                        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                            <div class="flex-1">
                                <div class="inline-flex items-center px-3 py-1 mb-3 text-xs font-bold tracking-wider text-white uppercase bg-white border rounded-full bg-opacity-20 backdrop-blur-md border-white/20">
                                    {{ $course->mapel->nama ?? 'Mata Pelajaran' }}
                                </div>
                                <h1 class="mb-2 text-3xl font-bold tracking-tight text-white">{{ $course->nama }}</h1>
                                <p class="max-w-2xl text-lg leading-relaxed text-cyan-50">
                                    {!! strip_tags($course->deskripsi) ?: 'Selamat datang di course ini! Jelajahi semua modul pembelajaran yang tersedia.' !!}
                                </p>
                                <div class="flex items-center gap-3 mt-6">
                                    <div class="flex items-center gap-2 text-white bg-black/20 px-3 py-1.5 rounded-full backdrop-blur-sm">
                                        <div class="flex items-center justify-center w-6 h-6 text-xs font-bold border border-white rounded-full bg-white/20">
                                            {{ substr($course->guru->user->name ?? 'G', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium">{{ $course->guru->user->name ?? 'Guru' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-5 border shadow-lg border-white/20 rounded-xl bg-white/10 backdrop-blur-md">
                                <div class="text-center min-w-[80px]">
                                    <p class="text-3xl font-bold text-white">{{ $moduls->count() }}</p>
                                    <p class="text-xs font-medium tracking-wide uppercase text-cyan-100">Modul</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigation Tabs --}}
                <div class="bg-white border-b border-gray-200">
                    <nav class="flex px-6 -mb-px space-x-8 overflow-x-auto" aria-label="Tabs">
                        <a href="#"
                           class="px-1 py-4 text-sm font-bold text-teal-600 transition-colors border-b-2 border-teal-600 whitespace-nowrap">
                            Course Content
                        </a>
                        <a href="{{ route('siswa.course.participants', $course->id) }}"
                           class="px-1 py-4 text-sm font-medium text-gray-500 transition-colors border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 whitespace-nowrap">
                            Participants
                        </a>
                        <a href="{{ route('siswa.course.grades', $course->id) }}"
                           class="px-1 py-4 text-sm font-medium text-gray-500 transition-colors border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 whitespace-nowrap">
                            Grades
                        </a>
                        <a href="{{ route('siswa.course.competencies', $course->id) }}"
                           class="px-1 py-4 text-sm font-medium text-gray-500 transition-colors border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 whitespace-nowrap">
                            Competencies
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Content Modul List --}}
            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="flex items-center gap-2 text-lg font-bold text-gray-800">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Daftar Modul Pembelajaran
                    </h2>
                    <button onclick="collapseAll()" class="text-sm font-semibold text-teal-600 transition-colors hover:text-teal-800">
                        Collapse all
                    </button>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($moduls->sortBy('created_at')->values() as $index => $modul)
                        {{-- PERBAIKAN: Menghapus kondisi open pada details agar default tertutup --}}
                        <details class="transition-colors duration-200 group open:bg-gray-50/50">
                            <summary class="flex items-center justify-between px-6 py-5 transition-all cursor-pointer select-none hover:bg-gray-50">
                                <div class="flex items-center flex-1 gap-4">
                                    <div class="flex items-center justify-center w-8 h-8 text-sm font-bold text-gray-500 transition-colors bg-gray-100 border border-gray-200 rounded-lg group-open:bg-teal-100 group-open:text-teal-700 group-open:border-teal-200">
                                        {{ $index + 1 }}
                                    </div>

                                    <div class="flex-1">
                                        <h3 class="text-base font-bold text-gray-800 transition-colors group-hover:text-teal-600">
                                            {{ $modul->judul }}
                                        </h3>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-gray-400 group-open:hidden">
                                            <span>{{ $modul->materis->count() }} Materi</span> &bull; <span>{{ $modul->tugas->count() }} Tugas</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-medium text-gray-400 group-open:hidden">
                                        {{ $modul->created_at->diffForHumans() }}
                                    </span>
                                    <div class="flex items-center justify-center w-8 h-8 transition-colors bg-white border border-gray-200 rounded-full group-open:bg-teal-50 group-open:border-teal-100">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 group-open:rotate-180 group-open:text-teal-600"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </summary>

                            <div class="px-6 pb-6 pt-2 pl-[4.5rem]">
                                @if ($modul->deskripsi)
                                    <div class="mb-5 text-sm leading-relaxed prose-sm text-gray-600 prose-teal">
                                        {!! strip_tags($modul->deskripsi) !!}
                                    </div>
                                @endif

                                <div class="flex flex-wrap gap-3 mb-5">
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        {{ $modul->materis->count() }} Materi
                                    </div>
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        {{ $modul->tugas->count() }} Tugas
                                    </div>
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        {{ $modul->kuis->count() }} Kuis
                                    </div>
                                </div>

                                <a href="{{ route('siswa.modul.show', $modul->id) }}"
                                   class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white transition-all duration-200 bg-teal-600 rounded-lg hover:bg-teal-700 hover:shadow-lg hover:-translate-y-0.5 group/btn">
                                    Mulai Belajar
                                    <svg class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </details>
                    @empty
                        <div class="flex flex-col items-center justify-center px-4 py-16 text-center">
                            <div class="flex items-center justify-center w-16 h-16 mb-4 border border-gray-300 border-dashed rounded-full bg-gray-50">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Belum ada modul tersedia</h3>
                            <p class="max-w-sm mt-1 text-sm text-gray-500">
                                Guru belum mempublikasikan modul untuk course ini. Cek lagi nanti ya!
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        function collapseAll() {
            document.querySelectorAll('details[open]').forEach(detail => {
                detail.removeAttribute('open');
            });
        }
    </script>
</x-app-layout>
