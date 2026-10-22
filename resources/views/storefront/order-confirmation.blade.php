@extends('layouts.storefront')

@section('title', 'Order Confirmed — ' . $order->order_number . ' — ManaCouture')

@section('content')
<div class="max-w-[900px] mx-auto px-6 py-16 md:py-24">
    <div class="text-center mb-12 animate-fade-up">
        <div class="h-16 w-16 bg-accent/20 text-accent rounded-full grid place-items-center mx-auto mb-6">
            <i class="ti ti-check text-3xl"></i>
        </div>
        <div class="eyebrow mb-2">Order Confirmed</div>
        <h1 class="font-display text-4xl md:text-5xl mb-3">Thank You For Your Patronage</h1>
        <p class="text-muted-foreground text-sm max-w-md mx-auto">
            Your order <strong>#{{ $order->order_number }}</strong> has been received and is now being hand-inspected in our Kochi atelier.
        </p>
    </div>

    <!-- Order Card -->
    <div class="bg-secondary/40 border border-border p-6 sm:p-10 space-y-8 shadow-soft">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-border">
            <div>
                <div class="text-xs text-muted-foreground eyebrow mb-1">Order Reference</div>
                <div class="font-display text-2xl font-semibold">{{ $order->order_number }}</div>
            </div>
            <div class="text-left sm:text-right">
                <div class="text-xs text-muted-foreground eyebrow mb-1">Order Date</div>
                <div class="text-sm font-medium">{{ $order->created_at->format('M d, Y · h:i A') }}</div>
            </div>
        </div>

        <!-- Items Ordered -->
        <div>
            <h3 class="eyebrow text-foreground mb-4">Heirloom Pieces</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between gap-4 py-3 border-b border-border/60">
                        <div class="flex items-center gap-4">
                            @if($item->product)
                                <img src="{{ $item->product->main_image_url }}" alt="{{ $item->product_name }}" class="w-14 h-18 object-cover bg-secondary border border-border">
                            @endif
                            <div>
                                <div class="font-display text-base font-medium">{{ $item->product_name }}</div>
                                <div class="text-xs text-muted-foreground">{{ $item->variant_title ?? 'Free Size' }} · Qty: {{ $item->qty }}</div>
                            </div>
                        </div>
                        <div class="font-display text-base font-semibold text-foreground">
                            ₹{{ number_format($item->line_total, 0) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid sm:grid-cols-2 gap-8 pt-4">
            <div>
                <h4 class="eyebrow mb-2">Shipping Destination</h4>
                <div class="text-xs text-muted-foreground leading-relaxed">
                    @php $addr = $order->shipping_address ?? []; @endphp
                    <div class="font-semibold text-foreground text-sm mb-1">{{ $addr['name'] ?? $order->customer?->full_name }}</div>
                    <div>{{ $addr['address_line1'] ?? '' }}</div>
                    @if(!empty($addr['address_line2']))
                        <div>{{ $addr['address_line2'] }}</div>
                    @endif
                    <div>{{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }} {{ $addr['postal_code'] ?? '' }}</div>
                    <div class="mt-2 text-foreground font-medium"><i class="ti ti-phone text-xs"></i> {{ $addr['phone'] ?? $order->customer?->phone }}</div>
                </div>
            </div>

            <div>
                <h4 class="eyebrow mb-2">Payment & Fulfillment</h4>
                <div class="text-xs text-muted-foreground space-y-1.5">
                    <div>Payment Status: <span class="uppercase font-semibold px-2 py-0.5 rounded text-[10px] {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $order->payment_status }}</span></div>
                    <div>Fulfillment: <span class="uppercase font-semibold px-2 py-0.5 rounded text-[10px] bg-blue-100 text-blue-800">{{ $order->fulfillment_status }}</span></div>
                    <div>Shipping Method: <span class="font-medium text-foreground">{{ $order->shippingMethod?->name ?? 'Complimentary Ground Shipping' }}</span></div>
                </div>
            </div>
        </div>

        <!-- Total Calculation -->
        <div class="pt-6 border-t border-border flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div class="text-xs text-muted-foreground">
                An official confirmation invoice has been generated for order <strong>#{{ $order->order_number }}</strong>.
            </div>
            <div class="text-right">
                <span class="text-xs text-muted-foreground eyebrow mr-3">Grand Total:</span>
                <span class="font-display text-3xl font-bold text-foreground">₹{{ number_format($order->grand_total, 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-12 flex flex-wrap justify-center gap-4">
        <a href="{{ route('shop.index') }}" class="btn-luxe">
            Explore More Weaves <i class="ti ti-arrow-right"></i>
        </a>
        <a href="{{ route('account') }}" class="btn-outline-luxe">
            View Atelier Account
        </a>
    </div>
</div>
@endsection
