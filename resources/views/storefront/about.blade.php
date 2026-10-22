@extends('layouts.storefront')

@section('title', 'Our Story — ' . \App\Models\Setting::get('cms_site_title', 'ManaCouture') . ' Kerala Atelier')
@section('meta_description', \App\Models\Setting::get('cms_about_hero_heading', 'The atelier, the master weavers in Balaramapuram and the philosophy behind ManaCouture Kerala couture.'))

@section('content')
<div>
    <!-- Hero Banner -->
    <section class="relative h-[65vh] overflow-hidden">
        @php
            $aboutHeroImg = \App\Models\Setting::get('cms_about_hero_image', 'images/manacoture/festival.jpg');
            $aboutHeroSrc = str_starts_with($aboutHeroImg, 'http') ? $aboutHeroImg : asset($aboutHeroImg);
        @endphp
        <img
            src="{{ $aboutHeroSrc }}"
            alt="{{ \App\Models\Setting::get('cms_site_title', 'ManaCouture') }} Atelier Story"
            class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-primary/45"></div>
        <div class="relative h-full flex flex-col items-center justify-center text-center text-primary-foreground px-6">
            <div class="eyebrow text-gold-soft mb-4">{{ \App\Models\Setting::get('cms_about_hero_eyebrow', 'Since 2018') }}</div>
            <h1 class="font-display text-4xl sm:text-6xl md:text-7xl max-w-4xl leading-[1.05]">
                {{ \App\Models\Setting::get('cms_about_hero_heading', 'A Kerala atelier for the modern woman.') }}
            </h1>
        </div>
    </section>

    <!-- Founder Quote -->
    <section class="max-w-4xl mx-auto px-6 py-24 md:py-32 text-center">
        <p class="font-serif text-2xl sm:text-3xl md:text-4xl italic leading-relaxed text-foreground/90">
            "{{ \App\Models\Setting::get('cms_about_quote', 'We weave for the women who wear their culture with quiet pride — brides, mothers, daughters, and the friends who dance with them under Onam lamps.') }}"
        </p>
        <div class="eyebrow mt-8 text-accent font-semibold">
            {{ \App\Models\Setting::get('cms_about_quote_author', '— Anjali Menon, Founder & Creative Director') }}
        </div>
    </section>

    <!-- 3 Pillars of Craft -->
    <section class="max-w-[1400px] mx-auto px-6 lg:px-10 pb-28 grid md:grid-cols-3 gap-10 lg:gap-14">
        <div class="bg-secondary/40 p-8 border border-border shadow-soft">
            <div class="eyebrow text-accent mb-3">{{ \App\Models\Setting::get('cms_about_pillar1_eyebrow', '01 · Origin') }}</div>
            <h3 class="font-display text-2xl mb-3 text-foreground">{{ \App\Models\Setting::get('cms_about_pillar1_title', 'The Balaramapuram Loom') }}</h3>
            <p class="text-sm text-muted-foreground leading-relaxed">
                {{ \App\Models\Setting::get('cms_about_pillar1_desc', 'Every ManaCouture Kasavu begins on a hand-pit loom in Balaramapuram, where third-generation master weavers work exclusively with pure zari and combed high-count cotton.') }}
            </p>
        </div>

        <div class="bg-secondary/40 p-8 border border-border shadow-soft">
            <div class="eyebrow text-accent mb-3">{{ \App\Models\Setting::get('cms_about_pillar2_eyebrow', '02 · Craftsmanship') }}</div>
            <h3 class="font-display text-2xl mb-3 text-foreground">{{ \App\Models\Setting::get('cms_about_pillar2_title', 'The Kochi Atelier') }}</h3>
            <p class="text-sm text-muted-foreground leading-relaxed">
                {{ \App\Models\Setting::get('cms_about_pillar2_desc', 'Our Kochi atelier meticulously finishes each creation — hand-hemmed borders, gentle cold pressing, wrapped in breathable natural muslin, and presented in signature reusable keepsake trunks.') }}
            </p>
        </div>

        <div class="bg-secondary/40 p-8 border border-border shadow-soft">
            <div class="eyebrow text-accent mb-3">{{ \App\Models\Setting::get('cms_about_pillar3_eyebrow', '03 · Patronage') }}</div>
            <h3 class="font-display text-2xl mb-3 text-foreground">{{ \App\Models\Setting::get('cms_about_pillar3_title', 'The Client Care') }}</h3>
            <p class="text-sm text-muted-foreground leading-relaxed">
                {{ \App\Models\Setting::get('cms_about_pillar3_desc', 'From bespoke bridal appointments to festive wardrobe curations, our client atelier offers one-on-one virtual and in-person consultations with insured worldwide delivery.') }}
            </p>
        </div>
    </section>

    <!-- Visual Heritage Feature -->
    <section class="bg-secondary/30 border-y border-border py-24">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div>
                    <div class="eyebrow mb-3">{{ \App\Models\Setting::get('cms_about_ethics_eyebrow', 'Ethical Atelier') }}</div>
                    <h2 class="font-display text-4xl md:text-5xl mb-6">{{ \App\Models\Setting::get('cms_about_ethics_heading', 'Honoring The Hands That Weave') }}</h2>
                    <p class="text-muted-foreground leading-relaxed mb-6">
                        {{ \App\Models\Setting::get('cms_about_ethics_desc', 'We believe authentic luxury begins with fair dignity. ManaCouture pays our master weavers more than double the regional standard wage, sponsors healthcare for weaver families, and reinvests 5% of all bridal proceeds into handloom preservation trusts across Kerala.') }}
                    </p>
                    <div class="flex gap-4">
                        <a href="{{ route('shop.index') }}" class="btn-luxe">
                            Explore Heirlooms <i class="ti ti-arrow-right"></i>
                        </a>
                        <a href="{{ route('home') }}" class="btn-outline-luxe">
                            Back To Home
                        </a>
                    </div>
                </div>
                <div class="aspect-[4/3] bg-secondary overflow-hidden shadow-luxe">
                    @php
                        $ethicsImg = \App\Models\Setting::get('cms_about_ethics_image', 'images/manacoture/hero-saree.jpg');
                        $ethicsSrc = str_starts_with($ethicsImg, 'http') ? $ethicsImg : asset($ethicsImg);
                    @endphp
                    <img src="{{ $ethicsSrc }}" alt="Weaving Kasavu" class="h-full w-full object-cover">
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
