<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResourceHQ - Premium Resource Booking</title>
    <meta name="view-transition" content="same-origin">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/js/app.ts'])
    
    <!-- Alpine for FAQ Accordion -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        
        .reveal-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-up.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .hover-lift {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .hover-lift:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.4);
        }
        
        /* Premium Background Glow */
        .glow-bg {
            position: absolute;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(52, 211, 153, 0.15) 0%, rgba(0,0,0,0) 70%);
            top: -300px;
            left: 50%;
            transform: translateX(-50%);
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-50 min-h-screen overflow-x-hidden antialiased relative">
    
    <div class="glow-bg"></div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-zinc-950/80 backdrop-blur-md border-b border-zinc-800 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 no-underline text-zinc-50 font-bold text-lg tracking-tight hover-lift">
                <div class="bg-zinc-50 text-zinc-950 w-8 h-8 flex items-center justify-center rounded-md text-base shadow-[0_0_15px_rgba(255,255,255,0.2)]">R</div>
                <span>ResourceHQ</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="#features" class="text-sm font-medium text-zinc-400 hover:text-zinc-50 transition-colors hidden md:block">Features</a>
                <a href="#social-proof" class="text-sm font-medium text-zinc-400 hover:text-zinc-50 transition-colors hidden md:block">Testimonials</a>
                <a href="#faq" class="text-sm font-medium text-zinc-400 hover:text-zinc-50 transition-colors hidden md:block">FAQ</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-zinc-900 bg-emerald-400 px-5 py-2.5 rounded-lg hover:bg-emerald-500 transition-colors shadow-[0_0_20px_rgba(52,211,153,0.3)] hover-lift">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-50 hover:text-emerald-400 transition-colors">Log In</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium text-zinc-950 bg-gradient-to-r from-emerald-400 to-emerald-500 px-5 py-2.5 rounded-lg transition-all duration-300 shadow-[0_0_20px_rgba(52,211,153,0.2)] hover:shadow-[0_0_30px_rgba(52,211,153,0.4)] hover-lift">Start Free Trial</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- 1. Hero Section -->
    <section class="pt-40 pb-20 px-6 max-w-7xl mx-auto text-center flex flex-col items-center min-h-[90vh] relative">
        <div class="reveal-up inline-flex items-center gap-2 px-4 py-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 mb-8 backdrop-blur-sm mt-8">
            <span class="flex h-2 w-2 relative">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span class="text-xs font-semibold text-emerald-400 tracking-wide uppercase">No Credit Card Required for 14-Day Trial</span>
        </div>
        
        <h1 class="reveal-up text-5xl md:text-7xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-zinc-50 via-zinc-200 to-zinc-500 mb-6 max-w-4xl leading-tight" style="transition-delay: 100ms;">
            Stop double-booking.<br/>Start streamlining.
        </h1>
        
        <p class="reveal-up text-lg md:text-xl text-zinc-400 max-w-2xl mb-12 leading-relaxed" style="transition-delay: 200ms;">
            The smart booking platform built for your organization. Instantly reserve everything from <strong>Meeting Rooms</strong> and <strong>Executive SUVs</strong> to <strong>MacBook Pros</strong> and <strong>3D Printers</strong> with automated approvals.
        </p>
        
        <div class="reveal-up flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto" style="transition-delay: 300ms;">
            <a href="{{ route('register') }}" class="w-full sm:w-auto text-base font-bold text-zinc-950 bg-gradient-to-r from-emerald-400 to-emerald-500 px-10 py-4 rounded-xl transition-all duration-300 shadow-[0_0_20px_rgba(52,211,153,0.3)] hover:shadow-[0_0_40px_rgba(52,211,153,0.5)] hover-lift flex items-center justify-center gap-2">
                Get Started Free
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
            <a href="#features" class="w-full sm:w-auto text-base font-semibold text-zinc-50 bg-zinc-900 border border-zinc-700 px-10 py-4 rounded-xl hover:bg-zinc-800 transition-all duration-300 hover:border-zinc-500 hover-lift text-center shadow-lg hover:shadow-xl">
                Book a Demo
            </a>
        </div>
        
        <p class="reveal-up mt-6 text-sm text-zinc-500 font-medium" style="transition-delay: 400ms;">
            <svg class="w-4 h-4 inline text-emerald-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Cancel anytime. No hidden fees.
        </p>

        <!-- Hero Visual (Dashboard Mockup) -->
        <div class="reveal-up w-full mt-20 relative group" style="transition-delay: 500ms;">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent z-10 pointer-events-none rounded-2xl"></div>
            <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="ResourceHQ Dashboard Preview" class="relative w-full h-auto rounded-2xl border border-zinc-800 shadow-[0_20px_50px_rgba(0,0,0,0.5)] object-cover opacity-80 transition-opacity duration-500 group-hover:opacity-100 object-top max-h-[600px]">
        </div>
    </section>

    <!-- 2. Core Value & Features -->
    <section id="features" class="py-24 px-6 bg-zinc-950 border-t border-zinc-900 relative">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="reveal-up text-3xl md:text-4xl font-bold text-zinc-50 mb-6 tracking-tight">Everything you need to manage assets</h2>
                <p class="reveal-up text-lg text-zinc-400 max-w-2xl mx-auto">From real-time clash prevention to smart analytics, ResourceHQ solves your biggest operational bottlenecks.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="reveal-up hover-lift bg-zinc-900 border border-zinc-800 rounded-2xl p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-colors"></div>
                    <div class="w-12 h-12 bg-zinc-950 border border-zinc-700 rounded-xl flex items-center justify-center mb-6 text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-50 mb-3">Zero Double-Bookings</h3>
                    <ul class="text-zinc-400 text-sm space-y-3">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Real-time availability engine</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Smart collision detection</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Instant digital confirmation</li>
                    </ul>
                </div>
                
                <!-- Feature 2 -->
                <div class="reveal-up hover-lift bg-zinc-900 border border-zinc-800 rounded-2xl p-8 relative overflow-hidden group" style="transition-delay: 100ms;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-colors"></div>
                    <div class="w-12 h-12 bg-zinc-950 border border-zinc-700 rounded-xl flex items-center justify-center mb-6 text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-50 mb-3">Automated Workflows</h3>
                    <ul class="text-zinc-400 text-sm space-y-3">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Multi-tier approval routing</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Real-time push notifications</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Seamless manager dashboard</li>
                    </ul>
                </div>
                
                <!-- Feature 3 -->
                <div class="reveal-up hover-lift bg-zinc-900 border border-zinc-800 rounded-2xl p-8 relative overflow-hidden group" style="transition-delay: 200ms;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl group-hover:bg-purple-500/20 transition-colors"></div>
                    <div class="w-12 h-12 bg-zinc-950 border border-zinc-700 rounded-xl flex items-center justify-center mb-6 text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-50 mb-3">Deep Analytics</h3>
                    <ul class="text-zinc-400 text-sm space-y-3">
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Track asset utilization rates</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Identify underperforming resources</li>
                        <li class="flex items-start gap-2"><svg class="w-5 h-5 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Generate ROI reports instantly</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Social Proof & Credibility -->
    <section id="social-proof" class="py-24 px-6 bg-zinc-900 border-t border-zinc-800">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <p class="reveal-up text-sm font-bold text-emerald-400 uppercase tracking-widest mb-4">Trusted by 500+ Forward-Thinking Teams</p>
                <div class="reveal-up flex flex-wrap justify-center items-center gap-8 md:gap-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-500" style="transition-delay: 100ms;">
                    <!-- Placeholder Logos -->
                    <div class="text-2xl font-bold font-serif">Acme Corp</div>
                    <div class="text-2xl font-bold font-sans">Globex</div>
                    <div class="text-2xl font-bold tracking-tighter">Soylent</div>
                    <div class="text-2xl font-bold italic">Initech</div>
                    <div class="text-2xl font-extrabold uppercase">Umbrella</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-20">
                <!-- Testimonial 1 -->
                <div class="reveal-up bg-zinc-950 border border-zinc-800 rounded-2xl p-8 shadow-lg relative">
                    <div class="text-emerald-400 mb-6">
                        <!-- 5 Stars -->
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-zinc-300 text-lg leading-relaxed mb-8">"Before ResourceHQ, our team wasted hours tracking down the <strong>Company Car</strong> and finding out who had the <strong>DSLR Camera</strong>. Now, everything is booked instantly with zero conflicts. It's completely transformed our operations."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-zinc-800 rounded-full flex items-center justify-center font-bold text-lg text-zinc-400">SJ</div>
                        <div>
                            <div class="font-bold text-zinc-50">Sarah Jenkins</div>
                            <div class="text-sm text-zinc-500">Operations Director, Acme Corp</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="reveal-up bg-zinc-950 border border-zinc-800 rounded-2xl p-8 shadow-lg relative" style="transition-delay: 100ms;">
                    <div class="text-emerald-400 mb-6">
                        <!-- 5 Stars -->
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <p class="text-zinc-300 text-lg leading-relaxed mb-8">"The approval workflows and real-time dashboard are incredible. I can see exactly when the <strong>Seminar Hall</strong> is being utilized and approve high-value requests for the <strong>Projector 01</strong> directly from my phone."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-zinc-800 rounded-full flex items-center justify-center font-bold text-lg text-zinc-400">MR</div>
                        <div>
                            <div class="font-bold text-zinc-50">Marcus Reed</div>
                            <div class="text-sm text-zinc-500">Resource Manager, Globex</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Friction Reducers (FAQ) -->
    <section id="faq" class="py-24 px-6 bg-zinc-950 border-t border-zinc-900">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="reveal-up text-3xl font-bold text-zinc-50 mb-4">Frequently Asked Questions</h2>
                <p class="reveal-up text-zinc-400">Everything you need to know about the product and billing.</p>
            </div>
            
            <div class="space-y-4" x-data="{ active: null }">
                <!-- FAQ 1 -->
                <div class="reveal-up border border-zinc-800 rounded-xl bg-zinc-900/50 overflow-hidden">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none">
                        <span class="font-semibold text-zinc-50 text-lg">Do I need a credit card to start the trial?</span>
                        <svg class="w-5 h-5 text-emerald-400 transform transition-transform duration-300" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 1" x-collapse style="display: none;">
                        <div class="px-6 pb-5 text-zinc-400">
                            No! You can sign up for our 14-day free trial without entering any payment information. We only ask for payment details when you are ready to upgrade.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="reveal-up border border-zinc-800 rounded-xl bg-zinc-900/50 overflow-hidden" style="transition-delay: 100ms;">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none">
                        <span class="font-semibold text-zinc-50 text-lg">Can I manage multiple locations?</span>
                        <svg class="w-5 h-5 text-emerald-400 transform transition-transform duration-300" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7-7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 2" x-collapse style="display: none;">
                        <div class="px-6 pb-5 text-zinc-400">
                            Yes, ResourceHQ supports infinite categories and locations. You can set up departments, buildings, and specific resource types effortlessly.
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="reveal-up border border-zinc-800 rounded-xl bg-zinc-900/50 overflow-hidden" style="transition-delay: 200ms;">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-6 py-5 flex items-center justify-between text-left focus:outline-none">
                        <span class="font-semibold text-zinc-50 text-lg">Is there a limit on how many resources I can add?</span>
                        <svg class="w-5 h-5 text-emerald-400 transform transition-transform duration-300" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7-7-7-7"></path></svg>
                    </button>
                    <div x-show="active === 3" x-collapse style="display: none;">
                        <div class="px-6 pb-5 text-zinc-400">
                            There are absolutely no limits on resources, categories, or bookings. Our infrastructure scales perfectly with your enterprise.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Final Push / Footer CTA -->
    <section class="py-32 px-6 relative overflow-hidden bg-zinc-900 border-t border-zinc-800">
        <div class="absolute inset-0 bg-emerald-900/5 z-0"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="max-w-4xl mx-auto text-center relative z-10 flex flex-col items-center">
            <h2 class="reveal-up text-4xl md:text-5xl font-bold text-zinc-50 mb-6 tracking-tight">Ready to streamline your workspace?</h2>
            <p class="reveal-up text-zinc-400 mb-10 text-xl max-w-2xl">Join thousands of forward-thinking teams using ResourceHQ to eliminate booking friction and manage their assets efficiently.</p>
            
            <a href="{{ route('register') }}" class="reveal-up inline-flex text-lg font-bold text-zinc-950 bg-gradient-to-r from-emerald-400 to-emerald-500 px-12 py-5 rounded-xl transition-all duration-300 shadow-[0_0_30px_rgba(52,211,153,0.3)] hover:shadow-[0_0_50px_rgba(52,211,153,0.5)] hover-lift items-center gap-2">
                Start your 14-day free trial
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
            
            <div class="reveal-up mt-8 flex items-center justify-center gap-6 text-sm text-zinc-500 font-medium">
                <span class="flex items-center gap-1.5"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> No credit card needed</span>
                <span class="flex items-center gap-1.5"><svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Setup in 2 minutes</span>
            </div>
        </div>
    </section>

    <!-- Clean Footer -->
    <footer class="bg-zinc-950 border-t border-zinc-900 py-12 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6 text-zinc-500 text-sm">
            <div class="flex items-center gap-2">
                <div class="bg-zinc-800 text-zinc-400 w-6 h-6 flex items-center justify-center rounded text-xs font-bold">R</div>
                <span class="font-semibold text-zinc-400">ResourceHQ</span>
                <span>&copy; {{ date('Y') }}. All rights reserved.</span>
            </div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-zinc-300 transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-zinc-300 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-zinc-300 transition-colors">Contact Support</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('is-visible');
                        }, (entry.target.style.transitionDelay ? 0 : index * 100)); 
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

            document.querySelectorAll('.reveal-up').forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>
