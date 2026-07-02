<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TIXPass - ESDM Tiketing Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .transition-custom {
            transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center font-sans antialiased p-4">

    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl h-[600px] overflow-hidden" id="authContainer">
        
        <div class="absolute inset-0 opacity-5 pointer-events-none bg-[radial-gradient(#0a2540_1px,transparent_1px)] [background-size:16px_16px] z-0"></div>

        <div class="absolute top-0 left-0 h-full w-full md:w-1/2 flex flex-col justify-center items-center px-8 md:px-12 text-center transition-custom z-20" id="loginFormBox">
            <div class="w-full max-w-sm relative z-20">
                
                <div class="mb-4 flex flex-col items-center justify-center gap-1">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="h-14 w-auto object-contain mb-1">
                    <span class="text-xs font-bold text-slate-700 tracking-wider uppercase">Sistem Tiketing Internal</span>
                </div>
                
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Selamat Datang!</h2>
                <p class="text-sm text-slate-500 mb-6">Silakan masuk menggunakan akun Anda</p>
                
                <div id="loginAlert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold"></div>

                <div class="space-y-3 mb-6">
                    <input type="text" id="username_login" placeholder="Username" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    <input type="password" id="password_login" placeholder="Password" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                </div>
                
                <button type="button" id="submitLoginBtn" class="w-full bg-[#0a2540] hover:bg-[#113357] text-white font-semibold py-3 rounded-xl shadow-lg transition transform active:scale-95 text-sm uppercase tracking-wider">
                    Masuk Aplikasi
                </button>
            </div>
        </div>

        <div class="absolute top-0 right-0 h-full w-full md:w-1/2 flex flex-col justify-center items-center px-8 md:px-12 text-center transition-custom z-10" id="registerFormBox">
            <div class="w-full max-w-sm relative z-20">
                
                <div class="mb-4 flex flex-col items-center justify-center gap-1">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="h-14 w-auto object-contain mb-1">
                    <span class="text-xs font-bold text-slate-700 tracking-wider uppercase">Pendaftaran Akun Baru</span>
                </div>

                <h2 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Buat Akun Baru</h2>
                <p class="text-sm text-slate-500 mb-6">Daftarkan diri Anda untuk akses layanan internal</p>
                
                <div id="registerAlert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold"></div>

                <div class="space-y-3 mb-6">
                    <input type="text" id="username_register" placeholder="Username Baru" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    <input type="password" id="password_register" placeholder="Password Baru" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                </div>
                
                <button type="button" id="submitRegisterBtn" class="w-full bg-[#0a2540] hover:bg-[#113357] text-white font-semibold py-3 rounded-xl shadow-lg transition transform active:scale-95 text-sm uppercase tracking-wider">
                    Daftar Akun
                </button>
            </div>
        </div>

        <div class="absolute top-0 right-0 w-full md:w-1/2 h-full bg-[#ffde00] text-slate-900 transition-custom z-30 shadow-2xl overflow-hidden" id="colorOverlayContainer">
            <div class="absolute inset-0 flex flex-col items-center justify-center px-8 text-center h-full w-full">
                <h2 class="text-2xl md:text-3xl font-black mb-3 tracking-tight text-[#0a2540]" id="overlayTitle">Belum Punya Akun?</h2>
                <p class="hidden md:block text-sm text-slate-700 mb-8 max-w-xs font-medium leading-relaxed" id="overlayDesc">Silakan daftarkan identitas Anda terlebih dahulu untuk menggunakan sistem layanan.</p>
                <button type="button" id="btnToggleAuth" class="bg-[#0a2540] hover:bg-slate-800 text-white font-bold py-2.5 px-8 md:px-10 rounded-xl transition text-xs md:text-sm shadow-md tracking-wide">
                    Buat Akun Disini →
                </button>
            </div>
        </div>

    </div>

    <script>
        const btnToggleAuth = document.getElementById('btnToggleAuth');
        const overlayTitle = document.getElementById('overlayTitle');
        const overlayDesc = document.getElementById('overlayDesc');
        const loginFormBox = document.getElementById('loginFormBox');
        const registerFormBox = document.getElementById('registerFormBox');
        const colorOverlayContainer = document.getElementById('colorOverlayContainer');

        let isRegisterActive = false;

        // Logika Menggeser Tirai Kuning
        btnToggleAuth.addEventListener('click', () => {
            if (!isRegisterActive) {
                colorOverlayContainer.style.left = '0';
                overlayTitle.innerText = "Sudah Punya Akun?";
                overlayDesc.innerText = "Jika sudah memiliki akun terverifikasi, silakan kembali masuk ke portal login utama.";
                btnToggleAuth.innerText = "← Kembali Login";
                loginFormBox.style.zIndex = "10";
                registerFormBox.style.zIndex = "20";
                isRegisterActive = true;
            } else {
                colorOverlayContainer.style.left = 'auto';
                colorOverlayContainer.style.right = '0';
                overlayTitle.innerText = "Belum Punya Akun?";
                overlayDesc.innerText = "Silakan daftarkan identitas Anda terlebih dahulu untuk menggunakan sistem layanan.";
                btnToggleAuth.innerText = "Buat Akun Disini →";
                loginFormBox.style.zIndex = "20";
                registerFormBox.style.zIndex = "10";
                isRegisterActive = false;
            }
        });

        // Pengambilan Token CSRF Keamanan Laravel
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ==================== AJAX PROSES LOGIN ====================
        document.getElementById('submitLoginBtn').addEventListener('click', async () => {
            const username = document.getElementById('username_login').value;
            const password = document.getElementById('password_login').value;
            const alertBox = document.getElementById('loginAlert');

            if(!username || !password) return alert('Username dan password wajib diisi!');

            try {
                let response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username,
                        password
                    })
                });
                
                let result = await response.json();
                console.log("Membaca Respon Server Backend:", result);

                if(response.ok && result.success) {
                    // Beri warna hijau jika sukses
                    alertBox.className = "mb-4 p-3 rounded-xl text-xs font-semibold bg-green-100 text-green-800 block";
                    alertBox.innerText = result.message;
                    
                    // Mengalihkan halaman secara aman berdasarkan data redirect controller
                    setTimeout(() => {
                        window.location.href = result.redirect ? result.redirect : '/dashboard';
                    }, 800);
                } else {
                    throw new Error(result.message || "Akses ditolak.");
                }
            } catch (err) {
                // Beri warna merah jika ada kesalahan data/password salah
                alertBox.className = "mb-4 p-3 rounded-xl text-xs font-semibold bg-red-100 text-red-800 block";
                alertBox.innerText = err.message || "Gagal masuk portal.";
            }
        });

        // ==================== AJAX PROSES REGISTER ====================
        document.getElementById('submitRegisterBtn').addEventListener('click', async () => {
            const username = document.getElementById('username_register').value;
            const password = document.getElementById('password_register').value;
            const alertBox = document.getElementById('registerAlert');

            if(!username || !password) return alert('Mohon tentukan username dan password baru!');

            try {
                let response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username,
                        password
                    })
                });
                let result = await response.json();

                if(response.ok && result.success) {
                    alertBox.className = "mb-4 p-3 rounded-xl text-xs font-semibold bg-green-100 text-green-800 block";
                    alertBox.innerText = result.message;
                    
                    // Bersihkan form register
                    document.getElementById('username_register').value = "";
                    document.getElementById('password_register').value = "";
                    
                    // Kembalikan tirai kuning ke posisi semula setelah 1.2 detik
                    setTimeout(() => btnToggleAuth.click(), 1200);
                } else {
                    throw new Error(result.message || "Gagal mendaftar.");
                }
            } catch (err) {
                alertBox.className = "mb-4 p-3 rounded-xl text-xs font-semibold bg-red-100 text-red-800 block";
                alertBox.innerText = err.message || "Gagal membuat akun.";
            }
        });
    </script>
</body>
</html>