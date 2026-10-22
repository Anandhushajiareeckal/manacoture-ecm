@extends('layouts.storefront')

@section('title', $title . ' — ManaCouture Kerala Handlooms')
@section('meta_description', 'Shop Kerala Kasavu sarees, bridal silks, set mundu, kurtis, salwar sets and heirloom accessories.')

@section('content')
<div>
    <!-- Hero Strip -->
    <section class="bg-secondary/40 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-16 md:py-20">
            <div class="eyebrow mb-3">The Edit</div>
            <h1 class="font-display text-4xl md:text-6xl">{{ $title }}</h1>
            <p class="mt-3 text-muted-foreground text-sm md:text-base max-w-lg">
                {{ $products->total() }} handcrafted pieces, meticulously curated by our Kerala atelier.
            </p>
        </div>
    </section>

    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-10">
        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-4 border-b border-border mb-8">
            <div class="eyebrow text-[11px]">{{ $products->total() }} pieces found</div>
            
            <div class="flex items-center gap-4">
                <form id="sort-form" method="GET" class="flex items-center gap-2">
                    @foreach(request()->except('sort', 'page') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <label for="sort-select" class="eyebrow text-[10px] hidden sm:inline">Sort By:</label>
                    <select
                        name="sort"
                        id="sort-select"
                        onchange="document.getElementById('sort-form').submit()"
                        class="bg-transparent text-xs tracking-wider uppercase border border-border px-3 py-2 outline-none cursor-pointer focus:border-accent"
                    >
                        <option value="featured" {{ $sort === 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Weaves</option>
                        <option value="rating" {{ $sort === 'rating' ? 'selected' : '' }}>Top Rated</option>
                    </select>
                </form>

                <!-- Mobile Filter Toggle -->
                <button
                    onclick="document.getElementById('filter-drawer').classList.toggle('hidden')"
                    class="lg:hidden btn-outline-luxe py-2 px-3 text-xs flex items-center gap-1.5"
                >
                    <i class="ti ti-adjustments-horizontal"></i> Filters
                </button>
            </div>
        </div>

        <div class="grid lg:grid-cols-[260px_1fr] gap-10 lg:gap-14">
            <!-- Sidebar Filters (Desktop) -->
            <aside id="filter-drawer" class="hidden lg:block space-y-8">
                <!-- Active Filters & Reset -->
                @if(request()->hasAny(['c', 'category', 'occasion', 'fabric', 'color', 'price', 'q']))
                    <div class="p-4 bg-secondary/50 border border-border">
                        <div class="flex items-center justify-between mb-2">
                            <span class="eyebrow text-[10px]">Active Filters</span>
                            <a href="{{ route('shop.index') }}" class="text-xs text-accent hover:underline">Clear all</a>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            @if($activeCategory)
                                <span class="text-xs bg-background px-2.5 py-1 border border-border rounded-sm flex items-center gap-1">
                                    {{ $activeCategory->name }}
                                    <a href="{{ request()->fullUrlWithoutQuery(['c', 'category']) }}"><i class="ti ti-x text-[10px]"></i></a>
                                </span>
                            @endif
                            @if(request('occasion'))
                                <span class="text-xs bg-background px-2.5 py-1 border border-border rounded-sm flex items-center gap-1">
                                    {{ request('occasion') }}
                                    <a href="{{ request()->fullUrlWithoutQuery('occasion') }}"><i class="ti ti-x text-[10px]"></i></a>
                                </span>
                            @endif
                            @if(request('fabric'))
                                <span class="text-xs bg-background px-2.5 py-1 border border-border rounded-sm flex items-center gap-1">
                                    {{ request('fabric') }}
                                    <a href="{{ request()->fullUrlWithoutQuery('fabric') }}"><i class="ti ti-x text-[10px]"></i></a>
                                </span>
                            @endif
                            @if(request('price'))
                                <span class="text-xs bg-background px-2.5 py-1 border border-border rounded-sm flex items-center gap-1">
                                    {{ request('price') }}
                                    <a href="{{ request()->fullUrlWithoutQuery('price') }}"><i class="ti ti-x text-[10px]"></i></a>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Categories -->
                <div>
                    <h3 class="eyebrow mb-4 text-foreground">Categories</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a
                                href="{{ request()->fullUrlWithoutQuery(['c', 'category', 'page']) }}"
                                class="flex items-center justify-between py-1 transition-colors {{ !request('c') && !request('category') ? 'font-semibold text-accent' : 'text-foreground/80 hover:text-accent' }}"
                            >
                                <span>All Collections</span>
                                <span class="text-xs text-muted-foreground">{{ \App\Models\Product::active()->count() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a
                                    href="{{ route('shop.index', array_merge(request()->except('c', 'category', 'page'), ['c' => $cat->slug])) }}"
                                    class="flex items-center justify-between py-1 transition-colors {{ request('c') === $cat->slug ? 'font-semibold text-accent' : 'text-foreground/80 hover:text-accent' }}"
                                >
                                    <span>{{ $cat->name }}</span>
                                    <span class="text-xs text-muted-foreground">{{ $cat->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Occasion -->
                <div class="border-t border-border pt-6">
                    <h3 class="eyebrow mb-4 text-foreground">Occasion</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($occasions as $occ)
                            <li>
                                <a
                                    href="{{ route('shop.index', array_merge(request()->except('occasion', 'page'), request('occasion') === $occ ? [] : ['occasion' => $occ])) }}"
                                    class="flex items-center justify-between py-1 transition-colors {{ request('occasion') === $occ ? 'font-semibold text-accent' : 'text-foreground/80 hover:text-accent' }}"
                                >
                                    <span>{{ $occ }}</span>
                                    @if(request('occasion') === $occ)
                                        <i class="ti ti-check text-xs text-accent"></i>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Fabric -->
                <div class="border-t border-border pt-6">
                    <h3 class="eyebrow mb-4 text-foreground">Fabric & Material</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($fabrics as $fab)
                            <li>
                                <a
                                    href="{{ route('shop.index', array_merge(request()->except('fabric', 'page'), request('fabric') === $fab ? [] : ['fabric' => $fab])) }}"
                                    class="flex items-center justify-between py-1 transition-colors {{ request('fabric') === $fab ? 'font-semibold text-accent' : 'text-foreground/80 hover:text-accent' }}"
                                >
                                    <span>{{ $fab }}</span>
                                    @if(request('fabric') === $fab)
                                        <i class="ti ti-check text-xs text-accent"></i>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Price Range -->
                <div class="border-t border-border pt-6">
                    <h3 class="eyebrow mb-4 text-foreground">Price</h3>
                    <ul class="space-y-2 text-sm">
                        @php
                            $priceRanges = [
                                'under_3000' => 'Under ₹3,000',
                                '3000_8000' => '₹3,000 – ₹8,000',
                                '8000_20000' => '₹8,000 – ₹20,000',
                                'above_20000' => 'Above ₹20,000',
                            ];
                        @endphp
                        @foreach($priceRanges as $key => $label)
                            <li>
                                <a
                                    href="{{ route('shop.index', array_merge(request()->except('price', 'page'), request('price') === $key ? [] : ['price' => $key])) }}"
                                    class="flex items-center justify-between py-1 transition-colors {{ request('price') === $key ? 'font-semibold text-accent' : 'text-foreground/80 hover:text-accent' }}"
                                >
                                    <span>{{ $label }}</span>
                                    @if(request('price') === $key)
                                        <i class="ti ti-check text-xs text-accent"></i>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <!-- Product Grid -->
            <div>
                @if($products->isEmpty())
                    <div class="text-center py-24 bg-secondary/30 border border-border p-8">
                        <i class="ti ti-hanger text-4xl text-muted-foreground mb-4 block"></i>
                        <h3 class="font-display text-2xl mb-2">No Weaves Match Your Filters</h3>
                        <p class="text-sm text-muted-foreground max-w-sm mx-auto mb-6">
                            Try loosening your filter selections or browse our entire handcrafted catalog.
                        </p>
                        <a href="{{ route('shop.index') }}" class="btn-luxe">Clear All Filters</a>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
                        @foreach($products as $product)
                            @include('storefront.partials.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-16 pt-8 border-t border-border flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
