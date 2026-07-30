<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Kedaluwarsa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body class="bg-slate-100/70 min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm w-full max-w-sm sm:max-w-md p-6 sm:p-8 text-center">
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-xl sm:text-2xl mx-auto mb-4">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-2">Link Sudah Kedaluwarsa</h2>
        <p class="text-xs text-slate-500 mb-6 leading-relaxed">
            Link reset password ini sudah tidak berlaku. Silakan ajukan permintaan reset password baru.
        </p>
        <a href="{{ route('home') }}" class="inline-block w-full sm:w-auto bg-[#0a2540] hover:bg-[#113357] text-white text-xs font-bold px-6 py-3 rounded-xl transition">
            Kembali ke Halaman Login
        </a>
    </div>
</body>
</html>