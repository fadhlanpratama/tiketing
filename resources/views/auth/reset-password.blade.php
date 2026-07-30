<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
    </style>
</head>
<body class="bg-slate-100/70 min-h-screen font-sans text-slate-800 flex items-center justify-center antialiased p-4">

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm w-full max-w-md p-6 sm:p-8">

        {{-- Header --}}
        <div class="flex flex-col items-center justify-center gap-1 mb-6">
            <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="h-12 w-auto object-contain mb-1">
            <span class="text-xs font-bold text-slate-700 tracking-wider uppercase">Sistem Tiketing</span>
        </div>

        <div class="flex items-center gap-2.5 border-b border-slate-100 pb-3 mb-6">
            <div class="w-2.5 h-5 bg-amber-400 rounded-full"></div>
            <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Reset Kata Sandi</h3>
        </div>

        <p class="text-xs text-slate-500 mb-6 -mt-2">Masukkan kata sandi baru untuk akun Anda.</p>

        {{-- Alert --}}
        <div id="resetAlert" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold"></div>

        <input type="hidden" id="token" value="{{ $token }}">

        <div class="space-y-5">
            {{-- Email (read-only) --}}
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-2">Alamat Email</label>
                <div class="relative">
                    <input type="email" id="email" value="{{ $email }}" readonly disabled
                        class="w-full bg-slate-100/90 border border-slate-200/90 pl-10 pr-4 py-3 rounded-xl text-xs font-bold text-slate-600 cursor-not-allowed select-none">
                    <i class="fa-regular fa-envelope absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            {{-- Password Baru --}}
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-2">Kata Sandi Baru</label>
                <div class="relative flex items-center">
                    <input type="password" id="password" placeholder="Masukkan Kata Sandi Baru"
                        class="w-full bg-slate-50 border border-slate-200 pl-10 pr-10 py-3 rounded-xl focus:bg-white focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition text-xs font-semibold text-slate-800">
                    <i class="fa-solid fa-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <button type="button" class="toggle-password-btn absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1 flex items-center justify-center" data-target="password">
                        <i class="fa-regular fa-eye text-xs"></i>
                    </button>
                </div>
                <span class="error-msg text-xs text-red-500 font-medium mt-1.5 pl-0.5 flex items-center gap-1.5 hidden" id="err-password">
                    <i class="fa-solid fa-circle-exclamation text-xs"></i> <span></span>
                </span>
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-2">Konfirmasi Kata Sandi</label>
                <div class="relative flex items-center">
                    <input type="password" id="password_confirmation" placeholder="Konfirmasi Kata Sandi Baru"
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

            {{-- Password Requirements --}}
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

        <div class="pt-6 mt-6 border-t border-slate-100">
            <button id="submitReset" class="w-full bg-[#0a2540] hover:bg-[#113357] text-white font-semibold py-3 rounded-xl shadow-lg transition transform active:scale-95 text-sm uppercase tracking-wider flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i>
                <span>Reset Password</span>
            </button>
        </div>

    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

        function showAlert(msg, ok) {
            const box = document.getElementById('resetAlert');
            box.innerText = msg;
            box.classList.remove('hidden', 'bg-red-100', 'text-red-800', 'bg-green-100', 'text-green-800');
            box.classList.add(ok ? 'bg-green-100' : 'bg-red-100', ok ? 'text-green-800' : 'text-red-800');
        }

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

        ['password', 'password_confirmation'].forEach(id => {
            document.getElementById(id).addEventListener('input', () => clearError(id));
        });

        // Password Requirements Checklist
        let passwordStatus = { length: false, letters: false, number: false, uppercase: false };

        function updateRuleUI(elementId, conditionMet) {
            const el = document.getElementById(elementId);
            const icon = el.querySelector('.icon');
            if (conditionMet) {
                icon.innerText = "✅";
                el.className = "flex items-center gap-1.5 text-emerald-600 font-semibold transition-colors";
            } else {
                icon.innerText = "❌";
                el.className = "flex items-center gap-1.5 text-slate-500 transition-colors";
            }
        }

        document.getElementById('password').addEventListener('input', (e) => {
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

        function validateForm() {
            let isValid = true;
            const pass = document.getElementById('password').value;
            const passConfirm = document.getElementById('password_confirmation').value;

            const hasLetter = /[a-z]/.test(pass);
            const hasUpper = /[A-Z]/.test(pass);
            const hasNumber = /[0-9]/.test(pass);

            if (pass === '') {
                showError('password', 'Kata sandi baru wajib diisi.');
                isValid = false;
            } else if (pass.length < 8 || !hasLetter || !hasUpper || !hasNumber) {
                showError('password', 'Password harus minimal 8 karakter dengan kombinasi huruf besar, kecil, dan angka.');
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

            return isValid;
        }

        document.getElementById('submitReset').addEventListener('click', async () => {
            if (!validateForm()) return;

            const payload = {
                token: document.getElementById('token').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
            };

            try {
                const res = await fetch('/password/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                showAlert(result.message, result.success);
                if (result.success) {
                    setTimeout(() => window.location.href = '/portal', 1500);
                }
            } catch (err) {
                showAlert(err.message, false);
            }
        });
    </script>
</body>
</html>