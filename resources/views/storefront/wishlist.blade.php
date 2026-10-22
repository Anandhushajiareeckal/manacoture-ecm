@extends('layouts.storefront')

@section('title', 'My Wishlist — ManaCouture')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-16 md:py-20">
    <div class="eyebrow mb-2">Saved For Later</div>
    <h1 class="font-display text-4xl md:text-5xl mb-2">My Atelier Wishlist</h1>
    <p class="text-muted-foreground text-sm mb-12">
        {{ $products->count() }} {{ Str::plural('piece', $products->count()) }} curated for your personal collection.
    </p>

    @if($products->isEmpty())
        <div class="text-center py-24 bg-secondary/30 border border-border p-8 rounded-sm">
            <i class="ti ti-heart text-5xl text-muted-foreground mb-4 block"></i>
            <h2 class="font-display text-3xl mb-3">Your Wishlist Is Currently Empty</h2>
            <p class="text-muted-foreground text-sm max-w-sm mx-auto mb-8">
                Click the heart icon on any Kasavu, bridal or festival piece to save it to your atelier wishlist.
            </p>
            <a href="{{ route('shop.index') }}" class="btn-luxe">
                Explore The Collections <i class="ti ti-arrow-right"></i>
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($products as $product)
                @include('storefront.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    @endif
</div>
@endsection
