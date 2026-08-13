<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ESDM - Tiketing - Tambah Pengguna Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
    </style>
</head>
<body class="bg-slate-100/70 min-h-screen font-sans text-slate-800 flex flex-col antialiased">

    {{-- Header Utama --}}
    <header class="bg-[#0a2540] text-white sticky top-0 z-30 shadow-lg border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                    <div>
                        <h1 class="text-white font-black tracking-wider text-base sm:text-lg leading-tight">SISTEM TIKETING</h1>
                        <span class="text-[9px] sm:text-[10px] text-amber-400 uppercase font-bold tracking-widest block">Portal Administrator</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.manage') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-200 flex items-center gap-2 border border-white/10 active:scale-95">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Banner Header Card --}}
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between gap-4">
            <div class="space-y-1.5">
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Tambah Pengguna Baru</h2>
                <p class="text-xs sm:text-sm text-slate-500">Buat akun pengguna baru yang langsung aktif di sistem.</p>
            </div>
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-amber-400 to-amber-500 text-[#0a2540] rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shrink-0 shadow-md shadow-amber-500/20">
                <i class="fa-solid fa-user-plus"></i>
            </div>
        </div>

        {{-- Form Container --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-8" id="createForm" novalidate>
                @csrf

                {{-- SECTION 1: Informasi Pengguna --}}
                <div class="space-y-6">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-amber-400 rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Informasi Data Diri</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800 @error('nama_lengkap') border-red-400 bg-red-50/10 @enderror">
                                <i class="fa-regular fa-user absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 {{ $errors->has('nama_lengkap') ? '' : 'hidden' }}" id="err-nama_lengkap">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span>{{ $errors->first('nama_lengkap') }}</span>
                            </span>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-2">Alamat Email</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="contoh@esdm.go.id"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800 @error('email') border-red-400 bg-red-50/10 @enderror">
                                <i class="fa-regular fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 {{ $errors->has('email') ? '' : 'hidden' }}" id="err-email">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span>{{ $errors->first('email') }}</span>
                            </span>
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label for="no_telp" class="block text-xs font-bold text-slate-700 mb-2">Nomor HP</label>
                            <div class="relative">
                                <input type="tel" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" placeholder="Contoh: 081234567890"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800 @error('no_telp') border-red-400 bg-red-50/10 @enderror">
                                <i class="fa-solid fa-phone absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 {{ $errors->has('no_telp') ? '' : 'hidden' }}" id="err-no_telp">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span>{{ $errors->first('no_telp') }}</span>
                            </span>
                        </div>

                        {{-- Divisi (Custom Dropdown) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Divisi / Unit Kerja</label>
                            <input type="hidden" name="divisi" id="divisi" value="{{ old('divisi') }}">
                            <div class="relative w-full custom-dropdown" id="divisiDropdown">
                                <button type="button" class="dropdown-btn w-full bg-slate-50 border {{ $errors->has('divisi') ? 'border-red-400 bg-red-50/10' : 'border-slate-200' }} py-3 pl-10 pr-4 rounded-xl text-xs font-semibold text-slate-800 flex justify-between items-center cursor-pointer hover:bg-slate-100/80 transition focus:ring-2 focus:ring-amber-400 outline-none">
                                    <i class="fa-solid fa-building-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <span class="dropdown-label truncate {{ old('divisi') ? 'text-slate-800' : 'text-slate-400' }}">
                                        {{ old('divisi') ? old('divisi') : 'Pilih Divisi' }}
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                                </button>
                                <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1.5 max-h-56 overflow-y-auto bg-white border border-slate-200 shadow-2xl rounded-2xl p-1.5 space-y-0.5 text-xs text-slate-700 font-medium">
                                    @foreach($daftarDivisi as $d)
                                        <div data-value="{{ $d }}" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between {{ old('divisi') == $d ? 'bg-amber-50 text-amber-900 font-bold' : '' }}">
                                            <span>{{ $d }}</span>
                                            @if(old('divisi') == $d)
                                                <i class="fa-solid fa-check text-amber-600 text-xs"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 {{ $errors->has('divisi') ? '' : 'hidden' }}" id="err-divisi">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span>{{ $errors->first('divisi') }}</span>
                            </span>
                        </div>

                        {{-- Role (Custom Dropdown) --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-2">Role Akun</label>
                            <input type="hidden" name="role" id="role" value="{{ old('role') }}">
                            <div class="relative w-full custom-dropdown" id="roleDropdown">
                                <button type="button" class="dropdown-btn w-full bg-slate-50 border {{ $errors->has('role') ? 'border-red-400 bg-red-50/10' : 'border-slate-200' }} py-3 pl-10 pr-4 rounded-xl text-xs font-semibold text-slate-800 flex justify-between items-center cursor-pointer hover:bg-slate-100/80 transition focus:ring-2 focus:ring-amber-400 outline-none">
                                    <i class="fa-solid fa-user-tag absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                    <span class="dropdown-label truncate {{ old('role') ? 'text-slate-800' : 'text-slate-400' }}">
                                        @if(old('role') == 'user') User (Pengguna Biasa)
                                        @elseif(old('role') == 'pj') PJ Teknisi (Penanggung Jawab)
                                        @elseif(old('role') == 'admin') Admin (Pengelola Sistem)
                                        @else Pilih Role
                                        @endif
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
                                </button>
                                <div class="dropdown-menu hidden absolute left-0 right-0 z-50 mt-1.5 bg-white border border-slate-200 shadow-2xl rounded-2xl p-1.5 space-y-0.5 text-xs text-slate-700 font-medium">
                                    <div data-value="user" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between {{ old('role') == 'user' ? 'bg-amber-50 text-amber-900 font-bold' : '' }}">
                                        <span>User (Pengguna Biasa)</span>
                                        @if(old('role') == 'user') <i class="fa-solid fa-check text-amber-600 text-xs"></i> @endif
                                    </div>
                                    <div data-value="pj" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between {{ old('role') == 'pj' ? 'bg-amber-50 text-amber-900 font-bold' : '' }}">
                                        <span>PJ Teknisi (Penanggung Jawab)</span>
                                        @if(old('role') == 'pj') <i class="fa-solid fa-check text-amber-600 text-xs"></i> @endif
                                    </div>
                                    <div data-value="admin" class="dropdown-item px-3 py-2.5 rounded-xl cursor-pointer hover:bg-slate-50 transition flex items-center justify-between {{ old('role') == 'admin' ? 'bg-amber-50 text-amber-900 font-bold' : '' }}">
                                        <span>Admin (Pengelola Sistem)</span>
                                        @if(old('role') == 'admin') <i class="fa-solid fa-check text-amber-600 text-xs"></i> @endif
                                    </div>
                                </div>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 {{ $errors->has('role') ? '' : 'hidden' }}" id="err-role">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span>{{ $errors->first('role') }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Kata Sandi --}}
                <div class="space-y-6 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-[#0a2540] rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Kata Sandi Akun</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-2">Kata Sandi</label>
                            <div class="relative flex items-center">
                                <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-10 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800 @error('password') border-red-400 bg-red-50/10 @enderror">
                                <i class="fa-solid fa-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <button type="button" class="toggle-password-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1 flex items-center justify-center" data-target="password">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 {{ $errors->has('password') ? '' : 'hidden' }}" id="err-password">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span>{{ $errors->first('password') }}</span>
                            </span>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-2">Konfirmasi Kata Sandi</label>
                            <div class="relative flex items-center">
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Kata Sandi"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-10 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800">
                                <i class="fa-solid fa-shield-halved absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <button type="button" class="toggle-password-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1 flex items-center justify-center" data-target="password_confirmation">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 hidden" id="err-password_confirmation">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span></span>
                            </span>
                        </div>
                    </div>

                    {{-- Requirements Box --}}
                    <div id="passwordRequirements" class="hidden bg-slate-50 border border-slate-200/80 rounded-2xl p-4 space-y-2 text-xs font-medium text-slate-500">
                        <p class="font-bold text-slate-700 mb-1">Ketentuan Keamanan Kata Sandi:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                            <div id="req-length" class="flex items-center gap-1.5 transition-colors"><span class="icon">❌</span> Minimal 8 Karakter</div>
                            <div id="req-letters" class="flex items-center gap-1.5 transition-colors"><span class="icon">❌</span> Huruf Kecil (a-z)</div>
                            <div id="req-number" class="flex items-center gap-1.5 transition-colors"><span class="icon">❌</span> Angka (0-9)</div>
                            <div id="req-uppercase" class="flex items-center gap-1.5 transition-colors"><span class="icon">❌</span> Huruf Besar (A-Z)</div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.manage') }}" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition active:scale-95">
                        Batal
                    </a>
                    <button type="submit" class="px-7 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-[#0a2540] text-xs font-bold transition shadow-md hover:shadow-lg shadow-amber-400/20 active:scale-95 cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Pengguna</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            document.querySelectorAll('.toggle-password-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = btn.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'fa-regular fa-eye-slash text-xs text-slate-600'; 
                    } else {
                        input.type = 'password';
                        icon.className = 'fa-regular fa-eye text-xs text-slate-400';
                    }
                });
            });

            document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
                const btn = dropdown.querySelector('.dropdown-btn');
                const menu = dropdown.querySelector('.dropdown-menu');
                const arrow = btn.querySelector('.fa-chevron-down');
                const hiddenInput = dropdown.previousElementSibling && dropdown.previousElementSibling.type === 'hidden' 
                    ? dropdown.previousElementSibling 
                    : dropdown.querySelector('input[type="hidden"]');

                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    document.querySelectorAll('.fa-chevron-down').forEach(a => {
                        if (a !== arrow) a.classList.remove('rotate-180');
                    });
                    menu.classList.toggle('hidden');
                    arrow.classList.toggle('rotate-180');
                });

                dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.getAttribute('data-value');
                        const labelText = item.querySelector('span').innerText;
                        const labelSpan = btn.querySelector('.dropdown-label');

                        if (hiddenInput) {
                            hiddenInput.value = val;
                            clearError(hiddenInput.id);
                        }

                        labelSpan.innerText = labelText;
                        labelSpan.classList.remove('text-slate-400');
                        labelSpan.classList.add('text-slate-800');

                        dropdown.querySelectorAll('.dropdown-item').forEach(i => {
                            i.classList.remove('bg-amber-50', 'text-amber-900', 'font-bold');
                            const checkIcon = i.querySelector('.fa-check');
                            if (checkIcon) checkIcon.remove();
                        });

                        item.classList.add('bg-amber-50', 'text-amber-900', 'font-bold');
                        item.insertAdjacentHTML('beforeend', '<i class="fa-solid fa-check text-amber-600 text-xs"></i>');

                        menu.classList.add('hidden');
                        arrow.classList.remove('rotate-180');
                    });
                });
            });

            document.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
                document.querySelectorAll('.fa-chevron-down').forEach(a => a.classList.remove('rotate-180'));
            });

            function showError(fieldId, message) {
                const input = document.getElementById(fieldId);
                const errSpan = document.getElementById('err-' + fieldId);
                const dropdownContainer = document.getElementById(fieldId + 'Dropdown');

                if (dropdownContainer) {
                    const btn = dropdownContainer.querySelector('.dropdown-btn');
                    if (btn) btn.classList.add('border-red-400', 'bg-red-50/10');
                } else if (input) {
                    input.classList.add('border-red-400', 'bg-red-50/10');
                }

                if (errSpan) {
                    errSpan.querySelector('span').innerText = message;
                    errSpan.classList.remove('hidden');
                }
            }

            function clearError(fieldId) {
                const input = document.getElementById(fieldId);
                const errSpan = document.getElementById('err-' + fieldId);
                const dropdownContainer = document.getElementById(fieldId + 'Dropdown');

                if (dropdownContainer) {
                    const btn = dropdownContainer.querySelector('.dropdown-btn');
                    if (btn) btn.classList.remove('border-red-400', 'bg-red-50/10');
                } else if (input) {
                    input.classList.remove('border-red-400', 'bg-red-50/10');
                }

                if (errSpan) {
                    errSpan.classList.add('hidden');
                }
            }

            const form = document.getElementById('createForm');

            function validateForm() {
                let isValid = true;

                const nama = document.getElementById('nama_lengkap').value.trim();
                if (nama === '') {
                    showError('nama_lengkap', 'Nama lengkap wajib diisi.');
                    isValid = false;
                } else if (nama.length < 3) {
                    showError('nama_lengkap', 'Nama lengkap minimal 3 karakter.');
                    isValid = false;
                } else {
                    clearError('nama_lengkap');
                }

                const email = document.getElementById('email').value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === '') {
                    showError('email', 'Email wajib diisi.');
                    isValid = false;
                } else if (!emailRegex.test(email)) {
                    showError('email', 'Format email tidak valid.');
                    isValid = false;
                } else {
                    clearError('email');
                }

                const telp = document.getElementById('no_telp').value.trim();
                const telpRegex = /^[0-9+\-\s()]{8,20}$/;
                if (telp === '') {
                    showError('no_telp', 'Nomor telepon wajib diisi.');
                    isValid = false;
                } else if (!telpRegex.test(telp)) {
                    showError('no_telp', 'Format nomor telepon tidak valid.');
                    isValid = false;
                } else {
                    clearError('no_telp');
                }

                const divisi = document.getElementById('divisi').value;
                if (!divisi) {
                    showError('divisi', 'Divisi wajib dipilih.');
                    isValid = false;
                } else {
                    clearError('divisi');
                }

                const role = document.getElementById('role').value;
                if (!role) {
                    showError('role', 'Role wajib dipilih.');
                    isValid = false;
                } else {
                    clearError('role');
                }

                const pass = document.getElementById('password').value;
                const passConfirm = document.getElementById('password_confirmation').value;

                if (!pass) {
                    showError('password', 'Password wajib diisi.');
                    isValid = false;
                } else if (pass.length < 8 || !/[a-z]/.test(pass) || !/[A-Z]/.test(pass) || !/[0-9]/.test(pass)) {
                    showError('password', 'Password minimal 8 karakter dengan kombinasi huruf besar, kecil, dan angka.');
                    isValid = false;
                } else {
                    clearError('password');
                }

                if (!passConfirm) {
                    showError('password_confirmation', 'Konfirmasi password wajib diisi.');
                    isValid = false;
                } else if (pass !== passConfirm) {
                    showError('password_confirmation', 'Konfirmasi password tidak cocok.');
                    isValid = false;
                } else {
                    clearError('password_confirmation');
                }

                return isValid;
            }

            ['nama_lengkap', 'email', 'no_telp', 'password', 'password_confirmation'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', () => clearError(id));
                }
            });

            form.addEventListener('submit', (e) => {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });

            function updateRuleUI(elementId, conditionMet) {
                const el = document.getElementById(elementId);
                if (!el) return;
                const icon = el.querySelector('.icon');
                if (conditionMet) {
                    icon.innerText = "✅";
                    el.className = "flex items-center gap-1.5 text-emerald-600 font-semibold transition-colors";
                } else {
                    icon.innerText = "❌";
                    el.className = "flex items-center gap-1.5 text-slate-500 transition-colors";
                }
            }

            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                passwordInput.addEventListener('input', (e) => {
                    const val = e.target.value;
                    const requirementsPanel = document.getElementById('passwordRequirements');

                    if (val.length === 0) {
                        requirementsPanel.classList.add('hidden');
                        return;
                    } else {
                        requirementsPanel.classList.remove('hidden');
                    }

                    updateRuleUI('req-length', val.length >= 8);
                    updateRuleUI('req-letters', /[a-z]/.test(val));
                    updateRuleUI('req-number', /[0-9]/.test(val));
                    updateRuleUI('req-uppercase', /[A-Z]/.test(val));
                });
            }

        });
    </script>
</body>
</html>