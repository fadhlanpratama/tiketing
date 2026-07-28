<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Tiketing Layanan Internal - PSDMBP</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & FontAwesome 6 & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- AOS (Animate On Scroll) CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <style>  
        :root {  
            --primary-color: #0a2540;  
            --primary-dark: #06182b;
            --secondary-color: #FFC53A;  
            --secondary-hover: #e0b032;
            --accent-green: #1fa466;
            --text-light: #FFFFFF;  
            --text-dark: #1e293b;  
            --text-muted: #64748b;
            --background-light: #f4f7fa;  
            --card-background: #FFFFFF;  
            --card-shadow: 0 4px 20px rgba(10, 37, 64, 0.05);  
            --card-hover-shadow: 0 12px 28px rgba(10, 37, 64, 0.12);  
        }  

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand, .btn {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        a {
            text-decoration: none;
        }

        /* Top Announcement Bar */
        .top-info-bar {
            background: #06182b;
            color: #cbd5e1;
            font-size: 0.78rem;
            padding: 7px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Custom Header Navbar */
        .navbar-custom {
            background: var(--primary-color);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo-img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .brand-small {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            color: var(--secondary-color);
            text-transform: uppercase;
            line-height: 1.1;
        }

        .brand-main {
            font-size: 1.15rem;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 0.02em;
            line-height: 1.1;
        }

        .btn-login {
            background: var(--secondary-color);
            color: var(--primary-color) !important;
            border-radius: 12px;
            padding: 9px 22px;
            font-weight: 800;
            font-size: 0.85rem;
            border: none;
            box-shadow: 0 4px 12px rgba(255, 197, 58, 0.25);
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: var(--secondary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(255, 197, 58, 0.35);
            color: var(--primary-color) !important;
        }

        /* Hero Banner Container */
        .hero-banner-card {
            background: var(--primary-color);
            color: #ffffff;
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 10px 30px rgba(10, 37, 64, 0.12);
            position: relative;
            overflow: hidden;
        }

        .hero-badge-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--secondary-color);
            color: var(--primary-color);
            padding: 6px 16px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .hero-title {  
            font-size: 32px;  
            font-weight: 900;  
            margin-bottom: 12px;  
            letter-spacing: -0.02em;  
            line-height: 1.25;
            color: #ffffff;
        }  

        .hero-subtitle {  
            font-size: 0.95rem;  
            font-weight: 400;  
            color: #cbd5e1;
            margin-bottom: 0;
            max-width: 700px;
            line-height: 1.6;
        }  

        /* Quick Stats Badges (Pengganti Search Bar) */
        .hero-stats-badge {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 0.82rem;
            color: #e2e8f0;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
        }

        /* Services Grid Cards */  
        .services-grid {  
            display: grid;  
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;  
            margin-top: 28px;
        }  

        .service-card-hero {  
            background-color: #ffffff;  
            border-radius: 20px;  
            padding: 24px 20px;  
            text-align: center;  
            transition: all 0.25s ease;  
            display: flex;  
            flex-direction: column;  
            align-items: center;  
            justify-content: center;  
            text-decoration: none;  
            color: var(--text-dark);  
            border: 1px solid #e2e8f0;  
            box-shadow: var(--card-shadow);
            cursor: pointer;
        }  

        .service-card-hero:hover {  
            transform: translateY(-5px);  
            border-color: var(--secondary-color);
            box-shadow: var(--card-hover-shadow);
        }  

        .service-icon-hero {  
            background-color: #f1f5f9;  
            border-radius: 16px;  
            width: 58px;  
            height: 58px;  
            display: flex;  
            align-items: center;  
            justify-content: center;  
            margin-bottom: 14px;  
            transition: all 0.25s ease;
        }  

        .service-card-hero:hover .service-icon-hero {
            background-color: #fef9c3;
            transform: scale(1.05);
        }

        .service-icon-hero i {  
            font-size: 24px;  
            color: var(--primary-color);  
            transition: color 0.25s ease;
        }  

        .service-title-hero {  
            font-size: 14px;  
            font-weight: 800;  
            color: var(--text-dark);
            line-height: 1.3;  
        }  

        /* Section Title Indicator */
        .section-title {  
            font-size: 22px;  
            font-weight: 900;  
            color: var(--primary-color);  
            margin-bottom: 16px;  
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }  

        .section-title::before {
            content: "";
            width: 6px;
            height: 22px;
            border-radius: 999px;
            background: var(--secondary-color);
        }

        /* Penjelasan Sistem Tiketing */
        .system-about-section {
            padding: 10px 0;
        }

        .system-about-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 5px solid var(--secondary-color);
            border-radius: 20px;
            padding: 32px;
            box-shadow: var(--card-shadow);
        }

        .system-about-box h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .system-about-box p {
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 0;
        }

        /* Step Procedure Section */
        .step-section-pu {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: var(--card-shadow);
            padding: 36px 28px;
        }

        .step-nav-tabs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .step-tab-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            padding: 8px 18px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .step-tab-num {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #cbd5e1;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .step-tab-btn.active-step {
            background: var(--primary-color);
            color: #ffffff;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(10, 37, 64, 0.2);
        }

        .step-tab-btn.active-step .step-tab-num {
            background: var(--secondary-color);
            color: var(--primary-color);
        }

        .step-detail-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
        }

        .step-detail-box h5 {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--primary-color);
        }

        .step-detail-box p {
            font-size: 0.88rem;
            color: #475569;
            line-height: 1.6;
        }

        .prosedur-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .prosedur-list li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 8px;
            font-size: 0.84rem;
            color: #334155;
            line-height: 1.5;
        }

        .prosedur-list li::before {
            content: "\F26A";
            font-family: "bootstrap-icons";
            position: absolute;
            left: 0;
            top: 1px;
            color: var(--accent-green);
            font-size: 0.88rem;
            font-weight: 800;
        }

        /* Informasi Kontak Teknis */
        .info-contact-section {
            background: #ffffff;
            color: var(--text-dark);
            border: 1px solid #e2e8f0;
            border-left: 5px solid var(--secondary-color);
            border-radius: 16px;
            padding: 28px 32px;
            box-shadow: 0 4px 15px rgba(10, 37, 64, 0.04);
        }

        .info-contact-text h4 {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 4px;
        }

        .info-contact-text p {
            font-size: 0.85rem;
            color: #64748b;
            margin: 0;
        }

        .info-contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.83rem;
            color: var(--text-dark);
        }

        .info-contact-item i { 
            color: var(--primary-color); 
        }

        /* Floating Support Chat Avatar */  
        .chat-support {  
            position: fixed;  
            bottom: 24px;  
            right: 24px;  
            z-index: 1000;  
        }  

        .chat-avatar {  
            background-color: var(--secondary-color);  
            border-radius: 50%;  
            width: 58px;  
            height: 58px;  
            display: flex;  
            align-items: center;  
            justify-content: center;  
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);  
            cursor: pointer;  
            transition: all 0.25s ease;  
        }  

        .chat-avatar:hover {  
            transform: scale(1.08);  
            background-color: var(--secondary-hover);
        }  
        
        .chat-avatar i {  
            font-size: 24px;  
            color: var(--primary-color);  
        }  

        /* Footer Section */
        footer {
            background: var(--primary-dark);
            color: #cbd5e1;
            padding: 45px 0 20px 0;
            font-size: 0.82rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 60px;
        }

        .footer-heading {
            color: var(--secondary-color);
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 14px;
        }

        .social-icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            font-size: 0.85rem;
        }

        .social-icon-btn:hover {
            background: var(--secondary-color);
            color: var(--primary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .copyright-bar {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 32px;
            padding-top: 16px;
            text-align: center;
            color: #94a3b8;
            font-size: 0.76rem;
        }

        @media (max-width: 992px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 24px;
            }
            .hero-banner-card {
                padding: 30px 20px;
            }
            .info-contact-box {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .services-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Custom Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container" style="max-width: 1200px;">
            <a class="navbar-brand m-0" href="{{ route('landing') }}">
                <div class="brand-logo-container">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="brand-logo-img">
                    <div>
                        <div class="brand-main">SISTEM TIKETING</div>
                        <div class="brand-small">PORTAL PENGGUNA</div>
                    </div>
                </div>
            </a>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk Sistem
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container py-4 space-y-6" style="max-width: 1200px;">

        <!-- Hero Section Banner Card -->
        <section class="hero-banner-card my-3" data-aos="fade-up">
            <div class="row items-center">
                <div class="col-lg-12">
                    <div class="hero-badge-tag">
                        <i class="fa-solid fa-building-flag"></i>
                        <span>AKSES PENGGUNA TERPADU</span>
                    </div>

                    <h1 class="hero-title">
                        Butuh Bantuan Teknis atau Pelaporan BMN?
                    </h1>

                    <p class="hero-subtitle">
                        Sampaikan keluhan atau ajukan permintaan layanan operasional sarana gedung, jaringan, serta perawatan aset Barang Milik Negara (BMN) kepada tim teknis.
                    </p>

                    <!-- SOLUSI NO. 2: Ringkasan Keunggulan / Quick Badges -->
                    <div class="d-flex flex-wrap gap-3 mt-4 pt-2">
                        <div class="hero-stats-badge">
                            <i class="bi bi-shield-check text-warning"></i>
                            <span>Penanganan Terukur</span>
                        </div>
                        <div class="hero-stats-badge">
                            <i class="bi bi-clock-history text-warning"></i>
                            <span>SLA Respon Maks. 1x24 Jam</span>
                        </div>
                        <div class="hero-stats-badge">
                            <i class="bi bi-person-badge text-warning"></i>
                            <span>Tim Teknisi ESDM</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Grid Overlay -->
        <section class="my-4">
            <div class="services-grid">
                <a href="{{ route('home') }}" class="service-card-hero" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-icon-hero">
                        <i class="fa-solid fa-wifi"></i>
                    </div>
                    <div class="service-title-hero">Jaringan & Internet</div>
                </a>

                <a href="{{ route('home') }}" class="service-card-hero" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-icon-hero">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <div class="service-title-hero">Hardware & Aset BMN</div>
                </a>

                <a href="{{ route('home') }}" class="service-card-hero" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-icon-hero">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div class="service-title-hero">Sarana & Kelistrikan</div>
                </a>

                <a href="{{ route('home') }}" class="service-card-hero" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-icon-hero">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <div class="service-title-hero">Sistem & Aplikasi</div>
                </a>

                <a href="{{ route('home') }}" class="service-card-hero" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-icon-hero">
                        <i class="fa-solid fa-print"></i>
                    </div>
                    <div class="service-title-hero">Printer & Scanner</div>
                </a>

                <a href="{{ route('home') }}" class="service-card-hero" data-aos="fade-up" data-aos-delay="600">
                    <div class="service-icon-hero">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div class="service-title-hero">Fasilitas Gedung</div>
                </a>
            </div>
        </section>

        <!-- Penjelasan Sistem Tiketing Layanan Internal -->
        <section class="system-about-section my-4" id="penjelasan-sistem">
            <div class="system-about-box" data-aos="fade-up">
                <h3><i class="bi bi-info-circle-fill me-2 text-warning"></i> Tentang Sistem Tiketing Layanan Internal & Pengelolaan BMN</h3>
                <p>
                    Portal E-Ticketing PSDMBP dirancang sebagai media pelaporan terpadu untuk mempermudah seluruh pegawai dalam mengajukan permohonan bantuan teknis, perbaikan fasilitas gedung, kendala jaringan komunikasi, hingga perawatan aset Barang Milik Negara (BMN). Melalui sistem ini, setiap laporan ditangani secara transparan, terukur, dan dapat dipantau progresnya secara real-time guna mendukung efektivitas operasional lingkungan kerja Gedung Kantor PSDMBP Bandung.
                </p>
            </div>
        </section>

        <!-- Detail Prosedur Penanganan Tiket -->
        <section class="my-4">
            <div class="step-section-pu" data-aos="fade-up">
                <div class="text-center mb-4">
                    <div class="section-title">Detail Prosedur Penanganan Tiket</div>
                    <p class="text-muted small">Klik pada tiap tahapan di bawah untuk melihat rincian alur kerja penanganan tiket</p>
                </div>

                <div class="step-nav-tabs">
                    <button class="step-tab-btn active-step" id="btnStep1" type="button" onclick="switchStep(1, '#detailLangkah1')">
                        <div class="step-tab-num">1</div>
                        <span>Buat Laporan</span>
                    </button>
                    <button class="step-tab-btn" id="btnStep2" type="button" onclick="switchStep(2, '#detailLangkah2')">
                        <div class="step-tab-num">2</div>
                        <span>Verifikasi</span>
                    </button>
                    <button class="step-tab-btn" id="btnStep3" type="button" onclick="switchStep(3, '#detailLangkah3')">
                        <div class="step-tab-num">3</div>
                        <span>Disposisi</span>
                    </button>
                    <button class="step-tab-btn" id="btnStep4" type="button" onclick="switchStep(4, '#detailLangkah4')">
                        <div class="step-tab-num">4</div>
                        <span>Penanganan</span>
                    </button>
                    <button class="step-tab-btn" id="btnStep5" type="button" onclick="switchStep(5, '#detailLangkah5')">
                        <div class="step-tab-num">5</div>
                        <span>Penyelesaian</span>
                    </button>
                </div>

                <div class="position-relative">
                    <div class="collapse show toggle-zone" id="detailLangkah1">
                        <div class="step-detail-box">
                            <span class="badge bg-primary text-white mb-2 px-3 py-1.5 fw-semibold">Tahap 1 dari 5</span>
                            <h5 class="mb-3">Tahap 1: Pengisian Formulir "Buat Tiket Baru" (Sisi Pengguna)</h5>
                            <p class="mb-4">
                                Pengguna yang mengalami kendala operasional mengisi formulir pengaduan layanan secara langsung pada portal sistem. Pengisian dilakukan dengan memilih spesifikasi masalah hingga melampirkan data pendukung agar penanganan dapat diproses dengan cepat.
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Pilihan Kategori & Sub-Kategori:</strong> Memilih Kategori Utama masalah serta Sub-Kategori teknis terkait secara rinci.</li>
                                        <li><strong>Nomor BMN (Barang Milik Negara):</strong> Mengisi nomor BMN aset fisik (`Format: BMN-TAHUN-NOMOR-JENIS`) jika terkait barang inventaris.</li>
                                        <li><strong>Tingkat Urgensi / Prioritas:</strong> Menentukan tingkat kegentingan perbaikan (Pilihan: <em>Rendah</em>, <em>Sedang</em>, atau <em>Tinggi</em>).</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Deskripsi Masalah:</strong> Menuliskan detail kronologi keluhan, rincian ruangan, atau kendala yang dialami secara mendalam.</li>
                                        <li><strong>Lampiran Foto Dokumen / Kerusakan:</strong> Mengunggah bukti foto fisik kerusakan atau tangkapan layar (<em>JPG/PNG, max 2MB</em>).</li>
                                        <li><strong>Identitas Otomatis:</strong> Nama Pelapor dan Divisi/Unit Kerja akan terdeteksi otomatis sesuai akun yang sedang aktif.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="collapse toggle-zone" id="detailLangkah2">
                        <div class="step-detail-box">
                            <span class="badge bg-primary text-white mb-2 px-3 py-1.5 fw-semibold">Tahap 2 dari 5</span>
                            <h5 class="mb-3">Tahap 2: Verifikasi & Cek Kelengkapan Data (Sisi Admin Helpdesk)</h5>
                            <p class="mb-4">
                                Setiap tiket pelaporan yang masuk akan ditinjau langsung oleh Admin Helpdesk. Validasi ini bertujuan untuk memagari sistem dari data yang tidak valid, penumpukan laporan ganda, serta ketidaksesuaian nomor aset inventaris.
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Pemeriksaan Kelayakan Foto:</strong> Memastikan bukti foto yang diunggah jelas dan sesuai deskripsi keluhan.</li>
                                        <li><strong>Pencocokan Master Data BMN:</strong> Verifikasi kebenaran nomor aset dengan lokasi dan pengguna terdaftar.</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Penetapan Tingkat Urgensi:</strong> Menentukan prioritas perbaikan (Rendah, Sedang, Tinggi, Darurat).</li>
                                        <li><strong>Filter Laporan Ganda:</strong> Penggabungan tiket jika ada keluhan serupa pada ruangan yang sama.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="collapse toggle-zone" id="detailLangkah3">
                        <div class="step-detail-box">
                            <span class="badge bg-primary text-white mb-2 px-3 py-1.5 fw-semibold">Tahap 3 dari 5</span>
                            <h5 class="mb-3">Tahap 3: Disposisi Penugasan Lapangan (Sisi Admin Helpdesk)</h5>
                            <p class="mb-4">
                                Tiket yang telah dinyatakan valid akan segera didisposisikan oleh Admin Helpdesk kepada tim teknisi lapangan yang membidangi permasalahan tersebut. Sistem secara otomatis menerbitkan instruksi penugasan digital ke dasbor masing-masing petugas.
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Penyaluran Spesialisasi Teknisi:</strong> Menunjuk petugas penanggung jawab sesuai divisi teknis terkait.</li>
                                        <li><strong>Penerbitan Surat Kerja Digital:</strong> Instruksi resmi penanganan terkirim langsung ke dasbor petugas.</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Penetapan Target Pelayanan:</strong> Penghitungan estimasi waktu penanganan berdasarkan kategori masalah.</li>
                                        <li><strong>Notifikasi Otomatis ke Pelapor:</strong> Pemberitahuan resmi mengenai identitas teknisi penanggung jawab.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="collapse toggle-zone" id="detailLangkah4">
                        <div class="step-detail-box">
                            <span class="badge bg-primary text-white mb-2 px-3 py-1.5 fw-semibold">Tahap 4 dari 5</span>
                            <h5 class="mb-3">Tahap 4: Pelaksanaan Perbaikan & Pembaruan Progres (Sisi Teknisi)</h5>
                            <p class="mb-4">
                                Teknisi mendatangi lokasi atau melakukan tindakan perbaikan teknis. Selama proses perbaikan berlangsung, teknisi diwajibkan melakukan pencatatan pembaruan progres secara berkala ke dalam sistem untuk menjaga transparansi kerja.
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Inspeksi Fisik Lapangan:</strong> Pemeriksaan kondisi riil perangkat atau sarana gedung di lokasi pelapor.</li>
                                        <li><strong>Tindakan Perbaikan & Subtitusi:</strong> Eksekusi perbaikan teknis atau penggantian komponen rusak.</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Log Progres Real-Time:</strong> Penginputan persentase kemajuan perbaikan yang dapat dipantau pelapor.</li>
                                        <li><strong>Uji Fungsi Hasil Perbaikan:</strong> Pengujian bersama pelapor untuk memastikan fasilitas berfungsi normal.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="collapse toggle-zone" id="detailLangkah5">
                        <div class="step-detail-box">
                            <span class="badge bg-primary text-white mb-2 px-3 py-1.5 fw-semibold">Tahap 5 dari 5</span>
                            <h5 class="mb-3">Tahap 5: Konfirmasi Pelapor & Pengarsipan Permanen (Sistem Terpadu)</h5>
                            <p class="mb-4">
                                Setelah perbaikan tuntas, pelapor akan menerima pemberitahuan untuk melakukan peninjauan akhir. Tiket baru dinyatakan ditutup resmi setelah pelapor mengonfirmasi hasil perbaikan serta memberikan evaluasi kepuasan layanan.
                            </p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Konfirmasi Final Pelapor:</strong> Pernyataan persetujuan dari pelapor bahwa kendala terselesaikan.</li>
                                        <li><strong>Penilaian Evaluasi Kepuasan:</strong> Pemberian nilai dan ulasan untuk peningkatan kualitas performa teknis.</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="prosedur-list">
                                        <li><strong>Penutupan Tiket Otomatis:</strong> Sistem menutup tiket secara permanen pasca konfirmasi pelapor.</li>
                                        <li><strong>Pengarsipan History Rekapitulasi:</strong> Penyimpanan seluruh riwayat dokumen laporan ke basis data laporan bulanan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Main Footer Section -->
    <footer>
        <div class="container" style="max-width: 1200px;">
            <div class="row g-4 justify-content-between">
                <div class="col-lg-7 col-md-12">
                    <div class="mb-3">
                        <div class="footer-heading">
                            PUSAT SUMBER DAYA MINERAL, BATUBARA DAN PANAS BUMI
                        </div>
                        <div class="fw-semibold text-white-50" style="font-size: 0.82rem;">
                            Badan Geologi - Kementerian Energi dan Sumber Daya Mineral
                        </div>
                    </div>

                    <p style="font-size: 0.825rem; line-height: 1.7; color: #cbd5e1; text-align: justify;" class="mb-3">
                        Berdasarkan Peraturan Presiden RI No 97 th 2021, Badan Geologi mempunyai tugas menyelenggarakan penyelidikan dan pelayanan di bidang sumber daya geologi, vulkanologi dan mitigasi bencana geologi, air tanah, dan geologi lingkungan, serta survei geologi.
                    </p>

                    <div class="d-flex align-items-center gap-3 mb-3" style="font-size: 0.825rem; color: #e2e8f0;">
                        <i class="bi bi-telephone-fill text-warning fs-5"></i>
                        <div>
                            <div>(+62) 22-7215297</div>
                            <div style="color: #cbd5e1;">geologi@esdm.go.id</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="https://www.facebook.com/p/Badan-Geologi-100068349101047/" target="_blank" rel="noopener noreferrer" class="social-icon-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://x.com/badangeologi_" target="_blank" rel="noopener noreferrer" class="social-icon-btn"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/badan.geologi/" target="_blank" rel="noopener noreferrer" class="social-icon-btn"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/c/BadanGeologiBG" target="_blank" rel="noopener noreferrer" class="social-icon-btn"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.tiktok.com/@badangeologi" target="_blank" rel="noopener noreferrer" class="social-icon-btn"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <div class="footer-heading">Jam Pelayanan Operasional</div>
                    <div class="d-flex flex-column gap-2" style="font-size: 0.825rem; color: #cbd5e1;">
                        <div class="d-flex justify-content-between border-bottom border-secondary border-opacity-25 pb-2">
                            <span>Senin - Kamis:</span>
                            <span class="fw-semibold text-white">08:00 - 16:00 WIB</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom border-secondary border-opacity-25 pb-2">
                            <span>Jumat:</span>
                            <span class="fw-semibold text-white">08:00 - 16:30 WIB</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom border-secondary border-opacity-25 pb-2">
                            <span>Sabtu, Minggu & Libur Nasional:</span>
                            <span class="text-warning fw-semibold">Tutup</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript & Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-in-out'
        });

        function switchStep(stepNumber, targetCollapseId) {
            for (let i = 1; i <= 5; i++) {
                const btn = document.getElementById('btnStep' + i);
                if(btn) btn.classList.remove('active-step');
            }
            const activeBtn = document.getElementById('btnStep' + stepNumber);
            if(activeBtn) activeBtn.classList.add('active-step');

            const zones = document.querySelectorAll('.toggle-zone');
            zones.forEach(zone => {
                const bsCollapse = bootstrap.Collapse.getInstance(zone) || new bootstrap.Collapse(zone, { toggle: false });
                if ('#' + zone.id === targetCollapseId) {
                    bsCollapse.show();
                } else {
                    bsCollapse.hide();
                }
            });
        }
    </script>
</body>
</html>