<x-filament-panels::page>
    @php
        $user = auth()->user();
        $isGuru = $user->hasRole('guru');
        $guruId = $isGuru && $user->guru ? $user->guru->id : null;
        $guruName = $isGuru && $user->guru ? $user->name : '';

        // Filter jadwal jika guru
        $jadwals = $record->jadwals;
        if ($isGuru && $guruId) {
            $jadwals = $jadwals->where('guru_id', $guruId);
        }

        // Definisi hari
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Ambil semua jam mulai yang unik dan sort
        $allTimes = $jadwals->pluck('jam_mulai')->unique()->sort()->values();
        $timeSlots = [];

        foreach ($allTimes as $time) {
            $timeSlots[] = substr($time, 0, 5); // Ambil HH:MM saja
        }

        // Kelompokkan jadwal berdasarkan hari dan jam
        $scheduleGrid = [];
        foreach ($jadwals as $j) {
            $key = $j->hari . '_' . substr($j->jam_mulai, 0, 5);
            $scheduleGrid[$key] = $j;
        }

        // Tentukan waktu istirahat (bisa disesuaikan)
        $istirahat = [
            ['after' => '09:15', 'label' => 'Istirahat 1', 'time' => '09:15 - 09:35'],
            ['after' => '11:50', 'label' => 'Istirahat 2', 'time' => '11:50 - 12:10'],
        ];
    @endphp

    <style>
        .schedule-cell {
            min-height: 60px;
            vertical-align: middle;
        }

        @media print {
            /* Hide Filament UI elements */
            .fi-sidebar,
            .fi-topbar,
            .fi-header,
            .no-print,
            button,
            .fi-breadcrumbs {
                display: none !important;
            }

            /* Reset page margins */
            @page {
                size: A4 landscape;
                margin: 1cm;
            }

            body {
                margin: 0;
                padding: 0;
                background: white !important;
            }

            /* Show print header */
            .print-header {
                display: block !important;
                page-break-after: avoid;
            }

            /* Ensure full width */
            .fi-main {
                margin: 0 !important;
                padding: 0 !important;
            }

            .fi-page {
                padding: 0 !important;
                max-width: 100% !important;
            }

            /* Table styling for print */
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10pt;
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            td, th {
                padding: 8px 4px !important;
                border: 2px solid #000 !important;
            }

            .schedule-cell {
                min-height: 50px;
            }

            /* Colors for print */
            .bg-gray-200,
            .bg-gray-100 {
                background-color: #f3f4f6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .bg-yellow-100 {
                background-color: #fef3c7 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .bg-gray-50 {
                background-color: #f9fafb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Text colors */
            .text-gray-900,
            .text-gray-800 {
                color: #000 !important;
            }

            .text-gray-600,
            .text-gray-500 {
                color: #4b5563 !important;
            }

            /* Footer */
            .print-footer {
                display: block !important;
                margin-top: 20px;
                page-break-inside: avoid;
            }
        }

        /* Hide print header on screen */
        .print-header {
            display: none;
        }

        @media screen {
            .print-footer {
                display: none;
            }
        }
    </style>

    <!-- Print Header (only visible when printing) -->
    <div class="print-header">
        <div style="text-align: center; margin-bottom: 20px; padding: 20px 0; border-bottom: 3px solid #000;">
            <h1 style="margin: 0; font-size: 18pt; font-weight: bold; text-transform: uppercase;">
                JADWAL PELAJARAN KELAS {{ $record->nama }}
            </h1>
            @if ($isGuru)
                <h2 style="margin: 10px 0 5px 0; font-size: 14pt; font-weight: 600;">
                    Guru: {{ $guruName }}
                </h2>
            @endif
            <p style="margin: 5px 0 0 0; font-size: 11pt;">
                Tahun Pelajaran {{ now()->format('Y') }}/{{ now()->addYear()->format('Y') }}
            </p>
        </div>
    </div>

    <!-- Screen Header (hidden when printing) -->
    <div class="mb-6 no-print">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-2">
                JADWAL PELAJARAN KELAS {{ strtoupper($record->nama) }}
            </h2>
            @if ($isGuru)
                <h3 class="text-lg font-semibold text-center text-gray-700 dark:text-gray-300 mb-2">
                    Guru: {{ $guruName }}
                </h3>
            @endif
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                Tahun Pelajaran {{ now()->format('Y') }}/{{ now()->addYear()->format('Y') }}
            </p>
        </div>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow">
        <table class="w-full border-2 border-gray-800 dark:border-gray-600">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="border-2 border-gray-800 dark:border-gray-600 p-3 text-center font-bold text-gray-900 dark:text-white"
                        style="min-width: 130px; width: 130px;">
                        Waktu
                    </th>
                    @foreach ($hariList as $hari)
                        <th class="border-2 border-gray-800 dark:border-gray-600 p-3 text-center font-bold text-gray-900 dark:text-white"
                            style="min-width: 150px;">
                            {{ $hari }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($timeSlots as $index => $time)
                    <tr>
                        {{-- Kolom Waktu --}}
                        <td class="border-2 border-gray-800 dark:border-gray-600 p-3 text-center font-semibold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 schedule-cell">
                            @php
                                // Cari jadwal dengan jam mulai ini untuk mendapatkan jam selesai
                                $sampleJadwal = $jadwals->firstWhere(function($j) use ($time) {
                                    return substr($j->jam_mulai, 0, 5) === $time;
                                });
                                $endTime = $sampleJadwal ? substr($sampleJadwal->jam_selesai, 0, 5) : '';
                            @endphp
                            <div class="text-sm font-bold">{{ $time }}</div>
                            @if ($endTime)
                                <div class="text-xs text-gray-600 dark:text-gray-400">{{ $endTime }}</div>
                            @endif
                        </td>

                        {{-- Kolom untuk setiap hari --}}
                        @foreach ($hariList as $hari)
                            @php
                                $key = $hari . '_' . $time;
                                $jadwal = $scheduleGrid[$key] ?? null;
                            @endphp

                            <td class="border-2 border-gray-800 dark:border-gray-600 p-2 text-center schedule-cell {{ $jadwal ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-850' }}">
                                @if ($jadwal)
                                    <div class="flex flex-col items-center justify-center h-full">
                                        <div class="font-bold text-gray-900 dark:text-white text-base mb-1">
                                            {{ $jadwal->mapel->nama }}
                                        </div>
                                        @if (!$isGuru)
                                            <div class="text-xs text-gray-600 dark:text-gray-400 italic">
                                                {{ $jadwal->guru->user->name ?? '-' }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-gray-400 dark:text-gray-600 text-xs">-</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    {{-- Baris Istirahat --}}
                    @foreach ($istirahat as $rest)
                        @if ($time == $rest['after'])
                            <tr class="bg-yellow-100 dark:bg-yellow-900/30">
                                <td class="border-2 border-gray-800 dark:border-gray-600 p-2 text-center font-bold text-gray-900 dark:text-white text-sm">
                                    {{ $rest['time'] }}
                                </td>
                                <td colspan="6" class="border-2 border-gray-800 dark:border-gray-600 p-2 text-center font-bold text-gray-900 dark:text-white">
                                    {{ $rest['label'] }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach

                @if (count($timeSlots) == 0)
                    <tr>
                        <td colspan="7" class="border-2 border-gray-800 dark:border-gray-600 p-8 text-center text-gray-500 dark:text-gray-400">
                            <div class="text-lg mb-2">📅</div>
                            <div>Belum ada jadwal untuk kelas ini</div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Print Footer (only visible when printing) -->
    <div class="print-footer">
        <table style="width: 100%; margin-top: 30px; border: none;">
            <tr>
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div style="text-align: left;">
                        <p style="margin: 0; font-size: 10pt;">Mengetahui,</p>
                        <p style="margin: 5px 0 0 0; font-weight: bold; font-size: 10pt;">Kepala Sekolah</p>
                        <div style="height: 60px;"></div>
                        <p style="margin: 0; font-weight: bold; border-top: 1px solid #000; display: inline-block; padding-top: 2px; font-size: 10pt;">
                            _______________________
                        </p>
                    </div>
                </td>
                <td style="width: 50%; border: none; vertical-align: top;">
                    <div style="text-align: right;">
                        <p style="margin: 0; font-size: 10pt;">Negeri Katon, {{ now()->format('d F Y') }}</p>
                        @if ($isGuru)
                            <p style="margin: 5px 0 0 0; font-weight: bold; font-size: 10pt;">Guru Mata Pelajaran</p>
                            <div style="height: 60px;"></div>
                            <p style="margin: 0; font-weight: bold; border-top: 1px solid #000; display: inline-block; padding-top: 2px; font-size: 10pt;">
                                {{ $guruName }}
                            </p>
                        @else
                            <p style="margin: 5px 0 0 0; font-weight: bold; font-size: 10pt;">Wali Kelas</p>
                            <div style="height: 60px;"></div>
                            <p style="margin: 0; font-weight: bold; border-top: 1px solid #000; display: inline-block; padding-top: 2px; font-size: 10pt;">
                                _______________________
                            </p>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-6 no-print">
        @if ($isGuru)
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-lg mb-4">
                <div class="flex items-start">
                    <div class="text-blue-500 mr-3 mt-0.5">ℹ️</div>
                    <div class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>Informasi:</strong> Anda hanya melihat jadwal mata pelajaran yang Anda ajar di kelas ini.
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="text-xs text-gray-500 dark:text-gray-400">
                <p class="mb-1">📅 Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
                <p class="text-gray-400 dark:text-gray-500">Gunakan orientasi <strong>Landscape</strong> untuk hasil cetak terbaik</p>
            </div>
            <button onclick="window.print()"
                    class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg hover:shadow-lg transition-all duration-200 flex items-center gap-2 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Jadwal
            </button>
        </div>
    </div>
</x-filament-panels::page>
