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
                            class="px-1 py-4 text-sm font-medium transition-colors border-b-2 border-transparent text-slate-600 hover:text-slate-800 hover:border-slate-300 whitespace-nowrap">
                            Competencies
                        </a>
                        {{-- <a href="{{ route('siswa.course.badges', $course->id) }}"
                            class="px-1 py-4 text-sm font-semibold text-indigo-600 transition-colors border-b-2 border-indigo-600 whitespace-nowrap">
                            Badges
                        </a> --}}
                    </nav>
                </div>
            </div>

            <!-- Badges Overview -->
            <div class="mb-8 overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                <div class="px-6 py-5 border-b bg-slate-50 border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800">Koleksi Badge</h2>
                    <p class="mt-1 text-sm text-slate-600">Pencapaian dan penghargaan Anda dalam course ini</p>
                </div>

                <!-- Progress Section -->
                <div class="p-6 border-b border-slate-200 bg-gradient-to-br from-amber-50 to-orange-50">
                    <div class="flex flex-col items-center gap-6 md:flex-row">
                        <div class="relative">
                            <svg class="w-32 h-32 transform -rotate-90">
                                <circle cx="64" cy="64" r="56" stroke="#fef3c7" stroke-width="8" fill="none" />
                                <circle cx="64" cy="64" r="56" stroke="url(#badgeGradient)" stroke-width="8" fill="none"
                                    stroke-dasharray="{{ 2 * 3.14159 * 56 }}"
                                    stroke-dashoffset="{{ 2 * 3.14159 * 56 * (1 - $progressPercentage / 100) }}"
                                    stroke-linecap="round" />
                                <defs>
                                    <linearGradient id="badgeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#f59e0b;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#f97316;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-bold text-slate-800">{{ $earnedCount }}/{{ $totalBadges }}</span>
                                <span class="text-xs text-slate-500">Badges</span>
                            </div>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="mb-2 text-2xl font-bold text-slate-800">Pencapaian Badge Anda</h3>
                            <p class="mb-4 text-slate-600">Anda telah mengumpulkan {{ $earnedCount }} dari {{ $totalBadges }} badge yang tersedia</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    {{ $earnedCount }} Terkumpul
                                </span>
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $totalBadges - $earnedCount }} Belum Terbuka
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Badges Grid -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($availableBadges as $badge)
                    @php
                        $colorClasses = [
                            'blue' => ['bg' => 'bg-blue-500', 'from' => 'from-blue-400', 'to' => 'to-blue-600', 'light' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-600'],
                            'amber' => ['bg' => 'bg-amber-500', 'from' => 'from-amber-400', 'to' => 'to-amber-600', 'light' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-600'],
                            'emerald' => ['bg' => 'bg-emerald-500', 'from' => 'from-emerald-400', 'to' => 'to-emerald-600', 'light' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-600'],
                            'purple' => ['bg' => 'bg-purple-500', 'from' => 'from-purple-400', 'to' => 'to-purple-600', 'light' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-600'],
                            'rose' => ['bg' => 'bg-rose-500', 'from' => 'from-rose-400', 'to' => 'to-rose-600', 'light' => 'bg-rose-50', 'border' => 'border-rose-200', 'text' => 'text-rose-600'],
                            'indigo' => ['bg' => 'bg-indigo-500', 'from' => 'from-indigo-400', 'to' => 'to-indigo-600', 'light' => 'bg-indigo-50', 'border' => 'border-indigo-200', 'text' => 'text-indigo-600'],
                        ];
                        $colors = $colorClasses[$badge['color']] ?? $colorClasses['blue'];
                    @endphp

                    <div class="relative overflow-hidden transition-all duration-300 bg-white border shadow-sm group rounded-2xl border-slate-200 {{ $badge['earned'] ? 'hover:shadow-xl hover:-translate-y-1' : 'opacity-60' }}">

                        <!-- Badge Earned Ribbon -->
                        @if($badge['earned'])
                            <div class="absolute top-0 right-0 z-10">
                                <div class="relative px-3 py-1 text-xs font-bold text-white bg-gradient-to-br {{ $colors['from'] }} {{ $colors['to'] }} rounded-bl-lg">
                                    <svg class="inline w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    EARNED
                                </div>
                            </div>
                        @endif

                        <!-- Locked Overlay -->
                        @if(!$badge['earned'])
                            <div class="absolute inset-0 z-10 flex items-center justify-center bg-slate-900/10 backdrop-blur-[2px]">
                                <div class="p-3 bg-white rounded-full shadow-lg">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif

                        <div class="p-6">
                            <!-- Badge Icon -->
                            <div class="flex justify-center mb-4">
                                <div class="relative">
                                    <div class="flex items-center justify-center w-20 h-20 bg-gradient-to-br {{ $colors['from'] }} {{ $colors['to'] }} rounded-full shadow-lg">
                                        @if($badge['icon'] === 'assignment')
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        @elseif($badge['icon'] === 'star')
                                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @elseif($badge['icon'] === 'trophy')
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                            </svg>
                                        @elseif($badge['icon'] === 'medal')
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                        @elseif($badge['icon'] === 'clock')
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @elseif($badge['icon'] === 'graduation')
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    @if($badge['earned'])
                                        <div class="absolute -top-1 -right-1">
                                            <div class="flex items-center justify-center w-6 h-6 bg-white rounded-full shadow-md">
                                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Badge Info -->
                            <div class="text-center">
                                <h3 class="mb-2 text-lg font-bold text-slate-800">{{ $badge['name'] }}</h3>
                                <p class="mb-3 text-sm text-slate-600">{{ $badge['description'] }}</p>

                                <div class="p-3 border rounded-lg {{ $colors['light'] }} {{ $colors['border'] }}">
                                    <p class="text-xs font-semibold {{ $colors['text'] }} uppercase tracking-wide mb-1">Kriteria</p>
                                    <p class="text-sm text-slate-700">{{ $badge['criteria'] }}</p>
                                </div>

                                @if($badge['earned'] && $badge['earned_date'])
                                    <div class="flex items-center justify-center gap-1 mt-3 text-xs text-slate-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Diperoleh {{ \Carbon\Carbon::parse($badge['earned_date'])->format('d M Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
