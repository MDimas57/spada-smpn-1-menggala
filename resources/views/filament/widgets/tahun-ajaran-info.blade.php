<x-filament-widgets::widget>
    @if($this->getViewData()['tahunAjaran'])
        <x-filament::section>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-primary-100 dark:bg-primary-900/20 rounded-lg">
                        <x-heroicon-o-calendar class="w-8 h-8 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Tahun Ajaran {{ $this->getViewData()['tahunAjaran']->tahun }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Semester {{ $this->getViewData()['tahunAjaran']->semester }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        Aktif
                    </span>

                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('filament.admin.resources.tahun-ajarans.index') }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">
                            Kelola
                            <x-heroicon-m-arrow-right class="w-3 h-3" />
                        </a>
                    @endif
                </div>
            </div>
        </x-filament::section>
    @else
        <x-filament::section>
            <div class="flex items-center gap-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg dark:bg-yellow-900/20 dark:border-yellow-800">
                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0" />
                <div class="flex-1">
                    <h3 class="font-semibold text-sm text-yellow-800 dark:text-yellow-300">
                        Tahun Ajaran Belum Diatur
                    </h3>
                    <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                        Silakan aktifkan tahun ajaran terlebih dahulu.
                    </p>
                </div>
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('filament.admin.resources.tahun-ajarans.index') }}"
                       class="text-sm font-medium text-yellow-800 hover:underline dark:text-yellow-300">
                        Kelola
                    </a>
                @endif
            </div>
        </x-filament::section>
    @endif
</x-filament-widgets::widget>
