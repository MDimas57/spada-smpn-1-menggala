<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-3">
            @foreach($this->getViewData()['alerts'] as $alert)
                @if($alert['show'])
                    <div class="flex items-start gap-3 p-4 rounded-lg border
                        @if($alert['type'] === 'warning')
                            bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800
                        @elseif($alert['type'] === 'info')
                            bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-800
                        @endif
                    ">
                        <div class="flex-shrink-0">
                            @if($alert['type'] === 'warning')
                                <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                            @elseif($alert['type'] === 'info')
                                <x-heroicon-o-information-circle class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="font-semibold text-sm
                                @if($alert['type'] === 'warning')
                                    text-yellow-800 dark:text-yellow-300
                                @elseif($alert['type'] === 'info')
                                    text-blue-800 dark:text-blue-300
                                @endif
                            ">
                                {{ $alert['title'] }}
                            </h3>
                            <p class="text-sm mt-1
                                @if($alert['type'] === 'warning')
                                    text-yellow-700 dark:text-yellow-400
                                @elseif($alert['type'] === 'info')
                                    text-blue-700 dark:text-blue-400
                                @endif
                            ">
                                {{ $alert['message'] }}
                            </p>

                            @if($alert['action'])
                                <a href="{{ $alert['action'] }}"
                                   class="inline-flex items-center gap-1 mt-3 text-sm font-medium hover:underline
                                    @if($alert['type'] === 'warning')
                                        text-yellow-800 dark:text-yellow-300
                                    @elseif($alert['type'] === 'info')
                                        text-blue-800 dark:text-blue-300
                                    @endif
                                   ">
                                    {{ $alert['actionLabel'] }}
                                    <x-heroicon-m-arrow-right class="w-4 h-4" />
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            @if(collect($this->getViewData()['alerts'])->where('show', true)->isEmpty())
                <div class="flex items-center gap-3 p-4 rounded-lg border bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0" />
                    <div>
                        <h3 class="font-semibold text-sm text-green-800 dark:text-green-300">
                            Semua Baik!
                        </h3>
                        <p class="text-sm text-green-700 dark:text-green-400 mt-1">
                            Tidak ada notifikasi yang perlu perhatian saat ini.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
