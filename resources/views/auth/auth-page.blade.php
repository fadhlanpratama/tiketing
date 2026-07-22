<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
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
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center font-sans antialiased p-4">

    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl h-auto md:h-[680px] overflow-hidden flex flex-col md:block py-6 md:py-0" id="authContainer">
        
        <div class="absolute inset-0 opacity-5 pointer-events-none bg-[radial-gradient(#0a2540_1px,transparent_1px)] [background-size:16px_16px] z-0"></div>

        <!-- ================= FORM LOGIN ================= -->
        <div class="relative md:absolute top-0 left-0 w-full md:w-1/2 h-full flex flex-col justify-center items-center px-6 md:px-12 text-center transition-custom z-20 py-4 md:py-0" id="loginFormBox">
            <div class="w-full max-w-sm relative z-20">
                <div class="mb-4 flex flex-col items-center justify-center gap-1">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="h-14 w-auto object-contain mb-1">
                    <span class="text-xs font-bold text-slate-700 tracking-wider uppercase">Sistem Tiketing</span>
                </div>
                
                <h2 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Selamat Datang!</h2>
                <p class="text-sm text-slate-500 mb-6">Silakan masuk menggunakan akun tiketing Anda</p>
                
                <div id="loginAlert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold"></div>

                <div class="space-y-3 mb-1 text-left">
                    <div>
                        <input type="email" id="email_login" placeholder="Masukkan Alamat Email" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                        <span id="field_error_email_login" class="hidden text-[11px] text-red-500 font-semibold mt-1 pl-1 block"></span>
                    </div>
                    
                    <div>
                        <div class="relative">
                            <input type="password" id="password_login" placeholder="Masukkan Password" class="w-full bg-slate-50 border border-slate-200 p-3 pr-11 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                            <button type="button" class="toggle-password-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition" data-target="password_login">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        <span id="field_error_password_login" class="hidden text-[11px] text-red-500 font-semibold mt-1 pl-1 block"></span>
                    </div>
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

        <!-- ================= FORM REGISTER ================= -->
        <div class="hidden md:absolute top-0 right-0 w-full md:w-1/2 h-full overflow-y-auto custom-scrollbar transition-custom z-10 p-6 flex flex-col" id="registerFormBox">
            <div class="w-full max-w-sm relative z-20 mx-auto py-4 my-auto min-h-max">
                <div class="mb-2 flex flex-col items-center justify-center gap-1">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="h-12 w-auto object-contain mb-1">
                    <span class="text-xs font-bold text-slate-700 tracking-wider uppercase">Sistem Tiketing</span>
                </div>

                <h2 class="text-2xl font-extrabold text-slate-900 mb-1 tracking-tight text-center">Buat Akun Baru</h2>
                <p class="text-xs text-slate-500 mb-4 text-center">Daftarkan identitas Anda untuk permohonan akses</p>
                
                <div id="registerAlert" class="hidden mb-3 p-3 rounded-xl text-xs font-semibold text-center"></div>

                <div class="space-y-2 mb-4 text-left">
                    <input type="text" id="nama_lengkap_register" placeholder="Nama Lengkap" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                    
                    <div>
                        <input type="email" id="email_register" placeholder="Alamat Email" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                        <span id="field_error_email_register" class="hidden text-[11px] text-red-500 font-semibold mt-1 pl-1 block"></span>
                    </div>

                    <div>
                        <input type="tel" id="no_telp_register" placeholder="Nomor Telepon" class="w-full bg-slate-50 border border-slate-200 p-2.5 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                        <span id="field_error_no_telp_register" class="hidden text-[11px] text-red-500 font-semibold mt-1 pl-1 block"></span>
                    </div>
                    
                    <div>
                        <div class="relative">
                            <input type="password" id="password_register" placeholder="Masukkan Password" class="w-full bg-slate-50 border border-slate-200 p-2.5 pr-11 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                            <button type="button" class="toggle-password-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition" data-target="password_register">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        <span id="field_error_password_register" class="hidden text-[11px] text-red-500 font-semibold mt-1 pl-1 block"></span>
                    </div>
                    
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

                    <div>
                        <div class="relative">
                            <input type="password" id="password_confirmation_register" placeholder="Konfirmasi Password" class="w-full bg-slate-50 border border-slate-200 p-2.5 pr-11 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-sm">
                            <button type="button" class="toggle-password-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition" data-target="password_confirmation_register">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        <span id="field_error_password_confirmation_register" class="hidden text-[11px] text-red-500 font-semibold mt-1 pl-1 block"></span>
                    </div>

                    <div class="bg-amber-50 border border-amber-200/60 rounded-xl p-2.5 flex items-start gap-2 text-[11px] text-amber-800 font-medium mt-2">
                        <i class="fa-solid fa-circle-info text-amber-500 mt-0.5 shrink-0"></i>
                        <span>Divisi dan hak akses akun akan ditentukan serta diverifikasi oleh Admin setelah proses pendaftaran selesai. Akun hanya dapat digunakan setelah mendapat persetujuan Admin.</span>
                    </div>
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

    <!-- ================= MODAL LUPA PASSWORD ================= -->
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', () => {
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
            clearFormStates();
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

        function showAlertBox(alertId, message, isSuccess = false) {
            const alertBox = document.getElementById(alertId);
            alertBox.innerText = message;
            alertBox.classList.remove('hidden', 'bg-red-100', 'text-red-800', 'bg-green-100', 'text-green-800');
            if (isSuccess) alertBox.classList.add('bg-green-100', 'text-green-800', 'block');
            else alertBox.classList.add('bg-red-100', 'text-red-800', 'block');
        }

        function clearFormStates() {
            document.getElementById('loginAlert').classList.add('hidden');
            document.getElementById('registerAlert').classList.add('hidden');

            document.querySelectorAll('[id^="field_error_"]').forEach(span => {
                span.innerText = "";
                span.classList.add('hidden');
            });

            document.querySelectorAll('input').forEach(el => {
                el.classList.remove('border-red-400', 'bg-red-50/10');
            });
        }

        function showSingleFieldError(fieldId, message) {
            const errorSpan = document.getElementById(`field_error_${fieldId}`);
            const inputEl = document.getElementById(fieldId);
            
            if (errorSpan) {
                errorSpan.innerText = message;
                errorSpan.classList.remove('hidden');
            }
            if (inputEl) {
                inputEl.classList.add('border-red-400', 'bg-red-50/10');
            }
        }

        function markMassalInvalid(elementIds) {
            elementIds.forEach(id => {
                const el = document.getElementById(id);
                if(el) el.classList.add('border-red-400', 'bg-red-50/10');
            });
        }

        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                input.classList.remove('border-red-400', 'bg-red-50/10');
                const span = document.getElementById(`field_error_${input.id}`);
                if(span) span.classList.add('hidden');
            });
        });

        let passwordStatus = { length: false, letters: false, number: false, uppercase: false };

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

        let loginCountdownInterval = null; 
        document.getElementById('submitLoginBtn').addEventListener('click', async () => {
            clearFormStates();
            const email = document.getElementById('email_login').value.trim();
            const password = document.getElementById('password_login').value;

            if (!email || !password) {
                if(!email) markMassalInvalid(['email_login']);
                if(!password) markMassalInvalid(['password_login']);
                return showAlertBox('loginAlert', 'Email dan password wajib diisi!', false);
            }
            if (loginCountdownInterval) clearInterval(loginCountdownInterval);

            try {
                let response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email, password: password })
                });
                
                let result = await response.json();
                if (response.ok && result.success) {
                    showAlertBox('loginAlert', result.message || "Berhasil masuk...", true);
                    setTimeout(() => { window.location.href = result.redirect ? result.redirect : '/dashboard'; }, 800);
                } else {
                    const msg = result.message || "Terjadi kesalahan.";

                    if (result.field) {
                        showSingleFieldError(result.field, msg);
                        return;
                    }

                    if (response.status === 429) {
                        let match = msg.match(/\d+/);
                        if (match) {
                            let secondsLeft = parseInt(match[0], 10);
                            showAlertBox('loginAlert', `Terlalu banyak percobaan login. Silakan tunggu ${secondsLeft} detik lagi.`, false);
                            
                            loginCountdownInterval = setInterval(() => {
                                secondsLeft--;
                                if (secondsLeft <= 0) {
                                    clearInterval(loginCountdownInterval);
                                    showAlertBox('loginAlert', 'Waktu tunggu habis. Silakan coba login kembali.', true);
                                } else {
                                    showAlertBox('loginAlert', `Terlalu banyak percobaan login. Silakan tunggu ${secondsLeft} detik lagi.`, false);
                                }
                            }, 1000);
                        }
                        return;
                    }

                    showAlertBox('loginAlert', msg, false);
                }
            } catch (err) { showAlertBox('loginAlert', err.message, false); }
        });

        document.getElementById('submitRegisterBtn').addEventListener('click', async () => {
            clearFormStates();
            const nama_lengkap = document.getElementById('nama_lengkap_register').value.trim();
            const email = document.getElementById('email_register').value.trim();
            const no_telp = document.getElementById('no_telp_register').value.trim();
            const password = document.getElementById('password_register').value;
            const password_confirmation = document.getElementById('password_confirmation_register').value;

            if (!nama_lengkap || !email || !no_telp || !password || !password_confirmation) {
                let emptyFields = [];
                if(!nama_lengkap) emptyFields.push('nama_lengkap_register');
                if(!email) emptyFields.push('email_register');
                if(!no_telp) emptyFields.push('no_telp_register');
                if(!password) emptyFields.push('password_register');
                if(!password_confirmation) emptyFields.push('password_confirmation_register');
                
                markMassalInvalid(emptyFields);
                return showAlertBox('registerAlert', 'Semua data registrasi wajib diisi!', false);
            }

            if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showSingleFieldError('email_register', 'Format alamat email tidak valid!');
                return;
            }
            if(!/^[0-9+\-\s()]{8,20}$/.test(no_telp)) {
                showSingleFieldError('no_telp_register', 'Nomor telepon tidak valid!');
                return;
            }
            if (!passwordStatus.length || !passwordStatus.letters || !passwordStatus.number || !passwordStatus.uppercase) {
                showSingleFieldError('password_register', 'Password harus minimal 8 karakter dengan kombinasi huruf besar, kecil, dan angka.');
                return;
            }
            if (password !== password_confirmation) {
                showSingleFieldError('password_confirmation_register', 'Konfirmasi password tidak cocok dengan password utama!');
                return;
            }
            
            try {
                let response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ nama_lengkap, email, no_telp, password, password_confirmation })
                });
                
                let result = await response.json();
                if (response.ok && result.success) {
                    showAlertBox('registerAlert', result.message || "Registrasi berhasil. Permohonan pendaftaran Anda telah diterima dan sedang menunggu persetujuan Admin.", true);
                    
                    document.getElementById('nama_lengkap_register').value = "";
                    document.getElementById('email_register').value = "";
                    document.getElementById('no_telp_register').value = "";
                    document.getElementById('password_register').value = "";
                    document.getElementById('password_confirmation_register').value = "";
                    document.getElementById('passwordRequirements').classList.add('hidden');
                    
                    ['req-length', 'req-letters', 'req-number', 'req-uppercase'].forEach(id => updateRuleUI(id, false));
                    setTimeout(() => btnToggleAuth.click(), 1200);
                } else {
                    const msg = result.message || "Gagal membuat akun.";
                    if (msg.toLowerCase().includes('email')) {
                        showSingleFieldError('email_register', msg);
                    } else {
                        showAlertBox('registerAlert', msg, false);
                    }
                }
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

        document.querySelectorAll('.toggle-password-btn').forEach(btn => {
            btn.setAttribute('type', 'button');
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const targetId = btn.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = btn.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'fa-solid fa-eye-slash text-sm text-slate-600 transition-colors'; 
                } else {
                    input.type = 'password';
                    icon.className = 'fa-solid fa-eye text-sm text-slate-400 transition-colors';
                }
            });
        });
    </script>
</body>
</html>