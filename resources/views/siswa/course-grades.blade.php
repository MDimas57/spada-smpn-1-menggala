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
                            class="px-1 py-4 text-sm font-semibold text-indigo-600 transition-colors border-b-2 border-indigo-600 whitespace-nowrap">
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

            <!-- User Report Section -->
            <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                <!-- Header -->
                <div class="px-6 py-5 border-b bg-slate-50 border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800">Laporan Nilai - {{ Auth::user()->name }}</h2>
                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-indigo-700 bg-indigo-100 rounded-full">
                            NIS: {{ Auth::user()->siswa->nis ?? '-' }}
                        </span>
                    </div>
                </div>

                <!-- Grade Summary Cards -->
                <div class="grid grid-cols-1 gap-6 p-6 border-b md:grid-cols-3 border-slate-200 bg-slate-50/50">
                    <div class="p-5 bg-white border rounded-xl border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-600">Total Tugas</span>
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalTugas }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $tugasDikumpulkan }} dikumpulkan</p>
                    </div>

                    <div class="p-5 bg-white border rounded-xl border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-600">Total Kuis</span>
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalKuis }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $kuisDikerjakan }} dikerjakan</p>
                    </div>

                    <div class="p-5 bg-white border rounded-xl border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-600">Rata-rata Nilai</span>
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-slate-800">{{ number_format($rataRata, 1) }}</p>
                        <p class="mt-1 text-xs text-slate-500">Dari semua penilaian</p>
                    </div>
                </div>

                <!-- Grades Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left uppercase text-slate-700">
                                    Grade Item
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-center uppercase text-slate-700">
                                    Tipe
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-center uppercase text-slate-700">
                                    Grade
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-center uppercase text-slate-700">
                                    Range
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-center uppercase text-slate-700">
                                    Percentage
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-left uppercase text-slate-700">
                                    Feedback
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($grades as $modul => $items)
                                <!-- Modul Header -->
                                <tr class="bg-slate-100">
                                    <td colspan="6" class="px-6 py-3">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                            </svg>
                                            <span class="font-bold text-slate-800">{{ $modul }}</span>
                                        </div>
                                    </td>
                                </tr>

                                @foreach($items as $item)
                                    <tr class="transition-colors hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-slate-900">{{ $item['judul'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($item['tipe'] === 'tugas')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Tugas
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Kuis
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($item['nilai'] !== null)
                                                <span class="text-lg font-bold text-slate-800">{{ number_format($item['nilai'], 2) }}</span>
                                            @else
                                                <span class="text-sm text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-center text-slate-600">
                                            0-100
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($item['nilai'] !== null)
                                                @php
                                                    $percentage = $item['nilai'];
                                                    if ($percentage >= 80) {
                                                        $colorClass = 'text-emerald-600 bg-emerald-50';
                                                    } elseif ($percentage >= 60) {
                                                        $colorClass = 'text-blue-600 bg-blue-50';
                                                    } elseif ($percentage >= 40) {
                                                        $colorClass = 'text-amber-600 bg-amber-50';
                                                    } else {
                                                        $colorClass = 'text-rose-600 bg-rose-50';
                                                    }
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full {{ $colorClass }}">
                                                    {{ number_format($percentage, 2) }}%
                                                </span>
                                            @else
                                                <span class="text-sm text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item['feedback'])
                                                <p class="text-sm text-slate-600">{{ $item['feedback'] }}</p>
                                            @else
                                                <span class="text-sm text-slate-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-slate-100">
                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-base font-medium text-slate-900">Belum ada nilai tersedia</p>
                                            <p class="mt-1 text-sm text-slate-500">Nilai akan muncul setelah guru menilai tugas atau kuis Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Course Total Footer -->
                @if(!empty($grades))
                    <div class="px-6 py-4 border-t bg-slate-50 border-slate-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="font-bold text-slate-800">Course Total</span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-600">Range: 0-{{ $totalTugas + $totalKuis > 0 ? ($totalTugas + $totalKuis) * 100 : 100 }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
