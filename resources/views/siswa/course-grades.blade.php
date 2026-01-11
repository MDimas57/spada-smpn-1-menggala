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
                            <span class="ml-1 text-sm font-medium text-teal-600 md:ml-2">Grades</span>
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
                        <a href="#"
                           class="px-1 py-4 text-sm font-bold text-teal-600 transition-colors border-b-2 border-teal-600 whitespace-nowrap">
                            Grades
                        </a>
                        <a href="{{ route('siswa.course.competencies', $course->id) }}"
                           class="px-1 py-4 text-sm font-medium text-gray-500 transition-colors border-b-2 border-transparent hover:text-gray-800 hover:border-gray-300 whitespace-nowrap">
                            Competencies
                        </a>
                    </nav>
                </div>
            </div>

            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-800">Laporan Nilai - {{ Auth::user()->name }}</h2>
                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold text-teal-700 bg-teal-100 border border-teal-200 rounded-full">
                            NIS: {{ Auth::user()->siswa->nis ?? '-' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 p-6 border-b border-gray-200 md:grid-cols-3 bg-gray-50/50">
                    <div class="p-5 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Tugas</span>
                            <div class="p-2 text-blue-600 rounded-lg bg-blue-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-bold text-gray-800">{{ $totalTugas }}</p>
                            <p class="text-sm text-gray-400">Item</p>
                        </div>
                        <p class="flex items-center gap-1 mt-2 text-xs font-medium text-blue-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $tugasDikumpulkan }} dikumpulkan
                        </p>
                    </div>

                    <div class="p-5 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Total Kuis</span>
                            <div class="p-2 text-purple-600 rounded-lg bg-purple-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-bold text-gray-800">{{ $totalKuis }}</p>
                            <p class="text-sm text-gray-400">Item</p>
                        </div>
                        <p class="flex items-center gap-1 mt-2 text-xs font-medium text-purple-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $kuisDikerjakan }} dikerjakan
                        </p>
                    </div>

                    <div class="p-5 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md ring-1 ring-teal-100">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-semibold tracking-wide text-gray-500 uppercase">Rata-rata Nilai</span>
                            <div class="p-2 text-teal-600 rounded-lg bg-teal-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-bold text-teal-600">{{ number_format($rataRata, 1) }}</p>
                            <span class="text-sm text-gray-400">/ 100</span>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Dari semua aktivitas</p>
                    </div>
                </div>

                <div class="overflow-x-auto bg-white">
                    <table class="w-full">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Grade Item</th>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-center text-gray-500 uppercase">Tipe</th>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-center text-gray-500 uppercase">Nilai</th>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-center text-gray-500 uppercase">Range</th>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-center text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Feedback</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($grades as $modul => $items)
                                <tr class="border-l-4 border-teal-500 bg-gray-50">
                                    <td colspan="6" class="px-6 py-3">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            <span class="text-sm font-bold tracking-wide text-gray-800 uppercase">{{ $modul }}</span>
                                        </div>
                                    </td>
                                </tr>

                                @foreach($items as $item)
                                    <tr class="transition-colors hover:bg-gray-50 group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="p-1.5 rounded bg-gray-100 text-gray-400 group-hover:bg-white group-hover:text-teal-500 transition-colors">
                                                    @if($item['tipe'] === 'tugas')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    @endif
                                                </div>
                                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ $item['judul'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($item['tipe'] === 'tugas')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                    Tugas
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                                    Kuis
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($item['nilai'] !== null)
                                                <span class="text-base font-bold text-gray-800">{{ number_format($item['nilai'], 2) }}</span>
                                            @else
                                                <span class="text-xs italic text-gray-400">Belum dinilai</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-center text-gray-500">
                                            0 &ndash; 100
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($item['nilai'] !== null)
                                                @php
                                                    $percentage = $item['nilai'];
                                                    if ($percentage >= 85) {
                                                        $badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                                    } elseif ($percentage >= 70) {
                                                        $badgeClass = 'bg-cyan-100 text-cyan-800 border-cyan-200';
                                                    } elseif ($percentage >= 55) {
                                                        $badgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
                                                    } else {
                                                        $badgeClass = 'bg-rose-100 text-rose-800 border-rose-200';
                                                    }
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold rounded-full border {{ $badgeClass }}">
                                                    {{ number_format($percentage, 0) }}%
                                                </span>
                                            @else
                                                <span class="text-2xl text-gray-200">&bull;</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item['feedback'])
                                                <div class="flex items-start gap-2">
                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                                    <p class="text-sm italic text-gray-600 line-clamp-2" title="{{ $item['feedback'] }}">"{{ $item['feedback'] }}"</p>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="flex items-center justify-center w-16 h-16 mb-4 border border-gray-300 border-dashed rounded-full bg-gray-50">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <p class="text-base font-bold text-gray-900">Belum ada nilai tersedia</p>
                                            <p class="mt-1 text-sm text-gray-500">Nilai akan muncul setelah guru menilai tugas atau kuis Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(!empty($grades))
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="p-1.5 bg-teal-100 rounded text-teal-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <span class="text-lg font-bold text-gray-800">Course Total</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-500 uppercase">Total Score Range</span>
                                <p class="text-lg font-bold text-teal-700">0 &ndash; {{ $totalTugas + $totalKuis > 0 ? ($totalTugas + $totalKuis) * 100 : 100 }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>