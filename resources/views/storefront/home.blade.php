@extends('layouts.storefront')

@section('title', \App\Models\Setting::get('cms_site_title', 'ManaCouture') . ' — ' . \App\Models\Setting::get('cms_site_tagline', 'Handwoven Kerala Sarees & Bridal Couture'))
@section('meta_description', \App\Models\Setting::get('cms_hero_description', 'Kasavu sarees, bridal silks, set mundu and heirloom couture handwoven in Kerala by master artisans.'))

@section('content')
<div>
    <!-- HERO SECTION -->
    <section class="relative">
        <div class="grid lg:grid-cols-[1.1fr_1fr] min-h-[86vh]">
            <div class="relative flex items-center bg-secondary/40 px-6 sm:px-12 lg:px-20 py-20">
                <div class="absolute top-8 left-6 lg:left-20 eyebrow">
                    {{ \App\Models\Setting::get('cms_hero_eyebrow', 'Onam Collection · 2026') }}
                </div>
                <div class="max-w-xl animate-fade-up">
                    <h1 class="font-display text-[clamp(2.75rem,6vw,5.25rem)] leading-[1.02] tracking-tight text-foreground">
                        {{ \App\Models\Setting::get('cms_hero_title_1', 'Woven in gold.') }}
                        <span class="block italic font-serif text-accent">{{ \App\Models\Setting::get('cms_hero_title_2', 'Worn like poetry.') }}</span>
                    </h1>
                    <p class="mt-8 text-base text-muted-foreground max-w-md leading-relaxed">
                        {{ \App\Models\Setting::get('cms_hero_description', 'A limited edition of hand-loomed Kasavu sarees, dyed in ivory and edged with antique gold — crafted by third-generation weavers in Balaramapuram.') }}
                    </p>
                    <div class="mt-10 flex flex-wrap gap-4">
                        <a href="{{ \App\Models\Setting::get('cms_hero_btn1_url', route('shop.index')) }}" class="btn-luxe">
                            {{ \App\Models\Setting::get('cms_hero_btn1_text', 'Shop New Collection') }} <i class="ti ti-arrow-right"></i>
                        </a>
                        <a href="{{ \App\Models\Setting::get('cms_hero_btn2_url', route('shop.index', ['c' => 'kerala-sarees'])) }}" class="btn-outline-luxe">
                            {{ \App\Models\Setting::get('cms_hero_btn2_text', 'Explore Sarees') }}
                        </a>
                    </div>
                    <div class="mt-14 flex items-center gap-6 text-xs text-muted-foreground">
                        <div class="flex items-center gap-2">
                            <span class="gold-divider"></span>
                            <span>{{ \App\Models\Setting::get('cms_hero_badge_1', 'Handloom certified') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-accent flex items-center"><i class="ti ti-star-filled"></i></span>
                            <span>{{ \App\Models\Setting::get('cms_hero_badge_2', '4.9 · 12,400 reviews') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative overflow-hidden bg-secondary min-h-[60vh] lg:min-h-full">
                @php
                    $heroPath = \App\Models\Setting::get('cms_hero_image', 'images/manacoture/hero-saree.jpg');
                    $heroSrc = str_starts_with($heroPath, 'http') ? $heroPath : asset($heroPath);
                @endphp
                <img
                    src="{{ $heroSrc }}"
                    alt="{{ \App\Models\Setting::get('cms_site_title', 'ManaCouture') }} Editorial"
                    class="absolute inset-0 h-full w-full object-cover"
                />
                <div class="absolute bottom-6 right-6 bg-background/90 backdrop-blur-md px-5 py-4 max-w-[220px] shadow-lg border border-border/50">
                    <div class="eyebrow text-[9px] mb-1">Look 01</div>
                    <div class="font-display text-lg leading-tight">{{ $heroProduct?->name ?? 'Aiswarya Kasavu' }}</div>
                    <div class="text-xs text-muted-foreground mt-1">{{ $heroProduct?->fabric ?? 'Pure Kanchi cotton' }} · {{ $heroProduct?->color ?? 'Ivory & gold' }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- MARQUEE STRIP -->
    @php
        $marqueeRaw = \App\Models\Setting::get('cms_marquee_words', 'Kasavu, Bridal Silks, Set Mundu, Handloom, Kanchipuram, Onam, Vishu, Temple Wear');
        $marqueeWords = array_filter(array_map('trim', explode(',', $marqueeRaw)));
    @endphp
    @if(count($marqueeWords))
        <section class="border-y border-border overflow-hidden py-6 bg-background">
            <div class="animate-marquee whitespace-nowrap font-display text-2xl md:text-4xl italic text-foreground/60">
                @for($i = 0; $i < 2; $i++)
                    @foreach($marqueeWords as $word)
                        <span class="mx-8">
                            {{ $word }} <span class="text-accent">✦</span>
                        </span>
                    @endforeach
                @endfor
            </div>
        </section>
    @endif

    <!-- CURATED CATEGORIES -->
    <section class="max-w-[1400px] mx-auto px-6 lg:px-10 py-24 md:py-32">
        <div class="max-w-2xl mb-14">
            <div class="eyebrow mb-4">{{ \App\Models\Setting::get('cms_categories_eyebrow', 'Curated Categories') }}</div>
            <h2 class="font-display text-4xl md:text-5xl leading-[1.05]">{{ \App\Models\Setting::get('cms_categories_heading', 'The Kerala Weaves') }}</h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($categories as $category)
                <a href="{{ route('shop.index', ['c' => $category->slug]) }}" class="group relative aspect-[3/4] overflow-hidden bg-secondary block">
                    <img
                        src="{{ $category->image_url }}"
                        alt="{{ $category->name }}"
                        loading="lazy"
                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/20 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-6 text-primary-foreground">
                        <div class="eyebrow text-primary-foreground/80 text-[10px] mb-1">
                            {{ $category->products_count }} {{ Str::plural('piece', $category->products_count) }}
                        </div>
                        <h3 class="font-display text-2xl group-hover:text-gold-soft transition-colors">{{ $category->name }}</h3>
                        <span class="inline-flex items-center gap-1.5 text-xs text-primary-foreground/90 mt-2 font-medium tracking-wider uppercase">
                            Explore <i class="ti ti-arrow-right text-xs"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- FEATURED HEIRLOOMS -->
    <section class="bg-secondary/30 py-24 md:py-32 border-y border-border">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-14">
                <div>
                    <div class="eyebrow mb-4">{{ \App\Models\Setting::get('cms_featured_eyebrow', 'Featured Heirlooms') }}</div>
                    <h2 class="font-display text-4xl md:text-5xl">{{ \App\Models\Setting::get('cms_featured_heading', 'Handloom Masterpieces') }}</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="btn-outline-luxe">
                    {{ \App\Models\Setting::get('cms_featured_btn_text', 'View Entire Edit') }} <i class="ti ti-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                @foreach($featuredProducts as $product)
                    @include('storefront.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>

    <!-- BRIDAL SPOTLIGHT EDITORIAL -->
    <section class="max-w-[1400px] mx-auto px-6 lg:px-10 py-24 md:py-32">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div class="relative overflow-hidden aspect-[4/5] bg-secondary shadow-luxe">
                @php
                    $bridalPath = \App\Models\Setting::get('cms_bridal_image', 'images/manacoture/bridal.jpg');
                    $bridalSrc = str_starts_with($bridalPath, 'http') ? $bridalPath : asset($bridalPath);
                @endphp
                <img
                    src="{{ $bridalSrc }}"
                    alt="Bridal Couture Editorial"
                    loading="lazy"
                    class="absolute inset-0 h-full w-full object-cover"
                />
            </div>
            <div class="max-w-xl">
                <div class="eyebrow mb-4">{{ \App\Models\Setting::get('cms_bridal_eyebrow', 'The Bridal Atelier') }}</div>
                <h2 class="font-display text-4xl md:text-5xl leading-tight">
                    {{ \App\Models\Setting::get('cms_bridal_heading', 'Gold woven for a lifetime.') }}
                </h2>
                <p class="mt-6 text-muted-foreground leading-relaxed">
                    {{ \App\Models\Setting::get('cms_bridal_description', 'Every Kerala bride deserves a weave that honors tradition while commanding the room. Our bridal collection unites heirloom Kanchipuram silk with hand-beaten certified zari, finished by master artisans in our Kochi studio.') }}
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ \App\Models\Setting::get('cms_bridal_btn1_url', route('shop.index', ['c' => 'bridal'])) }}" class="btn-luxe">
                        {{ \App\Models\Setting::get('cms_bridal_btn1_text', 'Discover Bridal Silks') }} <i class="ti ti-arrow-right"></i>
                    </a>
                    <a href="{{ \App\Models\Setting::get('cms_bridal_btn2_url', route('about')) }}" class="btn-outline-luxe">
                        {{ \App\Models\Setting::get('cms_bridal_btn2_text', 'Book Atelier Appointment') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FESTIVAL FULL BLEED BANNER -->
    <section class="relative h-[70vh] overflow-hidden">
        @php
            $festPath = \App\Models\Setting::get('cms_festival_image', 'images/manacoture/festival.jpg');
            $festSrc = str_starts_with($festPath, 'http') ? $festPath : asset($festPath);
        @endphp
        <img
            src="{{ $festSrc }}"
            alt="Festival collection banner"
            loading="lazy"
            class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-primary/45"></div>
        <div class="relative h-full flex items-center max-w-[1400px] mx-auto px-6 lg:px-20">
            <div class="max-w-xl text-primary-foreground">
                <div class="eyebrow text-gold-soft mb-6">{{ \App\Models\Setting::get('cms_festival_eyebrow', 'Festival · Onam · Vishu') }}</div>
                <h2 class="font-display text-5xl md:text-6xl leading-[1.05]">
                    {{ \App\Models\Setting::get('cms_festival_heading', 'A courtyard lit for celebration.') }}
                </h2>
                <p class="mt-6 text-primary-foreground/85 max-w-md leading-relaxed text-sm md:text-base">
                    {{ \App\Models\Setting::get('cms_festival_description', 'Discover our festival edit — Kasavu for Onam, silks for Vishu, and temple couture for every sacred occasion.') }}
                </p>
                <a href="{{ \App\Models\Setting::get('cms_festival_btn_url', route('shop.index', ['occasion' => 'Festival'])) }}" class="btn-luxe mt-10 bg-background text-foreground border-background hover:bg-transparent hover:text-primary-foreground">
                    {{ \App\Models\Setting::get('cms_festival_btn_text', 'Shop The Edit') }} <i class="ti ti-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- BEST SELLERS -->
    <section class="max-w-[1400px] mx-auto px-6 lg:px-10 py-24 md:py-32">
        <div class="max-w-2xl mb-14">
            <div class="eyebrow mb-4">{{ \App\Models\Setting::get('cms_bestsellers_eyebrow', 'Best Sellers') }}</div>
            <h2 class="font-display text-4xl md:text-5xl leading-[1.05]">{{ \App\Models\Setting::get('cms_bestsellers_heading', 'Loved By Many') }}</h2>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($bestSellers as $product)
                @include('storefront.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>

    <!-- PROMISE & VALUES -->
    <section class="bg-secondary/50 py-24 md:py-32 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">
            <div class="max-w-2xl mb-14">
                <div class="eyebrow mb-4">{{ \App\Models\Setting::get('cms_promise_eyebrow', 'The ManaCouture Promise') }}</div>
                <h2 class="font-display text-4xl md:text-5xl leading-[1.05]">{{ \App\Models\Setting::get('cms_promise_heading', 'Craft That Lasts A Lifetime') }}</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $values = [
                        ['icon' => 'ti-sparkles', 'title' => 'Premium Fabrics', 'desc' => 'Only pure silks, handloom cottons and certified zari.'],
                        ['icon' => 'ti-scissors', 'title' => 'Authentic Kerala Weaves', 'desc' => 'Woven in Balaramapuram by third-generation families.'],
                        ['icon' => 'ti-award', 'title' => 'Handpicked Collections', 'desc' => 'Every single piece curated by our in-house atelier.'],
                        ['icon' => 'ti-shield-lock', 'title' => 'Secure Payments', 'desc' => 'PCI compliant checkout with 3D Secure & UPI.'],
                        ['icon' => 'ti-truck', 'title' => 'Global Shipping', 'desc' => 'Insured worldwide delivery, complimentary across India.'],
                        ['icon' => 'ti-refresh', 'title' => 'Easy 14-Day Returns', 'desc' => '14-day effortless returns on all unworn pieces.'],
                    ];
                @endphp
                @foreach($values as $v)
                    <div class="bg-background p-8 rounded-sm border border-border hover:shadow-luxe transition-shadow duration-300">
                        <div class="h-11 w-11 grid place-items-center border border-accent/60 rounded-full mb-6 text-accent">
                            <i class="ti {{ $v['icon'] }} text-xl"></i>
                        </div>
                        <div class="font-display text-xl mb-2">{{ $v['title'] }}</div>
                        <p class="text-sm text-muted-foreground leading-relaxed">{{ $v['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- PATRON TESTIMONIALS -->
    <section class="max-w-[1200px] mx-auto px-6 lg:px-10 py-24 md:py-32 text-center">
        <div class="eyebrow mb-4">Client Diaries</div>
        <h2 class="font-display text-4xl md:text-5xl mb-14">Words From Our Patrons</h2>
        <div class="grid md:grid-cols-3 gap-8 text-left">
            @php
                $testimonials = [
                    ['name' => 'Aparna Menon', 'role' => 'Kochi', 'quote' => 'My wedding Kasavu felt like an heirloom the moment I unfolded it. The gold border catches light in the most beautiful way.'],
                    ['name' => 'Divya Nair', 'role' => 'London', 'quote' => 'Shipped from Kerala to London in five days, packaged like a couture gown. The Set Mundu is now my Onam signature.'],
                    ['name' => 'Reshma Pillai', 'role' => 'Bengaluru', 'quote' => 'Every year I return for a new saree. The craftsmanship is unmatched, and their client atelier is genuinely personal.'],
                ];
            @endphp
            @foreach($testimonials as $t)
                <figure class="bg-secondary/40 p-8 border border-border flex flex-col justify-between">
                    <div>
                        <div class="text-accent flex gap-1 mb-4">
                            @for($s = 0; $s < 5; $s++)
                                <i class="ti ti-star-filled text-xs"></i>
                            @endfor
                        </div>
                        <blockquote class="font-serif text-lg italic leading-relaxed text-foreground/90">
                            "{{ $t['quote'] }}"
                        </blockquote>
                    </div>
                    <figcaption class="mt-6 pt-4 border-t border-border/60">
                        <div class="font-medium text-foreground">{{ $t['name'] }}</div>
                        <div class="text-xs text-muted-foreground">{{ $t['role'] }}</div>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </section>

    <!-- INSTAGRAM ATELIER GALLERY -->
    <section class="max-w-[1400px] mx-auto px-6 lg:px-10 pb-24 text-center">
        <div class="eyebrow mb-3">@manacouture</div>
        <h2 class="font-display text-3xl md:text-4xl mb-10">Follow The Atelier</h2>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-2">
            @php
                $instaImages = [
                    'images/manacoture/hero-saree.jpg',
                    'images/manacoture/bridal.jpg',
                    'images/manacoture/kasavu.jpg',
                    'images/manacoture/setmundu.jpg',
                    'images/manacoture/festival.jpg',
                    'images/manacoture/accessories.jpg',
                ];
            @endphp
            @foreach($instaImages as $img)
                <a href="{{ \App\Models\Setting::get('cms_social_instagram', 'https://instagram.com/manacouture') }}" target="_blank" class="relative aspect-square group overflow-hidden bg-secondary block">
                    <img src="{{ asset($img) }}" alt="" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/40 transition-colors grid place-items-center">
                        <i class="ti ti-brand-instagram text-2xl text-primary-foreground opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- NEWSLETTER -->
    <section class="border-t border-border bg-secondary/20">
        <div class="max-w-3xl mx-auto px-6 py-24 md:py-32 text-center">
            <div class="eyebrow mb-4">The Letter</div>
            <h2 class="font-display text-4xl md:text-5xl">Private Previews, In Your Inbox.</h2>
            <p class="mt-4 text-muted-foreground text-sm md:text-base">
                Be the first to see our limited edition weaves, artisan stories and private atelier events.
            </p>
            <form action="{{ route('home') }}" method="GET" class="mt-10 flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                <input
                    type="email"
                    required
                    placeholder="Your email address"
                    class="flex-1 h-12 px-4 bg-background border border-border focus:border-accent outline-none text-sm"
                />
                <button type="submit" class="btn-luxe">Subscribe</button>
            </form>
            <p class="mt-4 text-[11px] text-muted-foreground tracking-wide">
                By subscribing you agree to our privacy policy. Unsubscribe anytime.
            </p>
        </div>
    </section>
</div>
@endsection
