<x-filament::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Sidebar Filter --}}
        <div>
            <x-filament::card>
                <h3 class="mb-4 text-xl font-semibold">🔍 Filter Rekap Absensi</h3>

                {{-- Tipe Laporan --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipe Laporan</label>
                    <select wire:model.live="report_type"
                        class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                        <option value="summary">📊 Ringkasan (Per Kategori)</option>
                        <option value="detail">📋 Detail (Per Siswa)</option>
                        <option value="student">👤 Riwayat Individual</option>
                    </select>
                </div>

                {{-- Sumber Absensi --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Sumber Absensi</label>
                    <select wire:model.live="attendance_source"
                        class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                        @foreach ($this->getAvailableSourcesProperty() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    {{-- Info untuk guru non-wali kelas --}}
                    @if (!auth()->user()->hasRole('admin') && auth()->user()->guru && !auth()->user()->guru->waliKelas)
                        <p class="mt-2 text-xs text-warning-600 dark:text-warning-400">
                            ℹ Anda bukan wali kelas, hanya dapat melihat absensi mapel
                        </p>
                    @endif
                </div>

                {{-- Filter Kelas (hanya untuk mapel atau homeroom) --}}
                @if ($attendance_source != 'homeroom' || $attendance_source == 'all')
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Kelas</label>
                        <select wire:model.live="kelas_id"
                            class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                            <option value="">-- Semua Kelas --</option>
                            @foreach ($this->getKelasListProperty() as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Filter Guru (untuk admin) --}}
                @if (auth()->user()->hasRole('admin') && ($attendance_source == 'mapel' || $attendance_source == 'all'))
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Guru (Mapel)</label>
                        <select wire:model.live="guru_id"
                            class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                            <option value="">-- Semua Guru --</option>
                            @foreach ($this->getGuruListProperty() as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Filter Mapel (hanya untuk absensi mapel) --}}
                @if ($attendance_source != 'homeroom')
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Mata
                            Pelajaran</label>
                        <select wire:model.live="mapel_id"
                            class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                            <option value="">-- Semua Mapel --</option>
                            @foreach ($this->getMapelListProperty() as $m)
                                <option value="{{ $m->id }}">{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Filter Wali Kelas (untuk admin) --}}
                @if (auth()->user()->hasRole('admin') && ($attendance_source == 'homeroom' || $attendance_source == 'all'))
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Wali Kelas</label>
                        <select wire:model.live="wali_kelas_id"
                            class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                            <option value="">-- Semua Wali Kelas --</option>
                            @foreach ($this->getWaliKelasListProperty() as $wk)
                                <option value="{{ $wk->id }}">{{ $wk->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Filter Siswa (hanya untuk riwayat individual) --}}
                @if ($report_type == 'student')
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Siswa</label>
                        <select wire:model.live="siswa_id"
                            class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($this->getSiswaListProperty() as $s)
                                <option value="{{ $s->id }}">{{ $s->nis }} -
                                    {{ $s->user->name ?? $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Dari --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Dari Tanggal</label>
                    <input wire:model="from" type="date"
                        class="w-full p-2 mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                </div>

                {{-- Sampai --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Sampai Tanggal</label>
                    <input wire:model="to" type="date"
                        class="w-full p-2 mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <x-filament::button wire:click="search" color="primary" class="w-full">
                        🔍 Tampilkan
                    </x-filament::button>

                    <x-filament::button wire:click="exportExcel" color="success" class="w-full">
                        📥 Export Excel
                    </x-filament::button>
                </div>
            </x-filament::card>
        </div>

        {{-- Konten Rekap --}}
        <div class="md:col-span-2">
            <x-filament::card>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-semibold">📊 Hasil Rekap Absensi</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Periode: {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}
                        </p>
                    </div>
                    @if ($totalAbsensi > 0)
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Absensi</p>
                            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $totalAbsensi }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- RINGKASAN (Summary) --}}
                @if ($report_type == 'summary' && count($results))
                    <div class="overflow-x-auto rounded-lg">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                                    <th class="px-4 py-3 font-semibold text-left">No</th>
                                    <th class="px-4 py-3 font-semibold text-left">Tipe</th>
                                    <th class="px-4 py-3 font-semibold text-left">Kode</th>
                                    <th class="px-4 py-3 font-semibold text-left">Kategori</th>
                                    <th class="px-4 py-3 font-semibold text-right">Total</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y dark:divide-gray-700">
                                @foreach ($results as $index => $r)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 rounded text-xs font-medium
                                                {{ $r->source == 'Mapel' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $r->source == 'Harian' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                                                {{ $r->source == 'Harian' ? '🏠 Harian' : '📚 Mapel' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-mono font-bold">{{ $r->code }}</td>
                                        <td class="px-4 py-3">{{ $r->name }}</td>
                                        <td class="px-4 py-3 text-lg font-bold text-right">{{ $r->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- DETAIL (By Student) --}}
                @if ($report_type == 'detail' && count($detailResults))
                    <div class="overflow-x-auto rounded-lg">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                                    <th class="px-3 py-2 font-semibold text-left">No</th>
                                    <th class="px-3 py-2 font-semibold text-left">NIS</th>
                                    <th class="px-3 py-2 font-semibold text-left">Nama Siswa</th>
                                    <th class="px-3 py-2 font-semibold text-left">Kelas</th>
                                    <th class="px-3 py-2 font-semibold text-left">Tipe</th>
                                    <th
                                        class="px-3 py-2 font-semibold text-center bg-success-50 dark:bg-success-900/20">
                                        H</th>
                                    <th
                                        class="px-3 py-2 font-semibold text-center bg-warning-50 dark:bg-warning-900/20">
                                        S</th>
                                    <th class="px-3 py-2 font-semibold text-center bg-info-50 dark:bg-info-900/20">I
                                    </th>
                                    <th class="px-3 py-2 font-semibold text-center bg-danger-50 dark:bg-danger-900/20">A
                                    </th>
                                    <th class="px-3 py-2 font-semibold text-center">Total</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y dark:divide-gray-700">
                                @foreach ($detailResults as $index => $r)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-3 py-2">{{ $index + 1 }}</td>
                                        <td class="px-3 py-2 font-mono">{{ $r->nis ?? '-' }}</td>
                                        <td class="px-3 py-2 font-semibold">{{ $r->nama ?? '-' }}</td>
                                        <td class="px-3 py-2">{{ $r->kelas ?? '-' }}</td>
                                        <td class="px-3 py-2">
                                            <span
                                                class="px-2 py-1 rounded text-xs font-medium
                                                {{ $r->tipe == 'Mapel' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $r->tipe == 'Harian' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                                                {{ $r->tipe == 'Harian' ? '🏠 Harian' : '📚 Mapel' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 font-bold text-center text-success-600">
                                            {{ $r->hadir ?? 0 }}</td>
                                        <td class="px-3 py-2 font-bold text-center text-warning-600">
                                            {{ $r->sakit ?? 0 }}</td>
                                        <td class="px-3 py-2 font-bold text-center text-info-600">{{ $r->izin ?? 0 }}
                                        </td>
                                        <td class="px-3 py-2 font-bold text-center text-danger-600">
                                            {{ $r->alpha ?? 0 }}</td>
                                        <td class="px-3 py-2 font-bold text-center">{{ $r->total ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- RIWAYAT INDIVIDUAL (Student History) --}}
                @if ($report_type == 'student' && count($studentResults))
                    <div class="overflow-x-auto rounded-lg">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                                    <th class="px-3 py-2 font-semibold text-left">No</th>
                                    <th class="px-3 py-2 font-semibold text-left">Tanggal</th>
                                    <th class="px-3 py-2 font-semibold text-left">Tipe</th>
                                    <th class="px-3 py-2 font-semibold text-left">Keterangan</th>
                                    <th class="px-3 py-2 font-semibold text-center">Jam</th>
                                    <th class="px-3 py-2 font-semibold text-center">Status</th>
                                    <th class="px-3 py-2 font-semibold text-left">Catatan</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y dark:divide-gray-700">
                                @foreach ($studentResults as $index => $r)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-3 py-2">{{ $index + 1 }}</td>
                                        <td class="px-3 py-2 font-medium">
                                            {{ \Carbon\Carbon::parse($r->date)->format('d/m/Y') }}</td>
                                        <td class="px-3 py-2">
                                            <span
                                                class="px-2 py-1 rounded text-xs font-medium
                                                {{ $r->tipe == 'Mapel' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $r->tipe == 'Harian' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                                                {{ $r->tipe == 'Harian' ? '🏠 Harian' : '📚 Mapel' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2">{{ $r->keterangan ?? '-' }}</td>
                                        <td class="px-3 py-2 font-mono text-xs text-center">
                                            {{ $r->jam_mulai ? substr($r->jam_mulai, 0, 5) : '-' }}
                                            {{ $r->jam_selesai ? ' - ' . substr($r->jam_selesai, 0, 5) : '' }}
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-bold
                                                {{ $r->code == 'H' ? 'bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-100' : '' }}
                                                {{ $r->code == 'S' ? 'bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-100' : '' }}
                                                {{ $r->code == 'I' ? 'bg-info-100 text-info-800 dark:bg-info-900 dark:text-info-100' : '' }}
                                                {{ $r->code == 'A' ? 'bg-danger-100 text-danger-800 dark:bg-danger-900 dark:text-danger-100' : '' }}">
                                                {{ $r->code }} - {{ $r->name }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-600 dark:text-gray-400">
                                            {{ $r->note ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Empty State --}}
                @if (
                    ($report_type == 'summary' && !count($results)) ||
                        ($report_type == 'detail' && !count($detailResults)) ||
                        ($report_type == 'student' && !count($studentResults)))
                    <div class="py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum Ada Data</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Silakan atur filter dan klik "Tampilkan" untuk melihat rekap absensi.
                        </p>
                    </div>
                @endif
            </x-filament::card>
        </div>

    </div>
</x-filament::page>
