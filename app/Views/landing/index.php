<!doctype html>
<html lang="id" class="layout-wide" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Sistem Manajemen Pemagang Bank Muamalat Indonesia - Platform digital untuk pengelolaan program magang yang efisien dan terstruktur." />

    <title>Sistem Manajemen Pemagang - Bank Muamalat Indonesia</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon/favicon.ico') ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/fonts/iconify-icons.css') ?>" />

    <style>
        :root {
            --primary: #7c3aed;
            --primary-light: #8b5cf6;
            --primary-dark: #6d28d9;
            --primary-deeper: #5b21b6;
            --accent: #a78bfa;
            --accent-light: #c4b5fd;
            --surface: #ffffff;
            --surface-alt: #faf5ff;
            --text-primary: #1e1b4b;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --gradient-1: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);
            --gradient-2: linear-gradient(135deg, #6d28d9 0%, #7c3aed 50%, #a78bfa 100%);
            --gradient-hero: linear-gradient(160deg, #1e1b4b 0%, #3b0764 30%, #5b21b6 60%, #7c3aed 100%);
            --shadow-sm: 0 1px 3px rgba(124, 58, 237, 0.08);
            --shadow-md: 0 4px 20px rgba(124, 58, 237, 0.12);
            --shadow-lg: 0 10px 40px rgba(124, 58, 237, 0.18);
            --shadow-xl: 0 20px 60px rgba(124, 58, 237, 0.22);
            --radius: 16px;
            --radius-sm: 10px;
            --radius-lg: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
            background: var(--surface);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ===== NAVBAR ===== */
        .landing-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 16px 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .landing-nav.scrolled {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 10px 0;
            box-shadow: 0 1px 20px rgba(124, 58, 237, 0.08);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-brand-icon {
            width: 42px;
            height: 42px;
            background: var(--gradient-1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .nav-brand-text {
            font-size: 20px;
            font-weight: 700;
            color: white;
            transition: color 0.3s;
        }

        .landing-nav.scrolled .nav-brand-text {
            color: var(--primary-dark);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            font-size: 14px;
            padding: 8px 18px;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .landing-nav.scrolled .nav-links a {
            color: var(--text-secondary);
        }

        .nav-links a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.12);
        }

        .landing-nav.scrolled .nav-links a:hover {
            color: var(--primary);
            background: var(--surface-alt);
        }

        .btn-login-nav {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.3) !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 8px 24px !important;
            backdrop-filter: blur(10px);
        }

        .btn-login-nav:hover {
            background: white !important;
            color: var(--primary) !important;
            border-color: white !important;
        }

        .landing-nav.scrolled .btn-login-nav {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: white !important;
        }

        .landing-nav.scrolled .btn-login-nav:hover {
            background: var(--primary-dark) !important;
            border-color: var(--primary-dark) !important;
            box-shadow: var(--shadow-md);
        }

        /* Mobile menu toggle */
        .nav-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 4px;
        }

        .landing-nav.scrolled .nav-toggle {
            color: var(--primary);
        }

        /* ===== HERO ===== */
        .hero {
            background: var(--gradient-hero);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(167, 139, 250, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(30px, -30px);
            }
        }

        /* Animated grid pattern */
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(167, 139, 250, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(167, 139, 250, 0.05) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .hero-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 120px 24px 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-content {
            color: white;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(167, 139, 250, 0.15);
            border: 1px solid rgba(167, 139, 250, 0.25);
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 500;
            color: var(--accent-light);
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }

        .hero-badge-dot {
            width: 6px;
            height: 6px;
            background: #34d399;
            border-radius: 50%;
            animation: pulse-green 2s ease-in-out infinite;
        }

        @keyframes pulse-green {

            0%,
            100% {
                opacity: 1;
                box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.4);
            }

            50% {
                opacity: 0.8;
                box-shadow: 0 0 0 6px rgba(52, 211, 153, 0);
            }
        }

        .hero-title {
            font-size: clamp(2.2rem, 5vw, 3.6rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .hero-title span {
            background: linear-gradient(135deg, #c4b5fd 0%, #a78bfa 50%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: 17px;
            color: rgba(255, 255, 255, 0.7);
            max-width: 520px;
            margin-bottom: 36px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            color: var(--primary-dark);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            font-weight: 500;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-color: rgba(255, 255, 255, 0.3);
        }

        .hero-stats {
            display: flex;
            gap: 40px;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-stat-value {
            font-size: 28px;
            font-weight: 800;
            color: white;
        }

        .hero-stat-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 2px;
        }

        /* Hero illustration (right side) */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-card-stack {
            position: relative;
            width: 100%;
            max-width: 480px;
            aspect-ratio: 1;
        }

        .floating-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: var(--radius);
            padding: 20px;
            color: white;
            animation: card-float 6s ease-in-out infinite;
        }

        .floating-card:nth-child(1) {
            top: 5%;
            left: 5%;
            width: 280px;
            animation-delay: 0s;
        }

        .floating-card:nth-child(2) {
            top: 35%;
            right: 0;
            width: 260px;
            animation-delay: -2s;
        }

        .floating-card:nth-child(3) {
            bottom: 10%;
            left: 10%;
            width: 300px;
            animation-delay: -4s;
        }

        @keyframes card-float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        .fc-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .fc-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .fc-icon.purple {
            background: rgba(167, 139, 250, 0.2);
            color: #c4b5fd;
        }

        .fc-icon.green {
            background: rgba(52, 211, 153, 0.2);
            color: #34d399;
        }

        .fc-icon.blue {
            background: rgba(96, 165, 250, 0.2);
            color: #60a5fa;
        }

        .fc-title {
            font-weight: 600;
            font-size: 14px;
        }

        .fc-subtitle {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
        }

        .fc-value {
            font-size: 26px;
            font-weight: 700;
        }

        .fc-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            margin-top: 10px;
            overflow: hidden;
        }

        .fc-bar-fill {
            height: 100%;
            border-radius: 3px;
            background: var(--gradient-1);
        }

        .fc-list-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .fc-list-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ===== SECTION COMMON ===== */
        .section {
            padding: 100px 0;
        }

        .section-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section-header {
            text-align: center;
            max-width: 640px;
            margin: 0 auto 60px;
        }

        .section-overline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 12px;
        }

        .section-overline i {
            font-size: 16px;
        }

        .section-title {
            font-size: clamp(1.8rem, 3.5vw, 2.5rem);
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 16px;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .section-subtitle {
            font-size: 16px;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* ===== FEATURES ===== */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: var(--surface);
            border: 1px solid #f3e8ff;
            border-radius: var(--radius);
            padding: 32px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-1);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent-light);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
            background: var(--surface-alt);
            color: var(--primary);
        }

        .feature-card:hover .feature-icon {
            background: var(--gradient-1);
            color: white;
        }

        .feature-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .feature-desc {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* ===== HOW IT WORKS ===== */
        .section-alt {
            background: var(--surface-alt);
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            position: relative;
        }

        .steps-grid::before {
            content: '';
            position: absolute;
            top: 38px;
            left: 80px;
            right: 80px;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-light), var(--primary), var(--accent-light));
            opacity: 0.3;
        }

        .step-item {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--gradient-1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            margin: 0 auto 20px;
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.25);
            position: relative;
            z-index: 2;
        }

        .step-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .step-desc {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* ===== ROLES ===== */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .role-card {
            display: flex;
            gap: 20px;
            background: var(--surface);
            border: 1px solid #f3e8ff;
            border-radius: var(--radius);
            padding: 28px;
            transition: all 0.3s;
        }

        .role-card:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--accent-light);
        }

        .role-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .role-icon.admin {
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            color: #7c3aed;
        }

        .role-icon.hr {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }

        .role-icon.mentor {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #2563eb;
        }

        .role-icon.finance {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }

        .role-icon.intern {
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
            color: #db2777;
        }

        .role-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .role-desc {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* ===== CTA ===== */
        .cta-section {
            background: var(--gradient-hero);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(167, 139, 250, 0.12) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cta-content {
            text-align: center;
            color: white;
            position: relative;
            z-index: 2;
        }

        .cta-title {
            font-size: clamp(1.8rem, 3.5vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .cta-desc {
            font-size: 17px;
            color: rgba(255, 255, 255, 0.7);
            max-width: 600px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 16px;
            padding: 16px 40px;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.25);
            color: var(--primary-dark);
        }

        /* ===== FOOTER ===== */
        .landing-footer {
            background: var(--text-primary);
            padding: 60px 0 30px;
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand-text {
            font-size: 20px;
            font-weight: 700;
            color: white;
            margin-bottom: 12px;
        }

        .footer-brand-desc {
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .footer-social {
            display: flex;
            gap: 10px;
        }

        .footer-social a {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 18px;
        }

        .footer-social a:hover {
            background: var(--primary);
            color: white;
        }

        .footer-col-title {
            font-size: 14px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 16px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--accent-light);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-desc {
                margin: 0 auto 36px;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-visual {
                display: none;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .steps-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .steps-grid::before {
                display: none;
            }

            .roles-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .role-card.role-card-full {
                max-width: 100% !important;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .nav-links.active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 68px;
                left: 16px;
                right: 16px;
                background: white;
                border-radius: var(--radius);
                padding: 16px;
                box-shadow: var(--shadow-lg);
            }

            .nav-links.active a {
                color: var(--text-primary);
            }

            .nav-links.active .btn-login-nav {
                background: var(--primary) !important;
                color: white !important;
                border-color: var(--primary) !important;
                text-align: center;
            }

            .nav-toggle {
                display: block;
            }

            .hero-container {
                padding: 100px 16px 60px;
            }

            .hero-badge {
                font-size: 12px;
                padding: 5px 12px;
            }

            .hero-desc {
                font-size: 15px;
            }

            .hero-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                justify-content: center;
                padding: 12px 24px;
                font-size: 14px;
            }

            .hero-stats {
                flex-direction: row;
                gap: 24px;
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 36px;
                padding-top: 24px;
            }

            .hero-stat-value {
                font-size: 22px;
            }

            .hero-stat-label {
                font-size: 12px;
            }

            .section {
                padding: 60px 0;
            }

            .section-container {
                padding: 0 16px;
            }

            .section-header {
                margin-bottom: 36px;
            }

            .section-subtitle {
                font-size: 14px;
            }

            .features-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .feature-card {
                padding: 24px;
            }

            .steps-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .roles-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .role-card {
                padding: 20px;
                gap: 16px;
            }

            .role-card.role-card-full {
                max-width: 100% !important;
                grid-column: auto !important;
            }

            .role-icon {
                width: 48px;
                height: 48px;
                font-size: 20px;
                border-radius: 12px;
            }

            .role-title {
                font-size: 15px;
            }

            .role-desc {
                font-size: 13px;
            }

            .cta-section {
                padding: 60px 0;
            }

            .cta-desc {
                font-size: 15px;
            }

            .btn-cta {
                padding: 14px 32px;
                font-size: 15px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .landing-footer {
                padding: 40px 0 24px;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }

        @media (max-width: 420px) {
            .hero-container {
                padding: 90px 14px 50px;
            }

            .hero-title {
                font-size: 1.6rem;
            }

            .hero-badge {
                font-size: 11px;
                padding: 4px 10px;
            }

            .hero-stats {
                gap: 16px;
            }

            .hero-stat-value {
                font-size: 20px;
            }

            .section-title {
                font-size: 1.4rem;
            }

            .feature-card {
                padding: 20px;
            }

            .feature-icon {
                width: 44px;
                height: 44px;
                font-size: 20px;
            }

            .feature-title {
                font-size: 15px;
            }

            .feature-desc {
                font-size: 13px;
            }

            .step-number {
                width: 44px;
                height: 44px;
                font-size: 16px;
            }

            .step-title {
                font-size: 14px;
            }

            .step-desc {
                font-size: 13px;
            }

            .role-card {
                flex-direction: column;
                text-align: center;
                align-items: center;
                padding: 20px 16px;
            }

            .cta-title {
                font-size: 1.4rem;
            }

            .cta-desc {
                font-size: 14px;
            }

            .btn-cta {
                padding: 12px 28px;
                font-size: 14px;
                width: 100%;
                justify-content: center;
            }
        }

        /* ===== ANIMATIONS ===== */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="landing-nav" id="landingNav">
        <div class="nav-container">
            <a href="<?= base_url('/') ?>" class="nav-brand">
                <div class="nav-brand-icon">
                    <i class="icon-base ri-building-2-line"></i>
                </div>
                <span class="nav-brand-text">BMI Magang</span>
            </a>

            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                <i class="icon-base ri-menu-3-line"></i>
            </button>

            <ul class="nav-links" id="navLinks">
                <li><a href="#features">Fitur</a></li>
                <li><a href="#how-it-works">Cara Kerja</a></li>
                <li><a href="#roles">Peran</a></li>
                <li><a href="<?= base_url('login') ?>" class="btn-login-nav">
                        <i class="icon-base ri-login-box-line"></i> Masuk Sistem
                    </a></li>
            </ul>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="hero" id="hero">
        <div class="hero-grid"></div>
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Platform Digital Manajemen Magang
                </div>
                <h1 class="hero-title">
                    Sistem Pemagang<br>
                    <span>Bank Muamalat Indonesia</span>
                </h1>
                <p class="hero-desc">
                    Platform terintegrasi untuk mengelola seluruh siklus program magang — mulai dari pendaftaran,
                    absensi, penilaian kinerja, hingga pencairan tunjangan secara digital dan transparan.
                </p>
                <div class="hero-actions">
                    <a href="<?= base_url('login') ?>" class="btn-hero-primary">
                        <i class="icon-base ri-login-box-line"></i> Masuk ke Sistem
                    </a>
                    <a href="#features" class="btn-hero-secondary">
                        <i class="icon-base ri-compass-3-line"></i> Jelajahi Fitur
                    </a>
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="hero-stat-value">100+</div>
                        <div class="hero-stat-label">Pemagang Aktif</div>
                    </div>
                    <div>
                        <div class="hero-stat-value">15+</div>
                        <div class="hero-stat-label">Divisi</div>
                    </div>
                    <div>
                        <div class="hero-stat-value">24/7</div>
                        <div class="hero-stat-label">Akses Sistem</div>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-card-stack">
                    <!-- Card 1: Attendance -->
                    <div class="floating-card">
                        <div class="fc-header">
                            <div class="fc-icon purple">
                                <i class="icon-base ri-calendar-check-line"></i>
                            </div>
                            <div>
                                <div class="fc-title">Absensi Hari Ini</div>
                                <div class="fc-subtitle">Real-time tracking</div>
                            </div>
                        </div>
                        <div class="fc-value">92%</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.5)">Kehadiran pemagang</div>
                        <div class="fc-bar">
                            <div class="fc-bar-fill" style="width:92%"></div>
                        </div>
                    </div>

                    <!-- Card 2: KPI -->
                    <div class="floating-card">
                        <div class="fc-header">
                            <div class="fc-icon green">
                                <i class="icon-base ri-bar-chart-box-line"></i>
                            </div>
                            <div>
                                <div class="fc-title">KPI Performance</div>
                                <div class="fc-subtitle">Monthly evaluation</div>
                            </div>
                        </div>
                        <div class="fc-list-item">
                            <div class="fc-list-dot" style="background:#34d399"></div>
                            Excellent: 28 pemagang
                        </div>
                        <div class="fc-list-item">
                            <div class="fc-list-dot" style="background:#60a5fa"></div>
                            Good: 45 pemagang
                        </div>
                        <div class="fc-list-item">
                            <div class="fc-list-dot" style="background:#fbbf24"></div>
                            Average: 19 pemagang
                        </div>
                    </div>

                    <!-- Card 3: Allowance -->
                    <div class="floating-card">
                        <div class="fc-header">
                            <div class="fc-icon blue">
                                <i class="icon-base ri-wallet-3-line"></i>
                            </div>
                            <div>
                                <div class="fc-title">Tunjangan</div>
                                <div class="fc-subtitle">Bulan ini</div>
                            </div>
                        </div>
                        <div class="fc-value" style="font-size:22px">Rp 145.500.000</div>
                        <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:4px">
                            <i class="icon-base ri-check-double-line" style="color:#34d399"></i>
                            92 pemagang sudah dibayar
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section class="section" id="features">
        <div class="section-container">
            <div class="section-header fade-up">
                <div class="section-overline">
                    <i class="icon-base ri-flashlight-line"></i> Fitur Unggulan
                </div>
                <h2 class="section-title">Kelola Program Magang dengan Mudah</h2>
                <p class="section-subtitle">
                    Sistem yang dirancang khusus untuk mendigitalisasi dan mengoptimalkan seluruh proses manajemen pemagang di Bank Muamalat Indonesia.
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="icon-base ri-user-add-line"></i>
                    </div>
                    <div class="feature-title">Manajemen Pemagang</div>
                    <p class="feature-desc">Kelola data pemagang secara lengkap — informasi pribadi, penempatan divisi, periode magang, mentor, dan status keaktifan.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="icon-base ri-fingerprint-line"></i>
                    </div>
                    <div class="feature-title">Absensi Digital</div>
                    <p class="feature-desc">Sistem absensi berbasis foto selfie dengan validasi lokasi dan waktu. Mendukung clock-in, clock-out, dan koreksi kehadiran.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="icon-base ri-line-chart-line"></i>
                    </div>
                    <div class="feature-title">Penilaian KPI</div>
                    <p class="feature-desc">Evaluasi kinerja pemagang dengan indikator KPI yang terukur. Ranking otomatis dan laporan performa per periode.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="icon-base ri-wallet-3-line"></i>
                    </div>
                    <div class="feature-title">Tunjangan Otomatis</div>
                    <p class="feature-desc">Perhitungan tunjangan otomatis berdasarkan kehadiran dan kinerja. Transparansi penuh pada setiap pencairan.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="icon-base ri-task-line"></i>
                    </div>
                    <div class="feature-title">Log Aktivitas</div>
                    <p class="feature-desc">Pemagang mencatat aktivitas harian dan progress proyek. Mentor dapat memantau dan memberikan feedback secara langsung.</p>
                </div>

                <div class="feature-card fade-up">
                    <div class="feature-icon">
                        <i class="icon-base ri-bar-chart-grouped-line"></i>
                    </div>
                    <div class="feature-title">Dashboard & Laporan</div>
                    <p class="feature-desc">Dashboard interaktif dengan visualisasi data real-time. Laporan komprehensif untuk setiap aspek program magang.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section class="section section-alt" id="how-it-works">
        <div class="section-container">
            <div class="section-header fade-up">
                <div class="section-overline">
                    <i class="icon-base ri-route-line"></i> Cara Kerja
                </div>
                <h2 class="section-title">Proses yang Sederhana & Terstruktur</h2>
                <p class="section-subtitle">
                    Empat langkah mudah dalam mengelola program magang secara digital di Bank Muamalat Indonesia.
                </p>
            </div>

            <div class="steps-grid">
                <div class="step-item fade-up">
                    <div class="step-number">1</div>
                    <div class="step-title">Registrasi Pemagang</div>
                    <p class="step-desc">Admin/HR mendaftarkan pemagang baru ke dalam sistem dengan data lengkap dan penempatan divisi.</p>
                </div>

                <div class="step-item fade-up">
                    <div class="step-number">2</div>
                    <div class="step-title">Absensi & Aktivitas</div>
                    <p class="step-desc">Pemagang melakukan absensi harian dan mencatat aktivitas serta progress kerja melalui sistem.</p>
                </div>

                <div class="step-item fade-up">
                    <div class="step-number">3</div>
                    <div class="step-title">Monitoring & Evaluasi</div>
                    <p class="step-desc">Mentor memantau kinerja dan memberikan penilaian KPI. HR mengawasi seluruh progres program.</p>
                </div>

                <div class="step-item fade-up">
                    <div class="step-number">4</div>
                    <div class="step-title">Pencairan Tunjangan</div>
                    <p class="step-desc">Finance memproses tunjangan otomatis berdasarkan data kehadiran dan evaluasi kinerja pemagang.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ROLES ===== -->
    <section class="section" id="roles">
        <div class="section-container">
            <div class="section-header fade-up">
                <div class="section-overline">
                    <i class="icon-base ri-team-line"></i> Peran Pengguna
                </div>
                <h2 class="section-title">Akses Sesuai Peran Masing-Masing</h2>
                <p class="section-subtitle">
                    Setiap pengguna memiliki hak akses dan tampilan yang disesuaikan dengan peran dan tanggung jawabnya.
                </p>
            </div>

            <div class="roles-grid">
                <div class="role-card fade-up">
                    <div class="role-icon admin">
                        <i class="icon-base ri-shield-user-line"></i>
                    </div>
                    <div>
                        <div class="role-title">Admin</div>
                        <p class="role-desc">Mengelola seluruh sistem, pengaturan, manajemen pengguna, divisi, dan konfigurasi platform secara penuh.</p>
                    </div>
                </div>

                <div class="role-card fade-up">
                    <div class="role-icon hr">
                        <i class="icon-base ri-user-settings-line"></i>
                    </div>
                    <div>
                        <div class="role-title">HR Staff</div>
                        <p class="role-desc">Mengelola data pemagang, memproses cuti, memantau absensi, dan membuat laporan program magang.</p>
                    </div>
                </div>

                <div class="role-card fade-up">
                    <div class="role-icon mentor">
                        <i class="icon-base ri-user-star-line"></i>
                    </div>
                    <div>
                        <div class="role-title">Mentor</div>
                        <p class="role-desc">Membimbing pemagang, memantau aktivitas harian, memberikan penilaian KPI dan feedback atas kinerja.</p>
                    </div>
                </div>

                <div class="role-card fade-up">
                    <div class="role-icon finance">
                        <i class="icon-base ri-money-dollar-circle-line"></i>
                    </div>
                    <div>
                        <div class="role-title">Finance</div>
                        <p class="role-desc">Memproses dan mengelola tunjangan pemagang, verifikasi pembayaran, dan laporan keuangan program.</p>
                    </div>
                </div>

                <div class="role-card role-card-full fade-up" style="grid-column: 1 / -1; max-width: 50%; margin: 0 auto;">
                    <div class="role-icon intern">
                        <i class="icon-base ri-user-3-line"></i>
                    </div>
                    <div>
                        <div class="role-title">Pemagang (Intern)</div>
                        <p class="role-desc">Melakukan absensi, mencatat aktivitas harian, mengajukan cuti, dan melihat status tunjangan serta evaluasi kinerja.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="cta-section">
        <div class="section-container">
            <div class="cta-content fade-up">
                <h2 class="cta-title">Siap Menggunakan Sistem?</h2>
                <p class="cta-desc">
                    Masuk ke sistem manajemen pemagang untuk memulai pengelolaan program magang yang lebih efisien, terstruktur, dan transparan.
                </p>
                <a href="<?= base_url('login') ?>" class="btn-cta">
                    <i class="icon-base ri-login-box-line"></i> Masuk Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="landing-footer">
        <div class="section-container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand-text">BMI Magang</div>
                    <p class="footer-brand-desc">
                        Sistem Manajemen Pemagang Bank Muamalat Indonesia. Platform digital untuk mengelola program magang secara efisien dan terstruktur.
                    </p>
                    <div class="footer-social">
                        <a href="#" aria-label="Website"><i class="icon-base ri-global-line"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="icon-base ri-linkedin-box-line"></i></a>
                        <a href="#" aria-label="Instagram"><i class="icon-base ri-instagram-line"></i></a>
                    </div>
                </div>
                <div>
                    <div class="footer-col-title">Menu</div>
                    <ul class="footer-links">
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#how-it-works">Cara Kerja</a></li>
                        <li><a href="#roles">Peran</a></li>
                        <li><a href="<?= base_url('login') ?>">Login</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-col-title">Fitur</div>
                    <ul class="footer-links">
                        <li><a href="#">Manajemen Pemagang</a></li>
                        <li><a href="#">Absensi Digital</a></li>
                        <li><a href="#">Penilaian KPI</a></li>
                        <li><a href="#">Tunjangan</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer-col-title">Kontak</div>
                    <ul class="footer-links">
                        <li><a href="#">Bank Muamalat Indonesia</a></li>
                        <li><a href="#">Jl. Prof. DR. Satrio, Jakarta</a></li>
                        <li><a href="#">magang@bankmuamalat.co.id</a></li>
                        <li><a href="#">(021) 8066-7000</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; <?= date('Y') ?> Bank Muamalat Indonesia. All rights reserved.</span>
                <span>Sistem Manajemen Pemagang v1.0</span>
            </div>
        </div>
    </footer>

    <!-- ===== SCRIPTS ===== -->
    <script>
        // Navbar scroll effect
        const nav = document.getElementById('landingNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Mobile menu toggle
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');
        navToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = navToggle.querySelector('i');
            if (navLinks.classList.contains('active')) {
                icon.classList.replace('ri-menu-3-line', 'ri-close-line');
            } else {
                icon.classList.replace('ri-close-line', 'ri-menu-3-line');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu
                    navLinks.classList.remove('active');
                    navToggle.querySelector('i').classList.replace('ri-close-line', 'ri-menu-3-line');
                }
            });
        });

        // Intersection Observer for fade-up animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -40px 0px'
        });

        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

        // Stagger animation for children
        document.querySelectorAll('.features-grid, .steps-grid, .roles-grid').forEach(grid => {
            const gridObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const children = entry.target.querySelectorAll('.fade-up');
                        children.forEach((child, i) => {
                            setTimeout(() => child.classList.add('visible'), i * 120);
                        });
                        gridObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });
            gridObserver.observe(grid);
        });
    </script>
</body>

</html>