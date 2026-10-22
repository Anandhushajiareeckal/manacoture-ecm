@extends('layouts.storefront')

@section('title', 'Our Story — ManaCouture Kerala Atelier')
@section('meta_description', 'The atelier, the master weavers in Balaramapuram and the philosophy behind ManaCouture Kerala couture.')

@section('content')
<div>
    <!-- Hero Banner -->
    <section class="relative h-[65vh] overflow-hidden">
        <img
            src="{{ asset('images/manacoture/festival.jpg') }}"
            alt="ManaCouture Atelier Story"
            class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-primary/45"></div>
        <div class="relative h-full flex flex-col items-center justify-center text-center text-primary-foreground px-6">
            <div class="eyebrow text-gold-soft mb-4">Since 2018</div>
            <h1 class="font-display text-4xl sm:text-6xl md:text-7xl max-w-4xl leading-[1.05]">
                A Kerala atelier for the modern woman.
            </h1>
        </div>
    </section>

    <!-- Founder Quote -->
    <section class="max-w-4xl mx-auto px-6 py-24 md:py-32 text-center">
        <p class="font-serif text-2xl sm:text-3xl md:text-4xl italic leading-relaxed text-foreground/90">
            "We weave for the women who wear their culture with quiet pride —
            brides, mothers, daughters, and the friends who dance with them
            under Onam lamps."
        </p>
        <div class="eyebrow mt-8 text-accent font-semibold">— Anjali Menon, Founder & Creative Director</div>
    </section>

    <!-- 3 Pillars of Craft -->
    <section class="max-w-[1400px] mx-auto px-6 lg:px-10 pb-28 grid md:grid-cols-3 gap-10 lg:gap-14">
        <div class="bg-secondary/40 p-8 border border-border shadow-soft">
            <div class="eyebrow text-accent mb-3">01 · Origin</div>
            <h3 class="font-display text-2xl mb-3 text-foreground">The Balaramapuram Loom</h3>
            <p class="text-sm text-muted-foreground leading-relaxed">
                Every ManaCouture Kasavu begins on a hand-pit loom in Balaramapuram, where third-generation master weavers work exclusively with pure zari and combed high-count cotton.
            </p>
        </div>

        <div class="bg-secondary/40 p-8 border border-border shadow-soft">
            <div class="eyebrow text-accent mb-3">02 · Craftsmanship</div>
            <h3 class="font-display text-2xl mb-3 text-foreground">The Kochi Atelier</h3>
            <p class="text-sm text-muted-foreground leading-relaxed">
                Our Kochi atelier meticulously finishes each creation — hand-hemmed borders, gentle cold pressing, wrapped in breathable natural muslin, and presented in signature reusable keepsake trunks.
            </p>
        </div>

        <div class="bg-secondary/40 p-8 border border-border shadow-soft">
            <div class="eyebrow text-accent mb-3">03 · Patronage</div>
            <h3 class="font-display text-2xl mb-3 text-foreground">The Client Care</h3>
            <p class="text-sm text-muted-foreground leading-relaxed">
                From bespoke bridal appointments to festive wardrobe curations, our client atelier offers one-on-one virtual and in-person consultations with insured worldwide delivery.
            </p>
        </div>
    </section>

    <!-- Visual Heritage Feature -->
    <section class="bg-secondary/30 border-y border-border py-24">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div>
                    <div class="eyebrow mb-3">Ethical Atelier</div>
                    <h2 class="font-display text-4xl md:text-5xl mb-6">Honoring The Hands That Weave</h2>
                    <p class="text-muted-foreground leading-relaxed mb-6">
                        We believe authentic luxury begins with fair dignity. ManaCouture pays our master weavers more than double the regional standard wage, sponsors healthcare for weaver families, and reinvests 5% of all bridal proceeds into handloom preservation trusts across Kerala.
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
                    <img src="{{ asset('images/manacoture/hero-saree.jpg') }}" alt="Weaving Kasavu" class="h-full w-full object-cover">
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
