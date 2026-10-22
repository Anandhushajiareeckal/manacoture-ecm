@extends('layouts.storefront')

@section('title', ($page->meta_title ?: $page->title) . ' — ManaCouture Kerala Atelier')
@section('meta_description', $page->meta_description ?: ($page->excerpt ?: 'ManaCouture handwoven Kerala sarees and atelier policies.'))

@section('content')
<div class="bg-background">
    <!-- Header Hero Banner -->
    <section class="border-b border-border bg-secondary/30 py-16 md:py-24">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">
            <div class="max-w-3xl">
                <nav class="flex items-center gap-2 text-xs uppercase tracking-widest text-muted-foreground mb-4">
                    <a href="{{ route('home') }}" class="hover:text-accent transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-accent">Atelier Information</span>
                </nav>
                <h1 class="font-display text-4xl sm:text-5xl md:text-6xl text-foreground leading-[1.08] mb-6">
                    {{ $page->title }}
                </h1>
                @if($page->excerpt)
                    <p class="text-base md:text-lg text-muted-foreground leading-relaxed">
                        {{ $page->excerpt }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-[1400px] mx-auto px-6 lg:px-10 py-16 md:py-24">
        <div class="grid lg:grid-cols-[1fr_340px] gap-12 lg:gap-20">
            <!-- Article Body -->
            <article class="prose max-w-none text-foreground/90 font-sans leading-relaxed">
                <style>
                    .cms-content h2 {
                        font-family: 'Playfair Display', serif;
                        font-size: 1.85rem;
                        margin-top: 2rem;
                        margin-bottom: 1rem;
                        color: #191919;
                        font-weight: 500;
                    }
                    .cms-content h3 {
                        font-family: 'Playfair Display', serif;
                        font-size: 1.4rem;
                        margin-top: 1.75rem;
                        margin-bottom: 0.75rem;
                        color: #191919;
                        font-weight: 500;
                    }
                    .cms-content p {
                        margin-bottom: 1.25rem;
                        line-height: 1.8;
                        color: #55524d;
                    }
                    .cms-content ul, .cms-content ol {
                        margin-bottom: 1.5rem;
                        padding-left: 1.5rem;
                        color: #55524d;
                    }
                    .cms-content ul {
                        list-style-type: disc;
                    }
                    .cms-content ol {
                        list-style-type: decimal;
                    }
                    .cms-content li {
                        margin-bottom: 0.5rem;
                        line-height: 1.7;
                    }
                    .cms-content strong {
                        color: #191919;
                        font-weight: 600;
                    }
                </style>
                <div class="cms-content">
                    {!! $page->content !!}
                </div>

                <div class="mt-14 pt-8 border-t border-border flex items-center justify-between text-xs text-muted-foreground">
                    <div>Last revised: {{ $page->updated_at->format('F d, Y') }}</div>
                    <div class="flex items-center gap-2">
                        <span class="gold-divider"></span>
                        <span class="text-accent">ManaCouture Atelier Guarantee</span>
                    </div>
                </div>
            </article>

            <!-- Sidebar Navigation -->
            <aside class="space-y-8">
                <!-- Other Pages Navigation -->
                <div class="bg-secondary/40 p-6 md:p-8 border border-border">
                    <div class="eyebrow text-accent mb-4">Atelier Information</div>
                    <ul class="space-y-3 text-sm">
                        @foreach($otherPages as $op)
                            <li>
                                <a href="{{ route('pages.show', $op->slug) }}" class="flex items-center justify-between text-foreground/80 hover:text-accent transition-colors py-1 border-b border-border/40">
                                    <span>{{ $op->title }}</span>
                                    <i class="ti ti-chevron-right text-xs opacity-60"></i>
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('about') }}" class="flex items-center justify-between text-foreground/80 hover:text-accent transition-colors py-1">
                                <span>Our Atelier Story</span>
                                <i class="ti ti-chevron-right text-xs opacity-60"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Client Concierge Card -->
                <div class="bg-primary text-primary-foreground p-6 md:p-8">
                    <div class="eyebrow text-gold-soft mb-2">Private Concierge</div>
                    <div class="font-display text-2xl mb-3">Require Personal Guidance?</div>
                    <p class="text-xs text-primary-foreground/80 leading-relaxed mb-6">
                        Our textile advisors in Kochi are on hand to assist with custom styling, bridal appointments, and measurement advice.
                    </p>
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center gap-2">
                            <i class="ti ti-mail text-gold-soft"></i>
                            <span>{{ \App\Models\Setting::get('cms_contact_email', 'atelier@manacoture.com') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ti ti-phone text-gold-soft"></i>
                            <span>{{ \App\Models\Setting::get('cms_contact_phone', '+91 (484) 234-5678') }}</span>
                        </div>
                    </div>
                    <a href="mailto:{{ \App\Models\Setting::get('cms_contact_email', 'atelier@manacoture.com') }}" class="btn-luxe mt-6 w-full text-center bg-background text-foreground hover:bg-transparent hover:text-primary-foreground">
                        Contact Atelier Concierge
                    </a>
                </div>
            </aside>
        </div>
    </section>
</div>
@endsection
