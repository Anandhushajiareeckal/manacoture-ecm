@extends('layouts.storefront')

@section('title', 'Shopping Bag — ManaCouture')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-16 md:py-20">
    <div class="eyebrow mb-2">Your Bag</div>
    <h1 class="font-display text-4xl md:text-5xl mb-10">Shopping Bag ({{ count($items) }})</h1>

    @if(empty($items))
        <div class="text-center py-24 bg-secondary/30 border border-border p-8 rounded-sm">
            <i class="ti ti-shopping-bag text-5xl text-muted-foreground mb-4 block"></i>
            <h2 class="font-display text-3xl mb-3">Your Bag Is Currently Empty</h2>
            <p class="text-muted-foreground text-sm max-w-sm mx-auto mb-8">
                Explore our curated Kerala Kasavu, bridal couture and festival edits to begin your heirloom collection.
            </p>
            <a href="{{ route('shop.index') }}" class="btn-luxe">
                Explore The Collections <i class="ti ti-arrow-right"></i>
            </a>
        </div>
    @else
        <div class="grid lg:grid-cols-[1fr_420px] gap-12 lg:gap-16 items-start">
            <!-- Items List -->
            <div class="space-y-6">
                <!-- Free Shipping Indicator -->
                <div class="p-4 bg-secondary/50 border border-border">
                    @if($subtotal >= $freeShippingThreshold)
                        <div class="flex items-center gap-2 text-xs font-medium text-emerald-800">
                            <i class="ti ti-circle-check text-base"></i>
                            <span>You have unlocked complimentary insured shipping across India!</span>
                        </div>
                    @else
                        @php $remaining = $freeShippingThreshold - $subtotal; @endphp
                        <div class="text-xs text-foreground mb-2 flex justify-between">
                            <span>Add <strong>₹{{ number_format($remaining, 0) }}</strong> more to unlock complimentary shipping</span>
                            <span>Threshold: ₹{{ number_format($freeShippingThreshold, 0) }}</span>
                        </div>
                        <div class="w-full bg-border h-1.5 rounded-full overflow-hidden">
                            <div class="bg-accent h-full transition-all duration-500" style="width: {{ min(100, ($subtotal / $freeShippingThreshold) * 100) }}%"></div>
                        </div>
                    @endif
                </div>

                <!-- Product Rows -->
                @foreach($items as $item)
                    <div class="grid grid-cols-[100px_1fr] sm:grid-cols-[140px_1fr] gap-4 sm:gap-6 pb-6 border-b border-border">
                        <a href="{{ route('products.show', $item['product']->slug) }}" class="aspect-[4/5] overflow-hidden bg-secondary block shadow-soft">
                            <img src="{{ $item['product']->main_image_url }}" alt="{{ $item['product']->name }}" class="h-full w-full object-cover">
                        </a>

                        <div class="flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start gap-4">
                                    <div>
                                        <div class="eyebrow text-[10px] mb-1">{{ $item['product']->category?->name ?? 'Kerala Handloom' }}</div>
                                        <a href="{{ route('products.show', $item['product']->slug) }}" class="font-display text-xl sm:text-2xl hover:text-accent transition-colors block">
                                            {{ $item['product']->name }}
                                        </a>
                                        <div class="text-xs text-muted-foreground mt-1 space-y-0.5">
                                            <div>Fitting: <span class="text-foreground font-medium">{{ $item['size'] }}</span></div>
                                            <div>Color: <span class="text-foreground font-medium">{{ $item['color'] }}</span></div>
                                            <div>Fabric: <span class="text-foreground font-medium">{{ $item['product']->fabric ?? 'Pure Cotton' }}</span></div>
                                        </div>
                                    </div>

                                    <!-- Remove Item -->
                                    <form action="{{ route('cart.remove', $item['key']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-muted-foreground hover:text-destructive transition-colors" title="Remove piece">
                                            <i class="ti ti-x text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 mt-2">
                                <!-- Quantity Controller -->
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center border border-border">
                                    @csrf
                                    <input type="hidden" name="key" value="{{ $item['key'] }}">
                                    <button type="submit" name="qty" value="{{ $item['qty'] - 1 }}" class="px-2.5 py-1 text-muted-foreground hover:text-foreground">
                                        <i class="ti ti-minus text-xs"></i>
                                    </button>
                                    <span class="px-3 py-1 text-xs font-semibold">{{ $item['qty'] }}</span>
                                    <button type="submit" name="qty" value="{{ $item['qty'] + 1 }}" class="px-2.5 py-1 text-muted-foreground hover:text-foreground">
                                        <i class="ti ti-plus text-xs"></i>
                                    </button>
                                </form>

                                <!-- Line Total -->
                                <div class="text-right font-display text-lg font-semibold text-foreground">
                                    ₹{{ number_format($item['total'], 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="pt-2">
                    <a href="{{ route('shop.index') }}" class="text-xs uppercase tracking-widest text-muted-foreground hover:text-foreground flex items-center gap-1.5">
                        <i class="ti ti-arrow-left"></i> Continue Shopping Collections
                    </a>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="bg-secondary/40 border border-border p-6 sm:p-8 space-y-6">
                <h3 class="font-display text-2xl pb-4 border-b border-border">Atelier Summary</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between text-muted-foreground">
                        <span>Bag Subtotal</span>
                        <span class="font-medium text-foreground">₹{{ number_format($subtotal, 0) }}</span>
                    </div>

                    <div class="flex justify-between text-muted-foreground">
                        <span>Insured Delivery</span>
                        <span>
                            @if($shipping === 0)
                                <span class="text-emerald-700 font-semibold uppercase text-xs">Complimentary</span>
                            @else
                                <span class="font-medium text-foreground">₹{{ number_format($shipping, 0) }}</span>
                            @endif
                        </span>
                    </div>

                    @if($discount > 0)
                        <div class="flex justify-between text-emerald-700 font-medium text-sm">
                            <span>Privilege Discount ({{ $coupon['code'] }})</span>
                            <span>−₹{{ number_format($discount, 0) }}</span>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-border flex justify-between items-baseline">
                        <span class="font-display text-xl font-semibold">Estimated Total</span>
                        <span class="font-display text-2xl font-bold text-foreground">
                            ₹{{ number_format($total, 0) }}
                        </span>
                    </div>
                    <div class="text-[11px] text-muted-foreground text-right">Includes all GST & dispatch charges</div>
                </div>

                <!-- Coupon Box -->
                <div class="pt-4 border-t border-border">
                    @if($coupon)
                        <div class="p-3 bg-accent/10 border border-accent/40 rounded-sm flex items-center justify-between text-xs">
                            <div>
                                <span class="font-semibold text-accent uppercase tracking-wider">{{ $coupon['code'] }}</span>
                                <span class="text-muted-foreground block text-[11px]">{{ $coupon['description'] ?? 'Privilege applied' }}</span>
                            </div>
                            <form action="{{ route('cart.remove-coupon') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Remove</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.apply-coupon') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input
                                type="text"
                                name="code"
                                placeholder="Promotional or Onam Code"
                                required
                                class="flex-1 px-3 py-2 bg-background border border-border text-xs uppercase tracking-wider focus:border-accent outline-none"
                            >
                            <button type="submit" class="btn-outline-luxe py-2 px-4 text-xs">Apply</button>
                        </form>
                        <div class="text-[10px] text-muted-foreground mt-2">
                            Try code <strong class="text-foreground">ONAM2026</strong> for 15% atelier privilege.
                        </div>
                    @endif
                </div>

                <!-- Checkout CTA -->
                <div class="pt-2">
                    <a href="{{ route('checkout.index') }}" class="btn-luxe w-full py-4 text-center tracking-[0.2em] shadow-lg">
                        Proceed To Atelier Checkout <i class="ti ti-arrow-right"></i>
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-4 border-t border-border/80 text-xs text-muted-foreground space-y-2">
                    <div class="flex items-center gap-2">
                        <i class="ti ti-lock text-sm text-accent"></i>
                        <span>256-bit encrypted checkout with 3D Secure</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ti ti-sparkles text-sm text-accent"></i>
                        <span>Wrapped in signature muslin atelier packaging</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
