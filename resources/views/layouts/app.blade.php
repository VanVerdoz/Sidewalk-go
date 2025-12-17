<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sidewalk.Go</title>
    <link rel="icon" href="{{ asset('images/sw3.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #ff6b35;
            --primary-600: #f45b25;
            --primary-700: #d84315;
            --bg: #f7fafc;
            --surface: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --radius: 14px;
            --shadow-sm: 0 2px 6px rgba(0,0,0,0.06);
            --shadow-md: 0 8px 18px rgba(0,0,0,0.08);
            --border: #e5e7eb;
            --table-head: #f3f4f6;
            --table-hover: #f9fafb;
            --accent-bg: #fff5f0;
        }
        .dark {
            --bg: #0d1117;
            --surface: #141a22;
            --text: #e6e7eb;
            --muted: #b0b8c0;
            --shadow-sm: 0 2px 6px rgba(0,0,0,0.45);
            --shadow-md: 0 8px 18px rgba(0,0,0,0.55);
            --border: #2a3140;
            --table-head: #10161f;
            --table-hover: #161b24;
            --accent-bg: rgba(255,255,255,0.05);
        }

        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-700) 100%);
            color: white;
            padding: 0;
            position: fixed;
            top: 0;
            bottom: 0;
            height: 100vh; /* Fallback */
            height: 100dvh; /* Mobile viewport height fix */
            overflow: hidden;
            box-shadow: 4px 0 16px rgba(0, 0, 0, 0.08);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            isolation: isolate;
        }
        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(120px 120px at 50px 80px, rgba(255,255,255,0.18), transparent 60%), radial-gradient(140px 140px at 200px 220px, rgba(255,255,255,0.12), transparent 60%);
            opacity: 0.9;
            pointer-events: none;
            z-index: -1;
        }
        /* Custom Scrollbar for Sidebar Menu */
        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        .dark .sidebar { background: #0c1118; }

        .sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 24px 20px;
            margin-bottom: 0;
            flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo-circle {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            backdrop-filter: blur(4px);
            flex-shrink: 0;
        }

        .sidebar-logo-circle img {
            width: 28px;
            height: auto;
            object-fit: contain;
        }

        .sidebar-logo-circle i {
            font-size: 18px;
            color: white;
        }

        .sidebar-logo h2 {
            font-size: 20px;
            font-weight: 700;
            color: white;
            letter-spacing: 0.5px;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 12px; /* Add top padding back */
            flex: 1; 
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            margin-bottom: 0;
        }

        .sidebar-menu li {
            margin-bottom: 6px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: white;
            text-decoration: none;
            border-radius: 16px;
            transition: all 0.3s;
            font-size: 15px;
            position: relative;
            will-change: transform, background;
        }
        .glass-white {
            background: rgba(255,255,255,0.20);
            border: 1px solid rgba(255,255,255,0.28);
            backdrop-filter: blur(6px);
            color: var(--text);
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
        }
        .dark .glass-white {
            background: transparent;
            color: #ffffff;
            border-color: rgba(255,255,255,0.18);
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.20);
            transform: translateX(3px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
            backdrop-filter: blur(6px);
        }
        .sidebar-menu a.active { border-left: 0; }
        .dark .sidebar-menu a { color: #e5e7eb; }
        .dark .sidebar-menu a:hover,
        .dark .sidebar-menu a.active { background: rgba(255,255,255,0.12); }
        .dark .sidebar-menu a.active { border-left: 0; }

        .sidebar-menu a i {
            margin-right: 12px;
            width: 36px;
            height: 36px;
            line-height: 36px;
            text-align: center;
            border-radius: 10px;
            background: rgba(255,255,255,0.18);
        }
        .sidebar-menu a.active i,
        .sidebar-menu a:hover i {
            background: rgba(255,255,255,0.28);
        }

        .sidebar-user {
            position: static;
            margin-top: auto;
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(4px);
            flex-shrink: 0; /* Keep user section fixed height */
        }
        .dark .sidebar-user { border-top: 1px solid rgba(255,255,255,0.08); }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .sidebar-user-avatar {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        .dark .sidebar-user-avatar { background: rgba(255,255,255,0.12); color: #e5e7eb; }

        .sidebar-user-avatar i {
            font-size: 20px;
        }

        .sidebar-user-details { min-width: 0; }
        .sidebar-user-details h4 {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 3px;
            white-space: normal;
            word-break: break-word;
        }

        .sidebar-user-details p {
            font-size: 12px;
            opacity: 0.8;
            line-height: 1.2;
            margin: 0;
        }

        .btn-logout {
            width: 100%;
            padding: 10px;
            background: rgba(139, 37, 0, 0.8);
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: rgba(107, 29, 0, 1);
        }
        .dark .btn-logout { background: #1f2937; }
        .dark .btn-logout:hover { background: #0f172a; }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 0;
            background: var(--bg);
        }

        /* Header */
        .header {
            background: var(--surface);
            padding: 20px 40px;
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }
        .header-orange {
            background: #ff7a2a;
        }
        .header-orange .theme-group {
            background: rgba(255,245,235,0.88);
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
            color: #1b1b18;
            backdrop-filter: blur(6px);
        }
        .header-orange .theme-group .btn {
            background: transparent;
            border-color: rgba(0,0,0,0.08);
            color: #1b1b18;
        }
        .header-orange .theme-label {
            background: transparent;
            border: none;
            color: #1b1b18;
        }
        .header-orange .btn-profile {
            background: rgba(255,245,235,0.88);
            border: 1px solid rgba(0,0,0,0.08);
            color: #1b1b18;
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
            backdrop-filter: blur(6px);
        }
        .header-orange .header-user {
            background: rgba(255,245,235,0.88);
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
            color: #1b1b18;
            backdrop-filter: blur(6px);
        }
        .header-orange .header-user-info h4,
        .header-orange .header-user-info p {
            color: #1b1b18;
        }
        .header::before {
            content: none;
        }

        .header-left h3 {
            color: var(--primary);
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.2px;
            position: relative;
            display: inline-block;
        }
        .dark .header-left h3 { color: #ffffff; }

        .header-left .subtitle {
            color: var(--text);
            font-size: 14px;
            margin-top: 6px;
            opacity: 0.9;
        }
        .dark .header-left .subtitle { color: #ffffff; opacity: 1; }

        .header-left .meta {
            color: var(--muted);
            font-size: 13px;
            margin-top: 4px;
        }
        .dark .header-left .meta { color: #ffffff; }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .header-topbar {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            width: 100%;
        }
        .header-title-pill {
            justify-self: start;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border: 1px solid rgba(255,255,255,0.28);
            background: rgba(255,255,255,0.20);
            backdrop-filter: blur(6px);
            border-radius: 22px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.10);
            min-width: 260px;
            font-weight: 600;
            color: var(--text);
        }
        .header-title-pill i { color: var(--primary); }
        .dark .header-title-pill { background: transparent; border-color: rgba(255,255,255,0.18); color: #ffffff; }
        .theme-group {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border: 1px solid rgba(255,255,255,0.28);
            border-radius: 14px;
            background: rgba(255,255,255,0.20);
            backdrop-filter: blur(6px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.10);
        }
        .theme-group .btn { margin: 0; }
        .dark .theme-group { background: transparent; border-color: rgba(255,255,255,0.18); }
        .header-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .header-hero {
            margin-top: 14px;
            background: #e05a1a;
            border-radius: 18px;
            padding: 12px 14px;
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 14px;
            align-items: center;
            box-shadow: 0 6px 16px rgba(0,0,0,0.10);
            border: 1px solid rgba(0,0,0,0.10);
            color: #fff;
            width: 100%;
            margin-right: 0;
            position: relative;
            min-height: calc((80px * 2) + 10px);
        }
        .header-hero::before {
            content: none;
        }
        .header-hero::after {
            content: none;
        }
        .header-hero-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 14px;
            align-items: start;
            width: 100%;
        }
        .header-aside-cards {
            display: grid;
            grid-template-rows: repeat(2, 1fr);
            gap: 10px;
            height: calc((80px * 2) + 10px);
        }
        .aside-card {
            border-radius: 22px;
            padding: 12px 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid var(--border);
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            min-height: 90px;
            overflow: hidden;
            position: relative;
        }
        .aside-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(120px 120px at 18% 30%, rgba(255,255,255,0.14), transparent 60%),
                radial-gradient(140px 140px at 76% 28%, rgba(255,255,255,0.10), transparent 60%);
            pointer-events: none;
        }
        .aside-card .label {
            font-size: 13px;
            font-weight: 600;
            opacity: 0.85;
            text-shadow: 0 1px 1px rgba(0,0,0,0.15);
        }
        .aside-card .value {
            font-size: 18px;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0,0,0,0.18);
        }
        .aside-card .icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: inset 0 0 12px rgba(255,255,255,0.22);
        }
        .aside-yellow {
            background: #ff7a2a;
            color: #ffffff;
            border: 1px solid rgba(0,0,0,0.08);
        }
        .aside-yellow .icon { background: rgba(255,255,255,0.28); }
        .aside-pink {
            background: #ff7a2a;
            color: #ffffff;
            border: 1px solid rgba(0,0,0,0.08);
        }
        .aside-pink .icon { background: rgba(255,255,255,0.28); }
        .hero-title {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.2px;
            margin-bottom: 6px;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0,0,0,0.25);
        }
        .hero-header-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,0.95);
            color: var(--text);
            box-shadow: var(--shadow-sm);
            margin-bottom: 8px;
            font-size: 12px;
        }
        .hero-desc {
            font-size: 12px;
            line-height: 1.5;
            opacity: 0.95;
            color: rgba(255,255,255,0.95);
            text-shadow: 0 1px 2px rgba(0,0,0,0.22);
        }
        .hero-meta {
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 0;
            border-radius: 0;
            background: transparent;
            color: #fff;
            border: none;
            font-size: 11px;
            text-shadow: 0 1px 1px rgba(0,0,0,0.2);
        }
        .hero-illustration { display: block; justify-self: end; }
        
        @keyframes heroFloat { 0% { transform: translateY(0); } 50% { transform: translateY(6px); } 100% { transform: translateY(0); } }
        @keyframes steamRise { 0% { opacity: 0; transform: translateY(6px); } 50% { opacity: .9; transform: translateY(0); } 100% { opacity: 0; transform: translateY(-6px); } }
        .hero-svg { width: 280px; height: 110px; }
        .hero-svg .float { animation: heroFloat 3.6s ease-in-out infinite; }
        .hero-svg .steam { animation: steamRise 2.2s ease-in-out infinite; }
        .hero-person-img { width: 100%; max-width: 320px; height: auto; object-fit: contain; filter: drop-shadow(0 8px 18px rgba(0,0,0,0.25)); animation: personFloat 4.2s ease-in-out infinite; }
        @keyframes personFloat { 0% { transform: translateY(0); } 50% { transform: translateY(8px); } 100% { transform: translateY(0); } }
        @keyframes heroBokehMove {
            0% { background-position: 22% 26%, 62% 24%, 78% 70%; opacity: 1; }
            50% { background-position: 30% 32%, 56% 30%, 70% 64%; opacity: 0.9; }
            100% { background-position: 22% 26%, 62% 24%, 78% 70%; opacity: 1; }
        }
        @keyframes heroShine {
            0% { transform: translateX(-120%); }
            50% { transform: translateX(120%); }
            100% { transform: translateX(120%); }
        }
        .btn-profile-text { margin-left: 2px; }
        .theme-label { font-size: 12px; color: var(--muted); padding: 4px 8px; border-radius: 10px; border: 1px solid var(--border); background: var(--surface); }
        .dark .theme-label { color: #ffffff; background: transparent; }
        .btn-profile {
            padding: 8px 14px;
            border-radius: 20px;
            background: rgba(255,255,255,0.20);
            color: var(--text);
            box-shadow: 0 3px 10px rgba(0,0,0,0.10);
            border: 1px solid rgba(255,255,255,0.28);
            backdrop-filter: blur(6px);
            font-size: 13px;
        }
        .dark .btn-profile { background: transparent; color: #ffffff; border-color: rgba(255,255,255,0.18); }

        .header-notification {
            position: relative;
            cursor: pointer;
        }

        .header-notification i {
            font-size: 20px;
            color: var(--primary);
        }
        .dark .header-notification i { color: #ffffff; }

        .header-notification .badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            background: rgba(255,255,255,0.20);
            border-radius: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.10);
            border: 1px solid rgba(255,255,255,0.28);
            backdrop-filter: blur(6px);
        }

        .header-user-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, var(--primary), #f7931e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .header-user-info h4 {
            font-size: 14px;
            color: var(--text);
            font-weight: 500;
        }
        .dark .header-user-info h4 { color: #ffffff; }

        .header-user-info p {
            font-size: 12px;
            color: var(--muted);
        }
        .dark .header-user-info p { color: #ffffff; }

        /* Content Area */
        .content {
            padding: 30px 40px;
        }

        .card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            padding: 20px;
            border: 1px solid var(--border);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text);
        }
        .dark .card-title, .dark .page-title { color: #ffffff; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
            background: var(--surface);
            color: var(--text);
            box-shadow: var(--shadow-sm);
        }

        .btn:hover { transform: translateY(-1px); }

        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-600); }

        .btn-secondary { background: #374151; color: #fff; border-color: transparent; }
        .btn-secondary:hover { background: #1f2937; }
        .btn-toggle { background: transparent; color: var(--text); border-color: var(--border); }
        .btn-toggle:hover { background: var(--table-hover); }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { filter: brightness(0.95); }

        .btn-small { padding: 8px 10px; font-size: 13px; border-radius: 10px; }

        

        .btn-menu { display: none; }
        @media (min-width: 769px) { .btn-menu { display: none !important; } }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            display: none;
            z-index: 900;
        }

        .sidebar-open .sidebar-overlay { display: block; }
        body.sidebar-open { overflow: hidden; }

        .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; box-shadow: var(--shadow-sm); }
        .alert-success { background: rgba(16,185,129,0.1); color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
        .alert-error { background: rgba(239,68,68,0.1); color: #7f1d1d; border: 1px solid rgba(239,68,68,0.25); }

        table { width: 100%; border-collapse: separate; border-spacing: 0; background: var(--surface); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow-md); border: 1px solid var(--border); }
        thead th { background: var(--table-head); color: var(--text); text-align: left; padding: 12px 14px; font-weight: 600; border-bottom: 1px solid var(--border); }
        tbody td { padding: 12px 14px; border-bottom: 1px solid var(--border); color: var(--text); }
        tbody tr:hover { background: var(--table-hover); }
        .dark thead th, .dark tbody td { color: #ffffff; }

        .table-actions { display: flex; gap: 8px; }

        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
        .toast { display: flex; align-items: flex-start; gap: 12px; min-width: 280px; max-width: 420px; padding: 12px 14px; border-radius: 14px; box-shadow: var(--shadow-md); background: var(--surface); border: 1px solid var(--border); animation: toastIn 0.25s ease; }
        .toast-success { border-left: 4px solid var(--success); }
        .toast-error { border-left: 4px solid var(--danger); }
        .toast-icon { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .toast-icon.success { background: rgba(16,185,129,0.12); color: #10b981; }
        .toast-icon.error { background: rgba(239,68,68,0.12); color: #ef4444; }
        .toast-content { flex: 1; }
        .toast-title { font-size: 14px; font-weight: 600; color: var(--text); }
        .toast-message { font-size: 13px; color: var(--muted); margin-top: 3px; }
        .toast-close { background: transparent; border: none; color: #9ca3af; cursor: pointer; padding: 4px; border-radius: 8px; }
        .toast-close:hover { color: #6b7280; }
        @keyframes toastIn { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
        .toast-hide { opacity: 0; transform: translateY(-6px); transition: opacity 0.3s, transform 0.3s; }

        input[type="text"], input[type="number"], input[type="date"], select, textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.15);
        }

        .btn:focus-visible {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.2);
        }

        .sidebar-menu a:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,107,53,0.15);
        }

        .form-row { display: grid; grid-template-columns: repeat(12, 1fr); gap: 12px; }
        .col-12 { grid-column: span 12; }
        .col-6 { grid-column: span 6; }
        .col-4 { grid-column: span 4; }
        .col-3 { grid-column: span 3; }

        /* Responsive */
        @media (max-width: 768px) {
            .header { flex-wrap: wrap; gap: 12px; justify-content: flex-start; padding: 15px 20px; }
            .header-left { display: flex; flex-direction: column; align-items: stretch; gap: 10px; flex: 1 1 100%; }
            .header-left h3 { font-size: 18px; }
            .header-left .subtitle, .header-left .meta { display: none; }
            .header-right { display: none; }
            .theme-label { display: none; }
            .header-user-info { display: none; }
            .btn-profile { padding: 8px; }
            .btn-profile-text { display: none; }
            .btn-profile i { font-size: 16px; }
            .btn-menu { display: inline-flex; }
            
            /* Header Topbar Optimization */
            .header-topbar { 
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 10px;
            }
            .header-title-pill { 
                width: auto; 
                min-width: 0; 
                flex: 1;
                padding: 8px 14px;
                font-size: 13px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .header-actions-right { 
                justify-self: end; 
                flex-wrap: nowrap; 
                gap: 8px !important;
            }
            
            /* Hero Card Optimization */
            .header-hero-row { display: flex; flex-direction: column; gap: 12px; }
            .header-hero { 
                min-height: auto; 
                padding: 16px; 
                margin-bottom: 0; 
                display: block; /* Stack content */
            }
            .hero-illustration { 
                display: none; /* Hide illustration on mobile */
            }
            .hero-title { font-size: 16px; margin-bottom: 4px; }
            .hero-desc { font-size: 12px; margin-bottom: 8px; }
            .hero-meta { font-size: 11px; padding: 0; margin-top: 0; }
            
            /* Aside Cards Side-by-Side */
            .header-aside-cards { 
                grid-template-rows: none; 
                grid-template-columns: 1fr 1fr; /* Side by side */
                height: auto; 
                gap: 10px; 
                margin-top: 0; 
            }
            .aside-card { 
                min-height: 80px; 
                padding: 12px; 
                display: flex;
                flex-direction: column-reverse; /* Value on top of Label? Or Icon separate? */
                align-items: flex-start;
                justify-content: space-between;
                grid-template-columns: 1fr; /* Stack icon and text */
            }
            /* Revert grid for internal card layout if needed, but flex is easier */
            .aside-card {
                display: grid;
                grid-template-columns: 1fr auto;
                align-items: center;
            }
            .aside-card .icon {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }
            .aside-card .value { font-size: 18px; }
            .aside-card .label { font-size: 11px; line-height: 1.2; }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
                will-change: transform;
                width: 80vw;
                max-width: 320px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-user { position: static; bottom: auto; padding-top: 12px; }
            .sidebar-menu a { padding: 12px 16px; border-radius: 12px; }

            .content {
                padding: 20px; /* Reduced padding */
                padding-bottom: calc(20px + env(safe-area-inset-bottom, 0px));
            }

            .form-row { grid-template-columns: repeat(1, 1fr); }

            .table-container { overflow-x: auto; }
            .table { min-width: 780px; }
            thead th, tbody td { white-space: nowrap; }
        }
        @media (max-width: 640px) {
            .table-container { overflow: visible; }
            .table { min-width: 0; }
            table thead { display: none; }
            table { border: 0; }
            table, table tbody { display: block; width: 100%; }
            table tr { display: block; margin-bottom: 12px; border: 1px solid var(--border); border-radius: 12px; background: var(--surface); box-shadow: var(--shadow-sm); padding: 8px; }
            table td { display: inline-block; margin: 0 4px 8px; white-space: normal; padding: 8px 10px; border-bottom: 0; vertical-align: top; }
            table td::before { content: attr(data-label); display: block; font-weight: 600; color: var(--muted); margin-bottom: 4px; }
            table td:nth-child(n+6):not(:last-child) { display: none; }
            table td:nth-child(1),
            table td:nth-child(2),
            table td:nth-child(3) { width: calc(33.33% - 8px); }
            table td:nth-child(4),
            table td:nth-child(5),
            table td:last-child { width: calc(33.33% - 8px); }
            .table-actions, .action-buttons, .req-actions { flex-wrap: nowrap; }
            .table-actions .btn, .action-buttons .btn, .req-actions .btn { width: auto; }
            .hero-title { font-size: 15px; }
            .hero-desc { font-size: 11px; }
            .hero-meta { font-size: 10px; }
            .aside-card { min-height: 72px; }
        }
        @media (max-width: 480px) {
            .header { padding: 10px 14px; }
            .header-left h3 { font-size: 16px; }
            .btn-small { padding: 6px 8px; font-size: 12px; }
            .header-right { gap: 8px; }
            .header-aside-cards { gap: 14px; margin-top: 6px; }
            .aside-card .label { font-size: 12px; }
            .aside-card .value { font-size: 16px; }
        }
    </style>
    @stack('styles')
    <script>
        (function(){
            try {
                var saved = localStorage.getItem('theme');
                var preferDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if(saved === 'dark' || (!saved && preferDark)){
                    document.documentElement.classList.add('dark');
                } else if(saved === 'light') {
                    document.documentElement.classList.remove('dark');
                }
            } catch(e) {}
        })();
    </script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="sidebar-logo-circle">
                   <img src="{{ asset('images/sw3.png') }}" alt="Logo Sidewalk.Go">
                </div>
                <h2>Sidewalk.Go</h2>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @php
                    $user = session('user');
                    $role = $user['role'] ?? '';
                @endphp

                @if(in_array($role, ['owner', 'admin', 'kepala_gudang']))
                <li>
                    <a href="{{ route('produk.index') }}" class="{{ request()->routeIs('produk.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Stok Produk</span>
                    </a>
                </li>
                @endif

                @if(in_array($role, ['owner', 'raider', 'admin']))
                <li>
                    <a href="{{ route('penjualan.index') }}" class="{{ request()->routeIs('penjualan.*') ? 'active' : '' }} {{ (request()->routeIs('dashboard') && ($role ?? '') === 'raider') ? 'glass-white' : '' }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
                @endif

                @if(in_array($role, ['owner', 'admin']))
                <li>
                    <a href="{{ route('laporan-keuangan.index') }}" class="{{ request()->routeIs('laporan-keuangan.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>
                @endif

                @if(in_array($role, ['owner', 'admin', 'kepala_gudang']))
                <li>
                    <a href="{{ route('cabang.index') }}" class="{{ request()->routeIs('cabang.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Cabang</span>
                    </a>
                </li>
                @endif

                @if($role === 'raider')
                <li>
                    <a href="{{ route('raider.permintaan-stok.create') }}" class="{{ request()->routeIs('raider.permintaan-stok.*') ? 'active' : '' }} {{ (request()->routeIs('dashboard') && ($role ?? '') === 'raider') ? 'glass-white' : '' }}">
                        <i class="fas fa-box-open"></i>
                        <span>Request Stok</span>
                    </a>
                </li>
                @endif

                @if($role === 'kepala_gudang')
                <li>
                    <a href="{{ route('kepala.permintaan-stok.index') }}" class="{{ request()->routeIs('kepala.permintaan-stok.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Permintaan Stok Raider</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('kepala.produk-terjual') }}" class="{{ request()->routeIs('kepala.produk-terjual') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Produk Terjual</span>
                    </a>
                </li>
                @endif

                @if(in_array($role, ['owner', 'admin']))
                <li>
                    <a href="{{ route('pengguna.index') }}" class="{{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
                @endif

                
            </ul>

            <div class="sidebar-user">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="sidebar-user-details">
                        <h4>{{ $user['nama_lengkap'] ?? $user['username'] ?? 'Admin' }}</h4>
                        <p>{{ ucfirst(str_replace('_', ' ', $role)) }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </aside>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header {{ request()->routeIs('dashboard') ? 'header-orange' : '' }}">
                <div class="header-left">
                    <button type="button" class="btn btn-toggle btn-small btn-menu" id="menuToggle" aria-label="Buka menu" aria-expanded="false"><i class="fas fa-bars"></i></button>
                    <div class="header-topbar">
                        <div class="header-title-pill">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard {{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                        </div>
                        <div class="header-actions-right" style="display:flex; align-items:center; gap:10px; justify-self:end;">
                            <div class="theme-group">
                                <button type="button" class="btn btn-toggle btn-small" id="themeToggle" title="Mode tampilan (Gelap/Terang)" aria-label="Toggle mode"><i class="fas fa-moon"></i></button>
                                <span id="themeLabel" class="theme-label">Gelap</span>
                            </div>
                            <a href="{{ route('profile.show') }}" class="btn btn-profile">
                                <i class="fas fa-user-cog"></i>
                                <span class="btn-profile-text">Profil</span>
                            </a>
                            <div class="header-user">
                                <div class="header-user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="header-user-info">
                                    <h4>{{ $user['nama_lengkap'] ?? $user['username'] ?? 'Admin' }}</h4>
                                    <p>{{ ucfirst(str_replace('_', ' ', $role)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(request()->routeIs('dashboard'))
                    <div class="header-hero-row">
                        <div class="header-hero">
                        <div class="hero-left">
                            <div class="hero-title">Hi {{ $user['nama_lengkap'] ?? $user['username'] ?? 'Pengguna' }}, Selamat Datang Kembali!</div>
                            <div class="hero-desc">Pantau transaksi harian, stok produk, dan produk terlaris. Kendalikan performa cabang dengan insight yang ringkas dan cepat.</div>
                            <div class="hero-meta">
                                <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                                <span>Peran: {{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                            </div>
                        </div>
                        <div class="hero-illustration">
                            <img src="{{ asset('images/Hero.png') }}" alt="Ilustrasi pekerja" class="hero-person-img">
                        </div>
                        </div>
                            <div class="header-aside-cards">
                            <div class="aside-card aside-yellow">
                                <div>
                                    @if(($role ?? '') === 'kepala_gudang')
                                        <div class="label">Total Permintaan Stok Rider</div>
                                        <div class="value">{{ \App\Models\RequestStok::count() }}</div>
                                    @elseif(($role ?? '') === 'raider')
                                        <div class="label">Total Request Stok Pending</div>
                                        <div class="value">{{ \App\Models\RequestStok::where('status_permintaan','pending')->where('id_raider', session('user.id'))->count() }}</div>
                                    @elseif(($role ?? '') === 'admin')
                                        <div class="label">Total Laporan Keuangan</div>
                                        <div class="value">{{ \App\Models\LaporanKeuangan::count() }}</div>
                                    @else
                                        <div class="label">Total Pengguna</div>
                                        <div class="value">{{ \App\Models\Pengguna::count() }}</div>
                                    @endif
                                </div>
                                <div class="icon">
                                    @if(($role ?? '') === 'kepala_gudang')
                                        <i class="fas fa-box-open"></i>
                                    @elseif(($role ?? '') === 'raider')
                                        <i class="fas fa-hourglass-half"></i>
                                    @elseif(($role ?? '') === 'admin')
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    @else
                                        <i class="fas fa-users"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="aside-card aside-pink">
                                <div>
                                    <div class="label">Total Request Disetujui (Hari Ini)</div>
                                    <div class="value">{{ \App\Models\RequestStok::where('status','disetujui')->whereDate('tanggal', \Carbon\Carbon::today())->count() }}</div>
                                </div>
                                <div class="icon"><i class="fas fa-check-circle"></i></div>
                            </div>
                            </div>
                        </div>
                    </div>
                    @endif
                <div class="header-right"></div>
            </header>

            <!-- Content -->
            <div class="content">
                <div id="toastContainer" class="toast-container">
                    @if(session('success'))
                        <div class="toast toast-success" role="alert">
                            <div class="toast-icon success"><i class="fas fa-check"></i></div>
                            <div class="toast-content">
                                <div class="toast-title">Berhasil</div>
                                <div class="toast-message">{{ session('success') }}</div>
                            </div>
                            <button class="toast-close" onclick="this.closest('.toast').classList.add('toast-hide'); setTimeout(()=>this.closest('.toast').remove(),300)"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="toast toast-error" role="alert">
                            <div class="toast-icon error"><i class="fas fa-exclamation-circle"></i></div>
                            <div class="toast-content">
                                <div class="toast-title">Gagal</div>
                                <div class="toast-message">{{ session('error') }}</div>
                            </div>
                            <button class="toast-close" onclick="this.closest('.toast').classList.add('toast-hide'); setTimeout(()=>this.closest('.toast').remove(),300)"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                    @if(isset($errors) && $errors->any())
                        <div class="toast toast-error" role="alert">
                            <div class="toast-icon error"><i class="fas fa-exclamation-triangle"></i></div>
                            <div class="toast-content">
                                <div class="toast-title">Validasi</div>
                                <div class="toast-message">{{ $errors->first() }}</div>
                            </div>
                            <button class="toast-close" onclick="this.closest('.toast').classList.add('toast-hide'); setTimeout(()=>this.closest('.toast').remove(),300)"><i class="fas fa-times"></i></button>
                        </div>
                    @endif
                </div>

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        (function(){
            const container = document.getElementById('toastContainer');
            if(!container) return;
            const toasts = Array.from(container.querySelectorAll('.toast'));
            toasts.forEach(function(t){
                setTimeout(function(){
                    t.classList.add('toast-hide');
                    setTimeout(function(){ t.remove(); }, 320);
                }, 4500);
            });
        })();
        (function(){
            var btn = document.getElementById('themeToggle');
            var label = document.getElementById('themeLabel');
            if(!btn) return;
            function syncIcon(){
                var isDark = document.documentElement.classList.contains('dark');
                btn.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
                if(label) label.textContent = isDark ? 'Gelap' : 'Terang';
            }
            syncIcon();
            btn.addEventListener('click', function(){
                var root = document.documentElement;
                var isDark = root.classList.toggle('dark');
                try { localStorage.setItem('theme', isDark ? 'dark' : 'light'); } catch(e) {}
                syncIcon();
            });
        })();
        
        (function(){
            var menuBtn = document.getElementById('menuToggle');
            var sidebar = document.querySelector('.sidebar');
            var overlay = document.getElementById('sidebarOverlay');
            if(!menuBtn || !sidebar) return;
            function closeSidebar(){
                sidebar.classList.remove('active');
                document.body.classList.remove('sidebar-open');
                menuBtn.setAttribute('aria-expanded','false');
            }
            menuBtn.addEventListener('click', function(){
                var isOpen = sidebar.classList.toggle('active');
                document.body.classList.toggle('sidebar-open', isOpen);
                menuBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
            if(overlay){ overlay.addEventListener('click', closeSidebar); }
            var links = document.querySelectorAll('.sidebar-menu a');
            links.forEach(function(l){
                l.addEventListener('click', function(){ if(window.innerWidth <= 768) closeSidebar(); });
            });
            document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeSidebar(); });
            window.addEventListener('resize', function(){ if(window.innerWidth > 768){ closeSidebar(); } });
        })();
    </script>
    @stack('scripts')
</body>
</html>
