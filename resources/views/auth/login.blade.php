<x-guest-layout>
    <style>
        /* 1. LAYOUT CONTAINER UTAMA */
        .login-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
            background-color: #f1f5f9;
            position: relative;
        }

        /* 2. SIDEBAR (HANYA DESKTOP) */
        .sidebar-section {
            display: none;
            width: 40%;
            background-color: #0f172a;
            color: white;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 20;
            height: 100vh;
            position: sticky;
            top: 0;
        }

        .sidebar-accent-strip {
            position: absolute; right: 0; top: 0; bottom: 0; width: 8px;
            background: repeating-linear-gradient(45deg, #dc2626, #dc2626 10px, #ffffff 10px, #ffffff 20px, #2563eb 20px, #2563eb 30px, #ffffff 30px, #ffffff 40px);
            box-shadow: -4px 0 20px rgba(0,0,0,0.5);
            z-index: 25;
        }

        /* 3. KONTEN KANAN (AREA LOGIN) */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            width: 100%;
        }

        /* 4. HEADER MOBILE (HANYA HP) */
        .mobile-header {
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 2rem 1rem;
            background-color: #0f172a;
            color: white;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            z-index: 20;
            /* Kita buat lengkungan sedikit tapi tidak ekstrem */
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.2);
        }

        .mobile-accent-strip {
            position: absolute; top: 0; left: 0; right: 0; height: 6px;
            background: repeating-linear-gradient(90deg, #dc2626, #dc2626 10px, #ffffff 10px, #ffffff 20px, #2563eb 20px, #2563eb 30px, #ffffff 30px, #ffffff 40px);
        }

        /* 5. FORM WRAPPER */
        .form-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        .bg-grid {
            position: absolute; inset: 0;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.5;
            pointer-events: none;
        }

        /* 6. CARD DESAIN */
        .login-card {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 
                0 0 0 1px rgba(0,0,0,0.03), 
                0 20px 40px -5px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            padding: 2.5rem;
            animation: fadeIn Up 0.5s ease-out;
        }

        .card-top-line {
            position: absolute; top: 0; left: 0; width: 100%; height: 4px;
            background: linear-gradient(90deg, #dc2626, #991b1b);
        }

        /* 7. INPUT & BUTTON STYLES */
        .input-wrapper { position: relative; margin-bottom: 1.25rem; }
        
        .custom-label {
            display: block; font-size: 0.75rem; font-weight: 700; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .custom-input {
            width: 100%; padding: 14px 16px 14px 46px;
            background-color: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 12px; font-size: 0.95rem; color: #0f172a;
            transition: all 0.2s ease; font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .custom-input:focus {
            background-color: white; border-color: #0f172a;
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05); outline: none;
        }

        /* Password Toggle Button */
        .password-toggle {
            position: absolute; right: 16px; top: 43px; 
            color: #94a3b8; cursor: pointer; transition: color 0.3s;
            background: none; border: none; padding: 0;
        }
        .password-toggle:hover { color: #0f172a; }

        .input-icon {
            position: absolute; left: 16px; top: 43px; color: #94a3b8;
            transition: color 0.3s; width: 22px; height: 22px;
        }
        .input-wrapper:focus-within .input-icon { color: #0f172a; }

        .btn-submit {
            width: 100%; padding: 16px; background: #dc2626; color: white;
            font-family: 'Oswald', sans-serif; font-size: 1.1rem; text-transform: uppercase;
            letter-spacing: 1px; border: none; border-radius: 12px; cursor: pointer;
            transition: all 0.3s; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
            margin-top: 1rem;
        }
        .btn-submit:hover { background: #b91c1c; transform: translateY(-2px); }

        /* --- RESPONSIVE LOGIC --- */

        /* DESKTOP */
        @media (min-width: 1024px) {
            .login-wrapper { flex-direction: row; }
            .sidebar-section { display: flex; }
            .mobile-header { display: none; }
            .form-container { padding: 3rem; }
        }

        /* MOBILE FIX */
        @media (max-width: 1023px) {
            .login-wrapper { flex-direction: column; }
            .sidebar-section { display: none; }
            .mobile-header { display: flex; }
            
            .form-container { 
                align-items: flex-start; 
                margin-top: 0; /* RESET: Tidak ada margin negatif lagi */
                padding: 1.5rem 1rem; /* Beri jarak yang nyaman */
            }
            
            .login-card {
                padding: 2rem 1.5rem;
                /* Pastikan width full tapi ada margin dikit biar ga nempel pinggir layar banget */
                width: 100%; 
            }
        }
    </style>

    <div class="login-wrapper">

        <aside class="sidebar-section">
            <div class="sidebar-accent-strip"></div>
            <img src="{{ asset('images/logo_login.png') }}" alt="Tangwin Logo" class="w-80 h-auto mb-6 drop-shadow-2xl hover:scale-105 transition-transform duration-300">
        </aside>

        <div class="main-content">
            
            <header class="mobile-header">
                <div class="mobile-accent-strip"></div>
                <img src="{{ asset('images/logo_login.png') }}" alt="Tangwin Logo" class="w-52 h-auto mb-3 drop-shadow-lg">
            </header>

            <div class="form-container">
                <div class="bg-grid"></div>

                <div class="login-card">
                    <div class="card-top-line"></div>

                    <div class="mb-8 text-center sm:text-left">
                        <h2 class="font-oswald text-3xl text-[#0f172a] font-bold mb-1">WELCOME BACK</h2>
                        <p class="text-slate-500 text-sm">Please enter your credentials to access.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="input-wrapper">
                            <label for="email" class="custom-label">Email Address</label>
                            <input id="email" class="custom-input" type="email" name="email" :value="old('email')" required autofocus placeholder="manager@tangwin.com">
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
                        </div>

                        <div class="input-wrapper">
                            <div class="flex justify-between items-center">
                                <label for="password" class="custom-label">Password</label>
                                </div>
                            
                            <input id="password" class="custom-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                            
                            <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>

                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>

                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
                        </div>

                        <div class="mb-6">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#0f172a] shadow-sm focus:ring-[#0f172a] w-5 h-5" name="remember">
                                <span class="ml-3 text-sm text-slate-600 font-medium">Keep me logged in on this device</span>
                            </label>
                        </div>

                        <button type="submit" class="btn-submit">
                            Enter System
                        </button>
                    </form>

                    <div class="mt-8 text-center border-t border-slate-100 pt-6">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold">
                            &copy; {{ date('Y') }} Tangwin Cut Studio
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var eyeOpen = document.getElementById("eye-open");
            var eyeClosed = document.getElementById("eye-closed");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeOpen.style.display = "block";
                eyeClosed.style.display = "none";
            } else {
                passwordInput.type = "password";
                eyeOpen.style.display = "none";
                eyeClosed.style.display = "block";
            }
        }
    </script>
</x-guest-layout>