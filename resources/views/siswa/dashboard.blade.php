<x-app-layout>
    <div class="min-h-screen py-8 bg-gray-50 md:py-12">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">

            <div class="relative overflow-hidden text-white shadow-lg rounded-2xl bg-gradient-to-r from-cyan-600 to-teal-700">
                <div class="absolute top-0 right-0 w-64 h-64 -mt-10 -mr-10 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 -mb-5 -ml-5 rounded-full bg-white/10 blur-xl"></div>

                <div class="relative px-6 py-8 md:px-10 md:py-10">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-2 py-1 text-xs font-bold uppercase rounded bg-white/20 text-cyan-50">
                                    Student Dashboard
                                </span>
                            </div>
                            <h2 class="text-2xl font-bold tracking-tight md:text-3xl">
                                Halo, {{ Auth::user()->name }}! 👋
                            </h2>
                            <p class="max-w-xl mt-2 text-cyan-50 text-opacity-90">
                                Cek agenda belajarmu di sebelah kiri dan jadwal kalender di sebelah kanan.
                            </p>
                        </div>

                        <div class="mt-6 md:mt-0">
                            <div class="px-6 py-3 text-center border rounded-xl bg-white/10 backdrop-blur-sm border-white/20">
                                <span class="block text-xs font-medium tracking-wider uppercase text-cyan-100">Hari Ini</span>
                                <span class="block text-xl font-bold text-white">
                                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMM') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8">

                <div class="space-y-6 lg:col-span-8">

                    <div class="flex items-center justify-between">
                        <h3 class="flex items-center gap-2 text-lg font-bold text-gray-800">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Tugas & Aktivitas Terbaru
                        </h3>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 text-xs font-medium text-teal-700 bg-white border border-teal-100 rounded-full shadow-sm">
                                Semua ({{ $tugasTimeline->count() }})
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @if($tugasTimeline->isEmpty())
                            <div class="p-10 text-center bg-white border border-gray-300 border-dashed rounded-2xl">
                                <div class="inline-flex items-center justify-center w-16 h-16 mb-4 text-gray-400 rounded-full bg-gray-50">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h4 class="font-medium text-gray-900">Semua tugas selesai!</h4>
                                <p class="text-sm text-gray-500">Nikmati waktu istirahatmu.</p>
                            </div>
                        @else
                            @foreach($tugasTimeline->take(5) as $item) @php
                                    $isTugas = $item->item_type === 'tugas';
                                    $dueDate = $item->due_date;
                                    $now = \Carbon\Carbon::now('Asia/Jakarta');
                                    $isDone = !empty($item->is_done) && $item->is_done;
                                    $isOverdue = $dueDate->lt($now);

                                    // LOGIC WARNA & STYLE
                                    if ($isDone) {
                                        $borderLeftClass = 'border-l-4 border-l-teal-500'; // Strip kiri Hijau
                                        $bgBadge = 'bg-teal-50 text-teal-700 border-teal-100';
                                        $statusText = 'Selesai';
                                        $cardBg = 'bg-white opacity-75'; // Agak transparan kalau selesai
                                    } elseif ($isOverdue) {
                                        $borderLeftClass = 'border-l-4 border-l-red-500'; // Strip kiri Merah
                                        $bgBadge = 'bg-red-50 text-red-700 border-red-100';
                                        $statusText = 'Terlewat';
                                        $cardBg = 'bg-white';
                                    } else {
                                        $borderLeftClass = 'border-l-4 border-l-orange-500'; // Strip kiri Orange
                                        $bgBadge = 'bg-orange-50 text-orange-700 border-orange-100';
                                        $statusText = 'Tenggat Segera';
                                        $cardBg = 'bg-white shadow-sm hover:shadow-md transition-shadow';
                                    }
                                @endphp

                                <div class="relative overflow-hidden rounded-xl border border-gray-100 p-5 {{ $cardBg }} {{ $borderLeftClass }}">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start">

                                        <div class="flex-shrink-0">
                                            <div class="flex flex-col items-center justify-center w-16 h-16 text-center border border-gray-200 rounded-lg bg-gray-50">
                                                @if($item->due_date)
                                                    <span class="text-xs font-semibold text-gray-500 uppercase">{{ \Carbon\Carbon::parse($item->due_date)->format('M') }}</span>
                                                    <span class="text-xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($item->due_date)->format('d') }}</span>
                                                @else
                                                    <span class="text-xs text-gray-400">No Date</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-xs font-bold tracking-wide text-gray-500 uppercase">{{ $item->mapel->nama ?? 'Umum' }}</span>
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $bgBadge }}">
                                                            {{ $statusText }}
                                                        </span>
                                                    </div>
                                                    <h4 class="text-lg font-bold text-gray-800 line-clamp-1 group-hover:text-teal-600">
                                                        {{ $item->judul ?? $item->pertanyaan }}
                                                    </h4>
                                                </div>
                                            </div>

                                            @if(isset($item->guru))
                                                <p class="flex items-center gap-1 mt-1 text-sm text-gray-500">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                                    Oleh: {{ $item->guru->user->name ?? 'Guru' }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="self-center flex-shrink-0 mt-2 sm:mt-0">
                                             @if(!empty($item->is_done) && $item->is_done)
                                                <a href="{{ route('siswa.modul.show', $item->modul_id) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-teal-600 transition-colors rounded-lg bg-teal-50 hover:bg-teal-100">
                                                    Lihat Hasil
                                                </a>
                                            @else
                                                @if($isOverdue)
                                                    <span class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                                        Ditutup
                                                    </span>
                                                @else
                                                    @if($isTugas)
                                                        <a href="{{ route('siswa.modul.show', $item->modul_id) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white shadow-sm bg-teal-600 rounded-lg hover:bg-teal-700 transition-all hover:-translate-y-0.5">
                                                            Kerjakan
                                                        </a>
                                                    @else
                                                        <a href="{{ route('siswa.kuis.kerjakan', $item->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white shadow-sm bg-cyan-600 rounded-lg hover:bg-cyan-700 transition-all hover:-translate-y-0.5">
                                                            Mulai Kuis
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($tugasTimeline->count() > 5)
                                <div class="mt-4 text-center">
                                    <a href="#" class="text-sm font-semibold text-teal-600 hover:text-teal-800">
                                        Lihat Semua Aktivitas &rarr;
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="space-y-6 lg:col-span-4">

                    <div class="overflow-hidden bg-white border border-gray-200 shadow-sm rounded-2xl">
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <h3 class="text-sm font-bold tracking-wider text-gray-700 uppercase">Kalender Akademik</h3>
                        </div>
                        <div class="p-4">
                            <x-siswa-calendar :tugasTimeline="$tugasTimeline" />
                        </div>
                    </div>

                    <div class="p-5 text-white shadow-lg bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl">
                        <h4 class="mb-4 text-xs font-bold tracking-widest text-gray-300 uppercase">Ringkasan</h4>
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-teal-400">{{ $tugasTimeline->where('is_done', 1)->count() }}</div>
                                <div class="mt-1 text-xs text-gray-400">Selesai</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-orange-400">{{ $tugasTimeline->where('is_done', 0)->where('due_date', '>', now())->count() }}</div>
                                <div class="mt-1 text-xs text-gray-400">Pending</div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
