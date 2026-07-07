<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ESDM - Sistem Tiketing - Edit Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen p-4 sm:p-6 lg:p-8 flex items-center justify-center">

    <div class="bg-white rounded-3xl shadow-xl w-full max-w-xl overflow-hidden border border-slate-100 transition-all duration-300">
        
        <div class="bg-[#0a2540] text-white p-5 sm:p-6 flex flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-pen-to-square text-amber-400 text-xl"></i>
                <h3 class="text-sm sm:text-lg font-bold tracking-tight">Edit Tiket Laporan #TX-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</h3>
            </div>
        </div>

        <form id="ticketForm" action="{{ route('user.ticket.update', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4" novalidate>
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Utama <span class="text-red-500">*</span></label>
                <select id="selectKategori" name="kategori" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:bg-white transition text-sm text-slate-700 cursor-pointer">
                    <option value="">-- Pilih Kategori Masalah --</option>
                    <option value="IT—Hardware" {{ $ticket->kategori == 'IT—Hardware' ? 'selected' : '' }}>IT — Hardware</option>
                    <option value="IT—Software" {{ $ticket->kategori == 'IT—Software' ? 'selected' : '' }}>IT — Software</option>
                    <option value="IT—Jaringan" {{ $ticket->kategori == 'IT—Jaringan' ? 'selected' : '' }}>IT — Jaringan</option>
                    <option value="Administrasi" {{ $ticket->kategori == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                    <option value="Keamanan dan Kebersihan" {{ $ticket->kategori == 'Keamanan dan Kebersihan' ? 'selected' : '' }}>Keamanan dan Kebersihan</option>
                    <option value="Lainnya" {{ $ticket->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sub-Kategori <span class="text-red-500">*</span></label>
                <input type="text" id="inputSubKategori" name="sub_kategori" value="{{ $ticket->sub_kategori }}"
                    placeholder="Ketik detail sub-kategori masalah di sini..." 
                    class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:bg-white transition text-sm text-slate-700">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor BMN (Barang Milik Negara) <span class="text-red-500">*</span></label>
                <input type="text" id="inputBMN" name="nomor_bmn" value="{{ $ticket->nomor_bmn }}" placeholder="Maksimal 20 Karakter (Contoh: BMN-2026-9935-IT)" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:bg-white transition text-sm font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tingkat Urgensi / Prioritas <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                    <label class="border border-slate-200 rounded-xl p-2.5 sm:p-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 transition">
                        <input type="radio" name="prioritas" value="Rendah" class="accent-green-600" {{ $ticket->prioritas == 'Rendah' ? 'checked' : '' }}> Rendah
                    </label>
                    <label class="border border-slate-200 rounded-xl p-2.5 sm:p-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 transition">
                        <input type="radio" name="prioritas" value="Sedang" class="accent-amber-500" {{ $ticket->prioritas == 'Sedang' ? 'checked' : '' }}> Sedang
                    </label>
                    <label class="border border-slate-200 rounded-xl p-2.5 sm:p-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 transition">
                        <input type="radio" name="prioritas" value="Tinggi" class="accent-red-600" {{ $ticket->prioritas == 'Tinggi' ? 'checked' : '' }}> Tinggi
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Masalah <span class="text-red-500">*</span></label>
                <textarea id="deskripsiMasalah" name="deskripsi_masalah" placeholder="Tuliskan keluhan atau kronologi masalah Anda di sini..." rows="4" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 focus:bg-white transition text-sm">{{ $ticket->deskripsi_masalah }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ganti Lampiran Foto <span class="text-red-500">*</span></label>
                <input type="file" id="inputFoto" name="attachment_foto" accept="image/*" class="w-full text-xs sm:text-sm text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:cursor-pointer">
                @if($ticket->attachment_foto)
                    <div class="flex items-center gap-2 mt-2 text-xs text-green-600 bg-green-50 p-2 rounded-lg border border-green-100">
                        <i class="fa-solid fa-image"></i>
                        <span>File lampiran foto saat ini aman tersimpan di sistem.</span>
                    </div>
                @endif
                <p class="text-[10px] text-slate-400 mt-1">*Format gambar (.jpg, .png), maksimal file 2MB</p>
            </div>

            <div class="flex flex-row gap-3 justify-end pt-3 border-t border-slate-100">
                <a href="{{ route('user.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm px-5 py-2.5 rounded-xl transition text-center w-1/2 sm:w-auto">Batal</a>
                <button type="submit" class="bg-[#0a2540] hover:bg-slate-800 text-white font-semibold text-sm px-6 py-2.5 rounded-xl shadow-md transition transform active:scale-95 cursor-pointer w-1/2 sm:w-auto text-center">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <div id="popupNotification" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4 transform scale-95 transition-all duration-200">
            <div class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-slate-800">Formulir Gagal Dikirim</h4>
                <div id="popupMessage" class="text-xs text-red-600 mt-2 space-y-1 text-left bg-red-50/50 p-3 rounded-xl border border-red-100"></div>
            </div>
            <button id="closePopup" class="w-full bg-[#0a2540] hover:bg-slate-800 text-white text-xs font-semibold py-2.5 rounded-xl transition shadow-md">
                Perbaiki Sekarang
            </button>
        </div>
    </div>

    <script>
        const selectKategori = document.getElementById('selectKategori');
        const inputSubKategori = document.getElementById('inputSubKategori');
        const inputBMN = document.getElementById('inputBMN');
        const deskripsiMasalah = document.getElementById('deskripsiMasalah');
        const inputFoto = document.getElementById('inputFoto');
        const ticketForm = document.getElementById('ticketForm');
        
        const popupNotification = document.getElementById('popupNotification');
        const popupMessage = document.getElementById('popupMessage');
        const closePopup = document.getElementById('closePopup');
        const hasExistingPhoto = '{{ $ticket->attachment_foto ? "true" : "false" }}' === 'true';

        selectKategori.addEventListener('change', function() {
            if(this.value) {
                inputSubKategori.disabled = false;
                inputSubKategori.placeholder = "Ketik detail sub-kategori masalah di sini...";
                inputSubKategori.classList.remove('bg-slate-100');
                inputSubKategori.classList.add('bg-slate-50');
            } else {
                inputSubKategori.disabled = true;
                inputSubKategori.value = "";
                inputSubKategori.placeholder = "Silakan pilih kategori utama terlebih dahulu";
                inputSubKategori.classList.remove('bg-slate-50');
                inputSubKategori.classList.add('bg-slate-100');
            }
        });

        closePopup.addEventListener('click', () => {
            popupNotification.classList.add('hidden');
        });

        ticketForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            let errors = [];

            if (!selectKategori.value) {
                errors.push("• Silakan pilih kategori utama.");
            }
            if (inputSubKategori.disabled || !inputSubKategori.value.trim()) {
                errors.push("• Silakan isi sub-kategori.");
            }
            if (!inputBMN.value.trim()) {
                errors.push("• Silakan masukkan Nomor BMN.");
            }
            if (!deskripsiMasalah.value.trim()) {
                errors.push("• Silakan isi deskripsi masalah.");
            }
            if (!hasExistingPhoto && inputFoto.files.length === 0) {
                errors.push("• Silakan unggah lampiran foto.");
            }

            if (errors.length > 0) {
                popupMessage.innerHTML = errors.map(err => `<div>${err}</div>`).join("");
                popupNotification.classList.remove('hidden');
            } else {
                ticketForm.submit();
            }
        });
    </script>
</body>
</html>