<div class="group">
    <div class="relative overflow-hidden bg-secondary/50 aspect-[4/5]">
        <a href="{{ route('products.show', $product->slug) }}" class="block absolute inset-0">
            <!-- Primary Main Image -->
            <img
                src="{{ $product->main_image_url }}"
                alt="{{ $product->name }}"
                loading="lazy"
                class="absolute inset-0 h-full w-full object-cover transition-all duration-[900ms] ease-out group-hover:scale-105 group-hover:opacity-0"
            />
            <!-- Secondary Hover Image -->
            <img
                src="{{ $product->hover_image_url }}"
                alt="{{ $product->name }} Detail"
                loading="lazy"
                aria-hidden="true"
                class="absolute inset-0 h-full w-full object-cover opacity-0 transition-opacity duration-[900ms] ease-out group-hover:opacity-100"
            />
        </a>

        <!-- Badges -->
        <div class="absolute top-4 left-4 flex flex-col gap-2 pointer-events-none z-10">
            @if($product->badge)
                <span class="bg-primary text-primary-foreground text-[10px] tracking-[0.2em] uppercase px-3 py-1.5 shadow-sm">
                    {{ $product->badge }}
                </span>
            @endif
            @if($product->is_new)
                <span class="bg-background text-foreground text-[10px] tracking-[0.2em] uppercase px-3 py-1.5 shadow-sm">
                    New
                </span>
            @endif
            @if($product->discount_percent > 0)
                <span class="bg-accent text-accent-foreground text-[10px] tracking-[0.2em] uppercase px-3 py-1.5 font-medium shadow-sm">
                    −{{ $product->discount_percent }}%
                </span>
            @endif
        </div>

        <!-- Quick Actions (Wishlist & View) -->
        <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 z-10">
            <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                @csrf
                <button
                    type="submit"
                    aria-label="Save to wishlist"
                    class="h-9 w-9 grid place-items-center bg-background/95 backdrop-blur-sm text-foreground hover:bg-primary hover:text-primary-foreground transition-colors shadow-sm"
                    title="Save to wishlist"
                >
                    @php $inWishlist = in_array($product->id, session('wishlist', [])); @endphp
                    <i class="ti ti-heart {{ $inWishlist ? 'text-accent fill-accent' : '' }}"></i>
                </button>
            </form>
            <a
                href="{{ route('products.show', $product->slug) }}"
                aria-label="View Product"
                class="h-9 w-9 grid place-items-center bg-background/95 backdrop-blur-sm text-foreground hover:bg-primary hover:text-primary-foreground transition-colors shadow-sm"
                title="Quick View"
            >
                <i class="ti ti-eye"></i>
            </a>
        </div>

        <!-- Add to Bag Slide Up -->
        <div class="absolute inset-x-4 bottom-4 opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 z-10">
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="qty" value="1">
                <button
                    type="submit"
                    class="w-full btn-luxe bg-background/95 text-foreground border-foreground hover:bg-primary hover:text-primary-foreground text-xs py-2.5 shadow-md"
                >
                    Add to bag
                </button>
            </form>
        </div>
    </div>

    <!-- Product Meta -->
    <div class="pt-5 pb-2 px-1">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <div class="eyebrow text-[10px] mb-1.5">{{ $product->category?->name ?? 'Kerala Handloom' }}</div>
                <a href="{{ route('products.show', $product->slug) }}" class="font-display text-lg leading-snug hover:text-accent transition-colors block truncate">
                    {{ $product->name }}
                </a>
                <div class="text-xs text-muted-foreground mt-1">{{ $product->fabric ?? 'Pure Combed Cotton' }}</div>
            </div>
            <div class="text-right shrink-0">
                <div class="font-medium text-foreground">{{ $product->formatted_price }}</div>
                @if($product->compare_price)
                    <div class="text-xs text-muted-foreground line-through">{{ $product->formatted_compare_price }}</div>
                @endif
            </div>
        </div>
        <div class="mt-2.5 flex items-center gap-1.5 text-xs text-muted-foreground">
            <span class="text-accent flex items-center">
                <i class="ti ti-star-filled text-[11px]"></i>
            </span>
            <span class="font-medium text-foreground">{{ number_format($product->rating, 1) }}</span>
            <span>·</span>
            <span>{{ $product->reviews_count }} reviews</span>
        </div>
    </div>
</div>
