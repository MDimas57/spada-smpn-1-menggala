<x-app-layout>
    <div class="min-h-screen py-12 bg-slate-50/50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('siswa.my-courses') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-indigo-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            My Courses
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">{{ $course->nama }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Course Header with Banner -->
            <div class="mb-8 overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                @php
                    $headerGradient = 'from-indigo-600 to-violet-700';
                @endphp

                <div class="relative p-8 bg-gradient-to-br {{ $headerGradient }}">
                    <!-- Hexagon Pattern -->
                    <div class="absolute inset-0 opacity-20">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="header-hexagons" x="0" y="0" width="56" height="100"
                                    patternUnits="userSpaceOnUse">
                                    <polygon points="28,0 56,16.66 56,50 28,66.66 0,50 0,16.66" fill="white"
                                        fill-opacity="0.3" />
                                    <polygon points="28,66.66 56,83.32 56,116.66 28,133.32 0,116.66 0,83.32"
                                        fill="white" fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#header-hexagons)" />
                        </svg>
                    </div>

                    <div class="absolute top-0 right-0 w-64 h-64 -mt-16 -mr-16 rounded-full bg-white/10 blur-3xl"></div>

                    <div class="relative">
                        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                            <div class="flex-1">
                                <div
                                    class="inline-flex items-center px-3 py-1 mb-3 text-xs font-bold tracking-wider text-white uppercase bg-white rounded-full bg-opacity-30 backdrop-blur-sm">
                                    {{ $course->mapel->nama ?? 'Mata Pelajaran' }}
                                </div>
                                <h1 class="mb-2 text-3xl font-bold text-white">{{ $course->nama }}</h1>
                                <p class="max-w-2xl text-indigo-100">
                                    {!! strip_tags($course->deskripsi) ?:
                                        'Selamat datang di course ini! Jelajahi semua modul pembelajaran yang tersedia.' !!}
                                </p>
                                <div class="flex items-center gap-3 mt-4">
                                    <div class="flex items-center gap-2 text-white">
                                        <div
                                            class="flex items-center justify-center w-8 h-8 border border-white rounded-full bg-white/20">
                                            {{ substr($course->guru->user->name ?? 'G', 0, 1) }}
                                        </div>
                                        <span
                                            class="text-sm font-medium">{{ $course->guru->user->name ?? 'Guru' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-4 p-4 border border-white rounded-lg bg-white/10 backdrop-blur-sm">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-white">{{ $moduls->count() }}</p>
                                    <p class="text-xs text-indigo-200">Modul Tersedia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="border-b border-slate-200 bg-slate-50/50">
                    <nav class="flex px-6 -mb-px space-x-8" aria-label="Tabs">
                        <a href="#"
                            class="px-1 py-4 text-sm font-semibold text-indigo-600 transition-colors border-b-2 border-indigo-600 whitespace-nowrap">
                            Course
                        </a>
                        <a href="{{ route('siswa.course.participants', $course->id) }}"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Participants
                        </a>
                        <a href="{{ route('siswa.course.grades', $course->id) }}"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Grades
                        </a>
                        <a href="{{ route('siswa.course.competencies', $course->id) }}"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Competencies
                        </a>
                        {{-- <a href="{{ route('siswa.course.badges', $course->id) }}"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Badges
                        </a> --}}
                    </nav>
                </div>
            </div>

            <!-- Module List - Accordion Style -->
            <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b bg-slate-50 border-slate-200">
                    <h2 class="text-lg font-bold text-slate-800">Daftar Modul Pembelajaran</h2>
                    <button onclick="collapseAll()" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Collapse all
                    </button>
                </div>

                <!-- Accordion List -->
                <div class="divide-y divide-slate-200">
                    @forelse($moduls as $index => $modul)
                        <details class="group">
                            <summary
                                class="flex items-center justify-between px-6 py-4 transition-colors cursor-pointer hover:bg-slate-50">
                                <div class="flex items-center flex-1 gap-3">
                                    <!-- Chevron Icon -->
                                    <svg class="w-5 h-5 transition-transform text-slate-400 group-open:rotate-90"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>

                                    <!-- Module Title -->
                                    <h3 class="text-base font-semibold text-slate-800 group-hover:text-indigo-600">
                                        {{ $modul->judul }}
                                    </h3>
                                </div>

                                <!-- Module Meta (on collapsed state) -->
                                <div class="flex items-center gap-4 ml-4">
                                    <span class="text-xs text-slate-400">
                                        {{ $modul->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </summary>

                            <!-- Expanded Content -->
                            <div class="px-6 py-4 bg-slate-50/50">
                                <div class="pl-8">
                                    <!-- Module Description -->
                                    @if ($modul->deskripsi)
                                        <div class="mb-4 text-sm text-slate-600">
                                            {!! strip_tags($modul->deskripsi) !!}
                                        </div>
                                    @endif

                                    <!-- Module Stats -->
                                    <div class="flex flex-wrap gap-4 mb-4">
                                        <div class="flex items-center gap-2 text-sm text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                            <span>{{ $modul->materis->count() }} Materi</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                </path>
                                            </svg>
                                            <span>{{ $modul->tugas->count() }} Tugas</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <span>{{ $modul->kuis->count() }} Kuis</span>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <a href="{{ route('siswa.modul.show', $modul->id) }}"
                                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white transition-all duration-200 bg-indigo-600 rounded-lg hover:bg-indigo-700 hover:shadow-md">
                                        Mulai Belajar
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </details>
                    @empty
                        <div class="flex flex-col items-center justify-center px-4 py-16 text-center">
                            <div class="flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-indigo-50">
                                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-slate-900">Belum ada modul tersedia</h3>
                            <p class="max-w-sm mt-1 text-sm text-slate-500">
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
