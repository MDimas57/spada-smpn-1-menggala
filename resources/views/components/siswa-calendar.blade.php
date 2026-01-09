@php
    use Carbon\Carbon;
    
    $currentMonth = Carbon::now();
    $firstDay = $currentMonth->copy()->startOfMonth();
    $lastDay = $currentMonth->copy()->endOfMonth();
    $daysInMonth = $currentMonth->daysInMonth();
    $startingDayOfWeek = $firstDay->dayOfWeek; // 0 = Sunday, 6 = Saturday
    
    // Build array of task dates
    $taskDates = [];
    foreach ($tugasTimeline as $item) {
        if ($item->due_date) {
            $date = Carbon::parse($item->due_date)->format('Y-m-d');
            if (!isset($taskDates[$date])) {
                $taskDates[$date] = [];
            }
            $taskDates[$date][] = [
                'title' => $item->judul ?? $item->pertanyaan,
                'type' => $item->item_type,
                'due_date' => $item->due_date,
                'id' => $item->id
            ];
        }
    }
@endphp

<div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
    <!-- Header Kalender -->
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
        <h3 class="flex items-center gap-3 text-lg font-bold text-slate-800">
            <div class="p-2 text-indigo-600 bg-white border rounded-lg shadow-sm border-slate-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            Kalender Tugas
        </h3>
    </div>

    <!-- Kalender Grid -->
    <div class="p-6">
        <!-- Month/Year Header -->
        <div class="mb-6 text-center">
            <h4 class="text-xl font-bold text-slate-800">
                {{ $currentMonth->locale('id')->isoFormat('MMMM YYYY') }}
            </h4>
        </div>

        <!-- Day of Week Headers -->
        <div class="grid grid-cols-7 gap-2 mb-3">
            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                <div class="text-center text-xs font-bold text-slate-500 py-2">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Days -->
        <div class="grid grid-cols-7 gap-2">
            {{-- Empty cells before first day of month --}}
            @for ($i = 0; $i < $startingDayOfWeek; $i++)
                <div class="aspect-square"></div>
            @endfor

            {{-- Days of month --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = $currentMonth->copy()->setDay($day);
                    $dateString = $date->format('Y-m-d');
                    $isToday = $date->isToday();
                    $hasTask = isset($taskDates[$dateString]);
                    $taskCount = isset($taskDates[$dateString]) ? count($taskDates[$dateString]) : 0;
                    $isPast = $date->isPast() && !$isToday;
                @endphp

                <div class="relative aspect-square group cursor-pointer"
                    @if($hasTask)
                        x-data="{ showPopover: false }"
                        @mouseenter="showPopover = true"
                        @mouseleave="showPopover = false"
                    @endif
                >
                    <div class="flex flex-col items-center justify-center h-full p-1 text-xs transition-all duration-200 rounded-lg
                        @if($isToday)
                            bg-indigo-600 text-white font-bold
                        @elseif($hasTask)
                            bg-amber-50 border-2 border-amber-400 text-amber-900 font-semibold
                        @elseif($isPast)
                            bg-slate-100 text-slate-400
                        @else
                            bg-slate-50 text-slate-600 hover:bg-slate-100
                        @endif
                    ">
                        <span>{{ $day }}</span>
                        @if($hasTask)
                            <div class="flex gap-0.5 mt-1">
                                @foreach(range(0, min($taskCount - 1, 2)) as $i)
                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                                @endforeach
                                @if($taskCount > 3)
                                    <span class="text-xs font-bold">+{{ $taskCount - 3 }}</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Popover untuk task list --}}
                    @if($hasTask)
                        <div class="absolute left-0 z-50 hidden w-48 pt-2 group-hover:block"
                             @if(true)
                                x-show="showPopover"
                                @click.away="showPopover = false"
                             @endif
                        >
                            <div class="bg-white border border-slate-200 rounded-lg shadow-lg p-3 space-y-2 max-h-64 overflow-y-auto">
                                <div class="text-xs font-bold text-slate-600 pb-2 border-b border-slate-100">
                                    {{ $date->locale('id')->isoFormat('dddd, D MMM') }}
                                </div>
                                @foreach($taskDates[$dateString] as $task)
                                    @php
                                        $taskDueDate = Carbon::parse($task['due_date']);
                                        $isTaskOverdue = $taskDueDate->isPast();
                                    @endphp
                                    <div class="text-xs p-2 rounded
                                        @if($task['type'] === 'tugas')
                                            bg-blue-50 border-l-2 border-blue-500
                                        @else
                                            bg-purple-50 border-l-2 border-purple-500
                                        @endif
                                    ">
                                        <div class="font-semibold text-slate-800 truncate">
                                            {{ Str::limit($task['title'], 25) }}
                                        </div>
                                        <div class="text-slate-600 text-xs mt-1">
                                            {{ $taskDueDate->format('H:i') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endfor
        </div>

        <!-- Legend -->
        <div class="mt-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
            <p class="text-xs font-semibold text-slate-700 mb-2">Keterangan:</p>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-indigo-600"></div>
                    <span class="text-slate-600">Hari Ini</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded border-2 border-amber-400 bg-amber-50"></div>
                    <span class="text-slate-600">Ada Tugas</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                    <span class="text-slate-600">Jumlah Item</span>
                </div>
            </div>
        </div>
    </div>
</div>