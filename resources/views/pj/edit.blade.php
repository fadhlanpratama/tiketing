<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Sistem Tiketing - Edit Profil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-800 flex flex-col">

    <header class="bg-[#0a2540] text-white sticky top-0 z-30 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('image/esdm.png') }}" alt="Logo" class="w-10 h-10 object-contain">
                <div>
                    <h1 class="text-white font-black tracking-wider text-base sm:text-lg leading-tight">SISTEM TIKETING</h1>
                    <span class="text-[9px] sm:text-[10px] text-amber-400 uppercase font-bold tracking-widest block">Portal Pengguna</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('pj.dashboard') }}" class="bg-slate-700/50 hover:bg-slate-700 text-slate-300 hover:text-white px-3.5 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-3xl w-full mx-auto p-4 sm:p-6 lg:p-8">

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Edit Profil</h2>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi data diri dan kata sandi Anda.</p>
            </div>
            <div class="w-12 h-12 bg-amber-400/10 border border-amber-400/20 text-amber-600 rounded-2xl flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-pen"></i>
            </div>
        </div>

        {{-- Toast / Alert Notification --}}
        @if(session('success'))
            <div class="mb-6 bg-slate-900 text-white p-4 rounded-2xl shadow-xl flex items-center justify-between border border-slate-700/50 animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-500 text-white rounded-xl flex items-center justify-center text-sm shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <p class="text-xs font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl text-xs space-y-2 shadow-sm">
                <div class="flex items-center gap-2 font-bold text-red-800">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Periksa Kembali Inputan Anda:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-red-600 pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form action="{{ route('pj.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2 h-4 bg-amber-400 rounded-full"></div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Informasi Pengguna</h3>
                    </div>
                    
                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required
                                class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition @error('nama_lengkap') border-red-500 @enderror">
                            <i class="fa-regular fa-user absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                        </div>
                        @error('nama_lengkap')
                            <p class="text-[11px] text-red-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition @error('email') border-red-500 @enderror">
                            <i class="fa-regular fa-envelope absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                        </div>
                        @error('email')
                            <p class="text-[11px] text-red-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Telp --}}
                    <div>
                        <label for="no_telp" class="block text-xs font-bold text-slate-700 mb-1">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $user->no_telp) }}" placeholder="Contoh: 081234567890" required
                                class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition @error('no_telp') border-red-500 @enderror">
                            <i class="fa-solid fa-phone absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                        </div>
                        @error('no_telp')
                            <p class="text-[11px] text-red-500 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div class="w-2 h-4 bg-[#0a2540] rounded-full"></div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Ubah Password</h3>
                    </div>
                    <p class="text-[11px] text-slate-400 -mt-2">Kosongkan kolom di bawah ini jika tidak ingin mengubah kata sandi.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Password Baru --}}
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-10 py-2.5 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition @error('password') border-red-500 @enderror">
                                <i class="fa-solid fa-lock absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                                <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <i id="eyeIcon1" class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-[11px] text-red-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi baru"
                                    class="w-full bg-slate-50 border border-slate-200 pl-10 pr-10 py-2.5 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition">
                                <i class="fa-solid fa-shield-halved absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                                <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <i id="eyeIcon2" class="fa-regular fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-[11px] text-slate-500 space-y-1">
                        <p class="font-bold text-slate-700"><i class="fa-solid fa-circle-info text-amber-500 mr-1"></i>Syarat Kata Sandi Baru:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-slate-500 pl-1">
                            <li>Minimal 8 karakter</li>
                            <li>Mengandung kombinasi huruf besar & kecil</li>
                            <li>Mengandung angka</li>
                        </ul>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('pj.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-[#0a2540] text-xs font-bold transition shadow-md hover:shadow-lg transform active:scale-95 cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>

    </main>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>