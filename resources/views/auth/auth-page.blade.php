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
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center font-sans antialiased p-4">

    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl h-auto md:h-[680px] overflow-hidden flex flex-col md:block py-6 md:py-0" id="authContainer">
        
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

                <div class="space-y-3 mb-1">
                    <input type="email" id="email_login" placeholder="Masukkan Alamat Email" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    <input type="password" id="password_login" placeholder="Masukkan Password" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                </div>
                
                <div class="text-right mb-4">
                    <button type="button" id="openForgotModalBtn" class="text-sm font-bold text-[#0a2540] hover:text-[#113357] transition cursor-pointer focus:outline-none">
                        Lupa Password?
                    </button>
                </div>
                
                <button type="button" id="submitLoginBtn" class="w-full bg-[#0a2540] hover:bg-[#113357] text-white font-semibold py-3 rounded-xl shadow-lg transition transform active:scale-95 text-sm uppercase tracking-wider">
                    Masuk Aplikasi
                </button>
            </div>
        </div>

        <div class="hidden md:absolute top-0 right-0 w-full md:w-1/2 h-full overflow-y-auto custom-scrollbar transition-custom z-10 p-6 flex flex-col" id="registerFormBox">
            <div class="w-full max-w-sm relative z-20 mx-auto py-4 my-auto min-h-max">
                <div class="mb-2 flex flex-col items-center justify-center gap-1">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="h-12 w-auto object-contain mb-1">
                    <span class="text-xs font-bold text-slate-700 tracking-wider uppercase">sistem tiketing</span>
                </div>

                <h2 class="text-2xl font-extrabold text-slate-900 mb-1 tracking-tight text-center">Buat Akun Baru</h2>
                <p class="text-xs text-slate-500 mb-4 text-center">Daftarkan identitas Anda untuk akses layanan</p>
                
                <div id="registerAlert" class="hidden mb-3 p-3 rounded-xl text-xs font-semibold text-center"></div>

                <div class="space-y-2 mb-4 text-left">
                    <input type="text" id="nama_lengkap_register" placeholder="Nama Lengkap" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    <input type="email" id="email_register" placeholder="Alamat Email" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    <input type="tel" id="no_telp_register" placeholder="Nomor Telepon" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    
                    <div class="relative w-full text-left" id="roleWrapper">
                        <input type="hidden" id="role_register" value="">
                        <button type="button" id="roleBtn" class="w-full bg-slate-50 border border-slate-200 p-2.5 pr-10 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition-all text-sm text-slate-400 font-medium flex justify-between items-center cursor-pointer hover:bg-slate-100/60">
                            <span id="roleLabel">Pilih Jenis Akun</span>
                            <svg id="roleArrow" class="h-4 w-4 text-[#0a2540]/70 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="roleMenu" class="hidden absolute left-0 z-50 mt-1 w-full bg-white border border-slate-200 shadow-2xl rounded-xl p-1 space-y-0.5 text-sm text-slate-700 font-medium">
                            <div data-value="user" class="role-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Karyawan (User)</div>
                            <div data-value="pj" class="role-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Teknisi (PJ)</div>
                        </div>
                    </div>

                    <div class="relative w-full text-left" id="dropdownWrapper">
                        <input type="hidden" id="divisi_register" value="">
                        <button type="button" id="dropdownBtn" disabled class="w-full bg-slate-200/50 border border-slate-200 p-2.5 pr-10 rounded-xl text-sm text-slate-400 font-medium flex justify-between items-center cursor-not-allowed transition-all">
                            <span id="dropdownLabel">Pilih Jenis Akun Dahulu</span>
                            <svg id="dropdownArrow" class="h-4 w-4 text-[#0a2540]/40 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute left-0 z-50 mt-1 w-full max-h-48 overflow-y-auto bg-white border border-slate-200 shadow-2xl rounded-xl p-1 space-y-0.5 text-sm text-slate-700 font-medium"></div>
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
                
                <button type="button" id="submitRegisterBtn" class="w-full bg-[#0a2540] hover:bg-[#113357] text-white font-semibold py-3 rounded-xl shadow-lg transition transform active:scale-95 text-sm uppercase tracking-wider mb-2">
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

    <div id="forgotPasswordModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative animate-in fade-in zoom-in-95 duration-200">
            <h3 class="text-xl font-bold text-slate-900 mb-2">Reset Password</h3>
            <p class="text-sm text-slate-500 mb-4">Masukkan alamat email terdaftar Anda. Kami akan mengirimkan tautan untuk memperbarui kata sandi Anda.</p>
            
            <div id="modalAlert" class="hidden mb-3 p-3 rounded-xl text-xs font-semibold"></div>
            
            <input type="email" id="forgot_email" placeholder="contoh@email.com" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm mb-4">
            
            <div class="flex gap-3 justify-end">
                <button type="button" id="closeForgotModalBtn" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition">Batal</button>
                <button type="button" id="submitForgotBtn" class="bg-[#0a2540] hover:bg-[#113357] text-white px-5 py-2 text-sm font-semibold rounded-xl transition shadow-md">Kirim Link</button>
            </div>
        </div>
    </div>

   <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roleBtn = document.getElementById('roleBtn');
            const roleMenu = document.getElementById('roleMenu');
            const roleLabel = document.getElementById('roleLabel');
            const roleArrow = document.getElementById('roleArrow');
            const roleInput = document.getElementById('role_register');
            const roleItems = document.querySelectorAll('.role-item');

            const dropdownBtn = document.getElementById('dropdownBtn');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const dropdownLabel = document.getElementById('dropdownLabel');
            const dropdownArrow = document.getElementById('dropdownArrow');
            const hiddenInput = document.getElementById('divisi_register');

            const daftarDivisi = ["IT", "Humas", "Perpustakaan", "Perencanaan", "Keuangan", "Monitoring", "Kepegawaian", "Sarana Prasarana", "Keamanan dan Kebersihan", "Pengadaan", "Kearsipan", "Angkutan"];

            roleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = !roleMenu.classList.contains('hidden');
                closeAllDropdowns();
                if (!isOpen) {
                    roleMenu.classList.remove('hidden');
                    roleArrow.classList.add('rotate-180');
                }
            });

            roleItems.forEach(item => {
                item.addEventListener('click', () => {
                    const selectedRole = item.getAttribute('data-value');
                    roleInput.value = selectedRole;
                    roleLabel.innerText = item.innerText;
                    roleLabel.className = "text-slate-700 font-medium";
                    roleMenu.classList.add('hidden');
                    roleArrow.classList.remove('rotate-180');
                    hiddenInput.value = "";
                    dropdownLabel.innerText = "Pilih Divisi";
                    dropdownLabel.className = "text-slate-400 font-medium";
                    setupDivisiDropdown(selectedRole);
                });
            });

            function setupDivisiDropdown(role) {
                dropdownBtn.removeAttribute('disabled');
                dropdownBtn.className = "w-full bg-slate-50 border border-slate-200 p-2.5 pr-10 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition-all text-sm text-slate-400 font-medium flex justify-between items-center cursor-pointer hover:bg-slate-100/60";
                
                let menuHtml = '';
                daftarDivisi.forEach(div => {
                    let extraClass = div === "Keamanan dan Kebersihan" ? "text-xs sm:text-sm truncate" : "";
                    menuHtml += `<div data-value="${div}" class="dynamic-divisi-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900 ${extraClass}">${div}</div>`;
                });
                
                dropdownMenu.innerHTML = menuHtml;

                document.querySelectorAll('.dynamic-divisi-item').forEach(el => {
                    el.addEventListener('click', () => {
                        const val = el.getAttribute('data-value');
                        hiddenInput.value = val;
                        dropdownLabel.innerText = val;
                        dropdownLabel.className = "text-slate-700 font-medium";
                        dropdownMenu.classList.add('hidden');
                        dropdownArrow.classList.remove('rotate-180');
                    });
                });
            }

            dropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (dropdownBtn.hasAttribute('disabled')) return;
                const isOpen = !dropdownMenu.classList.contains('hidden');
                closeAllDropdowns();
                if (!isOpen) {
                    dropdownMenu.classList.remove('hidden');
                    dropdownArrow.classList.add('rotate-180');
                }
            });

            function closeAllDropdowns() {
                roleMenu.classList.add('hidden');
                roleArrow.classList.remove('rotate-180');
                dropdownMenu.classList.add('hidden');
                dropdownArrow.classList.remove('rotate-180');
            }
            document.addEventListener('click', closeAllDropdowns);

            // ==================== LOGIKA MODAL POP-UP LUPA PASSWORD ====================
            const forgotModal = document.getElementById('forgotPasswordModal');
            const openForgotModalBtn = document.getElementById('openForgotModalBtn');
            const closeForgotModalBtn = document.getElementById('closeForgotModalBtn');
            const submitForgotBtn = document.getElementById('submitForgotBtn');
            const forgotEmailInput = document.getElementById('forgot_email');

            openForgotModalBtn.addEventListener('click', () => {
                forgotModal.classList.remove('hidden');
                forgotEmailInput.value = "";
                document.getElementById('modalAlert').classList.add('hidden');
            });

            closeForgotModalBtn.addEventListener('click', () => {
                forgotModal.classList.add('hidden');
            });

            forgotModal.querySelector('.bg-white').addEventListener('click', (e) => {
                e.stopPropagation();
            });

            submitForgotBtn.addEventListener('click', async () => {
                const email = forgotEmailInput.value.trim();
                if (!email) {
                    showAlertBox('modalAlert', 'Email wajib diisi!', false);
                    return;
                }
                try {
                    let response = await fetch('/password/email', { 
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email: email })
                    });
                    let result = await response.json();
                    if(response.ok) {
                        showAlertBox('modalAlert', 'Link reset password telah dikirim ke email Anda.', true);
                        setTimeout(() => forgotModal.classList.add('hidden'), 2000);
                    } else {
                        throw new Error(result.message || 'Gagal mengirim email reset.');
                    }
                } catch (err) {
                    showAlertBox('modalAlert', err.message, false);
                }
            });
        });

        // ==================== TOGGLE AUTH FORM SWITCHER ====================
        const btnToggleAuth = document.getElementById('btnToggleAuth');
        const overlayTitle = document.getElementById('overlayTitle');
        const overlayDesc = document.getElementById('overlayDesc');
        const loginFormBox = document.getElementById('loginFormBox');
        const registerFormBox = document.getElementById('registerFormBox');
        const colorOverlayContainer = document.getElementById('colorOverlayContainer');

        let isRegisterActive = false;
        const isMobile = () => window.innerWidth < 768;

        btnToggleAuth.addEventListener('click', () => {
            btnToggleAuth.blur();
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
            if (isSuccess) alertBox.classList.add('bg-green-100', 'text-green-800', 'block');
            else alertBox.classList.add('bg-red-100', 'text-red-800', 'block');
        }

        // ==================== VALIDASI DILAYAR PASSWORD ====================
        let passwordStatus = { length: false, letters: false, number: false , uppercase: false };

        function updateRuleUI(elementId, conditionMet) {
            const el = document.getElementById(elementId);
            const icon = el.querySelector('.icon');
            if (conditionMet) {
                icon.innerText = "✅";
                el.className = "flex items-center gap-1.5 text-green-600 transition-colors";
            } else {
                icon.innerText = "❌";
                el.className = "flex items-center gap-1.5 text-slate-500 transition-colors";
            }
        }

        document.getElementById('password_register').addEventListener('input', (e) => {
            const val = e.target.value;
            const requirementsPanel = document.getElementById('passwordRequirements');
            if (val.length === 0) {
                requirementsPanel.classList.add('hidden');
                return;
            } else requirementsPanel.classList.remove('hidden');

            passwordStatus.length = val.length >= 8;
            passwordStatus.letters = /[a-z]/.test(val);
            passwordStatus.number = /[0-9]/.test(val);
            passwordStatus.uppercase = /[A-Z]/.test(val);
            updateRuleUI('req-length', passwordStatus.length);
            updateRuleUI('req-letters', passwordStatus.letters);
            updateRuleUI('req-number', passwordStatus.number);
            updateRuleUI('req-uppercase', passwordStatus.uppercase);
        });

        // ==================== PROSES SUBMIT LOGIN ====================
        let loginCountdownInterval = null; 
        document.getElementById('submitLoginBtn').addEventListener('click', async () => {
            const email = document.getElementById('email_login').value.trim();
            const password = document.getElementById('password_login').value;

            if (!email || !password) return showAlertBox('loginAlert', 'Email dan password wajib diisi!', false);
            if (loginCountdownInterval) clearInterval(loginCountdownInterval);

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
                    setTimeout(() => { window.location.href = result.redirect ? result.redirect : '/dashboard'; }, 800);
                } else {
                    if (response.status === 429 || (result.message && result.message.includes('tunggu'))) {
                        let match = result.message.match(/\d+/);
                        if (match) {
                            let secondsLeft = parseInt(match[0], 10);
                            showAlertBox('loginAlert', `Terlalu banyak percobaan login. Silakan tunggu ${secondsLeft} detik lagi.`, false);
                            loginCountdownInterval = setInterval(() => {
                                secondsLeft--;
                                if (secondsLeft <= 0) {
                                    clearInterval(loginCountdownInterval);
                                    showAlertBox('loginAlert', 'Waktu tunggu habis. Silakan coba login kembali.', true);
                                } else showAlertBox('loginAlert', `Terlalu banyak percobaan login. Silakan tunggu ${secondsLeft} detik lagi.`, false);
                            }, 1000);
                        }
                        return;
                    }
                    throw new Error(result.message || "Email atau password salah.");
                }
            } catch (err) { showAlertBox('loginAlert', err.message, false); }
        });

        // ==================== PROSES SUBMIT REGISTER ====================
        document.getElementById('submitRegisterBtn').addEventListener('click', async () => {
            const nama_lengkap = document.getElementById('nama_lengkap_register').value.trim();
            const email = document.getElementById('email_register').value.trim();
            const role = document.getElementById('role_register').value.trim();
            const divisi = document.getElementById('divisi_register').value.trim();
            const no_telp = document.getElementById('no_telp_register').value.trim();
            const password = document.getElementById('password_register').value;
            const password_confirmation = document.getElementById('password_confirmation_register').value;

            if (!nama_lengkap || !email || !role || !divisi || !no_telp || !password || !password_confirmation) {
                return showAlertBox('registerAlert', 'Semua data registrasi wajib diisi!', false);
            }
            if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return showAlertBox('registerAlert', 'Email tidak valid!', false);
            if(!/^[0-9+\-\s()]{8,20}$/.test(no_telp)) return showAlertBox('registerAlert', 'Nomor telepon tidak valid!', false);
            if (!passwordStatus.length || !passwordStatus.letters || !passwordStatus.number || !passwordStatus.uppercase) {
                return showAlertBox('registerAlert', 'Password harus terdiri dari minimal 8 karakter dan mengandung huruf besar, huruf kecil, serta angka.', false);
            }
            if (password !== password_confirmation) return showAlertBox('registerAlert', 'Konfirmasi password tidak cocok dengan password utama!', false);
            
            try {
                let response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ nama_lengkap, email, role, divisi, no_telp, password, password_confirmation })
                });
                
                let result = await response.json();
                if (response.ok && result.success) {
                    showAlertBox('registerAlert', result.message || "Registrasi berhasil!", true);
                    document.getElementById('nama_lengkap_register').value = "";
                    document.getElementById('email_register').value = "";
                    document.getElementById('role_register').value = "";
                    document.getElementById('roleLabel').innerText = "Pilih Jenis Akun";
                    document.getElementById('roleLabel').className = "text-slate-400";
                    document.getElementById('divisi_register').value = "";
                    
                    const dBtn = document.getElementById('dropdownBtn');
                    dBtn.setAttribute('disabled', 'disabled');
                    dBtn.className = "w-full bg-slate-200/50 border border-slate-200 p-2.5 pr-10 rounded-xl text-sm text-slate-400 font-medium flex justify-between items-center cursor-not-allowed transition-all";
                    
                    document.getElementById('dropdownLabel').innerText = "Pilih Jenis Akun Dahulu";
                    document.getElementById('no_telp_register').value = "";
                    document.getElementById('password_register').value = "";
                    document.getElementById('password_confirmation_register').value = "";
                    document.getElementById('passwordRequirements').classList.add('hidden');
                    
                    ['req-length', 'req-letters', 'req-number', 'req-uppercase'].forEach(id => updateRuleUI(id, false));
                    setTimeout(() => btnToggleAuth.click(), 1200);
                } else throw new Error(result.message || "Gagal membuat akun.");
            } catch (err) { showAlertBox('registerAlert', err.message, false); }
        });

        const loginInputs = [document.getElementById('email_login'), document.getElementById('password_login')];
        loginInputs.forEach(input => {
            input.addEventListener('keydown', (e) => { 
                if (e.key === 'Enter') { 
                    e.preventDefault(); 
                    document.getElementById('submitLoginBtn').click(); 
                } 
            });
        });

        const registerInputs = [
            document.getElementById('nama_lengkap_register'), document.getElementById('email_register'),
            document.getElementById('no_telp_register'), document.getElementById('password_register'),
            document.getElementById('password_confirmation_register')
        ];
        registerInputs.forEach(input => {
            input.addEventListener('keydown', (e) => { 
                if (e.key === 'Enter') { 
                    e.preventDefault(); 
                    document.getElementById('submitRegisterBtn').click(); 
                } 
            });
        });
    </script>
</body>
</html>