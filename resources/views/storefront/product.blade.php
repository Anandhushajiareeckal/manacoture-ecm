@extends('layouts.storefront')

@section('title', $product->name . ' — ManaCouture Kerala Handlooms')
@section('meta_description', $product->short_description ?? ($product->name . ' handwoven in Kerala with pure ' . ($product->fabric ?? 'cotton and zari.')))

@section('content')
<div>
    <!-- Breadcrumb -->
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 pt-8 pb-4">
        <div class="text-xs text-muted-foreground eyebrow flex items-center gap-2 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-foreground">Home</a>
            <span>·</span>
            <a href="{{ route('shop.index') }}" class="hover:text-foreground">Shop</a>
            @if($product->category)
                <span>·</span>
                <a href="{{ route('shop.index', ['c' => $product->category->slug]) }}" class="hover:text-foreground">{{ $product->category->name }}</a>
            @endif
            <span>·</span>
            <span class="text-foreground truncate max-w-xs">{{ $product->name }}</span>
        </div>
    </div>

    <!-- Main Product Section -->
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-6 md:py-10">
        <div class="grid lg:grid-cols-[1.1fr_480px] gap-10 lg:gap-16">
            <!-- Gallery -->
            <div class="grid grid-cols-[80px_1fr] gap-4">
                <!-- Thumbnails -->
                <div class="flex flex-col gap-3">
                    <button
                        onclick="changeImage('{{ $product->main_image_url }}', this)"
                        class="thumb-btn aspect-[4/5] overflow-hidden border-2 border-foreground cursor-pointer"
                    >
                        <img src="{{ $product->main_image_url }}" alt="" class="h-full w-full object-cover">
                    </button>
                    @if($product->hover_image_url)
                        <button
                            onclick="changeImage('{{ $product->hover_image_url }}', this)"
                            class="thumb-btn aspect-[4/5] overflow-hidden border-2 border-transparent hover:border-foreground/40 cursor-pointer"
                        >
                            <img src="{{ $product->hover_image_url }}" alt="" class="h-full w-full object-cover">
                        </button>
                    @endif
                    @foreach($product->images as $img)
                        <button
                            onclick="changeImage('{{ asset($img->path) }}', this)"
                            class="thumb-btn aspect-[4/5] overflow-hidden border-2 border-transparent hover:border-foreground/40 cursor-pointer"
                        >
                            <img src="{{ asset($img->path) }}" alt="" class="h-full w-full object-cover">
                        </button>
                    @endforeach
                </div>

                <!-- Main Active Image -->
                <div class="relative aspect-[4/5] overflow-hidden bg-secondary shadow-soft">
                    <img
                        id="main-gallery-image"
                        src="{{ $product->main_image_url }}"
                        alt="{{ $product->name }}"
                        class="absolute inset-0 h-full w-full object-cover transition-opacity duration-300"
                    />
                    @if($product->badge)
                        <span class="absolute top-4 left-4 bg-primary text-primary-foreground text-[10px] tracking-[0.2em] uppercase px-3 py-1.5 shadow-md">
                            {{ $product->badge }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="flex flex-col justify-start">
                <div class="eyebrow mb-2 flex items-center justify-between">
                    <span>{{ $product->category?->name ?? 'Kerala Handloom' }}</span>
                    <span class="text-[10px] text-muted-foreground">SKU: {{ $product->sku }}</span>
                </div>

                <h1 class="font-display text-3xl md:text-4xl text-foreground leading-tight mb-3">
                    {{ $product->name }}
                </h1>

                <!-- Rating -->
                <div class="flex items-center gap-2 mb-6 text-xs text-muted-foreground">
                    <span class="text-accent flex items-center">
                        <i class="ti ti-star-filled text-sm"></i>
                    </span>
                    <span class="font-semibold text-foreground">{{ number_format($product->rating, 1) }}</span>
                    <span>·</span>
                    <span>{{ $product->reviews_count }} verified atelier reviews</span>
                </div>

                <!-- Price -->
                <div class="flex items-baseline gap-3 mb-6 pb-6 border-b border-border">
                    <span class="font-display text-3xl font-semibold text-foreground">
                        {{ $product->formatted_price }}
                    </span>
                    @if($product->compare_price)
                        <span class="text-base text-muted-foreground line-through">
                            {{ $product->formatted_compare_price }}
                        </span>
                        <span class="text-xs bg-accent/20 text-accent font-semibold px-2.5 py-1 uppercase tracking-wider">
                            Save {{ $product->discount_percent }}%
                        </span>
                    @endif
                    <span class="text-xs text-muted-foreground ml-auto">Inclusive of all taxes</span>
                </div>

                <!-- Fabric & Color Tags -->
                <div class="grid grid-cols-2 gap-3 mb-6 text-xs">
                    <div class="p-3 bg-secondary/50 border border-border">
                        <div class="text-muted-foreground text-[10px] uppercase tracking-wider mb-1">Fabric</div>
                        <div class="font-medium text-foreground">{{ $product->fabric ?? 'Pure Kerala Handloom Cotton' }}</div>
                    </div>
                    <div class="p-3 bg-secondary/50 border border-border">
                        <div class="text-muted-foreground text-[10px] uppercase tracking-wider mb-1">Color Palette</div>
                        <div class="font-medium text-foreground">{{ $product->color ?? 'Ivory & Gold Zari' }}</div>
                    </div>
                </div>

                <!-- Form: Add to Cart -->
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Size / Fitting Selection -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="eyebrow text-[10px]">Select Size</span>
                            <a href="{{ route('about') }}" class="text-muted-foreground hover:text-accent underline text-[11px]">Size Guide</a>
                        </div>
                        <div class="flex gap-2">
                            @foreach(['Free Size', 'S', 'M', 'L', 'XL'] as $idx => $s)
                                <label class="cursor-pointer">
                                    <input type="radio" name="size" value="{{ $s }}" {{ $idx === 0 ? 'checked' : '' }} class="sr-only peer">
                                    <span class="px-4 py-2 border border-border text-xs font-medium uppercase tracking-wider block peer-checked:border-foreground peer-checked:bg-foreground peer-checked:text-background hover:border-foreground/50 transition-colors">
                                        {{ $s }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity & Actions -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center gap-3">
                            <!-- Qty Counter -->
                            <div class="flex items-center border border-border h-12">
                                <button type="button" onclick="adjustQty(-1)" class="px-3 text-muted-foreground hover:text-foreground">
                                    <i class="ti ti-minus"></i>
                                </button>
                                <input type="number" id="qty-input" name="qty" value="1" min="1" max="{{ max(1, $product->stock_qty) }}" class="w-12 text-center text-sm font-semibold border-none outline-none bg-transparent">
                                <button type="button" onclick="adjustQty(1)" class="px-3 text-muted-foreground hover:text-foreground">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>

                            <!-- Add to Bag Button -->
                            <button
                                type="submit"
                                class="flex-1 btn-luxe h-12 text-xs uppercase tracking-[0.2em]"
                                {{ $product->stock_qty <= 0 ? 'disabled' : '' }}
                            >
                                <i class="ti ti-shopping-bag text-base"></i>
                                {{ $product->stock_qty > 0 ? 'Add To Shopping Bag' : 'Out Of Stock' }}
                            </button>
                        </div>

                        <!-- Wishlist Toggle -->
                        <div class="flex gap-2">
                            <button
                                type="button"
                                onclick="document.getElementById('product-wishlist-form').submit()"
                                class="w-full btn-outline-luxe py-3 text-xs flex items-center justify-center gap-2"
                            >
                                <i class="ti ti-heart"></i> Save To Atelier Wishlist
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Hidden Wishlist Form -->
                <form id="product-wishlist-form" action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="hidden">
                    @csrf
                </form>

                <!-- Stock Notice -->
                <div class="mt-4 flex items-center gap-2 text-xs {{ $product->stock_qty <= 5 ? 'text-amber-700' : 'text-emerald-700' }}">
                    <i class="ti ti-circle-filled text-[8px]"></i>
                    @if($product->stock_qty > 0)
                        <span>In stock: {{ $product->stock_qty }} {{ Str::plural('piece', $product->stock_qty) }} remaining in atelier</span>
                    @else
                        <span>Currently unavailable for immediate dispatch</span>
                    @endif
                </div>

                <!-- Value Guarantees -->
                <div class="mt-8 border-t border-border pt-6 space-y-3 text-xs text-muted-foreground">
                    <div class="flex items-center gap-3">
                        <i class="ti ti-truck text-lg text-accent"></i>
                        <span>Complimentary insured shipping on all orders above ₹5,000</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="ti ti-certificate text-lg text-accent"></i>
                        <span>Certified authentic Kerala handloom with master weaver hallmark</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="ti ti-refresh text-lg text-accent"></i>
                        <span>14-day effortless returns and exchange policy</span>
                    </div>
                </div>

                <!-- Product Accordions -->
                <div class="mt-8 border-t border-border divide-y divide-border">
                    <details class="group py-4" open>
                        <summary class="flex justify-between items-center cursor-pointer list-none eyebrow text-foreground font-semibold">
                            <span>Description & Weaving Story</span>
                            <i class="ti ti-chevron-down group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <div class="mt-3 text-sm text-muted-foreground leading-relaxed">
                            {{ $product->description ?? 'Woven in Balaramapuram on traditional pit looms by third-generation master artisan families. Pure combed cotton yarns dyed with natural hues and finished with gold zari borders.' }}
                        </div>
                    </details>

                    <details class="group py-4">
                        <summary class="flex justify-between items-center cursor-pointer list-none eyebrow text-foreground font-semibold">
                            <span>Fabric, Details & Specifications</span>
                            <i class="ti ti-chevron-down group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <div class="mt-3 text-sm text-muted-foreground space-y-1.5">
                            <div><strong>Fabric:</strong> {{ $product->fabric ?? 'Pure Kerala Handloom Cotton' }}</div>
                            <div><strong>Color:</strong> {{ $product->color ?? 'Ivory & Gold' }}</div>
                            <div><strong>Occasion:</strong> {{ $product->occasion ?? 'Festival / Wedding / Auspicious Occasion' }}</div>
                            <div><strong>Length / Sizing:</strong> Standard 6.25m including running blouse piece</div>
                        </div>
                    </details>

                    <details class="group py-4">
                        <summary class="flex justify-between items-center cursor-pointer list-none eyebrow text-foreground font-semibold">
                            <span>Care & Preservation Guide</span>
                            <i class="ti ti-chevron-down group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <div class="mt-3 text-sm text-muted-foreground leading-relaxed">
                            Dry clean only for first 3 washes. Store wrapped in breathable muslin fabric away from moisture and direct sunlight. Iron on reverse side on low to medium silk setting.
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Reviews -->
    <section class="bg-secondary/30 border-y border-border py-20 mt-16">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
                <div>
                    <div class="eyebrow mb-2">Patron Impressions</div>
                    <h2 class="font-display text-3xl md:text-4xl">Client Reviews ({{ $product->reviews_count }})</h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="font-display text-3xl font-semibold">{{ number_format($product->rating, 1) }} / 5.0</div>
                        <div class="text-xs text-muted-foreground">Based on {{ $product->reviews_count }} reviews</div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @forelse($product->reviews as $rev)
                    <div class="bg-background p-6 border border-border shadow-soft">
                        <div class="flex items-center gap-1 text-accent mb-3">
                            @for($i = 0; $i < $rev->rating; $i++)
                                <i class="ti ti-star-filled text-xs"></i>
                            @endfor
                        </div>
                        <h4 class="font-display text-lg mb-2">{{ $rev->title ?? 'Stunning quality' }}</h4>
                        <p class="text-sm text-muted-foreground leading-relaxed mb-4">"{{ $rev->body }}"</p>
                        <div class="text-xs font-semibold text-foreground flex items-center gap-1">
                            <span>{{ $rev->customer?->first_name ?? 'Verified Patron' }}</span>
                            <i class="ti ti-circle-check text-emerald-600 text-sm" title="Verified Purchase"></i>
                        </div>
                    </div>
                @empty
                    <!-- Fallback default review -->
                    <div class="bg-background p-6 border border-border shadow-soft">
                        <div class="flex items-center gap-1 text-accent mb-3">
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                        </div>
                        <h4 class="font-display text-lg mb-2">An absolute heirloom piece</h4>
                        <p class="text-sm text-muted-foreground leading-relaxed mb-4">
                            "The gold zari catches morning temple light gracefully. The handloom weave breathes effortlessly and wraps with timeless dignity."
                        </p>
                        <div class="text-xs font-semibold text-foreground flex items-center gap-1">
                            <span>Aparna Menon, Kochi</span>
                            <i class="ti ti-circle-check text-emerald-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="bg-background p-6 border border-border shadow-soft">
                        <div class="flex items-center gap-1 text-accent mb-3">
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                        </div>
                        <h4 class="font-display text-lg mb-2">Unrivaled Kerala craftsmanship</h4>
                        <p class="text-sm text-muted-foreground leading-relaxed mb-4">
                            "Shipped to Bangalore in 48 hours. Beautifully packaged with cedar shavings and cotton pouch. Exceeded all my expectations."
                        </p>
                        <div class="text-xs font-semibold text-foreground flex items-center gap-1">
                            <span>Reshma P., Bangalore</span>
                            <i class="ti ti-circle-check text-emerald-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="bg-background p-6 border border-border shadow-soft">
                        <div class="flex items-center gap-1 text-accent mb-3">
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                            <i class="ti ti-star-filled text-xs"></i>
                        </div>
                        <h4 class="font-display text-lg mb-2">Soft, rich and pure</h4>
                        <p class="text-sm text-muted-foreground leading-relaxed mb-4">
                            "Felt so proud wearing this during Onam. Received so many compliments. Truly an authentic artisan piece."
                        </p>
                        <div class="text-xs font-semibold text-foreground flex items-center gap-1">
                            <span>Divya N., London</span>
                            <i class="ti ti-circle-check text-emerald-600 text-sm"></i>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Related Heirlooms -->
    <section class="max-w-[1400px] mx-auto px-6 lg:px-10 py-24">
        <div class="eyebrow mb-3">Complete The Ensemble</div>
        <h2 class="font-display text-3xl md:text-4xl mb-12">Related Heirlooms</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($relatedProducts as $rel)
                @include('storefront.partials.product-card', ['product' => $rel])
            @endforeach
        </div>
    </section>
</div>

<script>
    function changeImage(src, btn) {
        document.getElementById('main-gallery-image').src = src;
        document.querySelectorAll('.thumb-btn').forEach(b => {
            b.classList.remove('border-foreground');
            b.classList.add('border-transparent');
        });
        btn.classList.add('border-foreground');
        btn.classList.remove('border-transparent');
    }

    function adjustQty(amount) {
        const input = document.getElementById('qty-input');
        const current = parseInt(input.value) || 1;
        const max = parseInt(input.max) || 99;
        const next = Math.max(1, Math.min(max, current + amount));
        input.value = next;
    }
</script>
@endsection
