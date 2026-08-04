<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans antialiased min-h-screen flex">

    <!-- ================= SIDEBAR ================= -->
    <aside class="w-64 bg-[#0a2540] text-white flex flex-col shrink-0 min-h-screen shadow-xl sticky top-0 h-screen">
        <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-700/60 shrink-0">
            <img src="{{ asset('image/esdm.png') }}" alt="Logo" class="w-9 h-9 object-contain">
            <div>
                <h1 class="font-black text-sm tracking-wider">ADMIN PANEL</h1>
                <span class="text-[9px] text-amber-400 uppercase font-bold tracking-widest block">Sistem Tiketing</span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <!-- NAV 1: Persetujuan Akun -->
            <button onclick="switchTab('approvalTab')" id="btnApprovalTab" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition bg-amber-400 text-[#0a2540]">
                <i class="fa-solid fa-user-check text-sm"></i>
                <span>Persetujuan Akun</span>
                @if($pendingUsers->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $pendingUsers->count() }}</span>
                @endif
            </button>

            <!-- NAV 2: Manajemen Tiket (PJ & Closed) -->
            <button onclick="switchTab('ticketTab')" id="btnTicketTab" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition text-slate-300 hover:bg-slate-800 hover:text-white">
                <i class="fa-solid fa-ticket text-sm"></i>
                <span>Manajemen Tiket</span>
                @php $totalTiketNotif = $unassignedTickets->count() + $resolvedTickets->count(); @endphp
                @if($totalTiketNotif > 0)
                    <span class="ml-auto bg-amber-500 text-[#0a2540] text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $totalTiketNotif }}</span>
                @endif
            </button>
            <button onclick="switchTab('allTicketTab')" id="btnAllTicketTab" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition text-slate-300 hover:bg-slate-800 hover:text-white">
                <i class="fa-solid fa-list-check text-sm"></i>
                <span>Semua Tiket</span>
                <span class="ml-auto bg-slate-700 text-slate-200 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $allTickets->count() }}</span>
            </button>
        </nav>

        <div class="p-4 border-t border-slate-700/60 shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 p-6 lg:p-8 space-y-6 overflow-y-auto">

        <!-- Header Utama -->
        <div class="flex justify-between items-center bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
            <div>
                <h2 class="text-xl font-bold text-slate-800" id="pageTitle">Persetujuan Akun Pengguna</h2>
                <p class="text-xs text-slate-400 mt-0.5">Kelola verifikasi pendaftaran akun dan alur penanganan tiket helpdesk</p>
            </div>

            <div class="flex items-center gap-3">
                {{-- ===== NOTIFIKASI BELL ADMIN ===== --}}
                <div class="relative" id="notifWrapperAdmin">
                    <button type="button" id="notifBtnAdmin"
                        class="relative bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 w-10 h-10 flex items-center justify-center rounded-xl transition cursor-pointer">
                        <i class="fa-solid fa-bell text-sm"></i>
                        @if(($notifCountAdmin ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $notifCountAdmin > 9 ? '9+' : $notifCountAdmin }}
                        </span>
                        @endif
                    </button>

                    <div id="notifMenuAdmin" class="hidden absolute right-0 mt-2 w-80 max-w-[90vw] bg-white border border-slate-200 shadow-2xl rounded-2xl z-50 overflow-hidden">
                        <div class="p-3 border-b border-slate-100 bg-slate-50">
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Tiket Siap Diverifikasi</p>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                            @forelse(($notifResolvedAdmin ?? []) as $t)
                            <a href="{{ route('admin.ticket.show', $t->id) }}"
                                class="w-full text-left flex gap-2.5 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }} siap ditutup
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate">Pelapor: {{ $t->pelapor->nama_lengkap ?? '-' }} · PJ: {{ $t->penanggung_jawab }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->updated_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty
                            <div class="p-6 text-center">
                                <i class="fa-regular fa-bell-slash text-slate-300 text-2xl mb-2"></i>
                                <p class="text-xs text-slate-400">Belum ada tiket yang perlu ditutup.</p>
                            </div>
                            @endforelse
                            @forelse(($notifUserClosedAdmin ?? []) as $t)
                            <a href="{{ route('admin.ticket.show', $t->id) }}" class="w-full text-left flex gap-2.5 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-slash text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                        #TKT-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }} ditutup pelapor
                                    </p>
                                    <p class="text-[11px] text-slate-500 truncate">Pelapor: {{ $t->pelapor->nama_lengkap ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->updated_at->diffForHumans() }}</p>
                                </div>
                            </a>
                            @empty
                            @endforelse

                            @forelse(($notifPendingUsers ?? []) as $u)
                            <button type="button" onclick="switchTab('approvalTab'); document.getElementById('notifMenuAdmin').classList.add('hidden');" class="w-full text-left flex gap-2.5 p-3 hover:bg-slate-50 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-user-plus text-xs"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">Pendaftaran baru — {{ $u->nama_lengkap }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $u->email }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $u->created_at->diffForHumans() }}</p>
                                </div>
                            </button>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>

                <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200">
                    <i class="fa-solid fa-user-shield text-amber-500 me-1"></i> Mode Admin
                </span>
            </div>
        </div>

        <!-- Notifikasi Session -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 p-4 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-800 p-4 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ================= TAB 1: PERSETUJUAN AKUN ================= -->
        <div id="approvalTab" class="tab-content bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base">Permohonan Registrasi Akun</h3>
                <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
                    {{ $pendingUsers->count() }} Permohonan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-xs font-semibold border-b border-slate-100">
                            <th class="p-3.5">NAMA LENGKAP</th>
                            <th class="p-3.5">EMAIL</th>
                            <th class="p-3.5">NO TELP</th>
                            <th class="p-3.5">PILIH DIVISI</th>
                            <th class="p-3.5">PILIH ROLE</th>
                            <th class="p-3.5 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                        @forelse($pendingUsers as $user)
                        <tr>
                            <td class="p-3.5 font-bold text-slate-800">{{ $user->nama_lengkap }}</td>
                            <td class="p-3.5">{{ $user->email }}</td>
                            <td class="p-3.5">{{ $user->no_telp }}</td>
                            <form action="{{ route('admin.user.approve', $user->id) }}" method="POST">
                                @csrf
                                <td class="p-3.5">
                                    <select name="divisi" required class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs focus:outline-none focus:border-amber-500">
                                        <option value="" disabled selected>-- Pilih Divisi --</option>
                                        @foreach($daftarDivisi as $div)
                                            <option value="{{ $div }}">{{ $div }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-3.5">
                                    <select name="role" required class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs focus:outline-none focus:border-amber-500">
                                        <option value="user" selected>User (Pegawai)</option>
                                        <option value="pj">PJ (Teknisi)</option>
                                    </select>
                                </td>
                                <td class="p-3.5 text-center space-x-1">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-1.5 rounded-lg transition">
                                        Setujui
                                    </button>
                            </form>
                            <form action="{{ route('admin.user.reject', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Tolak pendaftaran ini?')" class="bg-red-500 hover:bg-red-600 text-white font-bold px-3 py-1.5 rounded-lg transition">
                                    Tolak
                                </button>
                            </form>
                                </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="p-8 text-center text-slate-400">Tidak ada permohonan pendaftaran akun.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= TAB 2: MANAJEMEN TIKET (PJ & CLOSED DILUAT DALAM 1 TAMPILAN) ================= -->
        <div id="ticketTab" class="tab-content hidden space-y-8">

            <!-- BAGIAN A: PENUGASAN PJ (TIKET STATUS OPEN) -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-user-gear text-[#0a2540]"></i>
                        <h3 class="font-bold text-slate-800 text-base">Penugasan PJ (Tiket Open)</h3>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ $unassignedTickets->count() }} Belum Ditunjuk PJ
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 -mt-2">Klik baris tiket untuk melihat detail lengkap.</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-xs font-semibold border-b border-slate-100">
                                <th class="p-3.5">ID TIKET</th>
                                <th class="p-3.5">PELAPOR</th>
                                <th class="p-3.5">KATEGORI</th>
                                <th class="p-3.5">PRIORITAS</th>
                                <th class="p-3.5">PILIH PJ / TEKNISI</th>
                                <th class="p-3.5 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                            @forelse($unassignedTickets as $ticket)
                            <tr class="clickable-row cursor-pointer hover:bg-slate-50 transition" data-url="{{ route('admin.ticket.show', $ticket->id) }}">
                                <td class="p-3.5 font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-3.5 font-bold">{{ $ticket->pelapor->nama_lengkap ?? 'User' }}</td>
                                <td class="p-3.5">{{ $ticket->kategori }}</td>
                                <td class="p-3.5"><span class="px-2 py-1 rounded text-[10px] font-bold border border-amber-200 bg-amber-50 text-amber-600">{{ $ticket->prioritas }}</span></td>

                                <form action="{{ route('admin.ticket.assign', $ticket->id) }}" method="POST" class="action-buttons contents">
                                    @csrf
                                    <td class="p-3.5">
                                        <select name="penanggung_jawab" required class="bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs w-full focus:outline-none focus:border-amber-500">
                                            <option value="" disabled selected>-- Pilih PJ --</option>
                                            @foreach($activePjs as $pj)
                                                <option value="{{ $pj->nama_lengkap }}">{{ $pj->nama_lengkap }} ({{ $pj->divisi }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-3.5 text-center">
                                        <button type="submit" class="bg-[#0a2540] hover:bg-slate-800 text-white font-bold px-4 py-2 rounded-lg transition">
                                            Tunjuk PJ
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="p-8 text-center text-slate-400">Tidak ada tiket yang butuh penunjukan PJ.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- BAGIAN B: VERIFIKASI CLOSED (TIKET STATUS RESOLVED) -->
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-green-600"></i>
                        <h3 class="font-bold text-slate-800 text-base">Tiket Resolved (Siap Diclosed Admin)</h3>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ $resolvedTickets->count() }} Siap Verifikasi
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 -mt-2">Klik baris tiket untuk melihat detail lengkap.</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-400 text-xs font-semibold border-b border-slate-100">
                                <th class="p-3.5">ID TIKET</th>
                                <th class="p-3.5">PELAPOR</th>
                                <th class="p-3.5">PJ TEKNISI</th>
                                <th class="p-3.5">BUKTI FOTO</th>
                                <th class="p-3.5 text-center">AKSI UBAH CLOSED</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                            @forelse($resolvedTickets as $ticket)
                            <tr class="clickable-row cursor-pointer hover:bg-slate-50 transition" data-url="{{ route('admin.ticket.show', $ticket->id) }}">
                                <td class="p-3.5 font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-3.5 font-bold">{{ $ticket->pelapor->nama_lengkap ?? 'User' }}</td>
                                <td class="p-3.5 text-slate-800 font-semibold">{{ $ticket->penanggung_jawab }}</td>
                                <td class="p-3.5 action-buttons">
                                    @if($ticket->hasil_resolved_foto)
                                        <a href="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" target="_blank" class="text-amber-600 hover:underline font-bold">
                                            <i class="fa-solid fa-image me-1"></i>Lihat Foto
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-center action-buttons">
                                    <form action="{{ route('admin.ticket.close', $ticket->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Ubah status tiket menjadi Closed?')" class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-lg transition">
                                            <i class="fa-solid fa-lock me-1"></i> Ubah Ke Closed
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="p-8 text-center text-slate-400">Tidak ada tiket berstatus Resolved.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

        <!-- ================= TAB 3: SEMUA TIKET ================= -->
        <div id="allTicketTab" class="tab-content hidden bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">

            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Seluruh Tiket Helpdesk</h3>
                    <p class="text-xs text-slate-400">Rekap semua tiket dari seluruh status. Klik baris untuk melihat detail.</p>
                </div>

                <div class="relative w-full sm:w-52" id="statusFilterWrapperAdmin">
                    <button type="button" id="statusFilterBtnAdmin"
                        class="w-full bg-slate-50 border border-slate-200 p-2.5 pr-10 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition-all text-xs font-semibold text-slate-600 flex justify-between items-center cursor-pointer hover:bg-slate-100/60">
                        <span id="statusFilterLabelAdmin"><i class="fa-solid fa-filter mr-1.5 text-slate-400"></i>Semua Status</span>
                        <svg id="statusFilterArrowAdmin" class="h-4 w-4 text-slate-400 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div id="statusFilterMenuAdmin" class="hidden absolute right-0 sm:left-0 z-50 mt-1 w-full bg-white border border-slate-200 shadow-2xl rounded-xl p-1 space-y-0.5 text-xs text-slate-700 font-medium">
                        <div data-value="semua" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Semua Status</div>
                        <div data-value="Open" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Open</div>
                        <div data-value="In Progress" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">In Progress</div>
                        <div data-value="Resolved" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Resolved</div>
                        <div data-value="Closed" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Closed</div>
                        <div data-value="Dibatalkan" class="status-filter-item-admin px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition">Dibatalkan oleh Pelapor</div>
                    </div>
                </div>
            </div>

            {{-- ===== VERSI DESKTOP ===== --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-semibold text-xs tracking-wider border-b border-slate-100">
                            <th class="p-4 pl-6">ID TIKET</th>
                            <th class="p-4">KATEGORI PERMINTAAN</th>
                            <th class="p-4">PELAPOR</th>
                            <th class="p-4">PJ TEKNISI</th>
                            <th class="p-4">PRIORITAS</th>
                            <th class="p-4 pr-6">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                        @forelse($allTickets as $ticket)
                        <tr class="clickable-row hover:bg-slate-50/50 transition cursor-pointer" data-url="{{ route('admin.ticket.show', $ticket->id) }}" data-status="{{ $ticket->status }}" data-closedby="{{ $ticket->closed_by }}">
                            <td class="p-4 pl-6 font-semibold text-slate-700">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">{{ $ticket->kategori }} — {{ $ticket->sub_kategori }}</p>
                                <span class="text-[11px] text-slate-400">Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</span>
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">{{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                                <span class="text-[11px] text-slate-400">{{ $ticket->pelapor->divisi ?? '-' }}</span>
                            </td>
                            <td class="p-4 text-slate-600">{{ $ticket->penanggung_jawab ?? '-' }}</td>
                            <td class="p-4">
                                @if(strtolower($ticket->prioritas) == 'tinggi')
                                    <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded-lg text-xs border border-red-100"><i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                                @elseif(strtolower($ticket->prioritas) == 'sedang')
                                    <span class="text-amber-600 font-bold bg-amber-50 px-2 py-1 rounded-lg text-xs border border-amber-100"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $ticket->prioritas }}</span>
                                @else
                                    <span class="text-slate-600 font-bold bg-slate-100 px-2 py-1 rounded-lg text-xs"><i class="fa-solid fa-circle-info mr-1"></i>{{ $ticket->prioritas }}</span>
                                @endif
                            </td>
                            <td class="p-4 pr-6">
                                @if($ticket->status == 'Open')
                                    <span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Open</span>
                                @elseif($ticket->status == 'In Progress')
                                    <span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">In Progress</span>
                                @elseif($ticket->status == 'Resolved')
                                    <span class="bg-green-100 text-green-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Resolved</span>
                                @elseif($ticket->status == 'Closed')
                                    <span class="bg-slate-100 text-slate-600 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Closed</span>
                                    @if($ticket->closed_by === 'user')
                                        <span class="text-[10px] text-slate-600 italic block mt-1"><i class="fa-solid fa-user-slash"></i> Dibatalkan oleh Pelapor</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="p-8 text-center text-slate-400 text-sm">Belum ada tiket sama sekali.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ===== VERSI MOBILE ===== --}}
            <div class="md:hidden p-4 space-y-4">
                @forelse($allTickets as $ticket)
                <div class="clickable-row bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3 cursor-pointer" data-url="{{ route('admin.ticket.show', $ticket->id) }}" data-status="{{ $ticket->status }}" data-closedby="{{ $ticket->closed_by }}">

                    <div class="flex justify-between items-center">
                        <span class="font-bold text-slate-700">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>

                        @if($ticket->status == 'Open')
                            <span class="bg-amber-100 text-amber-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="bg-blue-100 text-blue-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">In Progress</span>
                        @elseif($ticket->status == 'Resolved')
                            <span class="bg-green-100 text-green-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Resolved</span>
                        @elseif($ticket->status == 'Closed')
                            <span class="bg-slate-100 text-slate-600 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Closed</span>
                        @endif
                    </div>

                    @if($ticket->status == 'Closed' && $ticket->closed_by === 'user')
                        <p class="text-[10px] text-slate-600 italic"><i class="fa-solid fa-user-slash"></i> Dibatalkan oleh Pelapor</p>
                    @endif

                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">{{ $ticket->kategori }}</h4>
                        <p class="text-xs text-slate-600 mt-0.5">{{ $ticket->sub_kategori }}</p>
                        <p class="text-[11px] text-slate-400 mt-2"><i class="fa-solid fa-user"></i> Pelapor: {{ $ticket->pelapor->nama_lengkap ?? '-' }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5"><i class="fa-solid fa-user-gear"></i> PJ: {{ $ticket->penanggung_jawab ?? 'Belum ditunjuk' }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fa-regular fa-clock"></i> Diajukan: {{ $ticket->created_at->format('Y-m-d, H:i') }} WIB</p>

                        <p class="text-[10px] text-slate-500 font-medium mt-1">
                            <i class="fa-solid fa-layer-group mr-1 text-slate-400"></i>Prioritas:
                            @if(strtolower($ticket->prioritas) == 'tinggi')
                                <span class="text-red-600 font-bold">Tinggi</span>
                            @elseif(strtolower($ticket->prioritas) == 'sedang')
                                <span class="text-amber-600 font-bold">Sedang</span>
                            @else
                                <span class="text-slate-700 font-bold">Rendah</span>
                            @endif
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-center text-slate-400 text-sm py-4">Belum ada tiket sama sekali.</p>
                @endforelse
            </div>

        </div>

    </main>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');

            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.className = "nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition text-slate-300 hover:bg-slate-800 hover:text-white";
            });

            if (tabId === 'approvalTab') {
                document.getElementById('btnApprovalTab').className = "nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition bg-amber-400 text-[#0a2540]";
                document.getElementById('pageTitle').innerText = "Persetujuan Akun Pengguna";
            } else if (tabId === 'ticketTab') {
                document.getElementById('btnTicketTab').className = "nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition bg-amber-400 text-[#0a2540]";
                document.getElementById('pageTitle').innerText = "Manajemen Tiket Helpdesk";
            } else if (tabId === 'allTicketTab') {
                document.getElementById('btnAllTicketTab').className = "nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-bold transition bg-amber-400 text-[#0a2540]";
                document.getElementById('pageTitle').innerText = "Seluruh Tiket Helpdesk";
            }
        }

        // ===== KLIK BARIS / KARTU TIKET UNTUK BUKA DETAIL =====
        document.querySelectorAll('.clickable-row').forEach(row => {
            row.addEventListener('click', function () {
                window.location.href = this.getAttribute('data-url');
            });
        });

        // Cegah klik pada form/tombol/select/link di dalam baris ikut membuka detail
        document.querySelectorAll('.action-buttons').forEach(container => {
            container.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        });

        // ===== FILTER STATUS TAB SEMUA TIKET (client-side) =====
        const statusFilterBtnAdmin = document.getElementById('statusFilterBtnAdmin');
        const statusFilterMenuAdmin = document.getElementById('statusFilterMenuAdmin');
        const statusFilterArrowAdmin = document.getElementById('statusFilterArrowAdmin');
        const statusFilterLabelAdmin = document.getElementById('statusFilterLabelAdmin');

        if (statusFilterBtnAdmin) {
            statusFilterBtnAdmin.addEventListener('click', (e) => {
                e.stopPropagation();
                statusFilterMenuAdmin.classList.toggle('hidden');
                statusFilterArrowAdmin.classList.toggle('rotate-180');
            });

            document.querySelectorAll('.status-filter-item-admin').forEach(item => {
                item.addEventListener('click', () => {
                    const value = item.getAttribute('data-value');
                    statusFilterLabelAdmin.innerHTML = `<i class="fa-solid fa-filter mr-1.5 text-slate-400"></i>${item.innerText}`;
                    statusFilterMenuAdmin.classList.add('hidden');
                    statusFilterArrowAdmin.classList.remove('rotate-180');

                    document.querySelectorAll('#allTicketTab .clickable-row').forEach(row => {
                        const status = row.getAttribute('data-status');
                        const closedBy = row.getAttribute('data-closedby');
                        let show = true;

                        if (value === 'semua') {
                            show = true;
                        } else if (value === 'Dibatalkan') {
                            show = (status === 'Closed' && closedBy === 'user');
                        } else if (value === 'Closed') {
                            show = (status === 'Closed' && closedBy !== 'user');
                        } else {
                            show = (status === value);
                        }

                        row.classList.toggle('hidden', !show);
                    });
                });
            });

            document.addEventListener('click', () => {
                statusFilterMenuAdmin.classList.add('hidden');
                statusFilterArrowAdmin.classList.remove('rotate-180');
            });

            statusFilterMenuAdmin.addEventListener('click', (e) => e.stopPropagation());
        }

        // ===== TOGGLE NOTIFIKASI BELL ADMIN =====
        const notifBtnAdmin = document.getElementById('notifBtnAdmin');
        const notifMenuAdmin = document.getElementById('notifMenuAdmin');

        if (notifBtnAdmin && notifMenuAdmin) {
            notifBtnAdmin.addEventListener('click', (e) => {
                e.stopPropagation();
                notifMenuAdmin.classList.toggle('hidden');
            });

            document.addEventListener('click', () => {
                notifMenuAdmin.classList.add('hidden');
            });

            notifMenuAdmin.addEventListener('click', (e) => e.stopPropagation());
        }
    </script>
</body>
</html>