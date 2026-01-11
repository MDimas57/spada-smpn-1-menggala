<x-guest-layout>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - SMP Negeri 1 Menggala</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            /* --- CSS VARIABLES & RESET --- */
            :root {
                --bg-body: #f3f4f6;
                --bg-card: #ffffff;
                /* Diganti ke Teal-600 dan Teal-700 */
                --primary: #0d9488; 
                --primary-dark: #0f766e; 
                --danger: #dc2626;
                --text-dark: #1f2937;
                --text-gray: #6b7280;
                --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Segoe UI', sans-serif;
            }

            body {
                background-color: var(--bg-body);
                color: var(--text-dark);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                position: relative;
                overflow-x: hidden;
            }

            /* --- 1. BAGIAN HEADER LOGO --- */
            .header-logo {
                width: 100%;
                padding: 15px 20px;
                display: flex;
                align-items: center;
                justify-content: flex-start;
                gap: 15px;
                background-color: white;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                position: relative;
                z-index: 10;
                flex-shrink: 0;
            }

            .header-logo img,
            .header-logo svg {
                height: 50px;
                width: auto;
                filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
                flex-shrink: 0;
            }

            .logo-text {
                display: flex;
                flex-direction: column;
                gap: 2px;
                text-align: left;
                flex-shrink: 1;
                min-width: 0;
            }

            .logo-text h1 {
                font-size: 16px;
                color: var(--primary);
                font-weight: 900;
                line-height: 1.1;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                /* Gradient Teal */
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            .logo-text .subtitle {
                font-size: 10px;
                color: var(--text-gray);
                font-weight: 600;
                letter-spacing: 0.3px;
                margin-top: 2px;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }

            /* --- 2. BAGIAN UTAMA KONTEN --- */
            .main-content {
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
                width: 100%;
                min-height: calc(100vh - 120px);
            }

            .login-card {
                background-color: var(--bg-card);
                width: 100%;
                max-width: 450px;
                padding: 50px 30px 30px 30px;
                border-radius: 12px;
                box-shadow: var(--shadow);
                position: relative;
                text-align: center;
                margin: 0 auto;
            }

            /* Ikon Teal Mengambang */
            .floating-icon {
                background-color: var(--primary);
                width: 60px;
                height: 60px;
                border-radius: 10px;
                display: flex;
                justify-content: center;
                align-items: center;
                color: white;
                font-size: 28px;
                position: absolute;
                top: -30px;
                left: 50%;
                transform: translateX(-50%);
                /* Shadow Teal */
                box-shadow: 0 5px 15px rgba(13, 148, 136, 0.3);
                z-index: 5;
            }

            .login-title {
                font-size: 20px;
                font-weight: 700;
                color: var(--text-dark);
                margin-bottom: 8px;
                margin-top: 5px;
            }

            .login-subtitle {
                font-size: 12px;
                color: var(--text-gray);
                margin-bottom: 25px;
                font-weight: 500;
            }

            /* Password Visibility Toggle */
            .password-wrapper {
                position: relative;
            }

            .toggle-password {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                color: var(--text-gray);
                font-size: 16px;
                transition: color 0.3s;
                z-index: 2;
            }

            .toggle-password:hover {
                color: var(--primary);
            }

            /* Input Style */
            .form-group {
                margin-bottom: 15px;
                text-align: left;
            }

            .form-control {
                width: 100%;
                padding: 14px 15px;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                font-size: 14px;
                color: var(--text-dark);
                outline: none;
                transition: border-color 0.3s, box-shadow 0.3s;
                background-color: var(--bg-card);
            }

            .form-control:focus {
                border-color: var(--primary);
                /* Shadow Fokus Teal */
                box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
            }

            /* Placeholder styling */
            ::placeholder {
                color: var(--text-gray);
            }

            /* Tombol Submit Teal */
            .btn-submit {
                width: 100%;
                background-color: var(--primary);
                color: white;
                border: none;
                padding: 14px;
                font-size: 15px;
                font-weight: bold;
                border-radius: 8px;
                cursor: pointer;
                text-transform: uppercase;
                margin-top: 10px;
                transition: background 0.3s;
                /* Shadow Tombol Teal */
                box-shadow: 0 4px 6px rgba(13, 148, 136, 0.2);
            }

            .btn-submit:hover {
                background-color: var(--primary-dark);
            }

            /* Pesan Error */
            .error-message {
                color: var(--danger);
                font-size: 12px;
                margin-top: 4px;
                margin-left: 2px;
            }

            /* --- 3. BAGIAN FOOTER --- */
            .footer-copyright {
                width: 100%;
                padding: 15px 20px;
                text-align: center;
                background-color: white;
                box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.05);
                flex-shrink: 0;
                margin-top: auto;
            }

            .footer-copyright small {
                color: #90a4ae;
                font-size: 11px;
                display: block;
            }

            /* Desktop Styles */
            @media (min-width: 1025px) {
                body {
                    justify-content: center;
                    align-items: center;
                    padding: 40px;
                }

                .header-logo {
                    position: absolute;
                    top: 20px;
                    left: 30px;
                    width: auto;
                    padding: 0;
                    background: none;
                    box-shadow: none;
                    justify-content: flex-start;
                }

                .header-logo img,
                .header-logo svg {
                    height: 70px;
                }

                .logo-text h1 {
                    font-size: 22px;
                    letter-spacing: 0.8px;
                }

                .logo-text .subtitle {
                    font-size: 11px;
                }

                .main-content {
                    min-height: auto;
                    padding: 40px 20px;
                }

                .login-card {
                    max-width: 500px;
                    padding: 55px 40px 40px 40px;
                    margin: 20px auto;
                }

                .floating-icon {
                    width: 70px;
                    height: 70px;
                    top: -35px;
                    font-size: 32px;
                    border-radius: 12px;
                }

                .login-title {
                    font-size: 24px;
                }

                .login-subtitle {
                    font-size: 13px;
                }

                .footer-copyright {
                    position: absolute;
                    bottom: 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    width: auto;
                    padding: 0;
                    background: none;
                    box-shadow: none;
                    margin-top: 0;
                }
            }

            /* Tablet Styles */
            @media (max-width: 1024px) and (min-width: 769px) {
                .header-logo {
                    padding: 12px 30px;
                }

                .header-logo img,
                .header-logo svg {
                    height: 55px;
                }

                .logo-text h1 {
                    font-size: 18px;
                }

                .logo-text .subtitle {
                    font-size: 11px;
                }

                .main-content {
                    min-height: calc(100vh - 140px);
                    padding: 30px 20px;
                }

                .login-card {
                    max-width: 420px;
                    padding: 45px 30px 25px 30px;
                }

                .floating-icon {
                    width: 65px;
                    height: 65px;
                    top: -32px;
                    font-size: 30px;
                }

                .login-title {
                    font-size: 22px;
                }

                .login-subtitle {
                    font-size: 13px;
                }
            }

            /* Mobile Medium */
            @media (max-width: 768px) {
                .header-logo {
                    padding: 12px 15px;
                    gap: 12px;
                }

                .header-logo img,
                .header-logo svg {
                    height: 45px;
                }

                .logo-text h1 {
                    font-size: 14px;
                    line-height: 1.2;
                }

                .logo-text .subtitle {
                    font-size: 9px;
                }

                .main-content {
                    padding: 20px 15px;
                    min-height: calc(100vh - 120px);
                }

                .login-card {
                    padding: 45px 20px 25px 20px;
                    margin: 0 auto;
                }

                .floating-icon {
                    width: 55px;
                    height: 55px;
                    top: -27px;
                    font-size: 25px;
                }

                .login-title {
                    font-size: 18px;
                }

                .login-subtitle {
                    font-size: 11px;
                    margin-bottom: 20px;
                }

                .form-control {
                    padding: 13px 15px;
                }

                .btn-submit {
                    padding: 13px;
                    font-size: 14px;
                }

                .footer-copyright {
                    padding: 12px 15px;
                }
            }

            /* Mobile Small */
            @media (max-width: 480px) {
                .header-logo {
                    padding: 10px 12px;
                    gap: 10px;
                }

                .header-logo img,
                .header-logo svg {
                    height: 40px;
                }

                .logo-text h1 {
                    font-size: 13px;
                }

                .logo-text .subtitle {
                    font-size: 8px;
                }

                .main-content {
                    padding: 10px;
                    min-height: calc(100vh - 110px);
                }

                .login-card {
                    padding: 35px 15px 15px 15px;
                }

                .floating-icon {
                    width: 45px;
                    height: 45px;
                    top: -22px;
                    font-size: 20px;
                }

                .login-title {
                    font-size: 15px;
                }

                .form-control {
                    padding: 11px 12px;
                    font-size: 12px;
                }

                .btn-submit {
                    padding: 12px;
                    font-size: 13px;
                }
            }

            /* Extra Small Devices */
            @media (max-width: 320px) {
                .header-logo {
                    flex-direction: column;
                    text-align: center;
                    gap: 8px;
                    padding: 8px 10px;
                }

                .logo-text {
                    text-align: center;
                }

                .logo-text h1 {
                    font-size: 12px;
                }

                .logo-text .subtitle {
                    font-size: 7px;
                }
            }
        </style>
    </head>
    <body>

        <div class="header-logo">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center">
                <x-application-logo style="height: 50px; width: auto; filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));" class="text-teal-600" />
            </a>

            <div class="logo-text">
                <h1>SMP Negeri 1 Menggala</h1>
                <div class="subtitle">Sistem Pembelajaran Dalam Jaringan</div>
            </div>
        </div>

        <div class="main-content">
            <div class="login-card">
                <div class="floating-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2 class="login-title">Portal Pembelajaran</h2>
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Email">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            required
                            autocomplete="current-password"
                            placeholder="Password">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="remember" style="width: 16px; height: 16px; cursor: pointer;">
                            <span style="font-size: 13px; color: var(--text-gray);">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">Log In</button>
                </form>

                @if (Route::has('password.request'))
                    <div style="margin-top: 15px; text-align: center;">
                        <a href="{{ route('password.request') }}" style="font-size: 12px; color: var(--primary); text-decoration: none;">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="footer-copyright">
            <small>
                &copy; {{ date('Y') }}, SMP Negeri 1 Menggala. All rights reserved.
            </small>
        </div>

        <script>
            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('password');
                const toggleButton = document.querySelector('.toggle-password i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleButton.classList.remove('fa-eye');
                    toggleButton.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleButton.classList.remove('fa-eye-slash');
                    toggleButton.classList.add('fa-eye');
                }
            }
        </script>
    </body>
    </html>
</x-guest-layout>