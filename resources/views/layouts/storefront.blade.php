<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ManaCouture — Handwoven Kerala Sarees & Bridal Couture')</title>
    <meta name="description" content="@yield('meta_description', 'Kasavu sarees, bridal silks, set mundu and heirloom couture handwoven in Kerala.')">

    <!-- App Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Tailwind CSS CDN with ManaCouture Preset -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: '#fbf9f4',
                        foreground: '#282828',
                        primary: {
                            DEFAULT: '#191919',
                            foreground: '#fbf9f4',
                        },
                        secondary: {
                            DEFAULT: '#f1eee6',
                            foreground: '#282828',
                        },
                        muted: {
                            DEFAULT: '#f4f1ea',
                            foreground: '#78756f',
                        },
                        accent: {
                            DEFAULT: '#c89f56',
                            foreground: '#191919',
                        },
                        gold: '#c89f56',
                        'gold-soft': '#e9d4a8',
                        ivory: '#fbf9f4',
                        beige: '#f1eee6',
                        charcoal: '#282828',
                        border: '#e6e2d8',
                    },
                    fontFamily: {
                        sans: ['Inter', 'Manrope', 'sans-serif'],
                        display: ['Playfair Display', 'Cormorant Garamond', 'serif'],
                        serif: ['Cormorant Garamond', 'Playfair Display', 'serif'],
                    },
                    boxShadow: {
                        luxe: '0 20px 60px -30px rgba(17,17,17,0.35)',
                        soft: '0 1px 2px rgba(17,17,17,0.04), 0 8px 24px -12px rgba(17,17,17,0.08)',
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --background: #fbf9f4;
            --foreground: #282828;
            --primary: #191919;
            --primary-foreground: #fbf9f4;
            --secondary: #f1eee6;
            --muted: #f4f1ea;
            --muted-foreground: #78756f;
            --accent: #c89f56;
            --border: #e6e2d8;
        }

        body {
            background-color: var(--background);
            color: var(--foreground);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .font-display {
            font-family: 'Playfair Display', serif;
            letter-spacing: -0.01em;
            font-weight: 500;
        }

        .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }

        .eyebrow {
            font-family: 'Inter', sans-serif;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--muted-foreground);
        }

        .gold-divider {
            display: inline-block;
            width: 2.5rem;
            height: 1px;
            background: var(--accent);
            vertical-align: middle;
        }

        .btn-luxe {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.9rem 1.75rem;
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            background: var(--primary);
            color: var(--primary-foreground);
            border: 1px solid var(--primary);
            transition: all 0.35s ease;
            cursor: pointer;
        }

        .btn-luxe:hover {
            background: transparent;
            color: var(--primary);
        }

        .btn-outline-luxe {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.9rem 1.75rem;
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            background: transparent;
            color: var(--foreground);
            border: 1px solid var(--foreground);
            transition: all 0.35s ease;
            cursor: pointer;
        }

        .btn-outline-luxe:hover {
            background: var(--primary);
            color: var(--primary-foreground);
        }

        .link-underline {
            position: relative;
        }
        .link-underline::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            height: 1px;
            width: 100%;
            background: currentColor;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.4s ease;
        }
        .link-underline:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        @keyframes marquee {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: inline-block;
            animation: marquee 32s linear infinite;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.8s ease-out both;
        }

        ::selection {
            background: #e9d4a8;
            color: #282828;
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-background text-foreground antialiased selection:bg-gold-soft selection:text-charcoal">

    <!-- Announcement Bar -->
    <div class="bg-primary text-primary-foreground text-[11px] tracking-[0.24em] uppercase py-2.5 text-center overflow-hidden z-50">
        <div class="animate-marquee whitespace-nowrap inline-block">
            <span class="mx-8">Complimentary shipping across India</span>
            <span class="mx-8">·</span>
            <span class="mx-8">Handwoven in Kerala</span>
            <span class="mx-8">·</span>
            <span class="mx-8">New Onam Collection 2026</span>
            <span class="mx-8">·</span>
            <span class="mx-8">Complimentary shipping across India</span>
            <span class="mx-8">·</span>
            <span class="mx-8">Handwoven in Kerala</span>
            <span class="mx-8">·</span>
            <span class="mx-8">New Onam Collection 2026</span>
        </div>
    </div>

    <!-- Header -->
    <header id="site-header" class="sticky top-0 z-40 transition-all duration-300 bg-background border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">
            <div class="grid grid-cols-[auto_1fr_auto] items-center h-20">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden -ml-2 p-2 hover:text-accent transition-colors" aria-label="Open navigation menu">
                    <i class="ti ti-menu-2 text-2xl"></i>
                </button>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-9 text-[13px] tracking-wide">
                    <a href="{{ route('shop.index') }}" class="link-underline text-foreground/80 hover:text-foreground">Shop</a>
                    <a href="{{ route('shop.index', ['c' => 'kasavu']) }}" class="link-underline text-foreground/80 hover:text-foreground">Kasavu</a>
                    <a href="{{ route('shop.index', ['c' => 'bridal']) }}" class="link-underline text-foreground/80 hover:text-foreground">Bridal</a>
                    <a href="{{ route('shop.index', ['occasion' => 'Festival']) }}" class="link-underline text-foreground/80 hover:text-foreground">Festival</a>
                    <a href="{{ route('about') }}" class="link-underline text-foreground/80 hover:text-foreground">Journal</a>
                </nav>

                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="justify-self-center text-center group">
                    <div class="font-display text-2xl md:text-3xl leading-none tracking-tight group-hover:text-accent transition-colors">
                        ManaCouture
                    </div>
                    <div class="eyebrow mt-1 text-[9px] text-muted-foreground">Kerala · Est. 2018</div>
                </a>

                <!-- Header Actions -->
                <div class="flex items-center gap-1 md:gap-2 justify-self-end">
                    <!-- Search Trigger -->
                    <button onclick="toggleSearch()" aria-label="Search" class="p-2 hover:text-accent transition-colors">
                        <i class="ti ti-search text-xl"></i>
                    </button>

                    <!-- Account / Admin Link -->
                    @auth
                        <a href="{{ route('admin.dashboard') }}" aria-label="Admin Dashboard" class="p-2 hover:text-accent transition-colors hidden md:inline-flex items-center gap-1.5 text-xs text-muted-foreground" title="Admin Dashboard">
                            <i class="ti ti-settings text-xl"></i>
                            <span class="hidden xl:inline text-[11px] uppercase tracking-wider">Admin</span>
                        </a>
                    @else
                        <a href="{{ route('account') }}" aria-label="Account" class="p-2 hover:text-accent transition-colors hidden md:inline-flex" title="My Atelier Account">
                            <i class="ti ti-user text-xl"></i>
                        </a>
                    @endauth

                    <!-- Wishlist -->
                    <a href="{{ route('wishlist.index') }}" aria-label="Wishlist" class="p-2 hover:text-accent transition-colors relative" title="Saved for later">
                        <i class="ti ti-heart text-xl"></i>
                        @php $wishlistCount = count(session('wishlist', [])); @endphp
                        @if($wishlistCount > 0)
                            <span class="absolute top-1 right-1 bg-accent text-accent-foreground text-[9px] font-semibold rounded-full h-3.5 w-3.5 flex items-center justify-center">
                                {{ $wishlistCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Cart -->
                    @php
                        $cartItems = session('cart', []);
                        $cartCount = array_sum(array_column($cartItems, 'qty'));
                    @endphp
                    <a href="{{ route('cart.index') }}" aria-label="Shopping Bag" class="p-2 hover:text-accent transition-colors relative" title="Shopping Bag">
                        <i class="ti ti-shopping-bag text-xl"></i>
                        <span class="absolute -top-0.5 -right-0.5 bg-accent text-accent-foreground text-[10px] font-semibold rounded-full h-4 w-4 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Drawer -->
    <div id="mobile-drawer" class="fixed inset-0 z-50 lg:hidden hidden">
        <div class="absolute inset-0 bg-primary/40 backdrop-blur-sm" onclick="toggleMobileMenu()"></div>
        <aside class="absolute inset-y-0 left-0 w-[86%] max-w-sm bg-background p-8 flex flex-col z-10 shadow-2xl animate-fade-up">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <span class="font-display text-2xl">ManaCouture</span>
                    <div class="eyebrow text-[9px] mt-0.5">Kerala · Est. 2018</div>
                </div>
                <button aria-label="Close menu" onclick="toggleMobileMenu()" class="p-1 hover:text-accent">
                    <i class="ti ti-x text-2xl"></i>
                </button>
            </div>
            <nav class="flex flex-col gap-5 text-xl font-display">
                <a href="{{ route('shop.index') }}" onclick="toggleMobileMenu()" class="hover:text-accent transition-colors">Shop All</a>
                <a href="{{ route('shop.index', ['c' => 'kerala-sarees']) }}" onclick="toggleMobileMenu()" class="hover:text-accent transition-colors">Kerala Sarees</a>
                <a href="{{ route('shop.index', ['c' => 'kasavu']) }}" onclick="toggleMobileMenu()" class="hover:text-accent transition-colors">Kasavu Collection</a>
                <a href="{{ route('shop.index', ['c' => 'bridal']) }}" onclick="toggleMobileMenu()" class="hover:text-accent transition-colors">Bridal Couture</a>
                <a href="{{ route('shop.index', ['c' => 'set-mundu']) }}" onclick="toggleMobileMenu()" class="hover:text-accent transition-colors">Set Mundu</a>
                <a href="{{ route('shop.index', ['c' => 'kurtis']) }}" onclick="toggleMobileMenu()" class="hover:text-accent transition-colors">Kurtis & Salwar</a>
                <a href="{{ route('about') }}" onclick="toggleMobileMenu()" class="hover:text-accent transition-colors">The Atelier Journal</a>
                <div class="border-t border-border pt-4 mt-2 flex flex-col gap-3 text-base font-sans">
                    <a href="{{ route('account') }}" class="flex items-center gap-2 text-sm text-foreground/80 hover:text-accent">
                        <i class="ti ti-user"></i> My Atelier Account
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2 text-sm text-foreground/80 hover:text-accent">
                        <i class="ti ti-heart"></i> Saved Wishlist
                    </a>
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-2 text-sm text-foreground/80 hover:text-accent">
                        <i class="ti ti-shopping-bag"></i> Shopping Bag ({{ $cartCount }})
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-sm text-accent hover:underline mt-2">
                        <i class="ti ti-settings"></i> Admin Backend
                    </a>
                </div>
            </nav>
            <div class="mt-auto eyebrow pt-6 border-t border-border">Handwoven in Kerala · Loved Worldwide</div>
        </aside>
    </div>

    <!-- Search Modal -->
    <div id="search-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-primary/40 backdrop-blur-sm" onclick="toggleSearch()"></div>
        <div class="relative max-w-2xl mx-auto mt-20 p-6 z-10">
            <div class="bg-background border border-border p-6 shadow-2xl rounded-sm">
                <div class="flex items-center justify-between mb-4">
                    <span class="eyebrow">Search The Atelier</span>
                    <button onclick="toggleSearch()" class="hover:text-accent"><i class="ti ti-x text-xl"></i></button>
                </div>
                <form action="{{ route('shop.index') }}" method="GET">
                    <div class="relative">
                        <input type="search" name="q" placeholder="Search Kasavu, Bridal, Silk, Set Mundu..." class="w-full h-14 pl-4 pr-12 bg-secondary/50 border border-border focus:border-accent outline-none font-display text-lg" autofocus>
                        <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-accent">
                            <i class="ti ti-arrow-right text-2xl"></i>
                        </button>
                    </div>
                </form>
                <div class="mt-4 flex flex-wrap gap-2 text-xs">
                    <span class="text-muted-foreground mr-1">Trending:</span>
                    <a href="{{ route('shop.index', ['q' => 'Kasavu']) }}" class="px-2.5 py-1 bg-secondary hover:bg-accent hover:text-accent-foreground transition-colors">Kasavu</a>
                    <a href="{{ route('shop.index', ['c' => 'bridal']) }}" class="px-2.5 py-1 bg-secondary hover:bg-accent hover:text-accent-foreground transition-colors">Bridal Silks</a>
                    <a href="{{ route('shop.index', ['q' => 'Set Mundu']) }}" class="px-2.5 py-1 bg-secondary hover:bg-accent hover:text-accent-foreground transition-colors">Set Mundu</a>
                    <a href="{{ route('shop.index', ['occasion' => 'Onam']) }}" class="px-2.5 py-1 bg-secondary hover:bg-accent hover:text-accent-foreground transition-colors">Onam Edit</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Flash Messages -->
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 mt-4">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ti ti-circle-check text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800"><i class="ti ti-x"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ti ti-alert-triangle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800"><i class="ti ti-x"></i></button>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm px-4 py-3 rounded-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ti ti-info-circle text-lg"></i>
                    <span>{{ session('info') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-blue-600 hover:text-blue-800"><i class="ti ti-x"></i></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-32 border-t border-border bg-secondary/40">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-20">
            <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-5 mb-16">
                <!-- Brand Bio -->
                <div class="lg:col-span-2 max-w-sm">
                    <div class="font-display text-3xl mb-4">ManaCouture</div>
                    <p class="text-sm text-muted-foreground leading-relaxed">
                        Contemporary heirlooms rooted in the handloom traditions of Kerala.
                        Every weave is crafted by master artisans and delivered with care.
                    </p>
                    <div class="flex gap-3 mt-6">
                        <a href="https://instagram.com" target="_blank" aria-label="Instagram" class="h-10 w-10 border border-border rounded-full grid place-items-center hover:bg-primary hover:text-primary-foreground transition-colors">
                            <i class="ti ti-brand-instagram"></i>
                        </a>
                        <a href="https://facebook.com" target="_blank" aria-label="Facebook" class="h-10 w-10 border border-border rounded-full grid place-items-center hover:bg-primary hover:text-primary-foreground transition-colors">
                            <i class="ti ti-brand-facebook"></i>
                        </a>
                        <a href="https://youtube.com" target="_blank" aria-label="Youtube" class="h-10 w-10 border border-border rounded-full grid place-items-center hover:bg-primary hover:text-primary-foreground transition-colors">
                            <i class="ti ti-brand-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Collections -->
                <div>
                    <div class="eyebrow mb-5">Shop</div>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('shop.index') }}" class="text-foreground/80 hover:text-accent transition-colors">All Collections</a></li>
                        <li><a href="{{ route('shop.index', ['c' => 'kasavu']) }}" class="text-foreground/80 hover:text-accent transition-colors">Kasavu Sarees</a></li>
                        <li><a href="{{ route('shop.index', ['c' => 'bridal']) }}" class="text-foreground/80 hover:text-accent transition-colors">Bridal Couture</a></li>
                        <li><a href="{{ route('shop.index', ['c' => 'set-mundu']) }}" class="text-foreground/80 hover:text-accent transition-colors">Set Mundu</a></li>
                        <li><a href="{{ route('shop.index', ['c' => 'kurtis']) }}" class="text-foreground/80 hover:text-accent transition-colors">Kurtis & Salwar</a></li>
                    </ul>
                </div>

                <!-- Client Care -->
                <div>
                    <div class="eyebrow mb-5">Care</div>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('account') }}" class="text-foreground/80 hover:text-accent transition-colors">My Atelier Account</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-foreground/80 hover:text-accent transition-colors">Shopping Bag</a></li>
                        <li><a href="{{ route('about') }}" class="text-foreground/80 hover:text-accent transition-colors">Shipping & Delivery</a></li>
                        <li><a href="{{ route('about') }}" class="text-foreground/80 hover:text-accent transition-colors">Returns & Exchanges</a></li>
                        <li><a href="{{ route('about') }}" class="text-foreground/80 hover:text-accent transition-colors">Handloom Care Guide</a></li>
                    </ul>
                </div>

                <!-- Atelier -->
                <div>
                    <div class="eyebrow mb-5">Atelier</div>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('about') }}" class="text-foreground/80 hover:text-accent transition-colors">Our Kerala Heritage</a></li>
                        <li><a href="{{ route('about') }}" class="text-foreground/80 hover:text-accent transition-colors">Artisan Families</a></li>
                        <li><a href="{{ route('about') }}" class="text-foreground/80 hover:text-accent transition-colors">Sustainability & Ethics</a></li>
                        <li><a href="{{ route('about') }}" class="text-foreground/80 hover:text-accent transition-colors">Private Appointments</a></li>
                        <li><a href="{{ route('admin.dashboard') }}" class="text-accent hover:underline font-medium">Atelier Admin Backend</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-border pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-muted-foreground">
                <div>© {{ date('Y') }} ManaCouture · Handwoven in Balaramapuram & Kochi, Kerala, India</div>
                <div class="flex gap-6">
                    <a href="{{ route('about') }}" class="link-underline">Privacy Policy</a>
                    <a href="{{ route('about') }}" class="link-underline">Terms of Service</a>
                    <a href="{{ route('about') }}" class="link-underline">Authenticity Certificate</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts -->
    <script>
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobile-drawer');
            drawer.classList.toggle('hidden');
        }

        function toggleSearch() {
            const modal = document.getElementById('search-modal');
            modal.classList.toggle('hidden');
            if (!modal.classList.contains('hidden')) {
                const input = modal.querySelector('input[type="search"]');
                if (input) setTimeout(() => input.focus(), 50);
            }
        }

        // Header shadow on scroll
        window.addEventListener('scroll', () => {
            const header = document.getElementById('site-header');
            if (window.scrollY > 20) {
                header.classList.add('shadow-soft', 'backdrop-blur-xl', 'bg-background/90');
            } else {
                header.classList.remove('shadow-soft', 'backdrop-blur-xl', 'bg-background/90');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
