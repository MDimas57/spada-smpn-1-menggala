<x-filament::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

        {{-- Sidebar Filter (KIRI) --}}
        <div>
            <x-filament::card>
                <h3 class="mb-4 text-xl font-semibold">🔍 Filter Input Nilai</h3>

                {{-- Mata Pelajaran --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Mata Pelajaran <span class="text-danger-600">*</span>
                    </label>
                    <select wire:model.live="mapel_id"
                        class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($this->getMapelList() as $mapel)
                            <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jenis Ujian --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Jenis Ujian <span class="text-danger-600">*</span>
                    </label>
                    <select wire:model.live="jenis_ujian"
                        class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                        <option value="">-- Pilih Jenis Ujian --</option>
                        <option value="UTS">UTS (Ujian Tengah Semester)</option>
                        <option value="UAS">UAS (Ujian Akhir Semester)</option>
                    </select>
                </div>

                {{-- Tahun Ajaran --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tahun Ajaran <span class="text-danger-600">*</span>
                    </label>
                    <select wire:model.live="tahun_ajaran_id"
                        class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach ($this->getTahunAjaranList() as $ta)
                            <option value="{{ $ta->id }}">
                                {{ $ta->tahun }}
                                @if($ta->is_active) ✓ @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Kelas <span class="text-danger-600">*</span>
                    </label>
                    <select wire:model.live="kelas_id"
                        class="w-full mt-1 border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-800">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($this->getKelasList() as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col gap-2 mt-6">
                    <x-filament::button wire:click="simpanNilai" color="primary" class="w-full">
                        💾 Simpan Nilai
                    </x-filament::button>

                    <div class="grid grid-cols-2 gap-2">
                        <x-filament::button wire:click="selectAll" color="success" outlined class="w-full text-xs">
                            ✓ Pilih Semua
                        </x-filament::button>
                        <x-filament::button wire:click="deselectAll" color="gray" outlined class="w-full text-xs">
                            ✗ Batal Pilih
                        </x-filament::button>
                    </div>
                </div>

                {{-- Stats --}}
                @if ($totalSiswa > 0)
                    <div class="p-3 mt-4 space-y-2 rounded-lg bg-primary-50 dark:bg-primary-900/20">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-primary-700 dark:text-primary-300">Total Siswa</p>
                            <p class="text-lg font-bold text-primary-900 dark:text-primary-100">{{ $totalSiswa }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-primary-700 dark:text-primary-300">Terpilih</p>
                            <p class="text-lg font-bold text-primary-900 dark:text-primary-100">{{ $selectedCount }}</p>
                        </div>
                    </div>
                @endif

                {{-- Info untuk Guru --}}
                @if(!auth()->user()->hasRole('admin'))
                    <div class="p-3 mt-4 rounded-lg bg-info-50 dark:bg-info-900/20">
                        <p class="text-xs text-info-700 dark:text-info-300">
                            ℹ️ Anda hanya dapat melihat kelas dan mapel yang Anda ampu
                        </p>
                    </div>
                @endif
            </x-filament::card>
        </div>

        {{-- Konten Input Nilai (KANAN) --}}
        <div class="md:col-span-2">
            <x-filament::card>
                <div class="mb-4">
                    <h3 class="text-xl font-semibold">📝 Input Nilai Siswa</h3>
                    @if($mapel_id && $jenis_ujian && $kelas_id)
                        @php
                            $mapelName = \App\Models\Mapel::find($mapel_id)?->nama ?? '-';
                            $kelasName = \App\Models\Kelas::find($kelas_id)?->nama ?? '-';
                        @endphp
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $mapelName }} ({{ $jenis_ujian }}) - Kelas {{ $kelasName }}
                        </p>
                    @else
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Pilih filter di sebelah kiri untuk menampilkan daftar siswa
                        </p>
                    @endif
                </div>

                @if (count($nilai_list) > 0)
                    <div class="overflow-x-auto rounded-lg">
                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-200">
                                    <th class="px-3 py-3 font-semibold text-center w-16">
                                        <input type="checkbox"
                                            wire:click="selectAll"
                                            class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </th>
                                    <th class="px-3 py-3 font-semibold text-left w-24">NIS</th>
                                    <th class="px-3 py-3 font-semibold text-left">Nama Siswa</th>
                                    <th class="px-3 py-3 font-semibold text-left w-32">Kelas</th>
                                    <th class="px-3 py-3 font-semibold text-center w-32">Nilai (0-100)</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y dark:divide-gray-700">
                                @foreach ($nilai_list as $index => $siswa)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800"
                                        wire:key="siswa-{{ $siswa['siswa_id'] }}">
                                        {{-- Checkbox Pilih --}}
                                        <td class="px-3 py-3 text-center">
                                            <input type="checkbox"
                                                wire:model.live="nilai_list.{{ $index }}.selected"
                                                class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800">
                                        </td>

                                        {{-- NIS --}}
                                        <td class="px-3 py-3 font-mono text-sm">
                                            {{ $siswa['nis'] }}
                                        </td>

                                        {{-- Nama Siswa --}}
                                        <td class="px-3 py-3 font-medium">
                                            {{ $siswa['nama_siswa'] }}
                                        </td>

                                        {{-- Kelas --}}
                                        <td class="px-3 py-3 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $siswa['kelas_nama'] }}
                                        </td>

                                        {{-- Input Nilai --}}
                                        <td class="px-3 py-3">
                                            <input type="number"
                                                wire:model="nilai_list.{{ $index }}.nilai"
                                                min="0"
                                                max="100"
                                                step="1"
                                                placeholder="0-100"
                                                class="w-full px-3 py-2 text-center border-gray-300 rounded-lg focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800
                                                    {{ !$siswa['selected'] ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                @if(!$siswa['selected']) disabled @endif>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Info Footer --}}
                    <div class="p-4 mt-4 space-y-2 rounded-lg bg-info-50 dark:bg-info-900/20">
                        <div class="flex items-start gap-2">
                            <svg class="flex-shrink-0 w-5 h-5 mt-0.5 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1 text-sm text-info-700 dark:text-info-300">
                                <p class="font-medium">Cara menggunakan:</p>
                                <ul class="mt-1 ml-4 space-y-1 list-disc list-inside">
                                    <li>Centang siswa yang ingin diberi nilai</li>
                                    <li>Masukkan nilai (0-100) pada kolom nilai</li>
                                    <li>Klik <strong>Simpan Nilai</strong> untuk menyimpan</li>
                                    <li>Nilai yang sudah ada akan otomatis tercentang dan dapat diupdate</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Empty State --}}
                    <div class="py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum Ada Data</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Pilih <strong>Mata Pelajaran</strong>, <strong>Jenis Ujian</strong>, <strong>Tahun Ajaran</strong>, dan <strong>Kelas</strong> di filter sebelah kiri
                        </p>
                    </div>
                @endif
            </x-filament::card>
        </div>

    </div>
</x-filament::page>
