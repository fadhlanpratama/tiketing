@extends('admin.layout')

@section('title', 'Persetujuan Akun')
@section('page-title', 'Persetujuan Akun Pengguna')
@section('page-desc', 'Kelola verifikasi pendaftaran akun pengguna baru')

@section('content')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-6">
    <div class="flex justify-between items-center pb-4 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-amber-100 text-[#0a2540] flex items-center justify-center font-bold">
                <i class="fa-solid fa-user-check text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-base">Permohonan Registrasi Akun</h3>
                <p class="text-[11px] text-slate-400">Verifikasi role dan divisi pengguna sebelum memberikan akses.</p>
            </div>
        </div>
        <span class="bg-amber-100 text-[#0a2540] text-xs font-bold px-3 py-1 rounded-full shadow-sm">
            {{ $pendingUsers->count() }} Permohonan
        </span>
    </div>

    <!-- Tabel Desktop -->
    <div class="hidden md:block overflow-visible">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                    <th class="p-3.5 pl-4">NAMA LENGKAP</th>
                    <th class="p-3.5">EMAIL</th>
                    <th class="p-3.5">NO TELP</th>
                    <th class="p-3.5 min-w-[200px]">PILIH DIVISI</th>
                    <th class="p-3.5 min-w-[180px]">PILIH ROLE</th>
                    <th class="p-3.5 text-center pr-4">AKSI</th>
                </tr>
            </thead>
            <tbody class="text-xs text-slate-600 divide-y divide-slate-100">
                @forelse($pendingUsers as $user)
                <tr class="hover:bg-slate-50/50 transition duration-150">
                    <td class="p-3.5 pl-4 font-bold text-slate-800">{{ $user->nama_lengkap }}</td>
                    <td class="p-3.5 text-slate-500">{{ $user->email }}</td>
                    <td class="p-3.5 font-mono text-slate-600">{{ $user->no_telp }}</td>
                    
                    <!-- Custom Dropdown Divisi + Form Hidden Input -->
                    <td class="p-3.5">
                        <form action="{{ route('admin.user.approve', $user->id) }}" method="POST" id="form-approve-{{ $user->id }}">
                            @csrf
                            <input type="hidden" name="divisi" id="input-divisi-{{ $user->id }}">
                            <input type="hidden" name="role" id="input-role-{{ $user->id }}" value="user">

                            <div class="relative custom-dropdown" data-id="{{ $user->id }}-divisi">
                                <button type="button" class="dropdown-btn w-full bg-slate-50 hover:bg-slate-100/80 border border-slate-200/90 rounded-xl p-2.5 text-xs text-slate-700 font-medium flex items-center justify-between transition outline-none cursor-pointer">
                                    <span class="dropdown-label truncate text-slate-400">-- Pilih Divisi --</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                                </button>
                                <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-200/90 rounded-2xl shadow-xl p-1.5 space-y-0.5 max-h-48 overflow-y-auto no-scrollbar">
                                    @foreach($daftarDivisi as $div)
                                        <div data-value="{{ $div }}" data-target="input-divisi-{{ $user->id }}" class="dropdown-item w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-900 cursor-pointer flex items-center justify-between transition">
                                            <span>{{ $div }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Teks Notifikasi Error Langsung di Bawah Dropdown Divisi -->
                            <p class="error-msg-divisi hidden text-[10px] font-bold text-rose-500 mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-[9px]"></i> Divisi wajib dipilih!
                            </p>
                    </td>

                    <!-- Custom Dropdown Role -->
                    <td class="p-3.5">
                        <div class="relative custom-dropdown" data-id="{{ $user->id }}-role">
                            <button type="button" class="dropdown-btn w-full bg-slate-50 hover:bg-slate-100/80 border border-slate-200/90 rounded-xl p-2.5 text-xs text-slate-700 font-medium flex items-center justify-between transition outline-none cursor-pointer">
                                <span class="dropdown-label truncate text-slate-700 font-bold">User (Pegawai)</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                            </button>
                            <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-200/90 rounded-2xl shadow-xl p-1.5 space-y-0.5 max-h-48 overflow-y-auto no-scrollbar">
                                <div data-value="user" data-target="input-role-{{ $user->id }}" class="dropdown-item w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-900 cursor-pointer flex items-center justify-between transition">
                                    <span>User (Pegawai)</span>
                                </div>
                                <div data-value="pj" data-target="input-role-{{ $user->id }}" class="dropdown-item w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-900 cursor-pointer flex items-center justify-between transition">
                                    <span>PJ (Teknisi)</span>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Tombol Aksi -->
                    <td class="p-3.5 text-center pr-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3.5 py-2 rounded-xl transition text-xs shadow-sm flex items-center gap-1 cursor-pointer">
                                <i class="fa-solid fa-check text-[11px]"></i>
                                <span>Setujui</span>
                            </button>
                        </form>

                            {{-- Form Reject --}}
                            <form action="{{ route('admin.user.reject', $user->id) }}" method="POST" id="form-reject-{{ $user->id }}" class="inline">
                                @csrf
                                <button type="button" onclick="openRejectModal('{{ $user->id }}', '{{ $user->nama_lengkap }}')" class="bg-rose-500 hover:bg-rose-600 text-white font-bold px-3.5 py-2 rounded-xl transition text-xs shadow-sm flex items-center gap-1 cursor-pointer">
                                    <i class="fa-solid fa-xmark text-[11px]"></i>
                                    <span>Tolak</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-slate-400">Tidak ada permohonan pendaftaran akun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tampilan Mobile -->
    <div class="md:hidden space-y-4">
        @forelse($pendingUsers as $user)
        <div class="bg-slate-50/80 border border-slate-200/80 rounded-2xl p-4 space-y-3">
            <div>
                <h4 class="font-bold text-slate-800 text-sm">{{ $user->nama_lengkap }}</h4>
                <p class="text-[11px] text-slate-500"><i class="fa-regular fa-envelope me-1"></i>{{ $user->email }}</p>
                <p class="text-[11px] text-slate-400 font-mono"><i class="fa-solid fa-phone me-1"></i>{{ $user->no_telp }}</p>
            </div>

            <form action="{{ route('admin.user.approve', $user->id) }}" method="POST" id="form-approve-mobile-{{ $user->id }}" class="space-y-2.5">
                @csrf
                <input type="hidden" name="divisi" id="input-divisi-mobile-{{ $user->id }}">
                <input type="hidden" name="role" id="input-role-mobile-{{ $user->id }}" value="user">

                <!-- Mobile Custom Dropdown Divisi -->
                <div class="space-y-1 relative custom-dropdown">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Divisi</label>
                    <button type="button" class="dropdown-btn w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-700 font-medium flex items-center justify-between transition outline-none cursor-pointer">
                        <span class="dropdown-label truncate text-slate-400">-- Pilih Divisi --</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl p-1.5 space-y-0.5 max-h-48 overflow-y-auto no-scrollbar">
                        @foreach($daftarDivisi as $div)
                            <div data-value="{{ $div }}" data-target="input-divisi-mobile-{{ $user->id }}" class="dropdown-item w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-900 cursor-pointer flex items-center justify-between transition">
                                <span>{{ $div }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="error-msg-divisi hidden text-[10px] font-bold text-rose-500 mt-1 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation text-[9px]"></i> Divisi wajib dipilih!
                    </p>
                </div>

                <!-- Mobile Custom Dropdown Role -->
                <div class="space-y-1 relative custom-dropdown">
                    <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Role Hak Akses</label>
                    <button type="button" class="dropdown-btn w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-700 font-medium flex items-center justify-between transition outline-none cursor-pointer">
                        <span class="dropdown-label truncate text-slate-700 font-bold">User (Pegawai)</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl p-1.5 space-y-0.5 max-h-48 overflow-y-auto no-scrollbar">
                        <div data-value="user" data-target="input-role-mobile-{{ $user->id }}" class="dropdown-item w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-900 cursor-pointer flex items-center justify-between transition">
                            <span>User (Pegawai)</span>
                        </div>
                        <div data-value="pj" data-target="input-role-mobile-{{ $user->id }}" class="dropdown-item w-full text-left px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-900 cursor-pointer flex items-center justify-between transition">
                            <span>PJ (Teknisi)</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl transition text-xs shadow-sm flex items-center justify-center gap-1">
                        <i class="fa-solid fa-check"></i> Setujui
                    </button>
            </form>

                    <form action="{{ route('admin.user.reject', $user->id) }}" method="POST" id="form-reject-mobile-{{ $user->id }}" class="flex-1">
                        @csrf
                        <button type="button" onclick="openRejectModal('mobile-{{ $user->id }}', '{{ $user->nama_lengkap }}', true)" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-2.5 rounded-xl transition text-xs shadow-sm flex items-center justify-center gap-1">
                            <i class="fa-solid fa-xmark"></i> Tolak
                        </button>
                    </form>
                </div>
        </div>
        @empty
        <p class="text-center text-slate-400 text-xs py-6">Tidak ada permohonan pendaftaran akun.</p>
        @endforelse
    </div>
</div>

<!-- Modal Pop-up Konfirmasi Reject -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200 opacity-0">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 transform scale-95 transition-transform duration-200 space-y-5 text-center" id="rejectModalCard">
        <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        
        <div class="space-y-2">
            <h3 class="text-lg font-black text-slate-800">Konfirmasi Penolakan</h3>
            <p class="text-xs text-slate-500 leading-relaxed">
                Apakah Anda yakin ingin menolak permohonan pendaftaran dari <span id="rejectUserName" class="font-bold text-slate-800"></span>?
            </p>
        </div>

        <div class="flex items-center gap-2.5 pt-2">
            <button type="button" onclick="closeRejectModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-xl transition text-xs cursor-pointer">
                Batal
            </button>
            <button type="button" id="confirmRejectBtn" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 rounded-xl transition text-xs shadow-md cursor-pointer">
                Ya, Tolak
            </button>
        </div>
    </div>
</div>

<script>
    let activeRejectFormId = null;

    function openRejectModal(userId, userName, isMobile = false) {
        activeRejectFormId = isMobile ? `form-reject-mobile-${userId.replace('mobile-', '')}` : `form-reject-${userId}`;
        document.getElementById('rejectUserName').innerText = userName;

        const modal = document.getElementById('rejectModal');
        const card = document.getElementById('rejectModalCard');

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }, 10);
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        const card = document.getElementById('rejectModalCard');

        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        modal.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            activeRejectFormId = null;
        }, 200);
    }

    document.getElementById('confirmRejectBtn').addEventListener('click', () => {
        if (activeRejectFormId) {
            const form = document.getElementById(activeRejectFormId);
            if (form) form.submit();
        }
    });

    (function initCustomDropdowns() {
        const setupDropdowns = () => {
            // Event memilih item dropdown
            document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
                const btn = dropdown.querySelector('.dropdown-btn');
                const menu = dropdown.querySelector('.dropdown-menu');
                const label = dropdown.querySelector('.dropdown-label');
                const arrow = btn ? btn.querySelector('.fa-chevron-down') : null;
                const items = dropdown.querySelectorAll('.dropdown-item');

                if (!btn || !menu) return;

                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    document.querySelectorAll('.dropdown-menu').forEach(m => { if (m !== menu) m.classList.add('hidden'); });
                    document.querySelectorAll('.fa-chevron-down').forEach(a => { if (a !== arrow) a.classList.remove('rotate-180'); });

                    menu.classList.toggle('hidden');
                    if (arrow) arrow.classList.toggle('rotate-180');
                });

                items.forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const val = item.getAttribute('data-value');
                        const targetInputId = item.getAttribute('data-target');
                        const text = item.querySelector('span') ? item.querySelector('span').innerText : item.innerText;

                        if (targetInputId) {
                            const targetInput = document.getElementById(targetInputId);
                            if (targetInput) targetInput.value = val;
                        }

                        if (label) {
                            label.innerText = text;
                            label.classList.remove('text-slate-400');
                            label.classList.add('text-slate-800', 'font-bold');
                        }

                        // Sembunyikan error dan hapus border merah saat divisi dipilih
                        const errorMsg = dropdown.parentElement.querySelector('.error-msg-divisi') || dropdown.querySelector('.error-msg-divisi');
                        if (errorMsg) errorMsg.classList.add('hidden');
                        btn.classList.remove('border-rose-500', 'bg-rose-50');

                        menu.classList.add('hidden');
                        if (arrow) arrow.classList.remove('rotate-180');
                    });
                });
            });

            // Handler Validasi Form Approve
            document.querySelectorAll('form[action*="approve"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const divisiInput = form.querySelector('input[name="divisi"]');
                    
                    // Cari elemen error & button dropdown yang terhubung dengan form ini
                    const cellTd = form.closest('td') || form;
                    const errorMsg = cellTd.querySelector('.error-msg-divisi');
                    const dropdownBtn = cellTd.querySelector('.dropdown-btn');

                    if (!divisiInput || !divisiInput.value || divisiInput.value.trim() === '') {
                        e.preventDefault(); // Hentikan submit form
                        
                        // Tampilkan pesan error merah tepat di bawah dropdown
                        if (errorMsg) errorMsg.classList.remove('hidden');
                        if (dropdownBtn) {
                            dropdownBtn.classList.add('border-rose-500', 'bg-rose-50');
                        }
                    } else {
                        if (errorMsg) errorMsg.classList.add('hidden');
                        if (dropdownBtn) dropdownBtn.classList.remove('border-rose-500', 'bg-rose-50');
                    }
                });
            });

            // Tutup dropdown jika klik di tempat lain
            document.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
                document.querySelectorAll('.fa-chevron-down').forEach(arrow => arrow.classList.remove('rotate-180'));
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeRejectModal();
                    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
                }
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupDropdowns);
        } else {
            setupDropdowns();
        }
    })();
</script>
@endsection