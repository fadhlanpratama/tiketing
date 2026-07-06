<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - TIXPass ESDM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased flex flex-col">

    <header class="bg-[#0a2540] text-white sticky top-0 z-30 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-amber-400 p-2 rounded-xl text-[#0a2540]">
                    <i class="fa-solid fa-ticket text-lg"></i>
                </div>
                <div>
                    <h1 class="text-white font-black tracking-wider text-lg">TIXPass</h1>
                    <span class="text-[10px] text-amber-400 uppercase font-bold tracking-widest block -mt-1">Portal Pengguna</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-slate-300">Selamat datang,</p>
                    <p class="text-sm font-bold text-white">{{ session('nama_lengkap', 'Pegawai ESDM') }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center text-[#0a2540] font-bold uppercase shadow-inner">
                    {{ substr(session('nama_lengkap', 'PE'), 0, 2) }}
                </div>
                <div class="h-6 w-px bg-slate-700 hidden sm:block"></div>
                <a href="{{ route('logout') }}" class="flex items-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white px-4 py-2.5 rounded-xl text-xs font-semibold transition">
                    <i class="fa-solid fa-right-from-bracket"></i> <span class="hidden sm:inline">Logout</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <div class="bg-gradient-to-r from-[#0a2540] to-[#16406c] rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-5 pointer-events-none bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="relative z-10">
                <span class="bg-amber-400 text-[#0a2540] text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md mb-3 inline-block">Akses Karyawan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Butuh Bantuan IT atau Fasilitas?</h2>
                <p class="text-slate-300 text-sm mt-1">Buat, pantau, dan kelola semua permintaan tiket internal Anda dengan mudah.</p>
            </div>
            <button id="btnOpenModal" class="bg-amber-400 hover:bg-amber-300 text-[#0a2540] font-bold text-sm px-6 py-3 rounded-xl shadow-lg transition transform active:scale-95 flex items-center gap-2 shrink-0 self-stretch sm:self-auto justify-center">
                <i class="fa-solid fa-plus"></i> Buat Tiket Baru
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tiket Aktif</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">3 Tiket</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dalam Proses</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">1 Tiket</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Selesai</p>
                    <h3 class="text-xl font-bold text-slate-800 mt-0.5">14 Tiket</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Pengajuan Tiket</h3>
                    <p class="text-xs text-slate-400">Status pelacakan real-time untuk permohonan layanan internal Anda</p>
                </div>
                <div class="relative w-full sm:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Cari nomor tiket..." class="w-full bg-slate-50 border border-slate-200 pl-10 pr-4 py-2 rounded-xl focus:outline-none focus:border-amber-500 transition text-sm">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 font-semibold text-xs tracking-wider border-b border-slate-100">
                            <th class="p-4 pl-6">ID TIKET</th>
                            <th class="p-4">KATEGORI PERMINTAAN</th>
                            <th class="p-4">TANGGAL PENGAJUAN</th>
                            <th class="p-4">STATUS</th>
                            <th class="p-4 pr-6 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 pl-6 font-semibold text-slate-700">#TX-00482</td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">IT — Jaringan</p>
                                <span class="text-xs text-slate-400">Koneksi internet lambat di Lantai 3</span>
                            </td>
                            <td class="p-4">02 Jul 2026</td>
                            <td class="p-4">
                                <span class="bg-amber-100 text-amber-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">Open</span>
                            </td>
                            <td class="p-4 pr-6 text-center">
                                <button class="text-slate-600 hover:text-slate-800 font-semibold text-xs px-3 py-1.5 bg-slate-100 rounded-lg transition">Detail</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 pl-6 font-semibold text-slate-700">#TX-00431</td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">IT — Software</p>
                                <span class="text-xs text-slate-400">Instalasi aplikasi modul pelaporan database</span>
                            </td>
                            <td class="p-4">28 Jun 2026</td>
                            <td class="p-4">
                                <span class="bg-blue-100 text-blue-800 text-[11px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wide">In Progress</span>
                            </td>
                            <td class="p-4 pr-6 text-center">
                                <button class="text-slate-600 hover:text-slate-800 font-semibold text-xs px-3 py-1.5 bg-slate-100 rounded-lg transition">Detail</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="ticketModal" class="fixed inset-0 bg-slate-900/50 items-center justify-center p-4 z-50 overflow-y-auto hidden">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all my-8">
            <div class="bg-[#0a2540] text-white p-6 flex justify-between items-center">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-square-plus text-amber-400 text-xl"></i>
                    <h3 class="text-lg font-bold tracking-tight">Formulir Pengaduan Layanan</h3>
                </div>
                <button id="btnCloseModal" class="text-slate-400 hover:text-white transition text-lg">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="#" method="POST" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-3.5 rounded-xl border border-slate-100 text-xs text-slate-500">
                    <div>
                        <p class="font-semibold text-slate-400">Nama Pelapor:</p>
                        <p class="font-bold text-slate-700 mt-0.5">{{ session('nama_lengkap', 'Pegawai ESDM') }}</p>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-400">Kontak Email:</p>
                        <p class="font-bold text-slate-700 mt-0.5">{{ session('user_logged', 'email@esdm.go.id') }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Utama</label>
                    <select id="selectKategori" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 transition text-sm text-slate-700">
                        <option value="">-- Pilih Kategori Masalah --</option>
                        <option value="it_hardware">IT — Hardware</option>
                        <option value="it_software">IT — Software</option>
                        <option value="it_jaringan">IT — Jaringan</option>
                        <option value="sarpras">Sarana-Prasarana</option>
                        <option value="administrasi">Administrasi</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sub-Kategori</label>
                    <select id="selectSubKategori" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 transition text-sm text-slate-700" disabled>
                        <option value="">Silakan pilih kategori utama terlebih dahulu</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tingkat Urgensi / Prioritas</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="border border-slate-200 rounded-xl p-3 flex items-center justify-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 transition">
                            <input type="radio" name="prioritas" value="Rendah" class="accent-green-600" checked> Rendah
                        </label>
                        <label class="border border-slate-200 rounded-xl p-3 flex items-center justify-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 transition">
                            <input type="radio" name="prioritas" value="Sedang" class="accent-amber-500"> Sedang
                        </label>
                        <label class="border border-slate-200 rounded-xl p-3 flex items-center justify-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 transition">
                            <input type="radio" name="prioritas" value="Tinggi" class="accent-red-600"> Tinggi
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Masalah</label>
                    <textarea placeholder="Tuliskan kronologi singkat kerusakan/kebutuhan layanan secara detail agar memudahkan petugas..." rows="4" class="w-full bg-slate-50 border border-slate-200 p-3 rounded-xl focus:outline-none focus:border-amber-500 transition text-sm"></textarea>
                </div>

                <div class="flex gap-3 justify-end pt-2 border-t border-slate-100">
                    <button type="button" id="btnCancelModal" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm px-5 py-2.5 rounded-xl transition">Batal</button>
                    <button type="submit" class="bg-[#0a2540] hover:bg-slate-800 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md transition">Kirim Tiket</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const ticketModal = document.getElementById('ticketModal');
        const btnOpenModal = document.getElementById('btnOpenModal');
        const btnCloseModal = document.getElementById('btnCloseModal');
        const btnCancelModal = document.getElementById('btnCancelModal');

        // Fungsi Buka Tutup Modal
        const toggleModal = (show) => {
            if(show) {
                ticketModal.classList.remove('hidden');
                ticketModal.classList.add('flex');
            } else {
                ticketModal.classList.add('hidden');
                ticketModal.classList.remove('flex');
            }
        };

        btnOpenModal.addEventListener('click', () => toggleModal(true));
        btnCloseModal.addEventListener('click', () => toggleModal(false));
        btnCancelModal.addEventListener('click', () => toggleModal(false));

        // Pemetaan Data Sub-Kategori Sesuai Dokumen image_b2c75b.png
        const dataSubKategori = {
            it_hardware: ['Komputer rusak', 'Monitor bermasalah', 'Printer mati'],
            it_software: ['Instalasi aplikasi', 'Error sistem', 'Akun terkunci'],
            it_jaringan: ['Internet lambat', 'WiFi tidak konek', 'VPN bermasalah'],
            sarpras: ['AC rusak', 'Kebersihan', 'Kerusakan furnitur', 'Kelistrikan'],
            administrasi: ['Permintaan dokumen', 'ATK', 'Surat-menyurat'],
            lainnya: ['Di luar kategori di atas']
        };

        const selectKategori = document.getElementById('selectKategori');
        const selectSubKategori = document.getElementById('selectSubKategori');

        // Logika Pengubah Isi Dropdown Sub-Kategori Secara Dinamis
        selectKategori.addEventListener('change', function() {
            const key = this.value;
            selectSubKategori.innerHTML = ''; // Kosongkan data lama

            if(key && dataSubKategori[key]) {
                selectSubKategori.disabled = false;
                dataSubKategori[key].forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value = sub;
                    opt.innerText = sub;
                    selectSubKategori.appendChild(opt);
                });
            } else {
                selectSubKategori.disabled = true;
                const opt = document.createElement('option');
                opt.value = "";
                opt.innerText = "Silakan pilih kategori utama terlebih dahulu";
                selectSubKategori.appendChild(opt);
            }
        });
    </script>
</body>
</html>