<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sidewalk.Go</title>
    <link rel="icon" href="{{ asset('images/sw3.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #4b5563;
            --primary-600: #374151;
            --accent: #ff6b35;
            --accent-600: #f7931e;
            --text: #1f2937;
            --muted: #6b7280;
            --bg: #fff5f0;
            --surface: #ffffff;
            --radius: 18px;
            --shadow: 0 10px 24px rgba(0,0,0,0.10);
            --shadow-sm: 0 4px 10px rgba(0,0,0,0.08);
            --border: #e5e7eb;
        }
        .dark {
            --bg: #0d1117;
            --surface: #141a22;
            --text: #e6e7eb;
            --muted: #b0b8c0;
            --shadow: 0 10px 24px rgba(0,0,0,0.55);
            --shadow-sm: 0 4px 10px rgba(0,0,0,0.45);
            --border: #2a3140;
        }
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            min-height: 100vh;
            height: 100vh;
            display: grid;
            place-items: stretch;
            margin: 0;
        }
        .login-container {
            width: 100vw;
            height: 100vh;
            background: var(--surface);
            border-radius: 0;
            box-shadow: none;
            display: grid;
            grid-template-columns: 1.6fr 0.8fr;
            column-gap: 32px;
            overflow: hidden;
        }
        .login-left {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #eaf7f5 0%, #ffffff 100%);
            padding: 0;
        }
        .login-left::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,107,53,0.08) 0%, rgba(255,255,255,0) 60%);
            z-index: 2;
            pointer-events: none;
        }
        .login-left::after {
            content: "";
            position: absolute;
            right: 0; top: 0; bottom: 0;
            width: 36px;
            background: linear-gradient(to right, rgba(255,255,255,0), var(--bg));
            pointer-events: none;
        }
        .dark .login-left { background: linear-gradient(180deg, #0f1620 0%, #0d1117 100%); }
        .dark .login-left::before { background: linear-gradient(180deg, rgba(247,147,30,0.12) 0%, rgba(13,17,23,0.25) 80%); }
        .dark .login-left::after { background: linear-gradient(to right, rgba(13,17,23,0), var(--bg)); }
        .left-hero {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: 5% 10%;
            z-index: 1;
        }
        .login-right { padding: 30px; background: var(--bg); display: flex; align-items: center; justify-content: center; height: 100%; }
        .form-card { width: 100%; max-width: 480px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 16px 32px rgba(0,0,0,0.08); padding: 30px; display: flex; flex-direction: column; justify-content: center; animation: riseIn .45s ease both; }
        .accent-bar { width: 90px; height: 4px; border-radius: 9999px; margin: 0 auto 12px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-600) 100%); }
        .brand-logo { width: 140px; height: auto; display: block; margin: 0 auto; filter: drop-shadow(0 6px 12px rgba(0,0,0,0.08)); transform: translateY(2px); }
        .brand-wrap { width: 180px; margin: 4px auto 10px; padding: 12px; border-radius: 16px; background: rgba(255,107,53,0.07); box-shadow: 0 8px 18px rgba(0,0,0,0.06); }
        .welcome-title { font-size: 30px; font-weight: 800; letter-spacing: .2px; margin-bottom: 8px; text-align: center; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-600) 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .welcome-sub { text-align: center; color: var(--muted); font-size: 13px; margin: 0 0 18px; }
        .form-list { display: grid; gap: 16px; }
        .input-wrap { position: relative; }
        .form-label { font-size: 13px; color: var(--muted); margin-bottom: 6px; display: block; }
        .form-input { width: 100%; padding: 12px 44px 12px 40px; height: 46px; border: 1px solid var(--border); border-radius: 10px; background: var(--surface); font-size: 14px; color: var(--text); outline: none; transition: box-shadow .2s, border-color .2s; }
        .form-input::placeholder { color: #9aa0a6; }
        .form-input:hover { border-color: #d1d5db; }
        .form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(255,107,53,0.15); }
        .input-icon { position: absolute; left: 12px; top: 65%; transform: translateY(-50%); color: #9aa0a6; font-size: 18px; }
        
        .input-wrap:focus-within .form-label { color: var(--accent); }
        .toggle-btn { position: absolute; right: 10px; top: 65%; transform: translateY(-50%); width: 32px; height: 32px; border-radius: 8px; border: none; background: transparent; color: #6b7280; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; }
        .toggle-btn i { font-size: 18px; transform: translateY(0); }
        .toggle-btn:hover { color: var(--accent); }
        .btn-login { width: 100%; padding: 12px 16px; border: none; border-radius: 10px; background: linear-gradient(135deg, var(--accent) 0%, var(--accent-600) 100%); color: #fff; font-size: 15px; font-weight: 600; cursor: pointer; box-shadow: var(--shadow-sm); margin-top: 6px; transition: box-shadow .2s, transform .2s, filter .2s; }
        .btn-login:hover { filter: brightness(1.02); box-shadow: 0 8px 18px rgba(0,0,0,0.12); transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); box-shadow: var(--shadow-sm); }
        .btn-login:focus-visible { outline: 2px solid rgba(255,107,53,0.35); outline-offset: 2px; }
        .btn-login:disabled { background: #e5e7eb; color: #9aa0a6; cursor: not-allowed; }
        .note { color: var(--muted); font-size: 12px; margin-top: 8px; }
        .error-message { background: rgba(239,68,68,0.08); color: #7f1d1d; border: 1px solid rgba(239,68,68,0.25); padding: 10px 12px; border-radius: 10px; margin-bottom: 12px; font-size: 13px; }
        @media (max-width: 860px) { .login-container { grid-template-columns: 1fr; } .login-right { padding: 20px; } .form-card { max-width: 100%; } .brand-img { max-width: 280px; } }
        @keyframes riseIn { from { opacity: 0; transform: translateY(8px) scale(.98);} to { opacity:1; transform: translateY(0) scale(1);} }
</style>
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
    <div class="login-frame">
        <div class="login-container">
            <div class="login-left">
                <img src="{{ asset('images/sw.png') }}" alt="Sidewalk" class="left-hero"/>
            </div>

            <div class="login-right">
                <div class="form-card">
                    <div class="accent-bar"></div>
                    <div class="brand-wrap"><img id="brandLogo" src="{{ asset('images/sw1.png') }}" alt="Sidewalk.Go" class="brand-logo"/></div>
                    <div class="welcome-title">Masuk ke Dashboard</div>
                    <div class="welcome-sub">Silakan masuk untuk melanjutkan</div>

                    @if($errors->any())
                        <div class="error-message">{{ $errors->first() }}</div>
                    @endif

                    @if(session('success'))
                        <div class="error-message" style="background: rgba(16,185,129,0.1); color:#065f46; border:1px solid rgba(16,185,129,0.25);">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" autocomplete="on">
                        @csrf

                        <div class="form-list">
                            <div class="input-wrap">
                                <label class="form-label">Username</label>
                                <i class="fas fa-user input-icon"></i>
                                <input class="form-input" type="text" name="username" placeholder="Contoh: sidewalk" value="{{ old('username') }}" required autofocus>
                            </div>

                            <div class="input-wrap">
                                <label class="form-label">Kata Sandi</label>
                                <i class="fas fa-lock input-icon"></i>
                                <input id="passwordInput" class="form-input" type="password" name="password" placeholder="Contoh: Sandi123!" required>
                                <button id="togglePassword" type="button" class="toggle-btn" aria-label="Tampilkan password"><i class="fas fa-eye"></i></button>
                            </div>

                            <div>
                                <button type="submit" class="btn-login">Masuk</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            var btn = document.getElementById('togglePassword');
            var input = document.getElementById('passwordInput');
            if(btn && input){
                btn.addEventListener('click', function(){
                    var isPwd = input.type === 'password';
                    input.type = isPwd ? 'text' : 'password';
                    this.innerHTML = isPwd ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
                });
            }
            var img = document.getElementById('brandLogo');
            var fallback = document.getElementById('logo-fallback');
            if(img){
                img.addEventListener('error', function(){
                    if(fallback){ fallback.style.display = 'block'; }
                });
            }
        })();
    </script>
    <!-- Global Loader -->
    <div id="global-loader" style="display: none; position: fixed; inset: 0; background: rgba(255,255,255,0.8); z-index: 99999; justify-content: center; align-items: center; backdrop-filter: blur(5px);">
        <div style="display: flex; flex-direction: column; align-items: center;">
            <i class="fas fa-spinner fa-spin" style="font-size: 50px; color: #ff6b35;"></i>
            <p style="margin-top: 15px; font-weight: 600; color: #333;">Memuat...</p>
        </div>
    </div>
    <style>
        .dark #global-loader { background: rgba(13, 17, 23, 0.85) !important; }
        .dark #global-loader p { color: #e6e7eb !important; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('global-loader');
            const showLoader = () => { if(loader) loader.style.display = 'flex'; };
            const hideLoader = () => { if(loader) loader.style.display = 'none'; };

            // Page Navigation
            window.addEventListener('beforeunload', function() {
                showLoader();
            });

            // Form Submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    if (form.checkValidity()) showLoader();
                });
            });

            // Fetch API Interceptor
            const originalFetch = window.fetch;
            window.fetch = function() {
                showLoader();
                return originalFetch.apply(this, arguments).finally(hideLoader);
            };
            
            // XHR Interceptor
            const originalOpen = XMLHttpRequest.prototype.open;
            XMLHttpRequest.prototype.open = function() {
                this.addEventListener('loadstart', showLoader);
                this.addEventListener('loadend', hideLoader);
                originalOpen.apply(this, arguments);
            };

            // Hide on back/forward cache
            window.addEventListener('pageshow', function(event) {
                hideLoader();
            });
        });
    </script>
</body>
</html>
