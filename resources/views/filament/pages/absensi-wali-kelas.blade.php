<x-filament::page>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- SIDEBAR: Pengaturan --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Card Info Wali Kelas --}}
            @if(!$this->isAdmin && $this->waliKelasInfo)
                <x-filament::card>
                    <div class="text-center p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg border border-primary-200 dark:border-primary-800">
                        <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Wali Kelas</p>
                        <p class="text-lg font-bold text-primary-900 dark:text-primary-100 mt-1">
                            {{ $this->waliKelasInfo->nama }}
                        </p>
                    </div>
                </x-filament::card>
            @endif

            {{-- Card Pilih Kelas (Admin) --}}
            @if($this->isAdmin)
                <x-filament::card>
                    <h3 class="text-lg font-semibold mb-3">📚 Pilih Kelas</h3>
                    <x-filament::input.wrapper>
                        <select wire:model.live="class_id"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($this->availableClasses as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </x-filament::input.wrapper>
                </x-filament::card>
            @endif

            {{-- Card Tipe Absensi --}}
            <x-filament::card>
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-lg font-semibold">📋 Tipe Absensi</h3>

                    {{-- Toggle Auto/Manual --}}
                    <button
                        wire:click="toggleAutoMode"
                        class="text-xs px-2 py-1 rounded-full font-medium transition
                            {{ $isAutoMode
                                ? 'bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300'
                                : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'
                            }}">
                        {{ $isAutoMode ? '🤖 Auto' : '✋ Manual' }}
                    </button>
                </div>

                {{-- Info Auto Mode --}}
                @if($isAutoMode)
                    <div class="mb-3 p-2 bg-info-50 dark:bg-info-900/20 rounded text-xs text-info-700 dark:text-info-300">
                        <strong>Mode Otomatis:</strong>
                        <br>
                        06:00-11:59 → Masuk
                        <br>
                        12:00-23:59 → Pulang
                        <br>
                        <span class="text-[10px] opacity-75">Sekarang jam: {{ $this->currentHour }}:xx</span>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-2">
                    <button
                        wire:click="$set('check_type', 'masuk')"
                        {{ $isAutoMode ? 'disabled' : '' }}
                        class="px-4 py-3 rounded-lg text-sm font-semibold transition-all
                            {{ $check_type == 'masuk'
                                ? 'bg-success-600 text-white shadow-lg transform scale-105'
                                : 'border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'
                            }}
                            {{ $isAutoMode ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <div class="text-center">
                            <div class="text-2xl mb-1">🌅</div>
                            <div>Masuk</div>
                        </div>
                    </button>
                    <button
                        wire:click="$set('check_type', 'pulang')"
                        {{ $isAutoMode ? 'disabled' : '' }}
                        class="px-4 py-3 rounded-lg text-sm font-semibold transition-all
                            {{ $check_type == 'pulang'
                                ? 'bg-warning-600 text-white shadow-lg transform scale-105'
                                : 'border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'
                            }}
                            {{ $isAutoMode ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <div class="text-center">
                            <div class="text-2xl mb-1">🌆</div>
                            <div>Pulang</div>
                        </div>
                    </button>
                </div>
            </x-filament::card>

            {{-- Card Info Tanggal --}}
            <x-filament::card>
                <div class="text-center p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mt-1">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd') }}
                    </p>
                    <p class="text-lg font-bold text-primary-600 dark:text-primary-400 mt-1">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i') }}
                    </p>
                </div>
            </x-filament::card>

            {{-- Summary --}}
            @if(count($summary) && count($students))
                <x-filament::card>
                    <h3 class="text-sm font-semibold mb-3 text-gray-700 dark:text-gray-300">📊 Ringkasan</h3>
                    <div class="space-y-2">
                        @foreach($this->categories as $cat)
                            @if(isset($summary[$cat->id]))
                                <div class="flex justify-between items-center p-2 rounded
                                    {{ $cat->code == 'H' ? 'bg-success-50 dark:bg-success-900/20' : '' }}
                                    {{ $cat->code == 'S' ? 'bg-warning-50 dark:bg-warning-900/20' : '' }}
                                    {{ $cat->code == 'I' ? 'bg-info-50 dark:bg-info-900/20' : '' }}
                                    {{ $cat->code == 'A' ? 'bg-danger-50 dark:bg-danger-900/20' : '' }}">
                                    <span class="text-sm font-medium">{{ $cat->name }}</span>
                                    <span class="font-bold text-lg">{{ $summary[$cat->id] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </x-filament::card>
            @endif

        </div>

        {{-- KONTEN UTAMA: Daftar Siswa --}}
        <div class="lg:col-span-3">
            <x-filament::card>

                {{-- Header --}}
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            👥 Daftar Siswa
                        </h3>
                        @if($class_id)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Absensi <strong class="text-primary-600">{{ ucfirst($check_type) }}</strong>
                                @if($isAutoMode)
                                    <span class="ml-2 text-xs px-2 py-0.5 bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300 rounded-full">
                                        🤖 Auto
                                    </span>
                                @endif
                            </p>
                        @endif
                    </div>

                    @if(count($students))
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Total Siswa
                            </p>
                            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                {{ count($students) }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Daftar Siswa --}}
                @if(count($students))
                    <div class="space-y-3 mb-6">
                        @foreach($students as $index => $s)
                            <div class="p-4 border rounded-lg hover:shadow-md transition dark:border-gray-700 dark:bg-gray-800/50">
                                <div class="flex items-center justify-between">

                                    {{-- Info Siswa --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900
                                            flex items-center justify-center font-bold text-primary-600 dark:text-primary-300">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $s->user->name ?? $s->nama ?? 'Nama Siswa' }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                NIS: {{ $s->nis ?? '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Tombol Status & Simpan --}}
                                    <div class="flex gap-2 items-center">
                                        @foreach($this->categories as $cat)
                                            <button
                                                wire:click="setStatus({{ $s->id }}, {{ $cat->id }})"
                                                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all
                                                    {{ ($attendance[$s->id] ?? null) == $cat->id
                                                        ? 'shadow-lg transform scale-105'
                                                        : 'border hover:scale-105'
                                                    }}
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

                                        {{-- Tombol Simpan Individual --}}
                                        <button
                                            wire:click="saveCheck({{ $s->id }})"
                                            class="ml-2 px-3 py-2 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700 transition shadow-md">
                                            ✓
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Simpan Semua --}}
                    <div class="flex justify-end">
                        <x-filament::button wire:click="saveAll" color="success" size="lg">
                            💾 Simpan Semua Absensi
                        </x-filament::button>
                    </div>

                @else
                    {{-- Empty State --}}
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum Ada Siswa</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if($this->isAdmin)
                                Pilih kelas untuk memulai absensi.
                            @else
                                Belum ada siswa di kelas Anda.
                            @endif
                        </p>
                    </div>
                @endif

            </x-filament::card>
        </div>

    </div>
</x-filament::page>
