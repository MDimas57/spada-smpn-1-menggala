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
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            My Courses
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
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
                                <pattern id="header-hexagons" x="0" y="0" width="56" height="100" patternUnits="userSpaceOnUse">
                                    <polygon points="28,0 56,16.66 56,50 28,66.66 0,50 0,16.66" fill="white" fill-opacity="0.3" />
                                    <polygon points="28,66.66 56,83.32 56,116.66 28,133.32 0,116.66 0,83.32" fill="white" fill-opacity="0.3" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#header-hexagons)" />
                        </svg>
                    </div>

                    <div class="absolute top-0 right-0 w-64 h-64 -mt-16 -mr-16 rounded-full bg-white/10 blur-3xl"></div>

                    <div class="relative">
                        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                            <div class="flex-1">
                                <div class="inline-flex items-center px-3 py-1 mb-3 text-xs font-bold tracking-wider text-white uppercase bg-white rounded-full bg-opacity-30 backdrop-blur-sm">
                                    {{ $course->mapel->nama ?? 'Mata Pelajaran' }}
                                </div>
                                <h1 class="mb-2 text-3xl font-bold text-white">{{ $course->nama }}</h1>
                                <p class="max-w-2xl text-indigo-100">
                                    {!! strip_tags($course->deskripsi) ?: 'Selamat datang di course ini! Jelajahi semua modul pembelajaran yang tersedia.' !!}
                                </p>
                                <div class="flex items-center gap-3 mt-4">
                                    <div class="flex items-center gap-2 text-white">
                                        <div class="flex items-center justify-center w-8 h-8 border border-white rounded-full bg-white/20">
                                            {{ substr($course->guru->user->name ?? 'G', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium">{{ $course->guru->user->name ?? 'Guru' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="border-b border-slate-200 bg-slate-50/50">
                    <nav class="flex px-6 -mb-px space-x-8" aria-label="Tabs">
                        <a href="{{ route('siswa.course.show', $course->id) }}"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
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
                            class="px-1 py-4 text-sm font-semibold text-indigo-600 transition-colors border-b-2 border-indigo-600 whitespace-nowrap">
                            Competencies
                        </a>
                        {{-- <a href="{{ route('siswa.course.badges', $course->id) }}"
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Badges
                        </a> --}}
                    </nav>
                </div>
            </div>

            <!-- Competencies Overview -->
            <div class="mb-8 overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                <div class="px-6 py-5 border-b bg-slate-50 border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800">Kompetensi Pembelajaran</h2>
                    <p class="mt-1 text-sm text-slate-600">Perkembangan kompetensi Anda dalam course ini</p>
                </div>

                <!-- Overall Progress -->
                <div class="p-6 border-b border-slate-200 bg-gradient-to-br from-indigo-50 to-purple-50">
                    <div class="flex flex-col items-center gap-6 md:flex-row">
                        <div class="relative">
                            <svg class="w-32 h-32 transform -rotate-90">
                                <circle cx="64" cy="64" r="56" stroke="#e2e8f0" stroke-width="8" fill="none" />
                                <circle cx="64" cy="64" r="56" stroke="url(#gradient)" stroke-width="8" fill="none"
                                    stroke-dasharray="{{ 2 * 3.14159 * 56 }}"
                                    stroke-dashoffset="{{ 2 * 3.14159 * 56 * (1 - $overallProgress / 100) }}"
                                    stroke-linecap="round" />
                                <defs>
                                    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#6366f1;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-bold text-slate-800">{{ number_format($overallProgress, 0) }}%</span>
                                <span class="text-xs text-slate-500">Progress</span>
                            </div>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="mb-2 text-2xl font-bold text-slate-800">Pencapaian Keseluruhan</h3>
                            <p class="mb-4 text-slate-600">Anda telah menyelesaikan {{ $completedCompetencies }} dari {{ $totalCompetencies }} kompetensi</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $completedCompetencies }} Selesai
                                </span>
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $inProgressCompetencies }} Dalam Progress
                                </span>
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $notStartedCompetencies }} Belum Dimulai
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Competencies List by Module -->
            <div class="space-y-6">
                @forelse($competenciesByModule as $moduleName => $competencies)
                    <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <!-- Module Header -->
                        <div class="px-6 py-4 border-b bg-slate-50 border-slate-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 text-indigo-600 bg-white border rounded-lg border-slate-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">{{ $moduleName }}</h3>
                                </div>
                                <span class="text-sm font-medium text-slate-500">
                                    {{ count($competencies) }} Kompetensi
                                </span>
                            </div>
                        </div>

                        <!-- Competencies Items -->
                        <div class="divide-y divide-slate-200">
                            @foreach($competencies as $competency)
                                <div class="p-6 transition-colors hover:bg-slate-50">
                                    <div class="flex flex-col gap-4 md:flex-row md:items-start">
                                        <!-- Icon & Status -->
                                        <div class="flex-shrink-0">
                                            @if($competency['status'] === 'completed')
                                                <div class="flex items-center justify-center w-12 h-12 border-2 rounded-full bg-emerald-50 border-emerald-200">
                                                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @elseif($competency['status'] === 'in_progress')
                                                <div class="flex items-center justify-center w-12 h-12 border-2 border-blue-200 rounded-full bg-blue-50">
                                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center w-12 h-12 border-2 rounded-full bg-slate-50 border-slate-200">
                                                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between gap-4 mb-2">
                                                <h4 class="text-base font-semibold text-slate-800">{{ $competency['title'] }}</h4>
                                                @if($competency['status'] === 'completed')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 whitespace-nowrap">
                                                        Tercapai
                                                    </span>
                                                @elseif($competency['status'] === 'in_progress')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 whitespace-nowrap">
                                                        Dalam Progress
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 whitespace-nowrap">
                                                        Belum Dimulai
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="mb-3 text-sm text-slate-600">{{ $competency['description'] }}</p>

                                            <!-- Progress Bar -->
                                            <div class="mb-3">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-xs font-medium text-slate-700">Progress Kompetensi</span>
                                                    <span class="text-xs font-bold text-slate-800">{{ $competency['progress'] }}%</span>
                                                </div>
                                                <div class="w-full h-2 overflow-hidden rounded-full bg-slate-200">
                                                    <div class="h-full transition-all duration-500 rounded-full
                                                        @if($competency['progress'] >= 80) bg-emerald-500
                                                        @elseif($competency['progress'] >= 50) bg-blue-500
                                                        @elseif($competency['progress'] > 0) bg-amber-500
                                                        @else bg-slate-300
                                                        @endif"
                                                        style="width: {{ $competency['progress'] }}%">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Related Activities -->
                                            @if(!empty($competency['activities']))
                                                <div class="p-3 border rounded-lg bg-slate-50 border-slate-200">
                                                    <p class="mb-2 text-xs font-semibold text-slate-700">Aktivitas Terkait:</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($competency['activities'] as $activity)
                                                            <span class="inline-flex items-center px-2 py-1 text-xs bg-white border rounded text-slate-600 border-slate-200">
                                                                @if($activity['type'] === 'tugas')
                                                                    <svg class="w-3 h-3 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-3 h-3 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                @endif
                                                                {{ $activity['name'] }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="flex flex-col items-center justify-center px-4 py-16 text-center">
                            <div class="flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-slate-100">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <p class="text-base font-medium text-slate-900">Belum ada kompetensi yang ditetapkan</p>
                            <p class="mt-1 text-sm text-slate-500">Guru akan menetapkan kompetensi untuk course ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
