<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .transition-custom {
            transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center font-sans antialiased p-4">

    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl h-auto md:h-[650px] overflow-hidden flex flex-col md:block py-6 md:py-0" id="authContainer">
        
        <div class="absolute inset-0 opacity-5 pointer-events-none bg-[radial-gradient(#0a2540_1px,transparent_1px)] [background-size:16px_16px] z-0"></div>

        <div class="relative md:absolute top-0 left-0 w-full md:w-1/2 h-full flex flex-col justify-center items-center px-6 md:px-12 text-center transition-custom z-20 py-4 md:py-0" id="loginFormBox">
            <div class="w-full max-w-sm relative z-20">
                <div class="mb-4 flex flex-col items-center justify-center gap-1">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="h-14 w-auto object-contain mb-1">
                    <span class="text-xs font-bold text-slate-700 tracking-wider uppercase">Sistem Tiketing</span>
                </div>
                
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Selamat Datang!</h2>
                <p class="text-sm text-slate-500 mb-6">Silakan masuk menggunakan akun tiketing Anda</p>
                
                <div id="loginAlert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold"></div>

                <div class="space-y-3 mb-6">
                    <input type="email" id="email_login" placeholder="Masukkan Alamat Email" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    <input type="password" id="password_login" placeholder="Masukkan Password" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                </div>
                
                <button type="button" id="submitLoginBtn" class="w-full bg-[#0a2540] hover:bg-[#113357] text-white font-semibold py-3 rounded-xl shadow-lg transition transform active:scale-95 text-sm uppercase tracking-wider">
                    Masuk Aplikasi
                </button>
            </div>
        </div>

        <div class="hidden md:absolute top-0 right-0 w-full md:w-1/2 h-full max-h-[85vh] md:max-h-none overflow-y-auto md:overflow-visible flex flex-col justify-center items-center px-6 md:px-12 text-center transition-custom z-10 py-4 md:py-0" id="registerFormBox">
            <div class="w-full max-w-sm relative z-20 my-auto">
                <div class="mb-2 flex flex-col items-center justify-center gap-1">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="h-12 w-auto object-contain mb-1">
                    <span class="text-xs font-bold text-slate-700 tracking-wider uppercase">sistem tiketing</span>
                </div>

                <h2 class="text-2xl font-extrabold text-slate-900 mb-1 tracking-tight">Buat Akun Baru</h2>
                <p class="text-xs text-slate-500 mb-4">Daftarkan identitas Anda untuk akses layanan</p>
                
                <div id="registerAlert" class="hidden mb-3 p-3 rounded-xl text-xs font-semibold"></div>

                <div class="space-y-2 mb-4 text-left">
                    <input type="text" id="nama_lengkap_register" placeholder="Nama Lengkap" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    <input type="email" id="email_register" placeholder="Alamat Email" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    <input type="tel" id="no_telp_register" placeholder="Nomor Telepon" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    
                    <div class="relative w-full text-left" id="dropdownWrapper">
                        <input type="hidden" id="divisi_register" value="">

                        <button type="button" id="dropdownBtn" class="w-full bg-slate-50 border border-slate-200 p-2.5 pr-10 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition-all text-sm text-slate-400 font-medium flex justify-between items-center cursor-pointer hover:bg-slate-100/60">
                            <span id="dropdownLabel">Pilih Divisi</span>
                            <svg id="dropdownArrow" class="h-4 w-4 text-[#0a2540]/70 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div id="dropdownMenu" class="hidden absolute left-0 z-50 mt-1 w-full max-h-48 overflow-y-auto bg-white border border-slate-200 shadow-2xl rounded-xl p-1 space-y-0.5 text-sm text-slate-700 font-medium">
                            <div data-value="IT" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">IT</div>
                            <div data-value="Humas" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Humas</div>
                            <div data-value="Perpustakaan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Perpustakaan</div>
                            <div data-value="Perencanaan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Perencanaan</div>
                            <div data-value="Keuangan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Keuangan</div>
                            <div data-value="Monitoring" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Monitoring</div>
                            <div data-value="Kepegawaian" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Kepegawaian</div>
                            <div data-value="Sarana Prasarana" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Sarana Prasarana</div>
                            <div data-value="Keamanan dan Kebersihan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900 text-xs sm:text-sm truncate">Keamanan dan Kebersihan</div>
                            <div data-value="Pengadaan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Pengadaan</div>
                            <div data-value="Kearsipan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Kearsipan</div>
                            <div data-value="Angkutan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Angkutan</div>
                        </div>
                    </div>
                    
                    <input type="password" id="password_register" placeholder="Masukan Password" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    
                    <div id="passwordRequirements" class="hidden bg-slate-50 border border-slate-100 rounded-xl p-2.5 space-y-1 text-[11px] font-medium text-slate-500">
                        <div id="req-length" class="flex items-center gap-1.5 transition-colors">
                            <span class="icon">❌</span> Minimal 8 Karakter
                        </div>
                        <div id="req-letters" class="flex items-center gap-1.5 transition-colors">
                            <span class="icon">❌</span> Harus Mengandung Huruf (a-z)
                        </div>
                        <div id="req-number" class="flex items-center gap-1.5 transition-colors">
                            <span class="icon">❌</span> Harus Mengandung Angka (0-9)
                        </div>
                        <div id="req-uppercase" class="flex items-center gap-1.5 transition-colors">
                             <span class="icon">❌</span> Harus Mengandung Huruf Besar (A-Z)
                        </div>
                    </div>

                    <input type="password" id="password_confirmation_register" placeholder="Konfirmasi Password" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                </div>
                
                <button type="button" id="submitRegisterBtn" class="w-full bg-[#0a2540] hover:bg-[#113357] text-white font-semibold py-3 rounded-xl shadow-lg transition transform active:scale-95 text-sm uppercase tracking-wider">
                    Daftar Akun
                </button>
            </div>
        </div>

        <div class="relative md:absolute bottom-0 md:top-0 right-0 w-full md:w-1/2 h-auto md:h-full bg-[#ffde00] text-slate-900 transition-custom z-30 shadow-2xl overflow-hidden mt-4 md:mt-0 py-6 md:py-0 flex items-center justify-center" id="colorOverlayContainer">
            <div class="flex flex-col items-center justify-center px-8 text-center w-full">
                <h2 class="text-xl md:text-3xl font-black mb-2 tracking-tight text-[#0a2540]" id="overlayTitle">Belum Punya Akun?</h2>
                <p class="text-xs md:text-sm text-slate-700 mb-4 md:mb-8 max-w-xs font-medium leading-relaxed" id="overlayDesc">Silakan daftarkan identitas Anda terlebih dahulu untuk menggunakan sistem layanan.</p>
                <button type="button" id="btnToggleAuth" class="bg-[#0a2540] hover:bg-slate-800 text-white font-bold py-2.5 px-8 md:px-10 rounded-xl transition text-xs md:text-sm shadow-md tracking-wide">
                    Buat Akun Disini
                </button>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdownBtn = document.getElementById('dropdownBtn');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const dropdownLabel = document.getElementById('dropdownLabel');
            const dropdownArrow = document.getElementById('dropdownArrow');
            const hiddenInput = document.getElementById('divisi_register');
            const dropdownItems = document.querySelectorAll('.dropdown-item');

            dropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = !dropdownMenu.classList.contains('hidden');
                if (isOpen) {
                    dropdownMenu.classList.add('hidden');
                    dropdownArrow.classList.remove('rotate-180');
                } else {
                    dropdownMenu.classList.remove('hidden');
                    dropdownArrow.classList.add('rotate-180');
                }
            });

            dropdownItems.forEach(item => {
                item.addEventListener('click', () => {
                    const val = item.getAttribute('data-value');
                    hiddenInput.value = val;
                    dropdownLabel.innerText = val;
                    dropdownLabel.classList.remove('text-slate-400');
                    dropdownLabel.classList.add('text-slate-700');
                    
                    dropdownMenu.classList.add('hidden');
                    dropdownArrow.classList.remove('rotate-180');
                });
            });

            document.addEventListener('click', () => {
                dropdownMenu.classList.add('hidden');
                dropdownArrow.classList.remove('rotate-180');
            });
        });

        const btnToggleAuth = document.getElementById('btnToggleAuth');
        const overlayTitle = document.getElementById('overlayTitle');
        const overlayDesc = document.getElementById('overlayDesc');
        const loginFormBox = document.getElementById('loginFormBox');
        const registerFormBox = document.getElementById('registerFormBox');
        const colorOverlayContainer = document.getElementById('colorOverlayContainer');

        let isRegisterActive = false;
        const isMobile = () => window.innerWidth < 768;

        btnToggleAuth.addEventListener('click', () => {
            if (!isRegisterActive) {
                if (isMobile()) {
                    loginFormBox.classList.add('hidden');
                    registerFormBox.classList.remove('hidden');
                    registerFormBox.classList.add('flex');
                } else {
                    colorOverlayContainer.style.right = 'auto';
                    colorOverlayContainer.style.left = '0';
                    registerFormBox.classList.remove('hidden'); 
                    loginFormBox.style.zIndex = "10";
                    registerFormBox.style.zIndex = "20";
                }
                
                overlayTitle.innerText = "Sudah Punya Akun?";
                overlayDesc.innerText = "Jika sudah memiliki akun terverifikasi, silakan kembali masuk ke portal login utama.";
                btnToggleAuth.innerText = "Kembali Login";
                isRegisterActive = true;
            } else {
                if (isMobile()) {
                    registerFormBox.classList.add('hidden');
                    registerFormBox.classList.remove('flex');
                    loginFormBox.classList.remove('hidden');
                } else {
                    colorOverlayContainer.style.left = 'auto';
                    colorOverlayContainer.style.right = '0';
                    loginFormBox.style.zIndex = "20";
                    registerFormBox.style.zIndex = "10";
                }

                overlayTitle.innerText = "Belum Punya Akun?";
                overlayDesc.innerText = "Silakan daftarkan identitas Anda terlebih dahulu untuk menggunakan sistem layanan.";
                btnToggleAuth.innerText = "Buat Akun Disini";
                isRegisterActive = false;
            }
        });

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showAlertBox(alertId, message, isSuccess = false) {
            const alertBox = document.getElementById(alertId);
            alertBox.innerText = message;
            alertBox.classList.remove('hidden', 'bg-red-100', 'text-red-800', 'bg-green-100', 'text-green-800');
            
            if (isSuccess) {
                alertBox.classList.add('bg-green-100', 'text-green-800', 'block');
            } else {
                alertBox.classList.add('bg-red-100', 'text-red-800', 'block');
            }
        }

        let passwordStatus = { length: false, letters: false, number: false , uppercase: false };

        function updateRuleUI(elementId, conditionMet) {
            const el = document.getElementById(elementId);
            const icon = el.querySelector('.icon');
            
            if (conditionMet) {
                icon.innerText = "✅";
                el.classList.remove('text-slate-500');
                el.classList.add('text-green-600');
            } else {
                icon.innerText = "❌";
                el.classList.remove('text-green-600');
                el.classList.add('text-slate-500');
            }
        }

        document.getElementById('password_register').addEventListener('input', (e) => {
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

        // ==================== PROSES LOGIN ====================
        document.getElementById('submitLoginBtn').addEventListener('click', async () => {
            const email = document.getElementById('email_login').value.trim();
            const password = document.getElementById('password_login').value;

            if (!email || !password) {
                return showAlertBox('loginAlert', 'Email dan password wajib diisi!', false);
            }

            try {
                let response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ Email: email, password: password })
                });
                
                let result = await response.json();

                if (response.ok && result.success) {
                    showAlertBox('loginAlert', result.message || "Berhasil masuk...", true);
                    setTimeout(() => {
                        window.location.href = result.redirect ? result.redirect : '/dashboard';
                    }, 800);
                } else {
                    throw new Error(result.message || "Email atau password salah.");
                }
            } catch (err) {
                showAlertBox('loginAlert', err.message, false);
            }
        });

        // ==================== PROSES REGISTER ====================
        document.getElementById('submitRegisterBtn').addEventListener('click', async () => {
            const nama_lengkap = document.getElementById('nama_lengkap_register').value.trim();
            const email = document.getElementById('email_register').value.trim();
            const divisi = document.getElementById('divisi_register').value.trim();
            const no_telp = document.getElementById('no_telp_register').value.trim();
            const password = document.getElementById('password_register').value;
            const password_confirmation = document.getElementById('password_confirmation_register').value;

            if (!nama_lengkap || !email || !divisi || !no_telp || !password || !password_confirmation) {
                return showAlertBox('registerAlert', 'Semua data registrasi wajib diisi!', false);
            }

            if (!passwordStatus.length || !passwordStatus.letters || !passwordStatus.number || !passwordStatus.uppercase) {
                return showAlertBox('registerAlert', 'Password harus terdiri dari minimal 8 karakter dan mengandung huruf besar, huruf kecil, serta angka.', false);
            }

            if (password !== password_confirmation) {
                return showAlertBox('registerAlert', 'Konfirmasi password tidak cocok dengan password utama!', false);
            }

            if(!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                return showAlertBox('registerAlert', 'Email tidak valid!', false);
            }

            if(!no_telp || !/^[0-9+\-\s()]{8,20}$/.test(no_telp)) {
                return showAlertBox('registerAlert', 'Nomor telepon tidak valid!', false);
            }
            
            try {
                let response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ nama_lengkap, email, divisi, no_telp, password, password_confirmation })
                });
                
                let result = await response.json();

                if (response.ok && result.success) {
                    showAlertBox('registerAlert', result.message || "Registrasi berhasil!", true);
                    
                    document.getElementById('nama_lengkap_register').value = "";
                    document.getElementById('email_register').value = "";
                    
                    document.getElementById('divisi_register').value = "";
                    const dropdownLabel = document.getElementById('dropdownLabel');
                    dropdownLabel.innerText = "Pilih Divisi";
                    dropdownLabel.classList.remove('text-slate-700');
                    dropdownLabel.classList.add('text-slate-400');

                    document.getElementById('no_telp_register').value = "";
                    document.getElementById('password_register').value = "";
                    document.getElementById('password_confirmation_register').value = "";
                    
                    document.getElementById('passwordRequirements').classList.add('hidden');
                    
                    const items = ['req-length', 'req-letters', 'req-number', 'req-uppercase'];
                    items.forEach(id => updateRuleUI(id, false));

                    setTimeout(() => btnToggleAuth.click(), 1200);
                } else {
                    throw new Error(result.message || "Gagal membuat akun.");
                }
            } catch (err) {
                showAlertBox('registerAlert', err.message, false);
            }
        });
    </script>
</body>
</html>