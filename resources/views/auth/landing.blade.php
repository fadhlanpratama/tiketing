<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ESDM - Sistem Tiketing</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <style>
        :root {
            --primary-color: #0a2540;
            --primary-dark: #06182b;
            --primary-soft: #12365c;
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
            --sla-tinggi: #dc2626;
            --sla-tinggi-bg: #fef2f2;
            --sla-sedang: #d97706;
            --sla-sedang-bg: #fffbeb;
            --sla-rendah: #16a34a;
            --sla-rendah-bg: #f0fdf4;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

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

        a { text-decoration: none; }
        section[id] { scroll-margin-top: 120px; }

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

        .brand-logo-img { width: 38px; height: 38px; object-fit: contain; }

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

        .navbar-custom .nav-link-custom {
            color: #cbd5e1 !important;
            font-size: 0.86rem;
            font-weight: 700;
            padding: 8px 14px !important;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .navbar-custom .nav-link-custom:hover,
        .navbar-custom .nav-link-custom.active-link {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
        }

        .navbar-custom .nav-link-custom.active-link {
            color: var(--secondary-color) !important;
        }

        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.2);
            padding: 6px 10px;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255,255,255,0.85)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
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
            white-space: nowrap;
        }

        .btn-login:hover {
            background: var(--secondary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(255, 197, 58, 0.35);
            color: var(--primary-color) !important;
        }

        .hero-banner-card {
            background: linear-gradient(155deg, var(--primary-color) 0%, var(--primary-soft) 100%);
            color: #ffffff;
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 10px 30px rgba(10, 37, 64, 0.12);
            position: relative;
            overflow: hidden;
        }

        .hero-banner-card::after {
            content: "";
            position: absolute;
            top: -60px;
            right: -60px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 197, 58, 0.14) 0%, transparent 70%);
            pointer-events: none;
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
            max-width: 900px;
            line-height: 1.8;
            margin-top: 24px;
        }

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

        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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
        }

        .service-title-hero {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.3;
        }

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

        .step-section-pu {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: var(--card-shadow);
            padding: 36px 28px;
        }

        .actor-tabs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .actor-tab-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 22px;
            font-size: 0.86rem;
            font-weight: 800;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .actor-tab-btn i {
            font-size: 1rem;
            color: var(--primary-color);
        }

        .actor-tab-btn.active-actor {
            background: var(--primary-color);
            color: #ffffff;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(10, 37, 64, 0.2);
        }

        .actor-tab-btn.active-actor i { color: var(--secondary-color); }

        .actor-tab-sub {
            display: block;
            font-size: 0.68rem;
            font-weight: 600;
            opacity: 0.7;
        }

        .actor-timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .timeline-row {
            display: flex;
            gap: 20px;
            position: relative;
        }

        .timeline-marker-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }

        .timeline-dot {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .timeline-line {
            width: 2px;
            flex-grow: 1;
            background: #e2e8f0;
            margin: 4px 0;
        }

        .timeline-row:last-child .timeline-line { display: none; }

        .timeline-content {
            padding-bottom: 28px;
            flex-grow: 1;
        }

        .timeline-content h6 {
            font-size: 0.98rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 6px;
        }

        .timeline-content p {
            font-size: 0.85rem;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .sla-card {
            border-radius: 18px;
            padding: 22px 22px 20px;
            height: 100%;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .sla-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-hover-shadow);
        }

        .sla-priority-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            font-weight: 900;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 999px;
            margin-bottom: 16px;
        }

        .sla-card.sla-tinggi { border-top: 4px solid var(--sla-tinggi); }
        .sla-card.sla-sedang { border-top: 4px solid var(--sla-sedang); }
        .sla-card.sla-rendah { border-top: 4px solid var(--sla-rendah); }

        .sla-tinggi .sla-priority-pill { background: var(--sla-tinggi-bg); color: var(--sla-tinggi); }
        .sla-sedang .sla-priority-pill { background: var(--sla-sedang-bg); color: var(--sla-sedang); }
        .sla-rendah .sla-priority-pill { background: var(--sla-rendah-bg); color: var(--sla-rendah); }

        .sla-metric-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 10px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .sla-metric-row:last-of-type { border-bottom: none; }

        .sla-metric-label {
            font-size: 0.76rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .sla-metric-value {
            font-size: 0.92rem;
            font-weight: 800;
            color: var(--primary-color);
        }

        .sla-example {
            font-size: 0.78rem;
            color: #64748b;
            margin-top: 14px;
            line-height: 1.5;
        }

        .sla-footnote {
            font-size: 0.78rem;
            color: var(--text-muted);
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

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

        .info-contact-text p { font-size: 0.85rem; color: #64748b; margin: 0; }

        .info-contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.83rem;
            color: var(--text-dark);
        }

        .info-contact-item i { color: var(--primary-color); }

        .chat-support { position: fixed; bottom: 24px; right: 24px; z-index: 1000; }

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

        .chat-avatar:hover { transform: scale(1.08); background-color: var(--secondary-hover); }
        .chat-avatar i { font-size: 24px; color: var(--primary-color); }

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
            .services-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 24px; }
            .hero-banner-card { padding: 30px 20px; }
            .navbar-custom .nav-link-custom { padding: 10px 12px !important; }
        }

        @media (max-width: 576px) {
            .services-grid { grid-template-columns: 1fr; }
            .timeline-row { gap: 14px; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container" style="max-width: 1200px;">
            <a class="navbar-brand m-0" href="{{ route('landing') }}">
                <div class="brand-logo-container">
                    <img src="{{ asset('image/esdm.png') }}" alt="Logo ESDM" class="brand-logo-img">
                    <div>
                        <div class="brand-main">SISTEM TIKETING</div>
                        <div class="brand-small">LAYANAN INTERNAL</div>
                    </div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMainMenu" aria-controls="navMainMenu" aria-expanded="false" aria-label="Buka menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMainMenu">
                <ul class="navbar-nav mx-auto my-2 my-lg-0 gap-1">
                    <li class="nav-item"><a class="nav-link nav-link-custom active-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#layanan">Kategori Layanan</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#alur-tiket">Alur Tiket</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#sla">SLA</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom" href="#kontak">Kontak</a></li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk Sistem
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container py-4" style="max-width: 1200px;">

        <section class="hero-banner-card my-3" id="beranda" data-aos="fade-up">
            <div class="row items-center">
                <div class="col-lg-12">
                    <div class="hero-badge-tag">
                        <i class="fa-solid fa-building-flag"></i>
                        <span>SISTEM TIKETING LAYANAN INTERNAL</span>
                    </div>

                    <h1 class="hero-title">Kelola Tiket Layanan Internal dengan Cepat, Transparan, dan Terukur</h1>

                    <p class="hero-subtitle">
                        Sistem Ticketing Layanan Internal membantu proses pelaporan, penugasan, pemantauan, hingga penyelesaian tiket dalam satu platform terintegrasi. Seluruh aktivitas terdokumentasi secara sistematis sehingga meningkatkan efisiensi, transparansi, dan akuntabilitas layanan.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mt-4 pt-2">
                        <div class="hero-stats-badge">
                            <i class="bi bi-bell-fill text-warning"></i>
                            <span>Notifikasi Real-Time</span>
                        </div>
                        <a class="hero-stats-badge" style="text-decoration:none;">
                            <i class="bi bi-clock-history text-warning"></i>
                            <span>Monitoring Status Tiket</span>
                        </a>
                        <div class="hero-stats-badge">
                            <i class="bi bi-shield-check text-warning"></i>
                            <span>Dokumentasi Penyelesaian</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Grid -->
        <section class="my-5" id="layanan">
            <div class="text-center mb-4">
                <div class="section-title">Kategori Layanan Utama</div>
                <p class="text-muted small mb-0">Setiap kategori mewakili jenis layanan dan permasalahan yang dapat dilaporkan melalui Sistem Tiketing.</p>
            </div>
            <div class="services-grid">
                <a  class="service-card-hero" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-icon-hero"><i class="fa-solid fa-laptop-code"></i></div>
                    <div class="service-title-hero">IT — Software</div>
                    <div class="service-sla-tag text-muted small mt-2"><i class="bi bi-tag-fill"></i> Aplikasi & Sistem Informasi</div>
                </a>

                <a  class="service-card-hero" data-aos="fade-up" data-aos-delay="150">
                    <div class="service-icon-hero"><i class="fa-solid fa-desktop"></i></div>
                    <div class="service-title-hero">IT — Hardware</div>
                    <div class="service-sla-tag text-muted small mt-2"><i class="bi bi-tag-fill"></i> Komputer, Printer & Perangkat</div>
                </a>

                <a  class="service-card-hero" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-icon-hero"><i class="fa-solid fa-wifi"></i></div>
                    <div class="service-title-hero">IT — Jaringan</div>
                    <div class="service-sla-tag text-muted small mt-2"><i class="bi bi-tag-fill"></i> Internet, Wi-Fi & LAN</div>
                </a>

                <a class="service-card-hero" data-aos="fade-up" data-aos-delay="250">
                    <div class="service-icon-hero"><i class="fa-solid fa-folder-open"></i></div>
                    <div class="service-title-hero">Administrasi</div>
                    <div class="service-sla-tag text-muted small mt-2"><i class="bi bi-tag-fill"></i> Layanan Administrasi</div>
                </a>

                <a  class="service-card-hero" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-icon-hero"><i class="fa-solid fa-bolt"></i></div>
                    <div class="service-title-hero">Sarana-Prasarana</div>
                    <div class="service-sla-tag text-muted small mt-2"><i class="bi bi-tag-fill"></i> Gedung & Kelistrikan</div>
                </a>

                <a  class="service-card-hero" data-aos="fade-up" data-aos-delay="350">
                    <div class="service-icon-hero"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="service-title-hero">Keamanan</div>
                    <div class="service-sla-tag text-muted small mt-2"><i class="bi bi-tag-fill"></i> Fasilitas & Keamanan</div>
                </a>

                <a  class="service-card-hero" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-icon-hero"><i class="fa-solid fa-broom"></i></div>
                    <div class="service-title-hero">Kebersihan</div>
                    <div class="service-sla-tag text-muted small mt-2"><i class="bi bi-tag-fill"></i> Area & Kebersihan Gedung</div>
                </a>

                <a  class="service-card-hero" data-aos="fade-up" data-aos-delay="450">
                    <div class="service-icon-hero"><i class="fa-solid fa-ellipsis"></i></div>
                    <div class="service-title-hero">Lainnya</div>
                    <div class="service-sla-tag text-muted small mt-2"><i class="bi bi-tag-fill"></i> Permasalahan Lainnya</div>
                </a>
            </div>
        </section>

        <!-- Penjelasan Sistem Tiketing -->
        <section class="my-5">
            <div class="system-about-box" data-aos="fade-up">
                <h3><i class="bi bi-info-circle-fill me-2 text-warning"></i>  Tentang Sistem Tiketing Layanan Internal</h3>
                <p>
                     Sistem Tiketing Layanan Internal merupakan platform yang digunakan untuk mencatat, mengelola, dan memantau setiap laporan layanan berdasarkan kategori yang tersedia. Melalui sistem ini, proses pelaporan, penugasan, penanganan, hingga penyelesaian tiket dilakukan secara terintegrasi sehingga mendukung pelayanan yang lebih cepat, transparan, dan terdokumentasi.
                </p>
            </div>
        </section>

        <!-- Alur Tiket (3 Role) -->
        <section class="my-5" id="alur-tiket">
            <div class="step-section-pu" data-aos="fade-up">
                <div class="text-center mb-4">
                    <div class="section-title">Alur Kerja Sistem Tiketing</div>
                    <p class="text-muted small mb-0">Pilih peran untuk memahami alur kerja, tanggung jawab, serta notifikasi yang diterima pada setiap tahapan penanganan tiket.</p>
                </div>

                <div class="actor-tabs">
                    <button class="actor-tab-btn active-actor" id="actorBtn1" type="button" onclick="switchActor(1)">
                        <i class="bi bi-person-fill"></i>
                        <span>Pelapor</span>
                    </button>
                    <button class="actor-tab-btn" id="actorBtn2" type="button" onclick="switchActor(2)">
                        <i class="bi bi-tools"></i>
                        <span>Penanggung Jawab</span>
                    </button>
                    <button class="actor-tab-btn" id="actorBtn3" type="button" onclick="switchActor(3)">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Admin Data</span>
                    </button>
                </div>

                <!-- Sisi Pelapor -->
                <div class="actor-panel" id="actorPanel1">
                    <div class="alert alert-light border mb-4">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        <strong>Informasi:</strong> Pengguna yang belum memiliki akun dapat melakukan registrasi. Akun akan diverifikasi oleh Admin sebelum dapat digunakan untuk login dan membuat tiket layanan.
                    </div>
                    <div class="actor-timeline">

                        <!-- STEP 1 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">1</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>Pembuatan Tiket (Status: <strong>Open</strong>)</h6>
                                <p>
                                    Pelapor membuat tiket dengan mengisi kategori layanan, subkategori, deskripsi permasalahan,
                                    prioritas, serta lampiran pendukung apabila diperlukan. Setelah berhasil dikirim,
                                    sistem akan membuat ID tiket secara otomatis dan status tiket menjadi
                                    <strong>Open</strong>.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 2 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">2</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-bell-fill text-warning me-1"></i>
                                    Penugasan Penanggung Jawab (PJ)
                                </h6>
                                <p>
                                    Setelah Admin Data menetapkan Penanggung Jawab (PJ), pelapor akan menerima
                                    notifikasi yang berisi informasi mengenai PJ yang bertanggung jawab atas tiket tersebut.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 3 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">3</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-tools text-primary me-1"></i>
                                    Proses Penanganan (Status: <strong>In Progress</strong>)
                                </h6>
                                <p>
                                    Ketika PJ mulai menangani tiket, status berubah menjadi
                                    <strong>In Progress</strong>. Pelapor dapat memantau perkembangan dan
                                    berdiskusi melalui fitur komentar. Setiap komentar baru akan mengirimkan
                                    notifikasi kepada pihak yang terkait.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 4 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">4</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    Penyelesaian Tiket (Status: <strong>Resolved</strong>)
                                </h6>
                                <p>
                                    Setelah pekerjaan selesai, PJ mengunggah dokumentasi atau foto bukti
                                    penyelesaian dan mengubah status tiket menjadi
                                    <strong>Resolved</strong>. Pelapor akan menerima notifikasi untuk
                                    meninjau hasil penyelesaian.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 5 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">5</div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    Penutupan Tiket & Survei Kepuasan (Status: <strong>Closed</strong>)
                                </h6>
                                <p>
                                    Setelah Admin Data menutup tiket, status berubah menjadi
                                    <strong>Closed</strong>. Pelapor diwajibkan mengisi survei kepuasan
                                    sebagai evaluasi kualitas layanan. Selama survei belum diselesaikan,
                                    pelapor tidak dapat membuat tiket baru.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Sisi Penanggung Jawab (PJ) -->
                <div class="actor-panel d-none" id="actorPanel2">
                    <div class="alert alert-light border mb-4">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        <strong>Informasi:</strong> Pengguna yang belum memiliki akun dapat melakukan registrasi. Akun akan diverifikasi oleh Admin sebelum dapat digunakan untuk login dan membuat tiket layanan.
                    </div>
                    <div class="actor-timeline">

                        <!-- STEP 1 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">1</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-bell-fill text-warning me-1"></i>
                                    Penugasan Tiket (Status: <strong>Open</strong>)
                                </h6>
                                <p>
                                    PJ menerima notifikasi ketika Admin Data menetapkan tiket untuk ditangani. Pada tahap ini status tiket masih
                                    <strong>Open</strong>. PJ dapat melihat informasi pelapor, kategori layanan, deskripsi permasalahan, prioritas, serta lampiran yang tersedia.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 2 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">2</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-tools text-primary me-1"></i>
                                    Mulai Mengerjakan Tiket (Status: <strong>In Progress</strong>)
                                </h6>
                                <p>
                                    Setelah PJ menekan tombol <strong>Kerjakan</strong>, status tiket berubah menjadi
                                    <strong>In Progress</strong>. Selama proses penanganan, PJ dapat memberikan komentar atau membalas komentar pelapor. Setiap aktivitas komentar akan mengirimkan notifikasi kepada pihak terkait.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 3 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">3</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    Penyelesaian Tiket (Status: <strong>Resolved</strong>)
                                </h6>
                                <p>
                                    Setelah pekerjaan selesai, PJ wajib mengunggah foto atau dokumentasi bukti penyelesaian sebelum mengubah status tiket menjadi
                                    <strong>Resolved</strong>. Sistem kemudian mengirimkan notifikasi kepada pelapor untuk meninjau hasil penyelesaian.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 4 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">4</div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-bell-fill text-warning me-1"></i>
                                    Notifikasi Penutupan atau Pembatalan Tiket
                                </h6>
                                <p>
                                    PJ akan menerima notifikasi ketika Admin Data mengubah status tiket menjadi
                                    <strong>Closed</strong>. Apabila pelapor membatalkan tiket (<strong>Closed by User</strong>), PJ juga akan menerima notifikasi yang disertai alasan pembatalan sebagai informasi dan dokumentasi.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Sisi Admin Data -->
                <div class="actor-panel d-none" id="actorPanel3">
                    <div class="actor-timeline">

                        <!-- STEP 1 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">1</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-person-check-fill text-success me-1"></i>
                                    Persetujuan Akun Pengguna
                                </h6>
                                <p>
                                    Admin memverifikasi setiap pendaftaran pengguna sebelum akun dapat digunakan. Pada tahap ini, Admin menentukan <strong>role</strong> (Pelapor atau Penanggung Jawab) serta <strong>divisi</strong> sesuai data pengguna.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 2 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">2</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-person-workspace text-primary me-1"></i>
                                    Penugasan Penanggung Jawab (PJ)
                                </h6>
                                <p>
                                    Setelah tiket dibuat, Admin meninjau kategori, prioritas, dan detail permasalahan, kemudian menetapkan Penanggung Jawab (PJ) yang sesuai. Sistem secara otomatis mengirimkan notifikasi penugasan kepada PJ dan informasi penanggung jawab kepada pelapor.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 3 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">3</div>
                                <div class="timeline-line"></div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-bell-fill text-warning me-1"></i>
                                    Monitoring Penyelesaian Tiket
                                </h6>
                                <p>
                                    Ketika PJ mengubah status tiket menjadi <strong>Resolved</strong>, Admin menerima notifikasi untuk melakukan pemeriksaan terhadap hasil penyelesaian, dokumentasi atau foto bukti, serta memastikan proses penanganan telah sesuai.
                                </p>
                            </div>
                        </div>

                        <!-- STEP 4 -->
                        <div class="timeline-row">
                            <div class="timeline-marker-col">
                                <div class="timeline-dot">4</div>
                            </div>
                            <div class="timeline-content">
                                <h6>
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    Penutupan Tiket (Status: <strong>Closed</strong>)
                                </h6>
                                <p>
                                    Setelah proses verifikasi selesai, Admin mengubah status tiket menjadi <strong>Closed</strong>. Seluruh riwayat perubahan status dan aktivitas tiket tersimpan sebagai dokumentasi untuk keperluan monitoring dan evaluasi layanan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- SLA Section -->
        <section class="my-5" id="sla">
            <div class="text-center mb-3">
                <div class="section-title">Monitoring Target Penyelesaian (SLA)</div>
                <p class="sla-intro-note mx-auto mb-0">Setiap tiket memiliki target waktu penyelesaian berdasarkan tingkat prioritas.</p>
            </div>

            <div class="row g-3 mt-2">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="sla-card sla-tinggi">
                        <span class="sla-priority-pill"><i class="bi bi-exclamation-triangle-fill"></i> Prioritas Tinggi</span>
                        <div class="sla-metric-row">
                            <span class="sla-metric-label">Target Respons</span>
                            <span class="sla-metric-value">≤ 2 Jam Kerja</span>
                        </div>
                        <div class="sla-metric-row">
                            <span class="sla-metric-label">Target Selesai</span>
                            <span class="sla-metric-value">≤ 1 Hari Kerja</span>
                        </div>
                        <p class="sla-example">Contoh: Jaringan server mati, VPN terputus, kendala mendesak operasional.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="sla-card sla-sedang">
                        <span class="sla-priority-pill"><i class="bi bi-info-circle-fill"></i> Prioritas Sedang</span>
                        <div class="sla-metric-row">
                            <span class="sla-metric-label">Target Respons</span>
                            <span class="sla-metric-value">≤ 4 Jam Kerja</span>
                        </div>
                        <div class="sla-metric-row">
                            <span class="sla-metric-label">Target Selesai</span>
                            <span class="sla-metric-value">≤ 3 Hari Kerja</span>
                        </div>
                        <p class="sla-example">Contoh: Komputer kerja lambat, printer error, perbaikan fasilitas standar.</p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="sla-card sla-rendah">
                        <span class="sla-priority-pill"><i class="bi bi-check-circle-fill"></i> Prioritas Rendah</span>
                        <div class="sla-metric-row">
                            <span class="sla-metric-label">Target Respons</span>
                            <span class="sla-metric-value">≤ 1 Hari Kerja</span>
                        </div>
                        <div class="sla-metric-row">
                            <span class="sla-metric-label">Target Selesai</span>
                            <span class="sla-metric-value">≤ 7 Hari Kerja</span>
                        </div>
                        <p class="sla-example">Contoh: Permintaan instalasi software pendukung, pemeliharaan rutin.</p>
                    </div>
                </div>
            </div>

            <div class="sla-footnote mt-4" data-aos="fade-up">
                <i class="bi bi-clock-history mt-1"> <span>  SLA dihitung berdasarkan jam kerja operasional.</span></i>
            </div>
        </section>

        <!-- Informasi Kontak Teknis -->
        <section class="my-5" id="kontak">
            <div class="info-contact-section d-flex flex-wrap justify-content-between align-items-center gap-3" data-aos="fade-up">
                <div class="info-contact-text">
                    <h4>Bantuan Teknis & Helpdesk Internal</h4>
                    <p>Hubungi tim Helpdesk PSDMBP jika memerlukan bantuan terkait akses sistem tiketing.</p>
                </div>
                <div class="d-flex flex-wrap gap-4">
                    <div class="info-contact-item"><i class="bi bi-telephone-fill"></i> (+62) 22-7215297</div>
                    <div class="info-contact-item"><i class="bi bi-envelope-fill"></i> geologi@esdm.go.id</div>
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
                        <div class="footer-heading">PUSAT SUMBER DAYA MINERAL, BATUBARA DAN PANAS BUMI</div>
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

            <div class="copyright-bar">
                &copy; {{ date('Y') }} PSDMBP - Badan Geologi, Kementerian ESDM.
            </div>
        </div>
    </footer>

    <!-- JavaScript & Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        window.addEventListener('load', () => {
            if (!window.location.hash || window.location.hash === '#beranda') {
                window.scrollTo(0, 0);
            }
        });

        AOS.init({ duration: 700, once: true, easing: 'ease-in-out' });

        // Tab Switcher untuk 3 Role
        function switchActor(actorNumber) {
            for (let i = 1; i <= 3; i++) {
                const btn = document.getElementById('actorBtn' + i);
                const panel = document.getElementById('actorPanel' + i);
                if (btn && panel) {
                    if (i === actorNumber) {
                        btn.classList.add('active-actor');
                        panel.classList.remove('d-none');
                    } else {
                        btn.classList.remove('active-actor');
                        panel.classList.add('d-none');
                    }
                }
            }
        }

        const navLinks = document.querySelectorAll('.nav-link-custom');
        const sections = Array.from(navLinks)
            .map(link => document.querySelector(link.getAttribute('href')))
            .filter(Boolean);

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = '#' + entry.target.id;
                    navLinks.forEach(link => {
                        link.classList.toggle('active-link', link.getAttribute('href') === id);
                    });
                }
            });
        }, { rootMargin: '-40% 0px -50% 0px', threshold: 0 });

        sections.forEach(sec => observer.observe(sec));

        document.querySelectorAll('#navMainMenu .nav-link-custom').forEach(link => {
            link.addEventListener('click', () => {
                const menu = document.getElementById('navMainMenu');
                const bsCollapse = bootstrap.Collapse.getInstance(menu);
                if (bsCollapse && menu.classList.contains('show')) bsCollapse.hide();
            });
        });
    </script>
</body>
</html>