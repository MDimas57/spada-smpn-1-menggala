<x-app-layout>
    <div class="min-h-screen pb-20 bg-gray-50">

        <div class="sticky top-0 z-30 transition-all duration-300 border-b border-gray-200 shadow-sm bg-white/80 backdrop-blur-md">
            <div class="max-w-4xl px-4 py-4 mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-bold leading-tight text-gray-800">
                            {{ $kuis->judul }}
                        </h2>
                        <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                {{ $kuis->soals->count() }} Soal
                            </span>
                            <span class="text-gray-300">|</span>
                            <span class="flex items-center gap-1 font-medium text-orange-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $kuis->durasi_menit }} Menit
                            </span>
                        </div>
                    </div>

                    <div class="hidden w-1/3 md:block">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-teal-400 to-cyan-500 h-2.5 rounded-full" style="width: 0%" id="progress-bar"></div>
                        </div>
                        <div class="mt-1 text-xs text-right text-gray-400">Progress pengerjaan</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl px-4 py-8 mx-auto sm:px-6 lg:px-8">

            @if($kuis->instruksi)
                <div class="p-5 mb-8 border-l-4 shadow-sm bg-cyan-50 border-cyan-500 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="mb-1 text-sm font-bold tracking-wide uppercase text-cyan-800">Instruksi Pengerjaan</h3>
                            <div class="text-sm leading-relaxed text-cyan-700">
                                {!! $kuis->instruksi !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('siswa.kuis.submit', $kuis->id) }}" method="POST" id="form-kuis">
                @csrf

                <div class="space-y-8">
                    @foreach($kuis->soals as $index => $soal)
                        <div class="p-6 transition-shadow bg-white border border-gray-100 shadow-sm rounded-2xl md:p-8 hover:shadow-md question-card" id="soal-{{ $index }}">
                            <div class="flex items-start gap-4">
                                <span class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-lg font-bold text-teal-700 border border-teal-100 shadow-sm bg-teal-50 rounded-xl">
                                    {{ $index + 1 }}
                                </span>

                                <div class="w-full">
                                    <div class="mb-6 text-lg font-medium leading-relaxed prose text-gray-800 max-w-none">
                                        {!! $soal->pertanyaan !!}
                                    </div>

                                    @if($soal->tipe == 'pilihan_ganda')
                                        <div class="space-y-3">
                                            @foreach($soal->opsi_jawaban as $key => $value)
                                                <label class="relative flex items-center p-4 transition-all duration-200 border border-gray-200 cursor-pointer rounded-xl hover:bg-gray-50 hover:border-teal-300 group option-label">
                                                    <input type="radio"
                                                           name="jawaban[{{ $soal->id }}]"
                                                           value="{{ $key }}"
                                                           onchange="updateProgress()"
                                                           class="w-5 h-5 text-teal-600 transition duration-150 ease-in-out border-gray-300 form-radio focus:ring-teal-500 group-hover:border-teal-400">

                                                    <span class="flex w-full ml-3 text-gray-700 group-hover:text-gray-900">
                                                        <span class="w-6 font-bold text-gray-400 transition-colors group-hover:text-teal-500">{{ $key }}.</span>
                                                        <span class="flex-1">{{ $value }}</span>
                                                    </span>

                                                    </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <textarea name="jawaban[{{ $soal->id }}]"
                                                      rows="5"
                                                      oninput="updateProgress()"
                                                      class="w-full text-gray-700 placeholder-gray-400 transition-colors border-gray-300 shadow-sm rounded-xl focus:border-teal-500 focus:ring-teal-500"
                                                      placeholder="Tuliskan jawaban Anda secara lengkap di sini..."></textarea>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col items-center justify-center pt-6 mt-10 border-t border-gray-200">
                    <p class="mb-4 text-sm italic text-gray-500">Pastikan seluruh jawaban telah terisi dengan benar sebelum mengumpulkan.</p>

                    <button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin mengumpulkan jawaban? Waktu akan berhenti dan jawaban akan disimpan.')"
                            class="inline-flex items-center px-8 py-4 text-lg font-bold text-white transition-all duration-200 transform border border-transparent shadow-lg bg-gradient-to-r from-teal-600 to-cyan-600 rounded-xl hover:shadow-teal-500/30 hover:from-teal-700 hover:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 hover:-translate-y-1">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Kirim Jawaban Saya
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Highlight selected option logic
            const radioInputs = document.querySelectorAll('input[type="radio"]');

            radioInputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Reset all styles in this group
                    const name = this.getAttribute('name');
                    const groupInputs = document.querySelectorAll(`input[name="${name}"]`);

                    groupInputs.forEach(groupInput => {
                        const label = groupInput.closest('label');
                        label.classList.remove('bg-teal-50', 'border-teal-500', 'ring-1', 'ring-teal-500');
                        label.classList.add('border-gray-200');
                    });

                    // Set active style
                    if(this.checked) {
                        const label = this.closest('label');
                        label.classList.remove('border-gray-200');
                        label.classList.add('bg-teal-50', 'border-teal-500', 'ring-1', 'ring-teal-500');
                    }

                    updateProgress();
                });
            });
        });

        function updateProgress() {
            const totalQuestions = {{ $kuis->soals->count() }};
            let answeredCount = 0;

            // Hitung Radio Buttons (Pilihan Ganda)
            const radioNames = new Set();
            document.querySelectorAll('input[type="radio"]:checked').forEach(input => {
                radioNames.add(input.name);
            });
            answeredCount += radioNames.size;

            // Hitung Textarea (Essay)
            document.querySelectorAll('textarea').forEach(textarea => {
                if(textarea.value.trim().length > 0) {
                    answeredCount++;
                }
            });

            // Update Width
            const percentage = (answeredCount / totalQuestions) * 100;
            document.getElementById('progress-bar').style.width = percentage + '%';
        }
    </script>
</x-app-layout>
