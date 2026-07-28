<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESDM - Tiketing - Buat Tiket Baru</title>
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

    {{-- Main Content (Diperlebar menjadi max-w-7xl agar kiri kanan penuh dan seimbang) --}}
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Banner Card Judul Halaman --}}
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-200/80 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">Buat Tiket Baru</h2>
                <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Sampaikan keluhan atau permohonan bantuan teknis layanan Anda.</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-amber-400 text-slate-900 rounded-2xl flex items-center justify-center shrink-0 shadow-md">
                <i class="fa-solid fa-square-plus text-xl sm:text-2xl"></i>
            </div>
        </div>

        {{-- Form Container utama --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8 space-y-6">

            {{-- Sub-Judul Seksi --}}
            <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100">
                <div class="w-1.5 h-5 bg-amber-400 rounded-full"></div>
                <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider">INFORMASI PENGADUAN LAYANAN</h3>
            </div>

            <form id="ticketForm" action="{{ route('user.ticket.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" novalidate>
                @csrf

                {{-- Information Box Pelapor & Divisi --}}
                <div class="grid grid-cols-2 gap-4 bg-slate-50/80 p-4 rounded-xl border border-slate-200/60 text-xs">
                    <div>
                        <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Nama Pelapor</p>
                        <p class="font-bold text-slate-700 text-sm mt-0.5 whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ $user->nama_lengkap ?? 'Tidak Terdata' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-bold text-slate-400 uppercase tracking-wider text-[10px]">Divisi / Unit Kerja</p>
                        <p class="font-bold text-slate-700 text-sm mt-0.5 whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ $user->divisi ?? 'Tidak Terdata' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Kategori Utama --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Utama <span class="text-red-500">*</span></label>
                        <div class="relative w-full text-left" id="dropdownWrapper">
                            <input type="hidden" id="selectKategori" name="kategori" value="">

                            <button type="button" id="dropdownBtn" class="w-full bg-slate-50/80 border border-slate-200 p-3 pr-10 rounded-xl focus:outline-none focus:border-amber-400 focus:bg-white focus:ring-2 focus:ring-amber-400/20 transition-all text-sm text-slate-400 font-medium flex justify-between items-center cursor-pointer hover:bg-slate-100/60">
                                <span id="dropdownLabel" class="truncate pr-2">-- Pilih Kategori Masalah --</span>
                                <i id="dropdownArrow" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200 shrink-0"></i>
                            </button>

                            <div id="dropdownMenu" class="hidden absolute left-0 z-50 mt-1 w-full max-w-full box-border max-h-52 overflow-y-auto bg-white border border-slate-200 shadow-xl rounded-xl p-1.5 space-y-0.5 text-sm text-slate-700 font-medium">
                                <div data-value="IT—Hardware" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">IT - Hardware</div>
                                <div data-value="IT—Software" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">IT - Software</div>
                                <div data-value="IT—Jaringan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">IT - Jaringan</div>
                                <div data-value="Administrasi" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Administrasi</div>
                                <div data-value="Sarana—Prasarana" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Sarana - Prasarana</div>
                                <div data-value="Keamanan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Keamanan</div>
                                <div data-value="Kebersihan" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Kebersihan</div>
                                <div data-value="Lainnya" class="dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900">Lainnya</div>
                            </div>
                        </div>
                    </div>

                    {{-- Sub Kategori --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sub-Kategori <span class="text-red-500">*</span></label>
                        <div class="relative w-full text-left" id="subDropdownWrapper">
                            <input type="hidden" id="selectSubKategori" name="sub_kategori" value="">

                            <button type="button" id="subDropdownBtn" disabled class="w-full bg-slate-100 border border-slate-200 p-3 pr-10 rounded-xl focus:outline-none focus:border-amber-400 focus:bg-white focus:ring-2 focus:ring-amber-400/20 transition-all text-sm text-slate-400 font-medium flex justify-between items-center opacity-60 cursor-not-allowed">
                                <span id="subDropdownLabel" class="truncate pr-2">Silakan pilih kategori utama terlebih dahulu</span>
                                <i id="subDropdownArrow" class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200 shrink-0"></i>
                            </button>

                            <div id="subDropdownMenu" class="hidden absolute left-0 z-50 mt-1 w-full max-w-full box-border max-h-52 overflow-y-auto bg-white border border-slate-200 shadow-xl rounded-xl p-1.5 space-y-0.5 text-sm text-slate-700 font-medium">
                            </div>
                        </div>
                    </div>

                    {{-- Input Sub Kategori Manual --}}
                    <div id="wrapperSubKategoriManual" class="hidden lg:col-span-2 transition-all duration-200">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sebutkan Sub-Kategori Anda <span class="text-red-500">*</span></label>
                        <input type="text" id="inputSubKategoriManual" name="sub_kategori_manual" placeholder="Tulis sub-kategori secara spesifik di sini..."
                            class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-400 focus:bg-white transition text-sm">
                    </div>

                    {{-- Nomor BMN --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor BMN (Barang Milik Negara)</label>
                        <input type="text" id="inputBMN" name="nomor_bmn" placeholder="Format: BMN-TAHUN-NOMOR-JENIS" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-400 focus:bg-white transition text-sm font-mono">
                        <p class="text-[10px] text-slate-400 mt-1">*Kosongkan jika masalah tidak berkaitan dengan aset BMN</p>
                    </div>

                    {{-- Tingkat Urgensi --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tingkat Urgensi / Prioritas <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2 sm:gap-3">
                            <label class="border border-slate-200 rounded-xl p-2.5 sm:p-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 transition">
                                <input type="radio" name="prioritas" value="Rendah" class="accent-emerald-600" checked> Rendah
                            </label>
                            <label class="border border-slate-200 rounded-xl p-2.5 sm:p-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 transition">
                                <input type="radio" name="prioritas" value="Sedang" class="accent-amber-500"> Sedang
                            </label>
                            <label class="border border-slate-200 rounded-xl p-2.5 sm:p-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 transition">
                                <input type="radio" name="prioritas" value="Tinggi" class="accent-rose-600"> Tinggi
                            </label>
                        </div>
                    </div>

                    {{-- Deskripsi Masalah --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Masalah <span class="text-red-500">*</span></label>
                        <textarea id="deskripsiMasalah" name="deskripsi_masalah" placeholder="Tuliskan keluhan atau kronologi masalah Anda di sini..." rows="5" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-400 focus:bg-white transition text-sm"></textarea>
                    </div>

                    {{-- Lampiran Foto --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lampiran Foto Dokumen / Kerusakan</label>
                        <input type="file" id="inputFoto" name="attachment_foto" accept="image/*" class="w-full text-xs sm:text-sm text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:cursor-pointer">
                        <p class="text-[10px] text-slate-400 mt-1">*Format gambar (.jpg, .png), maksimal file 2MB. Kosongkan jika tidak ingin mengunggah foto.</p>
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('user.dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 active:scale-95">
                        Batal
                    </a>
                    
                    <button type="submit" class="bg-amber-400 hover:bg-amber-500 text-slate-900 px-6 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 shadow-sm flex items-center gap-2 active:scale-95">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Tiket</span>
                    </button>
                </div>
            </form>
        </div>
    </main>

    {{-- Modal Popup Notifikasi Gagal --}}
    <div id="popupNotification" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4 transform scale-95 transition-all duration-200">
            <div class="w-12 h-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h4 class="text-base font-bold text-slate-800">Formulir Gagal Dikirim</h4>
                <div id="popupMessage" class="text-xs text-red-600 mt-2 space-y-1 text-left bg-red-50/50 p-3 rounded-xl border border-red-100"></div>
            </div>
            <button id="closePopup" class="w-full bg-[#0a2540] hover:bg-[#06182b] text-white text-xs font-semibold py-2.5 rounded-xl transition shadow-md">
                Perbaiki Sekarang
            </button>
        </div>
    </div>

    <script>
        const dataSubKategori = {
            "IT—Hardware": [
                "Masalah Hard Disk & Penyimpanan",
                "Masalah Scanner & Printer",
                "Kerusakan Keyboard",
                "Kerusakan Mouse",
                "Kerusakan Printer Thermal / Kasir"
            ],
            "IT—Software": [
                "Sinkronisasi Data Sistem",
                "Gagal Upload Dokumen Digital",
                "Error Sistem LPSE / Tender",
                "Aplikasi E-Planning Tidak Diakses",
                "Error OMSPAN / Anggaran",
                "Gagal Upload Perencanaan Online",
                "Masalah Pencarian Arsip Sistem"
            ],
            "IT—Jaringan": [
                "Konflik IP Address",
                "Kerusakan Switch Jaringan",
                "Internet Lambat & Putus-Putus",
                "Kerusakan Kabel LAN / Fisik",
                "Terputus dari Server Lokal (LAN)"
            ],
            "Sarana—Prasarana": [
                "Kerusakan Kursi Kantor",
                "Perawatan & Cuci Kendaraan Dinas",
                "Kerusakan Lemari Arsip / Furnitur",
                "Wastafel & Saluran Air Tersumbat",
                "Gangguan Genset & Kelistrikan",
                "Kerusakan / Mogok Kendaraan Dinas",
                "Administrasi STNK Kendaraan Dinas"
            ],
            "Administrasi": [
                "Permintaan Pengadaan ATK",
                "Pemesanan Kendaraan Dinas Luar Kota",
                "Permintaan Pengemudi / Driver",
                "Permintaan Rekap Operasional Bulanan",
                "Alih Media Dokumen Fisik ke Digital",
                "Permintaan Pembelian Tinta Printer",
                "Permintaan Dokumentasi & Foto Acara"
            ],
            "Keamanan": [
                "Penambahan Petugas Keamanan Malam",
                "Kerusakan Palang Parkir Otomatis",
                "Kunci Cadangan Ruangan Hilang",
                "Gangguan CCTV Area Parkir",
                "Kartu Akses Gedung Hilang"
            ],
            "Kebersihan": [
                "Kebersihan Toilet",
                "Pengosongan Tempat Sampah",
                "Kebersihan Ruang Pantry",
                "Penanganan Serangga / Hama Ruang Arsip",
                "Pembersihan Kaca Jendela Luar Gedung"
            ]
        };

        const selectKategori = document.getElementById('selectKategori');
        const selectSubKategori = document.getElementById('selectSubKategori');

        const dropdownBtn = document.getElementById('dropdownBtn');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownLabel = document.getElementById('dropdownLabel');
        const dropdownArrow = document.getElementById('dropdownArrow');
        const dropdownItems = document.querySelectorAll('.dropdown-item');

        const subDropdownBtn = document.getElementById('subDropdownBtn');
        const subDropdownMenu = document.getElementById('subDropdownMenu');
        const subDropdownLabel = document.getElementById('subDropdownLabel');
        const subDropdownArrow = document.getElementById('subDropdownArrow');

        const wrapperSubKategoriManual = document.getElementById('wrapperSubKategoriManual');
        const inputSubKategoriManual = document.getElementById('inputSubKategoriManual');
        const inputBMN = document.getElementById('inputBMN');
        const deskripsiMasalah = document.getElementById('deskripsiMasalah');
        const ticketForm = document.getElementById('ticketForm');

        const popupNotification = document.getElementById('popupNotification');
        const popupMessage = document.getElementById('popupMessage');
        const closePopup = document.getElementById('closePopup');

        document.addEventListener('DOMContentLoaded', () => {

            dropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                subDropdownMenu.classList.add('hidden');
                subDropdownArrow.classList.remove('rotate-180');

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
                    const text = item.innerText;

                    selectKategori.value = val;
                    dropdownLabel.innerText = text;
                    dropdownLabel.classList.remove('text-slate-400');
                    dropdownLabel.classList.add('text-slate-700');

                    dropdownMenu.classList.add('hidden');
                    dropdownArrow.classList.remove('rotate-180');

                    selectSubKategori.value = "";
                    subDropdownLabel.innerText = "-- Pilih Sub-Kategori --";
                    subDropdownLabel.classList.remove('text-slate-700');
                    subDropdownLabel.classList.add('text-slate-400');
                    wrapperSubKategoriManual.classList.add('hidden');
                    inputSubKategoriManual.value = '';

                    if(val) {
                        subDropdownBtn.disabled = false;
                        subDropdownBtn.classList.remove('bg-slate-100', 'opacity-60', 'cursor-not-allowed');
                        subDropdownBtn.classList.add('bg-slate-50/80', 'cursor-pointer');
                        buildSubDropdownMenu(val);
                    }
                });
            });

            subDropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownMenu.classList.add('hidden');
                dropdownArrow.classList.remove('rotate-180');

                if (subDropdownBtn.disabled) return;

                const isOpen = !subDropdownMenu.classList.contains('hidden');
                if (isOpen) {
                    subDropdownMenu.classList.add('hidden');
                    subDropdownArrow.classList.remove('rotate-180');
                } else {
                    subDropdownMenu.classList.remove('hidden');
                    subDropdownArrow.classList.add('rotate-180');
                }
            });

            document.addEventListener('click', () => {
                dropdownMenu.classList.add('hidden');
                dropdownArrow.classList.remove('rotate-180');
                subDropdownMenu.classList.add('hidden');
                subDropdownArrow.classList.remove('rotate-180');
            });
        });

        function buildSubDropdownMenu(kategoriValue) {
            subDropdownMenu.innerHTML = "";
            let listItems = [];

            if (dataSubKategori[kategoriValue]) {
                listItems = [...dataSubKategori[kategoriValue]];
            }

            listItems.push("Lainnya");

            listItems.forEach(itemText => {
                let div = document.createElement('div');
                div.className = "sub-dropdown-item px-3 py-2 rounded-lg cursor-pointer hover:bg-slate-100 transition text-slate-900";
                div.setAttribute('data-value', itemText);
                div.innerText = itemText;

                div.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const val = div.getAttribute('data-value');

                    selectSubKategori.value = val;
                    subDropdownLabel.innerText = val;
                    subDropdownLabel.classList.remove('text-slate-400');
                    subDropdownLabel.classList.add('text-slate-700');

                    subDropdownMenu.classList.add('hidden');
                    subDropdownArrow.classList.remove('rotate-180');

                    if (val === "Lainnya") {
                        wrapperSubKategoriManual.classList.remove('hidden');
                    } else {
                        wrapperSubKategoriManual.classList.add('hidden');
                        inputSubKategoriManual.value = '';
                    }
                });

                subDropdownMenu.appendChild(div);
            });
        }

        closePopup.addEventListener('click', () => {
            popupNotification.classList.add('hidden');
        });

        ticketForm.addEventListener('submit', function(event) {
            event.preventDefault();
            let errors = [];

            if (!selectKategori.value) {
                errors.push("• Silakan pilih kategori utama.");
            }

            if (!selectSubKategori.value) {
                errors.push("• Silakan pilih sub-kategori.");
            }

            if (selectSubKategori.value === "Lainnya" && !inputSubKategoriManual.value.trim()) {
                errors.push("• Silakan ketik detail sub-kategori manual Anda.");
            }

            if (!deskripsiMasalah.value.trim()) {
                errors.push("• Silakan isi deskripsi masalah.");
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