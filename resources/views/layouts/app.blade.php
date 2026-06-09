<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title') | {{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->site_name : 'Mosh Edibles' }}</title>
    
    <meta content="{{ !empty($pageGlobalData->setting) ? $pageGlobalData->setting->description : 'Artisanal Treats Baked Fresh Daily.' }}" name="description" />
    <meta content="sky-hackeR(+2348082574927)" name="author" />
    
    <link rel="shortcut icon" href="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->favicon) : '' }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,400&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: { 
                        mosh: {
                            gold: '#B38B46',
                            dark: '#0D0B0A',
                            card: '#161311',
                            accent: '#6BA132',
                            cream: '#F5F5F0',
                            slateText: '#1E2329'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .theme-transition {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .html-light-mode .glass-card {
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 10px 30px -5px rgba(130, 110, 70, 0.06) !important;
        }

        .html-dark-mode .glass-card {
            background: rgba(22, 19, 17, 0.75) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.4) !important;
        }
    </style>
</head>
<body id="app-body" class="theme-transition font-sans antialiased min-h-screen flex flex-col justify-between bg-mosh-dark text-gray-300">

    <header id="app-header" class="theme-transition sticky top-0 left-0 right-0 z-50 border-b border-gray-500/10 glass-card">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            
            <a href="{{ url('/') }}" class="text-xl font-serif font-bold tracking-tight id-logo-text text-white">
                mosh<span class="text-mosh-gold font-normal">edibles</span>
            </a>

            <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
                <a href="{{ url('/products') }}" class="id-nav-link text-gray-400 hover:text-mosh-gold transition-colors">Our Menu</a>
                <a href="{{ url('/about') }}" class="id-nav-link text-gray-400 hover:text-mosh-gold transition-colors">About Us</a>
                <a href="{{ url('/contact') }}" class="id-nav-link text-gray-400 hover:text-mosh-gold transition-colors">Contact Us</a>
            </nav>

            <div class="flex items-center space-x-4">
                <button onclick="toggleGlobalTheme()" class="text-lg id-nav-link text-gray-400 hover:text-mosh-gold focus:outline-none" title="Change Theme Mode">
                    <i id="theme-icon" class="mdi mdi-weather-sunny"></i>
                </button>

                <a href="{{ url('/order') }}" class="bg-mosh-gold hover:bg-opacity-90 text-white text-xs font-semibold px-4 py-2 rounded-full transition-colors">
                    Order Now
                </a>
            </div>

        </div>
    </header>

    <div class="flex-grow">
        @yield('content')
    </div>

    <footer id="app-footer" class="theme-transition border-t border-gray-900/60 bg-mosh-dark text-gray-500 py-10 text-xs">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <div class="space-y-2">
                <h3 class="text-lg font-serif font-bold text-mosh-gold">mosh<span class="font-normal text-gray-400">edibles</span></h3>
                <p class="text-xs text-gray-400">Artisanal treats baked fresh daily.</p>
            </div>

            <div class="space-y-2">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-mosh-gold mb-3">Explore</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ url('/products') }}" class="id-nav-link text-gray-400 hover:text-mosh-gold transition-colors">View Menu</a></li>
                </ul>
            </div>

            <div class="space-y-2">
                <h4 class="text-xs font-semibold uppercase tracking-wider text-mosh-gold mb-3">Contact</h4>
                <p class="text-xs text-gray-400 leading-relaxed">
                    hello@moshedibles.com <br>
                    Open Tue - Sun, 8 AM - 6 PM
                </p>
            </div>

            <div class="space-y-2">
                <h6 class="text-xs uppercase tracking-widest font-bold text-mosh-gold mb-3">Internal Portals</h6>
                <p class="text-xs leading-relaxed text-gray-400 mb-2">Authorized personnel secure management console access.</p>
                <a href="{{ url('/staff/login') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-mosh-gold border border-mosh-gold/30 hover:border-mosh-gold bg-mosh-gold/5 hover:bg-mosh-gold/10 px-4 py-2 rounded transition">
                    <i class="mdi mdi-lock-open-outline"></i>
                    <span>Staff Portal Dashboard</span>
                </a>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-4 mt-8 pt-6 border-t border-gray-500/10 text-center text-xs text-gray-400 flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0">
            <p>&copy; {{ date('Y') }} Mosh Edibles. All rights reserved.</p>
            <div class="flex space-x-6 text-gray-400">
                <span class="text-[10px] font-mono tracking-wider uppercase bg-gray-500/5 px-2 py-0.5 rounded border border-gray-500/10">HQ: Lagos, Nigeria</span>
            </div>
        </div>
    </footer>

    <script>
        function applyThemeMode(mode) {
            const htmlNode = document.documentElement;
            const bodyNode = document.getElementById('app-body');
            const headerNode = document.getElementById('app-header');
            const footerNode = document.getElementById('app-footer');
            const iconNode = document.getElementById('theme-icon');
            
            const logoTexts = document.querySelectorAll('.id-logo-text');
            const navLinks = document.querySelectorAll('.id-nav-link');
            const primaryHeadings = document.querySelectorAll('.id-heading-main');
            const bodyTexts = document.querySelectorAll('.id-text-body');
            const priceTexts = document.querySelectorAll('.id-price-text');

            if (mode === 'light') {
                htmlNode.classList.remove('html-dark-mode');
                htmlNode.classList.add('html-light-mode');
                
                bodyNode.classList.replace('bg-mosh-dark', 'bg-mosh-cream');
                bodyNode.classList.replace('text-gray-300', 'text-mosh-slateText');
                
                headerNode.className = "theme-transition sticky top-0 left-0 right-0 z-50 border-b border-gray-200 glass-card";
                footerNode.className = "theme-transition border-t border-gray-200 bg-white text-gray-500 py-10 text-xs";
                
                iconNode.className = "mdi mdi-weather-night";
                
                logoTexts.forEach(el => { el.classList.remove('text-white'); el.classList.add('text-gray-900'); });
                navLinks.forEach(el => { el.classList.remove('text-gray-400'); el.classList.add('text-gray-700'); });
                primaryHeadings.forEach(el => { el.classList.remove('text-white'); el.classList.add('text-gray-900'); });
                bodyTexts.forEach(el => { el.classList.remove('text-gray-400'); el.classList.add('text-gray-700'); });
                priceTexts.forEach(el => { el.classList.remove('text-white'); el.classList.add('text-gray-900'); });
            } else {
                htmlNode.classList.remove('html-light-mode');
                htmlNode.classList.add('html-dark-mode');
                
                bodyNode.classList.replace('bg-mosh-cream', 'bg-mosh-dark');
                bodyNode.classList.replace('text-mosh-slateText', 'text-gray-300');
                
                headerNode.className = "theme-transition sticky top-0 left-0 right-0 z-50 border-b border-gray-500/10 glass-card";
                footerNode.className = "theme-transition border-t border-gray-100/10 bg-mosh-dark text-gray-500 py-10 text-xs";
                
                iconNode.className = "mdi mdi-weather-sunny";
                
                logoTexts.forEach(el => { el.classList.remove('text-gray-900'); el.classList.add('text-white'); });
                navLinks.forEach(el => { el.classList.remove('text-gray-700'); el.classList.add('text-gray-400'); });
                primaryHeadings.forEach(el => { el.classList.remove('text-gray-900'); el.classList.add('text-white'); });
                bodyTexts.forEach(el => { el.classList.remove('text-gray-700'); el.classList.add('text-gray-400'); });
                priceTexts.forEach(el => { el.classList.remove('text-gray-900'); el.classList.add('text-white'); });
            }
        }

        function toggleGlobalTheme() {
            let activeTheme = localStorage.getItem('mosh-ui-theme') === 'light' ? 'dark' : 'light';
            localStorage.setItem('mosh-ui-theme', activeTheme);
            applyThemeMode(activeTheme);
        }

        (function() {
            const savedTheme = localStorage.getItem('mosh-ui-theme') || 'dark';
            window.addEventListener('DOMContentLoaded', () => applyThemeMode(savedTheme));
        })();
    </script>
</body>
</html>