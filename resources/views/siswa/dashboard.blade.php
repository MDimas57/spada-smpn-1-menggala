<!-- UPDATED dashboard.blade.php -->
<x-app-layout>
    <div class="min-h-screen py-12 bg-slate-50/50">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">

            <div class="relative overflow-hidden text-white shadow-xl rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 shadow-indigo-200">
                <div class="absolute top-0 right-0 w-64 h-64 -mt-16 -mr-16 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 -mb-16 -ml-16 rounded-full bg-white/10 blur-2xl"></div>

                <div class="relative p-8 md:p-10">
                    <div class="flex flex-col justify-between gap-6 md:flex-row md:items-center">
                        <div>
                            <h2 class="mb-2 text-3xl font-bold tracking-tight">
                                Selamat datang, {{ Auth::user()->name }}! 👋
                            </h2>
                            <p class="max-w-2xl text-lg text-indigo-100">
                                Siap untuk belajar hari ini? Berikut adalah aktivitas terbaru dan prioritas tugasmu.
                            </p>
                        </div>
                        <div class="hidden text-right md:block">
                            <p class="text-sm font-semibold tracking-wider text-indigo-200 uppercase">Hari ini</p>
                            <p class="text-3xl font-bold">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <!-- Calendar Section -->
                <div class="lg:col-span-1">
                    <x-siswa-calendar :tugasTimeline="$tugasTimeline" />
                </div>

                <!-- Timeline Section -->
                <div class="lg:col-span-2">
                    <div class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="flex items-center gap-3 text-lg font-bold text-slate-800">
                                <div class="p-2 text-indigo-600 bg-white border rounded-lg shadow-sm border-slate-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                Timeline Aktivitas & Tugas
                            </h3>
                            <span class="text-xs font-medium px-2.5 py-1 bg-slate-200 text-slate-600 rounded-full">
                                {{ $tugasTimeline->count() }} Item
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @if($tugasTimeline->isEmpty())
                                <div class="py-16 text-center">
                                    <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 rounded-full bg-slate-50">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-medium text-slate-900">Semua Beres!</h4>
                                    <p class="mt-1 text-slate-500">Tidak ada tugas atau kuis yang perlu dikerjakan saat ini.</p>
                                </div>
                            @else
                                @foreach($tugasTimeline->take(3) as $item)
                                    @php
                                        $isTugas = $item->item_type === 'tugas';

                                        // Ambil due_date (deadline atau created_at)
                                        $dueDate = $item->due_date;

                                        // Gunakan timezone yang sama dengan config (Asia/Jakarta)
                                        $now = \Carbon\Carbon::now('Asia/Jakarta');

                                        // Tentukan status berdasarkan apakah sudah dikerjakan dan deadline
                                        $isDone = !empty($item->is_done) && $item->is_done; // Sudah dikerjakan/dikumpulkan
                                        $isOverdue = $dueDate->lt($now); // Deadline sudah lewat
                                        $hoursUntilDeadline = $dueDate->diffInHours($now, false); // false = bisa negatif jika sudah lewat
                                        $isUpcoming = $hoursUntilDeadline > 0 && $hoursUntilDeadline <= 24; // Dalam 24 jam ke depan

                                        // Logic status:
                                        // 1. Jika sudah dikerjakan → SELESAI (hijau)
                                        // 2. Jika belum dikerjakan + deadline lewat → TERLEWAT (merah)
                                        // 3. Jika belum dikerjakan + deadline belum lewat → TENGGAT SEGERA (kuning)
                                        if ($isDone) {
                                            $bgClass = 'bg-emerald-50';
                                            $textClass = 'text-emerald-600';
                                            $borderClass = 'border-emerald-100';
                                            $badgeClass = 'bg-emerald-100 text-emerald-700';
                                            $statusText = 'Selesai';
                                        } elseif ($isOverdue) {
                                            $bgClass = 'bg-rose-50';
                                            $textClass = 'text-rose-600';
                                            $borderClass = 'border-rose-100';
                                            $badgeClass = 'bg-rose-100 text-rose-700';
                                            $statusText = 'Terlewat';
                                        } else {
                                            $bgClass = 'bg-amber-50';
                                            $textClass = 'text-amber-600';
                                            $borderClass = 'border-amber-100';
                                            $badgeClass = 'bg-amber-100 text-amber-700';
                                            $statusText = 'Tenggat Segera';
                                        }
                                    @endphp

                                    <div class="p-6 transition-colors duration-200 group hover:bg-slate-50">
                                        <div class="flex flex-col gap-5 sm:flex-row">

                                            <div class="flex-shrink-0">
                                                <div class="flex items-center justify-center h-12 w-12 rounded-xl {{ $bgClass }} {{ $textClass }} border {{ $borderClass }}">
                                                    @if($isTugas)
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col gap-2 mb-2 md:flex-row md:items-start md:justify-between">
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1">
                                                            <span class="text-xs font-bold tracking-wider uppercase text-slate-500">
                                                                {{ $item->mapel->nama ?? 'Umum' }}
                                                            </span>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeClass }}">
                                                                {{ $statusText }}
                                                            </span>
                                                        </div>
                                                        <h4 class="text-lg font-bold transition-colors text-slate-800 group-hover:text-indigo-600">
                                                            {{ $item->judul ?? $item->pertanyaan }}
                                                        </h4>
                                                        @if(isset($item->guru))
                                                            <p class="flex items-center gap-1 mt-1 text-sm text-slate-500">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                                {{ $item->guru->user->name ?? 'Guru' }}
                                                            </p>
                                                        @endif
                                                    </div>

                                                    <div class="flex flex-row gap-3 mt-2 text-sm md:flex-col md:gap-1 text-slate-500 md:text-right md:items-end md:mt-0">
                                                         @if($item->due_date)
                                                            <div class="flex items-center gap-1.5 {{ $isOverdue ? 'text-rose-600 font-medium' : '' }}">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                                {{ \Carbon\Carbon::parse($item->due_date)->format('d M, H:i') }}
                                                            </div>
                                                        @endif
                                                        <div class="flex items-center gap-1.5 text-xs text-slate-400">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>

                                                @if(isset($item->deskripsi))
                                                    <div class="mb-4 text-sm prose-sm prose text-slate-600 line-clamp-2 max-w-none">
                                                        {!! strip_tags($item->deskripsi) !!}
                                                    </div>
                                                @endif

                                                <div class="flex items-center mt-3">
                                                        @if(!empty($item->is_done) && $item->is_done)
                                                            <span class="inline-flex items-center px-3 py-2 text-sm font-semibold bg-emerald-100 text-emerald-800 rounded-lg">
                                                                Sudah mengerjakan
                                                            </span>
                                                            <a href="{{ route('siswa.modul.show', $item->modul_id) }}" class="ml-3 text-sm font-medium text-indigo-600 hover:underline">Lihat</a>
                                                        @else
                                                            @if($isOverdue)
                                                                <button class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-200 rounded-lg w-full sm:w-auto justify-center cursor-not-allowed" disabled>
                                                                    Waktu Habis
                                                                </button>
                                                                <a href="{{ route('siswa.modul.show', $item->modul_id) }}" class="ml-3 text-sm font-medium text-indigo-600 hover:underline">Lihat</a>
                                                            @else
                                                                @if($isTugas)
                                                                    <a href="{{ route('siswa.modul.show', $item->modul_id) }}"
                                                                       class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 w-full sm:w-auto justify-center">
                                                                        Kerjakan Tugas
                                                                        <svg class="w-4 h-4 transition-transform ms-2 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                                        </svg>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('siswa.kuis.kerjakan', $item->id) }}"
                                                                       class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 w-full sm:w-auto justify-center">
                                                                        Mulai Kuis
                                                                        <svg class="w-4 h-4 transition-transform ms-2 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                        </svg>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        @if($tugasTimeline->count() > 3)
                        <div class="px-6 py-4 text-center border-t border-slate-100 bg-slate-50/50">
                            <a href="#" class="text-sm font-medium text-indigo-600 transition-colors hover:text-indigo-800">
                                Lihat Semua Aktivitas ({{ $tugasTimeline->count() - 4 }} lainnya) &rarr;
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
