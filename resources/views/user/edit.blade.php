<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ESDM - Tiketing - Edit Profil User</title>
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
                        <span class="text-[9px] sm:text-[10px] text-amber-400 uppercase font-bold tracking-widest block">Portal Pengguna</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('user.dashboard') }}" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl text-xs font-semibold transition-all duration-200 flex items-center gap-2 border border-white/10 active:scale-95">
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
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Edit Profil</h2>
                <p class="text-xs sm:text-sm text-slate-500">Perbarui informasi profil dan kata sandi akun Anda.</p>
            </div>
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-amber-400 to-amber-500 text-[#0a2540] rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shrink-0 shadow-md shadow-amber-500/20">
                <i class="fa-solid fa-user-pen"></i>
            </div>
        </div>

        {{-- Alert Sukses --}}
        @if(session('success'))
            <div class="bg-emerald-900/90 text-white p-4 rounded-2xl shadow-xl flex items-center justify-between border border-emerald-700/50 backdrop-blur-sm animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-500 text-white rounded-xl flex items-center justify-center text-sm shrink-0 shadow-sm">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Form Container --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-8" id="editProfileForm" novalidate>
                @csrf
                @method('PUT')

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
                                <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" placeholder="Nama Lengkap"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800 @error('nama_lengkap') border-red-400 bg-red-50/10 @enderror">
                                <i class="fa-regular fa-user absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 hidden" id="err-nama_lengkap">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span></span>
                            </span>
                            @error('nama_lengkap')
                                <span class="text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-exclamation text-xs"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-2">Alamat Email</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" placeholder="Alamat Email"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800 @error('email') border-red-400 bg-red-50/10 @enderror">
                                <i class="fa-regular fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 hidden" id="err-email">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span></span>
                            </span>
                            @error('email')
                                <span class="text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-exclamation text-xs"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label for="no_telp" class="block text-xs font-bold text-slate-700 mb-2">Nomor HP</label>
                            <div class="relative">
                                <input type="tel" name="no_telp" id="no_telp" value="{{ old('no_telp', $user->no_telp) }}" placeholder="Contoh: 081234567890"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800 @error('no_telp') border-red-400 bg-red-50/10 @enderror">
                                <i class="fa-solid fa-phone absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 hidden" id="err-no_telp">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span></span>
                            </span>
                            @error('no_telp')
                                <span class="text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-exclamation text-xs"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- Divisi (Read-Only) --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="divisi" class="block text-xs font-bold text-slate-700">Divisi / Unit Kerja</label>
                                <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-semibold border border-slate-200/80">Terkunci</span>
                            </div>
                            <div class="relative">
                                <input type="text" id="divisi" value="{{ $user->divisi->nama_divisi ?? $user->divisi ?? 'Divisi Utama' }}" disabled readonly
                                    class="w-full bg-slate-100/90 border border-slate-200/90 pl-10 pr-10 py-3 rounded-xl text-xs font-bold text-slate-600 cursor-not-allowed select-none">
                                <i class="fa-solid fa-building-user absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                                <i class="fa-solid fa-lock absolute right-3.5 top-3.5 text-slate-300 text-xs"></i>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">hubungi Admin jika terjadi kesalahan penempatan divisi.</p>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Ubah Kata Sandi --}}
                <div class="space-y-6 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <div class="w-2.5 h-5 bg-[#0a2540] rounded-full"></div>
                        <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Ubah Kata Sandi</h3>
                    </div>
                    <p class="text-xs text-slate-400 -mt-2">Biarkan kolom di bawah ini tetap kosong jika Anda tidak berencana memperbarui kata sandi.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Password Baru --}}
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-2">Kata Sandi Baru</label>
                            <div class="relative flex items-center">
                                <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi Baru"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-10 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800 @error('password') border-red-400 bg-red-50/10 @enderror">
                                <i class="fa-solid fa-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                <button type="button" class="toggle-password-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1 flex items-center justify-center" data-target="password">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                            <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 hidden" id="err-password">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i> <span></span>
                            </span>
                            @error('password')
                                <span class="text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-exclamation text-xs"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-2">Konfirmasi Kata Sandi</label>
                            <div class="relative flex items-center">
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Kata Sandi Baru"
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
                            <div id="req-length" class="flex items-center gap-1.5 transition-colors">
                                <span class="icon">❌</span> Minimal 8 Karakter
                            </div>
                            <div id="req-letters" class="flex items-center gap-1.5 transition-colors">
                                <span class="icon">❌</span> Harus Mengandung Huruf Kecil (a-z)
                            </div>
                            <div id="req-number" class="flex items-center gap-1.5 transition-colors">
                                <span class="icon">❌</span> Harus Mengandung Angka (0-9)
                            </div>
                            <div id="req-uppercase" class="flex items-center gap-1.5 transition-colors">
                                <span class="icon">❌</span> Harus Mengandung Huruf Besar (A-Z)
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('user.dashboard') }}" class="px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition active:scale-95">
                        Batal
                    </a>
                    <button type="submit" class="px-7 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-[#0a2540] text-xs font-bold transition shadow-md hover:shadow-lg shadow-amber-400/20 active:scale-95 cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // Toggle Password
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

            // Helper Fungsi Tampilkan & Sembunyikan Error
            function showError(fieldId, message) {
                const input = document.getElementById(fieldId);
                const errSpan = document.getElementById('err-' + fieldId);
                if (input && errSpan) {
                    input.classList.add('border-red-400', 'bg-red-50/10');
                    errSpan.querySelector('span').innerText = message;
                    errSpan.classList.remove('hidden');
                }
            }

            function clearError(fieldId) {
                const input = document.getElementById(fieldId);
                const errSpan = document.getElementById('err-' + fieldId);
                if (input && errSpan) {
                    input.classList.remove('border-red-400', 'bg-red-50/10');
                    errSpan.classList.add('hidden');
                }
            }

            // Validasi Real-time / On-Submit
            const form = document.getElementById('editProfileForm');

            function validateForm() {
                let isValid = true;

                // 1. Nama Lengkap
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

                // 2. Email (Notif ala Gmail)
                const email = document.getElementById('email').value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email === '') {
                    showError('email', 'Email wajib diisi.');
                    isValid = false;
                } else if (!emailRegex.test(email)) {
                    showError('email', 'Format alamat email tidak valid!');
                    isValid = false;
                } else {
                    clearError('email');
                }

                // 3. Nomor Telepon
                const telp = document.getElementById('no_telp').value.trim();
                const telpRegex = /^[0-9+\-\s()]{8,20}$/;
                if (telp === '') {
                    showError('no_telp', 'Nomor telepon wajib diisi.');
                    isValid = false;
                } else if (!telpRegex.test(telp)) {
                    showError('no_telp', 'Nomor telepon tidak valid!');
                    isValid = false;
                } else {
                    clearError('no_telp');
                }

                // 4. Password Baru (Jika diisi)
                const pass = document.getElementById('password').value;
                const passConfirm = document.getElementById('password_confirmation').value;

                if (pass.length > 0) {
                    const hasLetter = /[a-z]/.test(pass);
                    const hasUpper = /[A-Z]/.test(pass);
                    const hasNumber = /[0-9]/.test(pass);

                    if (pass.length < 8) {
                        showError('password', 'Password minimal 8 karakter.');
                        isValid = false;
                    } else if (!hasLetter || !hasUpper || !hasNumber) {
                        showError('password', 'Password harus mengandung huruf besar, huruf kecil, dan angka.');
                        isValid = false;
                    } else {
                        clearError('password');
                    }

                    if (passConfirm === '') {
                        showError('password_confirmation', 'Konfirmasi kata sandi wajib diisi.');
                        isValid = false;
                    } else if (pass !== passConfirm) {
                        showError('password_confirmation', 'Konfirmasi kata sandi tidak cocok.');
                        isValid = false;
                    } else {
                        clearError('password_confirmation');
                    }
                } else {
                    clearError('password');
                    clearError('password_confirmation');
                }

                return isValid;
            }

            // Bersihkan error saat pengguna mengetik kembali
            ['nama_lengkap', 'email', 'no_telp', 'password', 'password_confirmation'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', () => clearError(id));
                }
            });

            // Handle Submit Form
            form.addEventListener('submit', (e) => {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });

            // Password Requirements Checklist Box
            let passwordStatus = { length: false, letters: false, number: false, uppercase: false };

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

                    passwordStatus.length = val.length >= 8;
                    passwordStatus.letters = /[a-z]/.test(val);
                    passwordStatus.number = /[0-9]/.test(val);
                    passwordStatus.uppercase = /[A-Z]/.test(val);

                    updateRuleUI('req-length', passwordStatus.length);
                    updateRuleUI('req-letters', passwordStatus.letters);
                    updateRuleUI('req-number', passwordStatus.number);
                    updateRuleUI('req-uppercase', passwordStatus.uppercase);
                });
            }

        });
    </script>
</body>
</html>