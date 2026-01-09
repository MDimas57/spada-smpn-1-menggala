<x-filament::page>
    <div class="w-full">



        {{-- KONTEN UTAMA: Daftar Siswa --}}
        <div class="lg:col-span-4 w-full">
            <x-filament::card>

                {{-- Header Info Jadwal --}}
                @if ($selectedSchedule)
                    <div
                        class="mb-6 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg border border-primary-200 dark:border-primary-800">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-primary-900 dark:text-primary-100">
                                    {{ $selectedSchedule->mapel->nama ?? 'Mata Pelajaran' }}
                                </h3>
                                <div class="mt-2 space-y-1 text-sm text-primary-700 dark:text-primary-300">
                                    <p>📍 <strong>Kelas:</strong> {{ $selectedSchedule->kelas->nama ?? 'Kelas' }}</p>
                                    <p>👨‍🏫 <strong>Guru:</strong> {{ $selectedSchedule->guru->user->name ?? 'Guru' }}
                                    </p>
                                    <p>🕐 <strong>Waktu:</strong> {{ $selectedSchedule->jam_mulai }} -
                                        {{ $selectedSchedule->jam_selesai }}</p>
                                    <p>📅 <strong>Tanggal:</strong>
                                        {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 items-end">
                                {{-- Summary Badge --}}
                                @if (count($summary))
                                    <div class="flex gap-2 flex-wrap justify-end">
                                        @foreach ($this->categories as $cat)
                                            @if (isset($summary[$cat->id]))
                                                <div
                                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $cat->code == 'H' ? 'bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-100' : '' }}
                                                    {{ $cat->code == 'S' ? 'bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-100' : '' }}
                                                    {{ $cat->code == 'I' ? 'bg-info-100 text-info-800 dark:bg-info-900 dark:text-info-100' : '' }}
                                                    {{ $cat->code == 'A' ? 'bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-100' : '' }}">
                                                    {{ $cat->code }}: {{ $summary[$cat->id] }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Tombol Kembali --}}
                                {{-- <button
                                    wire:click="resetToScheduleList"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    ← Kembali
                                </button> --}}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Daftar Siswa --}}
                @if (count($students))
                    <div class="space-y-3">
                        @foreach ($students as $index => $s)
                            <div
                                class="p-4 border rounded-lg hover:shadow-md transition
                                dark:border-gray-700 dark:bg-gray-800/50">
                                <div class="flex items-center justify-between">

                                    {{-- Info Siswa --}}
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900
                                            flex items-center justify-center font-bold text-primary-600 dark:text-primary-300">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $s->user->name ?? ($s->nama ?? 'Nama Siswa') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                NIS: {{ $s->nis ?? '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Tombol Status --}}
                                    <div class="flex gap-2">
                                        @foreach ($this->categories as $cat)
                                            <button wire:click="setStatus({{ $s->id }}, {{ $cat->id }})"
                                                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all
                                                    {{ ($attendance[$s->id] ?? null) == $cat->id ? 'shadow-lg transform scale-105' : 'border hover:scale-105' }}
                                                    {{ $cat->code == 'H' && ($attendance[$s->id] ?? null) == $cat->id ? 'bg-success-600 text-white' : '' }}
                                                    {{ $cat->code == 'H' && ($attendance[$s->id] ?? null) != $cat->id ? 'border-success-300 text-success-700 hover:bg-success-50' : '' }}

                                                    {{ $cat->code == 'S' && ($attendance[$s->id] ?? null) == $cat->id ? 'bg-warning-600 text-white' : '' }}
                                                    {{ $cat->code == 'S' && ($attendance[$s->id] ?? null) != $cat->id ? 'border-warning-300 text-warning-700 hover:bg-warning-50' : '' }}

                                                    {{ $cat->code == 'I' && ($attendance[$s->id] ?? null) == $cat->id ? 'bg-info-600 text-white' : '' }}
                                                    {{ $cat->code == 'I' && ($attendance[$s->id] ?? null) != $cat->id ? 'border-info-300 text-info-700 hover:bg-info-50' : '' }}

                                                    {{ $cat->code == 'A' && ($attendance[$s->id] ?? null) == $cat->id ? 'bg-danger-600 text-white' : '' }}
                                                    {{ $cat->code == 'A' && ($attendance[$s->id] ?? null) != $cat->id ? 'border-danger-300 text-danger-700 hover:bg-danger-50' : '' }}
                                                ">
                                                {{ $cat->code }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Simpan & Kembali --}}
                    <div class="mt-6 flex justify-between gap-3">
                        <x-filament::button wire:click="resetToScheduleList" outlined>
                            ← Kembali
                        </x-filament::button>
                        <x-filament::button wire:click="save" color="success" size="lg">
                            💾 Simpan Absensi
                        </x-filament::button>
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-10">
                        @if ($this->todaySchedules->count())

                            <h3 class="text-2xl font-bold text-gray-100 mb-12 flex items-center justify-center gap-2">

                                📅 Jadwal Hari Ini
                            </h3>

                            <!-- GRID 2 KOLOM -->
                            <!-- GRID 2 KOLOM dengan jarak lebih lebar -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto px-2">


                                @foreach ($this->todaySchedules as $sch)
                                    <button wire:click="loadScheduleStudents({{ $sch->id }})"
                                        class="w-full flex justify-between items-center px-6 py-5 rounded-2xl
                           bg-gray-800/70 border border-gray-700
                           hover:shadow-lg hover:bg-gray-700/70
                           transition-all duration-200 text-left group">
                                        <div>
                                            <p class="font-semibold text-gray-100 text-lg">
                                                {{ $sch->mapel->nama }} — {{ $sch->kelas->nama }}
                                            </p>

                                            <p class="text-sm text-gray-400 mt-1 flex items-center gap-2">
                                                ⏰ {{ $sch->jam_mulai }} - {{ $sch->jam_selesai }}
                                                <span class="mx-1">•</span>
                                                👨‍🏫 {{ $sch->guru->user->name }}
                                            </p>
                                        </div>

                                        <svg class="w-7 h-7 text-gray-500 group-hover:text-primary-400 transition"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                @endforeach

                            </div>

                            <p class="mt-6 text-sm text-gray-400">
                                Klik jadwal untuk memuat daftar siswa dan mulai absensi.
                            </p>
                        @else
                            <div class="max-w-lg mx-auto text-center opacity-80">
                                <h3 class="text-lg font-semibold text-gray-100">
                                    Tidak Ada Jadwal Hari Ini
                                </h3>

                                <p class="mt-2 text-gray-400">
                                    Pilih kelas, mata pelajaran, dan jadwal untuk memulai absensi.
                                </p>
                            </div>

                        @endif
                    </div>


                @endif

            </x-filament::card>
        </div>
</x-filament::page>
