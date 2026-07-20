<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Sistem Tiketing - Dashboard Admin</title>
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
            <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-200">
                <i class="fa-solid fa-user-shield text-amber-500 me-1"></i> Mode Admin
            </span>
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
                            <tr>
                                <td class="p-3.5 font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-3.5 font-bold">{{ $ticket->pelapor->nama_lengkap ?? 'User' }}</td>
                                <td class="p-3.5">{{ $ticket->kategori }}</td>
                                <td class="p-3.5"><span class="px-2 py-1 rounded text-[10px] font-bold border border-amber-200 bg-amber-50 text-amber-600">{{ $ticket->prioritas }}</span></td>
                                
                                <form action="{{ route('admin.ticket.assign', $ticket->id) }}" method="POST">
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
                            <tr>
                                <td class="p-3.5 font-bold text-slate-800">#TKT-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="p-3.5 font-bold">{{ $ticket->pelapor->nama_lengkap ?? 'User' }}</td>
                                <td class="p-3.5 text-slate-800 font-semibold">{{ $ticket->penanggung_jawab }}</td>
                                <td class="p-3.5">
                                    @if($ticket->hasil_resolved_foto)
                                        <a href="{{ asset('storage/' . $ticket->hasil_resolved_foto) }}" target="_blank" class="text-amber-600 hover:underline font-bold">
                                            <i class="fa-solid fa-image me-1"></i>Lihat Foto
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-center">
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

    </main>

    <!-- ================= JAVASCRIPT LOGIKA SWITCH 2 TAB ================= -->
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
            }
        }
    </script>
</body>
</html>