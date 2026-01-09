<div class="fi-wi-widget rounded-lg border border-gray-200 bg-white shadow-sm p-6">
    <!-- Header -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path>
            </svg>
            📅 Jadwal Pelajaran Hari Ini - {{ $hari }}
        </h3>
        <p class="text-sm text-gray-500 mt-1">Total: {{ $totalJadwal }} jadwal pelajaran</p>
    </div>

    @if($jadwals->isEmpty())
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada jadwal hari ini</h3>
            <p class="mt-1 text-sm text-gray-500">Nikmati hari istirahat Anda!</p>
        </div>
    @else
        <!-- Jadwal Cards -->
        <div class="space-y-4">
            @foreach($jadwals as $jadwal)
                <div class="relative overflow-hidden rounded-lg border border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 hover:shadow-md transition-shadow">

                    <!-- Colored Left Border -->
                    <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-blue-500 to-indigo-500"></div>

                    <div class="ml-3 flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Waktu -->
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                    🕐 {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                </span>
                            </div>

                            <!-- Info Jadwal -->
                            <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                                <div>
                                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Kelas</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $jadwal->kelas->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Mata Pelajaran</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $jadwal->mapel->nama }}</p>
                                </div>
                                <div class="hidden md:block">
                                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Guru</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $jadwal->guru->user->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Icon -->
                        <div class="ml-4 flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-200">
                                <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .fi-wi-widget {
        background: #fff;
    }
</style>
