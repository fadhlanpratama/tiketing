@php
    // Syarat menampilkan history chat: In Progress, Resolved, atau Closed (bukan di-close user)
    $tampilChat = in_array($ticket->status, ['In Progress', 'Resolved']) 
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
@endphp

<div class="space-y-4">

    {{-- ================= AREA CHAT / HISTORY CHAT ================= --}}
    @if($tampilChat)
    <div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
            Percakapan {{ $isPj ? 'dengan Pelapor' : 'dengan PJ' }}
        </p>

        {{-- Box History Pesan --}}
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 max-h-64 overflow-y-auto overflow-x-hidden space-y-2">
            @forelse($ticket->messages as $msg)
                @php
                    $isMine = $isPj ? $msg->sender_type === 'pj' : $msg->sender_type === 'user';
                @endphp
                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                    <div class="{{ $isMine ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-700' }} rounded-2xl px-3 py-2 max-w-[75%] text-xs sm:text-sm">
                        <p class="font-semibold text-[10px] opacity-80 mb-0.5">{{ $msg->sender_nama }}</p>
                        <p class="break-words [overflow-wrap:anywhere]">{{ $msg->pesan }}</p>
                        <p class="text-[9px] opacity-70 mt-1">{{ $msg->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-2">Belum ada percakapan.</p>
            @endforelse
        </div>

        {{-- Input Chat HANYA aktif jika status masih 'In Progress' --}}
        @if($ticket->status === 'In Progress')
        <form action="{{ route($chatRoute, $ticket->id) }}" method="POST" class="mt-2 flex gap-2">
            @csrf
            <input type="text" name="pesan" required maxlength="1000"
                   class="flex-1 border border-slate-200 rounded-xl px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Tulis pesan...">
            <button type="submit" class="bg-[#0a2540] hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm px-4 py-2 rounded-xl transition">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>
        @endif
    </div>
    @endif


    {{-- ================= AREA SURVEI (BERADA DI BAWAH CHAT) ================= --}}
    @if($bisaSurvei)
    <div>
        @if(!$isPj)
            {{-- SISI USER --}}
            @if(!$ticket->survei_kepuasan)
            <form action="{{ route('user.ticket.survei', $ticket->id) }}" method="POST" class="bg-green-50 border border-green-100 rounded-xl p-4">
                @csrf
                <p class="text-xs font-semibold text-green-700 mb-2">Bagaimana kepuasan Anda terhadap penyelesaian tiket ini?</p>
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex gap-1 js-star-rating" data-ticket-id="{{ $ticket->id }}">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer" title="{{ $starMap[$i] }}">
                            <input type="radio" name="survei_kepuasan" value="{{ $starMap[$i] }}" class="hidden js-star-input" required>
                            <i class="fa-regular fa-star text-lg text-amber-400 transition" data-star="{{ $i }}"></i>
                        </label>
                        @endfor
                    </div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold text-xs px-4 py-2 rounded-xl transition">
                        Kirim Survei
                    </button>
                </div>
                <p class="js-survei-label text-[11px] text-slate-500 mt-1.5 italic" data-ticket-id="{{ $ticket->id }}">&nbsp;</p>
            </form>
            @else
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
            {{-- SISI PJ (read-only) --}}
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

</div>

<script>
    (function () {
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
    })();
</script>