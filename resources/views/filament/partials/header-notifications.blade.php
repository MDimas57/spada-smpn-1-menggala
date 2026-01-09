{{-- resources/views/filament/partials/header-notifications.blade.php --}}
@php
    use App\Models\Kelas;
    use App\Models\Siswa;
    use App\Models\PengumpulanTugas;

    $alerts = [];

    if (auth()->user()->hasRole('admin')) {
        $kelasTanpaWali = Kelas::whereDoesntHave('waliKelas')->count();
        $siswaBelumKelas = Siswa::whereNull('kelas_id')->count();

        if ($kelasTanpaWali > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Kelas Tanpa Wali',
                'description' => 'Segera assign wali kelas',
                'count' => $kelasTanpaWali,
                'url' => route('filament.admin.resources.wali-kelas.index'),
            ];
        }

        if ($siswaBelumKelas > 0) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Siswa Belum Masuk Kelas',
                'description' => 'Masukkan siswa ke kelas',
                'count' => $siswaBelumKelas,
                'url' => route('filament.admin.resources.siswas.index'),
            ];
        }
    }

    if (auth()->user()->hasRole('guru')) {
        $guruId = auth()->user()->guru?->id;

        if ($guruId) {
            $tugasBelumDikoreksi = PengumpulanTugas::whereNull('nilai')
                ->whereHas('tugas', function($q) use ($guruId) {
                    $q->whereHas('modul', function($m) use ($guruId) {
                        $m->where('guru_id', $guruId);
                    });
                })
                ->count();

            if ($tugasBelumDikoreksi > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'Tugas Menunggu Koreksi',
                    'description' => 'Koreksi tugas siswa',
                    'count' => $tugasBelumDikoreksi,
                    'url' => '#',
                ];
            }
        }
    }

    $totalAlerts = count($alerts);
@endphp

@if($totalAlerts > 0)
    <div x-data="{ open: false }" class="relative">
        {{-- Bell Icon Button --}}
        <button
            @click="open = !open"
            type="button"
            class="relative flex items-center justify-center w-9 h-9 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>

            {{-- Notification Badge --}}
            <span class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $totalAlerts }}
            </span>
        </button>

        {{-- Dropdown Notifications --}}
        <div
            x-show="open"
            @click.away="open = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute right-0 z-50 mt-2 w-96 origin-top-right rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            style="display: none; transform: translateX(-150px);"
        >
            {{-- Header --}}
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                    Notifikasi ({{ $totalAlerts }})
                </h3>
            </div>

            {{-- Notifications List --}}
            <div class="py-1">
                @foreach($alerts as $alert)
                    <a
                        href="{{ $alert['url'] }}"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
                    >
                        {{-- Icon --}}
                        @if($alert['type'] === 'warning')
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $alert['title'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                {{ $alert['description'] }}
                            </p>
                        </div>

                        {{-- Count Badge --}}
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full flex-shrink-0">
                            {{ $alert['count'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
