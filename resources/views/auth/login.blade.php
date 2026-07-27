<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - WebFasilkom</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #network-bg {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .login-shell {
            position: relative;
            isolation: isolate;
            min-height: 100vh;
            overflow: hidden;
            background:
                radial-gradient(circle at 18% 18%, rgba(243, 175, 61, 0.22), transparent 28%),
                radial-gradient(circle at 82% 14%, rgba(59, 130, 246, 0.24), transparent 30%),
                linear-gradient(135deg, #061c35 0%, #0f2a4d 48%, #1b456f 100%);
        }

        .login-shell::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,.85), transparent 85%);
        }

        .login-container {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 767px) {
            .login-shell::before {
                background-size: 34px 34px;
            }
        }
    </style>
</head>
<body class="bg-primary-dark text-slate-900">
<main class="login-shell">
    <canvas id="network-bg"></canvas>

    <div class="login-container min-h-screen flex items-center justify-center px-4 py-8 sm:px-6">
        <div class="w-full max-w-6xl overflow-hidden rounded-2xl border border-white/15 bg-white/10 shadow-2xl shadow-black/30 backdrop-blur-xl">
            <div class="grid min-h-[620px] lg:grid-cols-[1.08fr_0.92fr]">
                <section class="relative hidden overflow-hidden bg-primary p-10 text-white lg:flex lg:flex-col lg:justify-between">
                    <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(255,255,255,.12),transparent_42%),radial-gradient(circle_at_78%_30%,rgba(243,175,61,.28),transparent_30%)]"></div>
                    <div class="relative">
                        <a href="{{ route('home') }}" class="inline-flex items-center">
                            <img src="{{ asset('header.png') }}" alt="Pekanbaru Home Cleaning" class="h-14 w-auto object-contain">
                        </a>
                    </div>

                    <div class="relative max-w-xl">
                        <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-widest text-blue-100">
                            <span class="h-2 w-2 rounded-full bg-accent"></span>
                            Admin Portal
                        </div>
                        <h1 class="text-4xl font-bold leading-tight tracking-tight">Halaman untuk kelola konten.</h1>
                        <p class="mt-5 max-w-lg text-base leading-7 text-blue-100/85">Masuk untuk mengatur pesanan, customer, layanan jasa, testimoni, menu, dan data utama website.</p>
                    </div>

                    <div class="relative grid grid-cols-3 gap-3">
                        <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur-md">
                            <i class="ri-newspaper-line text-2xl text-accent"></i>
                            <div class="mt-3 text-sm font-semibold">Konten</div>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur-md">
                            <i class="ri-chat-voice-line text-2xl text-accent"></i>
                            <div class="mt-3 text-sm font-semibold">Testimoni</div>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/10 p-4 backdrop-blur-md">
                            <i class="ri-shield-check-line text-2xl text-accent"></i>
                            <div class="mt-3 text-sm font-semibold">Akses</div>
                        </div>
                    </div>
                </section>

                <section class="flex items-center justify-center bg-white px-5 py-8 sm:px-8 lg:px-12">
                    <div class="w-full max-w-md">
                        <div class="mb-8 lg:hidden">
                            <a href="{{ route('home') }}" class="inline-flex items-center">
                                <img src="{{ asset('header.png') }}" alt="Pekanbaru Home Cleaning" class="h-14 w-auto object-contain">
                            </a>
                        </div>

                        <div class="mb-8">
                            <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-primary/5 px-3 py-1.5 text-xs font-bold uppercase tracking-widest text-primary">
                                <i class="ri-lock-2-line text-sm"></i>
                                Area Admin
                            </div>
                            <h2 class="text-3xl font-bold tracking-tight text-slate-900">Masuk ke dashboard</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Gunakan akun administrator yang sudah terdaftar.</p>
                        </div>

                        @if($errors->any())
                            <div class="mb-5 flex gap-3 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                                <i class="ri-error-warning-line mt-0.5 text-lg"></i>
                                <span>{{ $errors->first() }}</span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-5 flex gap-3 rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                                <i class="ri-error-warning-line mt-0.5 text-lg"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-slate-400">
                                        <i class="ri-mail-line text-lg"></i>
                                    </span>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="block h-12 w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-medium text-slate-800 shadow-sm transition focus:border-primary focus:bg-white focus:ring-primary" placeholder="admin@pekanbaruhomecleaning.com" required autofocus autocomplete="email">
                                </div>
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-slate-400">
                                        <i class="ri-key-2-line text-lg"></i>
                                    </span>
                                    <input id="password" type="password" name="password" class="block h-12 w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-medium text-slate-800 shadow-sm transition focus:border-primary focus:bg-white focus:ring-primary" placeholder="Masukkan password" required autocomplete="current-password">
                                </div>
                            </div>

                            @if(\App\Models\Setting::get('login_captcha') === 'iya')
                            <div>
                                <label for="captcha" class="mb-2 block text-sm font-semibold text-slate-700">Captcha</label>
                                <div class="flex items-center gap-3">
                                    <div class="relative flex-1">
                                        <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-slate-400">
                                            <i class="ri-shield-check-line text-lg"></i>
                                        </span>
                                        <input id="captcha" type="text" name="captcha_user_answer" class="block h-12 w-full rounded-xl border-slate-200 bg-slate-50 pl-11 pr-4 text-sm font-medium text-slate-800 shadow-sm transition focus:border-primary focus:bg-white focus:ring-primary" placeholder="Jawaban" required autocomplete="off">
                                    </div>
                                    <div class="h-12 px-4 bg-primary/10 rounded-xl flex items-center justify-center font-bold text-primary" id="captcha-question">
                                        {{ $captchaQuestion }}
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Jawab pertanyaan matematika di atas</p>
                            </div>
                            @endif

                            <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 text-sm font-bold text-white shadow-lg shadow-primary/25 transition hover:bg-primary-dark focus:outline-none focus:ring-4 focus:ring-primary/20">
                                <span>Login</span>
                                <i class="ri-arrow-right-line text-lg"></i>
                            </button>

                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-slate-200"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="bg-white px-4 text-slate-400">atau</span>
                                </div>
                            </div>

                            <a href="{{ route('auth.google.redirect') }}" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100">
                                <svg class="h-5 w-5" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span>Login dengan Google</span>
                            </a>
                        </form>

                        <div class="mt-8 flex items-center justify-between border-t border-slate-100 pt-5 text-xs text-slate-400">
                            <span>PHC Pekanbaru</span>
                            <a href="{{ route('home') }}" class="font-semibold text-primary hover:text-primary-dark">Kembali ke website</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

    <script>
    (function() {
        const canvas = document.getElementById('network-bg');
        const ctx = canvas.getContext('2d');
        let width, height, particles, mouse, animId;

        const config = {
            particleCount: 80,
            particleSize: 2.5,
            lineDistance: 150,
            particleColor: 'rgba(59, 130, 246, 0.8)',
            lineColor: 'rgba(59, 130, 246, 0.15)',
            speed: 0.4,
            mouseRadius: 200
        };

        mouse = { x: null, y: null };

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }

        function createParticles() {
            particles = [];
            for (let i = 0; i < config.particleCount; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * config.speed,
                    vy: (Math.random() - 0.5) * config.speed,
                    size: Math.random() * config.particleSize + 1
                });
            }
        }

        function drawParticle(p) {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
            ctx.fillStyle = config.particleColor;
            ctx.fill();

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size + 4, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(59, 130, 246, 0.1)';
            ctx.fill();
        }

        function drawLines() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < config.lineDistance) {
                        const opacity = 1 - dist / config.lineDistance;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.strokeStyle = `rgba(59, 130, 246, ${opacity * 0.2})`;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }

                if (mouse.x !== null) {
                    const dx = particles[i].x - mouse.x;
                    const dy = particles[i].y - mouse.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < config.mouseRadius) {
                        const opacity = 1 - dist / config.mouseRadius;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(mouse.x, mouse.y);
                        ctx.strokeStyle = `rgba(147, 197, 253, ${opacity * 0.4})`;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            }
        }

        function update() {
            particles.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;

                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                if (mouse.x !== null) {
                    const dx = p.x - mouse.x;
                    const dy = p.y - mouse.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < config.mouseRadius) {
                        const force = (config.mouseRadius - dist) / config.mouseRadius;
                        p.vx += (dx / dist) * force * 0.02;
                        p.vy += (dy / dist) * force * 0.02;
                    }
                }

                const speed = Math.sqrt(p.vx * p.vx + p.vy * p.vy);
                if (speed > config.speed * 3) {
                    p.vx = (p.vx / speed) * config.speed * 3;
                    p.vy = (p.vy / speed) * config.speed * 3;
                }
            });
        }

        function animate() {
            ctx.clearRect(0, 0, width, height);
            update();
            drawLines();
            particles.forEach(drawParticle);
            animId = requestAnimationFrame(animate);
        }

        window.addEventListener('resize', () => {
            resize();
        });

        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });

        window.addEventListener('mouseleave', () => {
            mouse.x = null;
            mouse.y = null;
        });

        resize();
        createParticles();
        animate();
    })();
    </script>
</body>
</html>
