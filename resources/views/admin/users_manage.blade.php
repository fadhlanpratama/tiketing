@extends('admin.layout')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('page-desc', 'Kelola seluruh akun pengguna, PJ Teknisi, dan admin')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-visible">

    @if(session('success'))
        <div class="m-5 p-4 bg-emerald-900/90 text-white rounded-2xl shadow-md flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-500 text-white rounded-xl flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-check"></i>
            </div>
            <p class="text-xs sm:text-sm font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="m-5 p-4 bg-rose-900/90 text-white rounded-2xl shadow-md flex items-center gap-3">
            <div class="w-8 h-8 bg-rose-500 text-white rounded-xl flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <p class="text-xs sm:text-sm font-semibold">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Header & Filter -->
    <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-800">Seluruh Pengguna</h3>
            <p class="text-xs text-slate-400">Klik baris pengguna untuk mengedit data.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">

            <!-- Search -->
            <div class="relative w-full sm:w-56">
                <input type="text" id="searchInput" placeholder="Cari nama / email..."
                    class="w-full bg-slate-50 border border-slate-200 p-2.5 pl-9 rounded-xl text-xs font-semibold text-slate-600 focus:ring-2 focus:ring-amber-400 outline-none">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            </div>

            <!-- Filter Role -->
            <div class="relative w-full sm:w-44 custom-dropdown" id="roleFilterWrapper">
                <button type="button" class="dropdown-btn w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-xs font-semibold text-slate-600 flex justify-between items-center cursor-pointer hover:bg-slate-100/80 transition focus:ring-2 focus:ring-amber-400 outline-none">
                    <span class="dropdown-label truncate"><i class="fa-solid fa-user-tag mr-1.5 text-slate-400"></i>Semua Role</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="dropdown-menu hidden absolute right-0 z-50 mt-1.5 w-full bg-white border border-slate-200 shadow-2xl rounded-2xl p-1.5 space-y-0.5 text-xs text-slate-700 font-medium no-scrollbar">
                    <div data-filter-type="role" data-value="semua" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition bg-amber-50 text-amber-900 font-bold"><span>Semua Role</span></div>
                    <div data-filter-type="role" data-value="user" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition"><span>User</span></div>
                    <div data-filter-type="role" data-value="pj" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition"><span>PJ Teknisi</span></div>
                    <div data-filter-type="role" data-value="admin" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition"><span>Admin</span></div>
                </div>
            </div>

            <!-- Filter Status -->
            <div class="relative w-full sm:w-44 custom-dropdown" id="statusFilterWrapper">
                <button type="button" class="dropdown-btn w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-xs font-semibold text-slate-600 flex justify-between items-center cursor-pointer hover:bg-slate-100/80 transition focus:ring-2 focus:ring-amber-400 outline-none">
                    <span class="dropdown-label truncate"><i class="fa-solid fa-signal mr-1.5 text-slate-400"></i>Semua Status</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                </button>
                <div class="dropdown-menu hidden absolute right-0 z-50 mt-1.5 w-full bg-white border border-slate-200 shadow-2xl rounded-2xl p-1.5 space-y-0.5 text-xs text-slate-700 font-medium no-scrollbar">
                    <div data-filter-type="status" data-value="semua" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition bg-amber-50 text-amber-900 font-bold"><span>Semua Status</span></div>
                    <div data-filter-type="status" data-value="active" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition"><span>Aktif</span></div>
                    <div data-filter-type="status" data-value="rejected" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition"><span>Ditolak</span></div>
                </div>
            </div>

            <a href="{{ route('admin.users.create') }}"
                class="w-full sm:w-auto bg-[#0a2540] text-amber-400 font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-[#0a2540]/90 transition flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Tambah Pengguna
            </a>
        </div>
    </div>

    <!-- Tabel Desktop -->
    <div class="hidden md:block overflow-x-auto no-scrollbar rounded-b-2xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 font-bold text-xs tracking-wider uppercase border-b border-slate-100">
                    <th class="p-4 pl-6">NAMA</th>
                    <th class="p-4">EMAIL / NO. TELP</th>
                    <th class="p-4">DIVISI</th>
                    <th class="p-4">ROLE</th>
                    <th class="p-4">STATUS</th>
                    <th class="p-4 pr-6 text-right">AKSI</th>
                </tr>
            </thead>
            <tbody id="userTableBody" class="text-sm text-slate-600 divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="desktop-row hover:bg-slate-50/80 cursor-pointer transition"
                    onclick="window.location='{{ route('admin.users.edit', $user->id) }}'"
                    data-name="{{ strtolower($user->nama_lengkap) }}"
                    data-email="{{ strtolower($user->email) }}"
                    data-role="{{ $user->role }}"
                    data-status="{{ $user->status }}">
                    <td class="p-4 pl-6 font-bold text-slate-800">{{ $user->nama_lengkap }}</td>
                    <td class="p-4">
                        <p class="font-semibold text-slate-700">{{ $user->email }}</p>
                        <span class="text-[11px] text-slate-400">{{ $user->no_telp }}</span>
                    </td>
                    <td class="p-4">{{ $user->divisi ?? '-' }}</td>
                    <td class="p-4">
                        @if($user->role == 'admin')
                            <span class="text-violet-700 font-bold bg-violet-50 px-2.5 py-1 rounded-lg text-xs border border-violet-100">Admin</span>
                        @elseif($user->role == 'pj')
                            <span class="text-blue-700 font-bold bg-blue-50 px-2.5 py-1 rounded-lg text-xs border border-blue-100">PJ Teknisi</span>
                        @else
                            <span class="text-slate-600 font-bold bg-slate-100 px-2.5 py-1 rounded-lg text-xs">User</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($user->status == 'active')
                            <span class="bg-emerald-100 text-emerald-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Aktif</span>
                        @else
                            <span class="bg-rose-100 text-rose-700 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Ditolak</span>
                        @endif
                    </td>
                    <td class="p-4 pr-6" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-end">
                            @if(in_array($user->id, $activeTicketUserIds))
                                <button type="button" disabled
                                    title="Tidak dapat dihapus: masih memiliki tiket Open, In Progress atau Resolved"
                                    class="w-8 h-8 rounded-lg bg-slate-100 text-slate-300 cursor-not-allowed flex items-center justify-center">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            @else
                                <button type="button" onclick="openDeleteModal('{{ $user->id }}', '{{ addslashes($user->nama_lengkap) }}')"
                                    class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-600 transition flex items-center justify-center cursor-pointer" title="Hapus Pengguna">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-8 text-center text-slate-400 text-sm">Belum ada data pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tampilan Mobile -->
    <div id="userMobileContainer" class="md:hidden p-4 space-y-3">
        @forelse($users as $user)
        <div class="mobile-row bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2.5 cursor-pointer"
            onclick="window.location='{{ route('admin.users.edit', $user->id) }}'"
            data-name="{{ strtolower($user->nama_lengkap) }}"
            data-email="{{ strtolower($user->email) }}"
            data-role="{{ $user->role }}"
            data-status="{{ $user->status }}">
            <div class="flex justify-between items-center">
                <span class="font-bold text-slate-800 text-xs">{{ $user->nama_lengkap }}</span>
                @if($user->status == 'active')
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Aktif</span>
                @else
                    <span class="bg-rose-100 text-rose-700 text-[10px] px-2.5 py-0.5 rounded-full font-bold uppercase">Ditolak</span>
                @endif
            </div>
            <div class="text-[11px] text-slate-500 space-y-0.5">
                <p><i class="fa-solid fa-envelope me-1 text-slate-400"></i>{{ $user->email }}</p>
                <p><i class="fa-solid fa-phone me-1 text-slate-400"></i>{{ $user->no_telp }}</p>
                <p><i class="fa-solid fa-building me-1 text-slate-400"></i>{{ $user->divisi ?? '-' }}</p>
                <p><i class="fa-solid fa-user-tag me-1 text-slate-400"></i>{{ ucfirst($user->role) }}</p>
            </div>
            <div class="flex items-center justify-end pt-1" onclick="event.stopPropagation()">
                @if(in_array($user->id, $activeTicketUserIds))
                    <span class="w-full bg-slate-100 text-slate-400 font-bold text-xs py-2 rounded-lg flex items-center justify-center gap-1.5 cursor-not-allowed" title="Masih memiliki tiket aktif">
                        <i class="fa-solid fa-lock"></i> Tidak Dapat Dihapus
                    </span>
                @else
                    <button type="button" onclick="openDeleteModal('{{ $user->id }}', '{{ addslashes($user->nama_lengkap) }}')"
                        class="w-full bg-rose-50 text-rose-600 hover:bg-rose-100 font-bold text-xs py-2 rounded-lg flex items-center justify-center gap-1.5 transition">
                        <i class="fa-solid fa-trash"></i> Hapus Pengguna
                    </button>
                @endif
            </div>
        </div>
        @empty
        <p class="text-center text-slate-400 text-xs py-4">Belum ada data pengguna.</p>
        @endforelse
    </div>

    <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 font-semibold rounded-b-2xl">
        <span id="pageInfo">Menampilkan 0 - 0 dari 0 data</span>
        <div class="flex items-center gap-1.5" id="paginationControls"></div>
    </div>
</div>

<!-- ================= MODAL KONFIRMASI HAPUS ================= -->
<div id="deleteConfirmModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 text-center transform transition-all animate-fade-in space-y-4">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-xl shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-slate-800">Apakah Anda Yakin?</h3>
            <p class="text-xs text-slate-500 mt-1">Akun <span id="deleteUserName" class="font-bold text-slate-700"></span> akan dihapus permanen dari sistem.</p>
        </div>
        <form id="deleteUserForm" action="" method="POST" class="flex items-center gap-3 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold text-xs py-3 rounded-xl transition cursor-pointer">
                Batal
            </button>
            <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs py-3 rounded-xl shadow-md transition cursor-pointer">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const itemsPerPage = 15;
    let currentPage = 1;
    let selectedRoleFilter = 'semua';
    let selectedStatusFilter = 'semua';
    let searchKeyword = '';

    function openDeleteModal(userId, userName) {
        const modal = document.getElementById('deleteConfirmModal');
        const nameSpan = document.getElementById('deleteUserName');
        const form = document.getElementById('deleteUserForm');
        
        nameSpan.innerText = userName;
        form.action = `/admin/users/${userId}`;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const btn = dropdown.querySelector('.dropdown-btn');
        const menu = dropdown.querySelector('.dropdown-menu');
        const arrow = btn.querySelector('.fa-chevron-down');
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.dropdown-menu').forEach(m => { if (m !== menu) m.classList.add('hidden'); });
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });
    });
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.fa-chevron-down').forEach(a => a.classList.remove('rotate-180'));
    });

    const desktopRows = Array.from(document.querySelectorAll('.desktop-row'));
    const mobileRows = Array.from(document.querySelectorAll('.mobile-row'));

    function checkFilter(row) {
        const role = row.getAttribute('data-role');
        const status = row.getAttribute('data-status');
        const name = row.getAttribute('data-name');
        const email = row.getAttribute('data-email');

        const matchRole = selectedRoleFilter === 'semua' || role === selectedRoleFilter;
        const matchStatus = selectedStatusFilter === 'semua' || status === selectedStatusFilter;
        const matchSearch = searchKeyword === '' || name.includes(searchKeyword) || email.includes(searchKeyword);

        return matchRole && matchStatus && matchSearch;
    }

    function renderPage() {
        const isMobile = window.innerWidth < 768;
        const activeRows = isMobile ? mobileRows : desktopRows;
        const filtered = activeRows.filter(checkFilter);
        const totalItems = filtered.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = startIdx + itemsPerPage;

        desktopRows.forEach(r => r.classList.add('hidden'));
        mobileRows.forEach(r => r.classList.add('hidden'));
        filtered.slice(startIdx, endIdx).forEach(r => r.classList.remove('hidden'));

        document.getElementById('pageInfo').innerText = totalItems > 0
            ? `Menampilkan ${startIdx + 1} - ${Math.min(endIdx, totalItems)} dari ${totalItems} data`
            : `Menampilkan 0 data`;

        const paginationControls = document.getElementById('paginationControls');
        paginationControls.innerHTML = '';
        if (totalPages > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.className = `px-3 py-1.5 rounded-lg border border-slate-200 transition ${currentPage === 1 ? 'opacity-40 cursor-not-allowed bg-slate-100' : 'bg-white hover:bg-slate-100 cursor-pointer'}`;
            prevBtn.innerHTML = `<i class="fa-solid fa-chevron-left"></i>`;
            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderPage(); } };
            paginationControls.appendChild(prevBtn);

            const maxVisible = 5;
            const startPage = Math.floor((currentPage - 1) / maxVisible) * maxVisible + 1;
            const endPage = Math.min(startPage + maxVisible - 1, totalPages);

            for (let i = startPage; i <= endPage; i++) {
                const pBtn = document.createElement('button');
                pBtn.className = `px-3 py-1.5 rounded-lg font-bold transition cursor-pointer ${i === currentPage ? 'bg-[#0a2540] text-amber-400' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100'}`;
                pBtn.innerText = i;
                pBtn.onclick = () => { currentPage = i; renderPage(); };
                paginationControls.appendChild(pBtn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.className = `px-3 py-1.5 rounded-lg border border-slate-200 transition ${currentPage === totalPages ? 'opacity-40 cursor-not-allowed bg-slate-100' : 'bg-white hover:bg-slate-100 cursor-pointer'}`;
            nextBtn.innerHTML = `<i class="fa-solid fa-chevron-right"></i>`;
            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderPage(); } };
            paginationControls.appendChild(nextBtn);
        }
    }

    window.addEventListener('resize', renderPage);

    document.getElementById('searchInput').addEventListener('input', (e) => {
        searchKeyword = e.target.value.toLowerCase().trim();
        currentPage = 1;
        renderPage();
    });

    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            const filterType = item.getAttribute('data-filter-type');
            const value = item.getAttribute('data-value');
            const labelText = item.querySelector('span').innerText;
            const dropdownContainer = item.closest('.custom-dropdown');
            const icon = filterType === 'role' ? 'fa-user-tag' : 'fa-signal';

            if (filterType === 'role') selectedRoleFilter = value;
            if (filterType === 'status') selectedStatusFilter = value;

            dropdownContainer.querySelector('.dropdown-label').innerHTML = `<i class="fa-solid ${icon} mr-1.5 text-slate-400"></i>${labelText}`;
            dropdownContainer.querySelectorAll('.dropdown-item').forEach(i => i.classList.remove('bg-amber-50', 'text-amber-900', 'font-bold'));
            item.classList.add('bg-amber-50', 'text-amber-900', 'font-bold');
            dropdownContainer.querySelector('.dropdown-menu').classList.add('hidden');
            dropdownContainer.querySelector('.fa-chevron-down').classList.remove('rotate-180');

            currentPage = 1;
            renderPage();
        });
    });

    renderPage();
</script>
@endpush
@endsection