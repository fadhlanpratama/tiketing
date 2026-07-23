@php
        // Syarat menampilkan history komentar: In Progress, Resolved, atau Closed (bukan di-close user)
        $tampilKomentar = in_array($ticket->status, ['In Progress', 'Resolved']) 
            || ($ticket->status === 'Closed' && $ticket->closed_by !== 'user');

        // Syarat survei: Resolved atau Closed (bukan di-close user)
        $bisaSurvei = $ticket->status === 'Resolved'
            || ($ticket->status === 'Closed' && $ticket->closed_by !== 'user');

        // Pemetaan angka ke teks ENUM database
        $starMap = [
            1 => 'Tidak Puas',
            2 => 'Kurang puas',
            3 => 'Cukup',
            4 => 'Puas',
            5 => 'Sangat Puas',
        ];

        $posisiTersimpan = $ticket->survei_kepuasan
            ? array_search($ticket->survei_kepuasan, $starMap)
            : false;

        // Pop-up survei aktif untuk user jika tiket bisa disurvei dan belum diisi
        $perluSurveiPopUp = !$isPj && $bisaSurvei && !$ticket->survei_kepuasan;
    @endphp

    <div class="space-y-6">

        {{-- ================= BANNER / STATUS SURVEI ================= --}}
        @if($bisaSurvei)
        <div>
            @if(!$isPj)
                @if(!$ticket->survei_kepuasan)
                {{-- Banner pemicu untuk membuka ulang Modal jika user sempat menutupnya --}}
                <div class="bg-gradient-to-r from-amber-500/10 via-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-4 shadow-sm flex items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <p class="text-[10px] font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-star text-amber-500"></i> Survei Kepuasan
                        </p>
                        <p class="text-xs font-bold text-slate-800">Mohon berikan penilaian atas pelayanan kami.</p>
                    </div>
                    <button type="button" onclick="openSurveiModal()" class="bg-amber-400 hover:bg-amber-500 text-[#0a2540] font-black text-xs px-3.5 py-2 rounded-xl transition shadow-sm shrink-0 cursor-pointer">
                        Isi Survei
                    </button>
                </div>
                @else
                {{-- Ringkasan Rating Jika Sudah Diisi --}}
                <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                    <p class="text-xs font-semibold text-green-700 mb-1">Terima kasih atas penilaian Anda</p>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-solid fa-star text-amber-500 text-sm {{ $i > $posisiTersimpan ? 'opacity-25' : '' }}"></i>
                        @endfor
                        <span class="text-xs text-slate-600 ml-2 font-medium">{{ $ticket->survei_kepuasan }}</span>
                    </div>
                </div>
                @endif
            @else
                {{-- Sisi PJ (read-only) --}}
                @if($ticket->survei_kepuasan)
                <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                    <p class="text-xs font-semibold text-green-700 mb-1">Rating dari Pelapor</p>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-solid fa-star text-amber-500 text-sm {{ $i > $posisiTersimpan ? 'opacity-25' : '' }}"></i>
                        @endfor
                        <span class="text-xs text-slate-600 ml-2 font-medium">{{ $ticket->survei_kepuasan }}</span>
                    </div>
                </div>
                @else
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-xs text-slate-500">
                    Menunggu pelapor mengisi survei kepuasan.
                </div>
                @endif
            @endif
        </div>
        @endif


        {{-- ================= AREA DISKUSI & KOMENTAR (FULL FEATURE) ================= --}}
        @if($tampilKomentar)
        <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-comments text-blue-600"></i> Diskusi & Komentar
                </h3>
                <span class="text-xs text-slate-500 font-medium">
                    {{ $ticket->messages->count() }} Komentar
                </span>
            </div>

            {{-- List Thread Komentar Tiket --}}
            <div class="space-y-4 max-h-[420px] overflow-y-auto pr-1">
                @forelse($ticket->messages as $msg)
                    @php
                        $isMsgPj = $msg->sender_type === 'pj';
                        $nama = $msg->sender_nama ?? 'Pengguna';
                        $inisial = strtoupper(substr($nama, 0, 1));
                    @endphp
                    
                    <div class="flex gap-3 p-3.5 rounded-xl border {{ $isMsgPj ? 'bg-slate-50/70 border-slate-200' : 'bg-white border-slate-100 shadow-sm' }}">
                        {{-- Avatar Inisial --}}
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm {{ $isMsgPj ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700' }}">
                                {{ $inisial }}
                            </div>
                        </div>

                        {{-- Isi Komentar --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs sm:text-sm font-bold text-slate-800">{{ $nama }}</span>
                                    {{-- Badge Role Pengirim --}}
                                    @if($isMsgPj)
                                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full">PJ / Petugas</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-600 text-[10px] font-medium px-2 py-0.5 rounded-full">Pelapor</span>
                                    @endif
                                </div>
                                <span class="text-[10px] sm:text-xs text-slate-400 font-medium shrink-0">
                                    {{ $msg->created_at->format('d M Y, H:i') }} WIB
                                </span>
                            </div>

                            <p class="text-xs sm:text-sm text-slate-600 whitespace-pre-line leading-relaxed break-words [overflow-wrap:anywhere] mt-1">
                                {{ $msg->pesan }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <i class="fa-regular fa-comment-dots text-slate-300 text-3xl mb-2"></i>
                        <p class="text-xs text-slate-400 font-medium">Belum ada komentar pada tiket ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Form Tambah Komentar (Hanya Aktif jika Status 'In Progress') --}}
            @if($ticket->status === 'In Progress')
            <form action="{{ route($chatRoute, $ticket->id) }}" method="POST" class="mt-4 pt-4 border-t border-slate-100">
                @csrf
                <div class="space-y-2">
                    <label for="pesan" class="block text-xs font-semibold text-slate-700">Tambah Komentar Baru</label>
                    <textarea name="pesan" id="pesan" rows="3" required maxlength="1000"
                        class="w-full border border-slate-200 rounded-xl p-3 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                        placeholder="Tulis balasan atau instruksi tambahan di sini..."></textarea>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-[#0a2540] hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm cursor-pointer">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Kirim Komentar</span>
                        </button>
                    </div>
                </div>
            </form>
            @else
            <div class="mt-4 pt-3 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-400 font-medium italic">
                    <i class="fa-solid fa-lock text-xs mr-1"></i> Diskusi telah dikunci karena tiket berstatus {{ $ticket->status }}.
                </p>
            </div>
            @endif
        </div>
        @endif

    </div>


    {{-- ================= POP-UP MODAL SURVEI KEPUASAN ================= --}}
    @if($perluSurveiPopUp)
    <div id="surveiModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-all opacity-0 hidden">
        <div class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-100 transform scale-95 transition-all duration-300">
            
            {{-- Header Pop-up --}}
            <div class="bg-gradient-to-r from-[#0a2540] to-[#16406c] text-white p-5 text-center relative">
                <button type="button" onclick="closeSurveiModal()" class="absolute top-4 right-4 text-white/70 hover:text-white transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
                <div class="w-12 h-12 bg-amber-400 text-[#0a2540] rounded-2xl flex items-center justify-center text-xl mx-auto mb-2 shadow-lg">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3 class="text-base font-extrabold">Survei Kepuasan Layanan</h3>
                <p class="text-xs text-slate-300 mt-1">Bagaimana kepuasan Anda terhadap penyelesaian tiket <strong>#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</strong>?</p>
            </div>

            {{-- Form Survei Modal --}}
            <form action="{{ route('user.ticket.survei', $ticket->id) }}" method="POST" class="p-6 space-y-5">
                @csrf
                
                <div class="space-y-3">
                    <p class="text-xs text-center font-bold text-slate-700">Pilih Bintang Penilaian:</p>
                    
                    {{-- Dynamic Stars --}}
                    <div class="flex items-center justify-center gap-2 bg-slate-50 p-4 rounded-2xl border border-slate-100 js-star-rating" data-ticket-id="{{ $ticket->id }}">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer p-1 transition transform hover:scale-125" title="{{ $starMap[$i] }}">
                            <input type="radio" name="survei_kepuasan" value="{{ $starMap[$i] }}" class="hidden js-star-input" required>
                            <i class="fa-regular fa-star text-3xl text-amber-400 hover:text-amber-500 transition" data-star="{{ $i }}"></i>
                        </label>
                        @endfor
                    </div>

                    <div class="text-center h-4">
                        <p class="js-survei-label text-xs font-bold text-amber-600 italic" data-ticket-id="{{ $ticket->id }}">&nbsp;</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2.5 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeSurveiModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs py-2.5 rounded-xl transition cursor-pointer">
                        Batal / Nanti
                    </button>
                    <button type="submit" class="bg-amber-400 hover:bg-amber-500 text-[#0a2540] font-black text-xs py-2.5 rounded-xl transition shadow-md flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Survei</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const surveiModal = document.getElementById('surveiModal');

        function openSurveiModal() {
            if(!surveiModal) return;
            surveiModal.classList.remove('hidden');
            setTimeout(() => {
                surveiModal.classList.remove('opacity-0');
                surveiModal.querySelector('.max-w-md').classList.remove('scale-95');
            }, 50);
        }

        function closeSurveiModal() {
            if(!surveiModal) return;
            surveiModal.classList.add('opacity-0');
            surveiModal.querySelector('.max-w-md').classList.add('scale-95');
            setTimeout(() => {
                surveiModal.classList.add('hidden');
            }, 300);
        }

        // Modal langsung terbuka otomatis saat halaman di-load jika tiket Resolved/Closed
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                openSurveiModal();
            }, 400);
        });

        // Interaksi Pemilihan Bintang Rating
        document.addEventListener('change', function (e) {
            if (!e.target.classList.contains('js-star-input')) return;

            var container = e.target.closest('.js-star-rating');
            if (!container) return;

            var ticketId = container.dataset.ticketId;
            var allInputs = Array.from(container.querySelectorAll('.js-star-input'));
            var selectedIndex = allInputs.indexOf(e.target) + 1;
            var selectedLabel = e.target.value;

            var stars = container.querySelectorAll('[data-star]');
            stars.forEach(function (star) {
                var value = parseInt(star.dataset.star, 10);
                if (value <= selectedIndex) {
                    star.classList.remove('fa-regular');
                    star.classList.add('fa-solid');
                } else {
                    star.classList.remove('fa-solid');
                    star.classList.add('fa-regular');
                }
            });

            var label = document.querySelector('.js-survei-label[data-ticket-id="' + ticketId + '"]');
            if (label) label.innerText = selectedLabel;
        });
    </script>
    @endif