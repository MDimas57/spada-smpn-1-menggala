<x-app-layout>
    <div class="min-h-screen py-12 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

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
                            <a href="{{ route('siswa.course.show', $course->id) }}" class="ml-1 text-sm font-medium text-gray-500 transition-colors hover:text-teal-600 md:ml-2">{{ $course->nama }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-teal-600 md:ml-2">Competencies</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                @php
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-b border-gray-200">
                    <nav class="flex px-6 -mb-px space-x-8 overflow-x-auto" aria-label="Tabs">
                        <a href="{{ route('siswa.course.show', $course->id) }}"
                           class="px-1 py-4 text-sm font-medium text-gray-500 transition-colors border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 whitespace-nowrap">
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
                        <a href="#"
                           class="px-1 py-4 text-sm font-bold text-teal-600 transition-colors border-b-2 border-teal-600 whitespace-nowrap">
                            Competencies
                        </a>
                    </nav>
                </div>
            </div>

            <div class="mb-8 overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Kompetensi Pembelajaran</h2>
                    <p class="mt-1 text-sm text-gray-500">Perkembangan kompetensi Anda dalam course ini</p>
                </div>

                <div class="p-6 border-b border-gray-200 bg-gradient-to-br from-teal-50 to-cyan-50">
                    <div class="flex flex-col items-center gap-8 md:flex-row">
                        <div class="relative flex-shrink-0">
                            <svg class="w-32 h-32 transform -rotate-90">
                                <circle cx="64" cy="64" r="56" stroke="#cbd5e1" stroke-width="8" fill="none" class="text-gray-200" />
                                <circle cx="64" cy="64" r="56" stroke="url(#gradient)" stroke-width="8" fill="none"
                                    stroke-dasharray="{{ 2 * 3.14159 * 56 }}"
                                    stroke-dashoffset="{{ 2 * 3.14159 * 56 * (1 - $overallProgress / 100) }}"
                                    stroke-linecap="round" 
                                    class="transition-all duration-1000 ease-out" />
                                <defs>
                                    <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#0d9488;stop-opacity:1" /> <stop offset="100%" style="stop-color:#0891b2;stop-opacity:1" /> </linearGradient>
                                </defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-bold text-teal-700">{{ number_format($overallProgress, 0) }}%</span>
                                <span class="text-xs font-semibold tracking-wide text-teal-500 uppercase">Progress</span>
                            </div>
                        </div>
                        
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="mb-2 text-2xl font-bold text-gray-800">Pencapaian Keseluruhan</h3>
                            <p class="mb-6 text-gray-600">Anda telah menyelesaikan <span class="font-bold text-teal-600">{{ $completedCompetencies }}</span> dari {{ $totalCompetencies }} kompetensi yang tersedia.</p>
                            
                            <div class="flex flex-wrap justify-center gap-3 md:justify-start">
                                <div class="inline-flex items-center px-4 py-2 text-xs font-medium bg-white border shadow-sm rounded-xl border-emerald-200 text-emerald-700">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <div class="flex flex-col items-start">
                                        <span class="text-lg font-bold leading-none">{{ $completedCompetencies }}</span>
                                        <span class="text-[10px] uppercase tracking-wide opacity-80">Selesai</span>
                                    </div>
                                </div>
                                
                                <div class="inline-flex items-center px-4 py-2 text-xs font-medium bg-white border shadow-sm rounded-xl border-cyan-200 text-cyan-700">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                    <div class="flex flex-col items-start">
                                        <span class="text-lg font-bold leading-none">{{ $inProgressCompetencies }}</span>
                                        <span class="text-[10px] uppercase tracking-wide opacity-80">Proses</span>
                                    </div>
                                </div>

                                <div class="inline-flex items-center px-4 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-200 shadow-sm rounded-xl">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    <div class="flex flex-col items-start">
                                        <span class="text-lg font-bold leading-none">{{ $notStartedCompetencies }}</span>
                                        <span class="text-[10px] uppercase tracking-wide opacity-80">Pending</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @forelse($competenciesByModule as $moduleName => $competencies)
                    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 text-teal-600 bg-white border border-teal-100 rounded-lg shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">{{ $moduleName }}</h3>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium text-teal-700 border border-teal-100 rounded-full bg-teal-50">
                                    {{ count($competencies) }} Kompetensi
                                </span>
                            </div>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach($competencies as $competency)
                                <div class="p-6 transition-colors hover:bg-gray-50/80">
                                    <div class="flex flex-col gap-5 md:flex-row md:items-start">
                                        <div class="flex-shrink-0">
                                            @if($competency['status'] === 'completed')
                                                <div class="flex items-center justify-center w-12 h-12 border-2 rounded-full shadow-sm bg-emerald-50 border-emerald-200">
                                                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @elseif($competency['status'] === 'in_progress')
                                                <div class="flex items-center justify-center w-12 h-12 border-2 rounded-full shadow-sm border-cyan-200 bg-cyan-50">
                                                    <svg class="w-6 h-6 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center w-12 h-12 border-2 border-gray-200 rounded-full bg-gray-50">
                                                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-wrap items-start justify-between gap-3 mb-2">
                                                <h4 class="text-base font-bold text-gray-800">{{ $competency['title'] }}</h4>
                                                @if($competency['status'] === 'completed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 whitespace-nowrap">
                                                        Tercapai
                                                    </span>
                                                @elseif($competency['status'] === 'in_progress')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-cyan-100 text-cyan-800 border border-cyan-200 whitespace-nowrap">
                                                        Dalam Progress
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 whitespace-nowrap">
                                                        Belum Dimulai
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="mb-4 text-sm leading-relaxed text-gray-600">{{ $competency['description'] }}</p>

                                            <div class="mb-4">
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">Progress</span>
                                                    <span class="text-xs font-bold text-gray-800">{{ $competency['progress'] }}%</span>
                                                </div>
                                                <div class="w-full h-2.5 overflow-hidden rounded-full bg-gray-100 border border-gray-200">
                                                    <div class="h-full transition-all duration-700 ease-out rounded-full
                                                        @if($competency['progress'] >= 80) bg-emerald-500
                                                        @elseif($competency['progress'] >= 50) bg-cyan-500
                                                        @elseif($competency['progress'] > 0) bg-amber-400
                                                        @else bg-gray-300
                                                        @endif"
                                                        style="width: {{ $competency['progress'] }}%">
                                                    </div>
                                                </div>
                                            </div>

                                            @if(!empty($competency['activities']))
                                                <div class="p-3.5 border rounded-xl bg-gray-50 border-gray-200/60">
                                                    <p class="mb-2 text-xs font-bold tracking-wide text-gray-500 uppercase">Aktivitas Terkait:</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($competency['activities'] as $activity)
                                                            <span class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium bg-white border rounded-lg text-gray-700 border-gray-200 shadow-sm transition-shadow hover:shadow-md">
                                                                @if($activity['type'] === 'tugas')
                                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                        <div class="flex flex-col items-center justify-center px-4 py-16 text-center">
                            <div class="flex items-center justify-center w-16 h-16 mb-4 border border-gray-300 border-dashed rounded-full bg-gray-50">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <p class="text-base font-bold text-gray-900">Belum ada kompetensi yang ditetapkan</p>
                            <p class="mt-1 text-sm text-gray-500">Guru akan menetapkan kompetensi untuk course ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>