@extends('layouts.storefront')

@section('title', 'Atelier Checkout — ManaCouture')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-12 md:py-16">
    <div class="mb-10">
        <a href="{{ route('home') }}" class="font-display text-3xl">ManaCouture</a>
        <div class="eyebrow text-[10px] mt-1 text-muted-foreground">Kerala · Est. 2018 · Secure Checkout</div>
    </div>

    <!-- Stepper Indicator -->
    <div class="flex items-center gap-3 mb-12 text-xs">
        <div class="flex items-center gap-2 font-medium text-foreground">
            <span class="h-6 w-6 rounded-full bg-primary text-primary-foreground grid place-items-center text-[11px]">1</span>
            <span class="eyebrow text-foreground text-[10px]">Contact</span>
        </div>
        <span class="w-8 h-px bg-border"></span>
        <div class="flex items-center gap-2 font-medium text-foreground">
            <span class="h-6 w-6 rounded-full bg-primary text-primary-foreground grid place-items-center text-[11px]">2</span>
            <span class="eyebrow text-foreground text-[10px]">Shipping</span>
        </div>
        <span class="w-8 h-px bg-border"></span>
        <div class="flex items-center gap-2 font-medium text-foreground">
            <span class="h-6 w-6 rounded-full bg-accent text-accent-foreground grid place-items-center text-[11px]">3</span>
            <span class="eyebrow text-foreground text-[10px]">Payment</span>
        </div>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="grid lg:grid-cols-[1fr_440px] gap-12 lg:gap-16 items-start">
            <!-- Checkout Fields -->
            <div class="space-y-10">
                <!-- 1. Contact Information -->
                <section class="bg-background border border-border p-6 sm:p-8 space-y-4 shadow-soft">
                    <h2 class="font-display text-2xl mb-4 flex items-center justify-between">
                        <span>1. Contact Information</span>
                        <a href="{{ route('account') }}" class="text-xs eyebrow text-accent hover:underline">Already patron? Sign in</a>
                    </h2>

                    <div>
                        <label for="email" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">Email Address <span class="text-red-500">*</span></label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            placeholder="patron@example.com"
                            value="{{ old('email', 'aparna.menon@example.com') }}"
                            class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm"
                        >
                        <span class="text-[11px] text-muted-foreground mt-1 block">Order confirmation and invoice will be sent to this email.</span>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="newsletter" name="newsletter" value="1" checked class="accent-accent">
                        <label for="newsletter" class="text-xs text-muted-foreground cursor-pointer">
                            Email me with private atelier collection previews and festival invitations.
                        </label>
                    </div>
                </section>

                <!-- 2. Shipping Address -->
                <section class="bg-background border border-border p-6 sm:p-8 space-y-4 shadow-soft">
                    <h2 class="font-display text-2xl mb-4">2. Shipping Destination</h2>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" required value="{{ old('first_name', 'Aparna') }}" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                        </div>
                        <div>
                            <label for="last_name" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" required value="{{ old('last_name', 'Menon') }}" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="address_line1" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">Address Line 1 <span class="text-red-500">*</span></label>
                        <input type="text" id="address_line1" name="address_line1" required placeholder="House / Flat / Building Name & Street" value="{{ old('address_line1', 'Atelier House, Princess Street') }}" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                    </div>

                    <div>
                        <label for="address_line2" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">Apartment, Suite, Landmark (Optional)</label>
                        <input type="text" id="address_line2" name="address_line2" placeholder="Near Fort Kochi Post Office" value="{{ old('address_line2', 'Fort Kochi') }}" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <div>
                            <label for="city" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">City <span class="text-red-500">*</span></label>
                            <input type="text" id="city" name="city" required value="{{ old('city', 'Kochi') }}" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                        </div>
                        <div>
                            <label for="state" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">State <span class="text-red-500">*</span></label>
                            <select id="state" name="state" required class="w-full h-12 px-3 bg-secondary/30 border border-border focus:border-accent outline-none text-sm cursor-pointer">
                                @foreach(['Kerala', 'Tamil Nadu', 'Karnataka', 'Maharashtra', 'Delhi', 'Telangana', 'Andhra Pradesh', 'Gujarat', 'West Bengal'] as $st)
                                    <option value="{{ $st }}" {{ old('state', 'Kerala') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="postal_code" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">PIN Code <span class="text-red-500">*</span></label>
                            <input type="text" id="postal_code" name="postal_code" required value="{{ old('postal_code', '682001') }}" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">Mobile Phone (For Dispatch Updates) <span class="text-red-500">*</span></label>
                        <input type="tel" id="phone" name="phone" required placeholder="+91 98470 11223" value="{{ old('phone', '+91-98470-11223') }}" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                    </div>
                </section>

                <!-- 3. Delivery Method -->
                <section class="bg-background border border-border p-6 sm:p-8 space-y-4 shadow-soft">
                    <h2 class="font-display text-2xl mb-4">3. Delivery Method</h2>

                    <div class="space-y-3">
                        @foreach($shippingMethods as $idx => $method)
                            @php
                                $rate = ($subtotal >= 5000 && $method->base_rate == 0) ? 0 : $method->base_rate;
                            @endphp
                            <label class="flex items-center justify-between p-4 border border-border rounded-sm cursor-pointer hover:border-accent transition-colors bg-secondary/20">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="shipping_method_id" value="{{ $method->id }}" {{ $idx === 0 ? 'checked' : '' }} class="accent-accent">
                                    <div>
                                        <div class="font-medium text-sm text-foreground">{{ $method->name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $method->description ?? 'Standard insured courier' }}</div>
                                    </div>
                                </div>
                                <div class="text-sm font-semibold">
                                    {{ $rate == 0 ? 'FREE' : '₹' . number_format($rate, 0) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </section>

                <!-- 4. Payment Selection -->
                <section class="bg-background border border-border p-6 sm:p-8 space-y-4 shadow-soft">
                    <h2 class="font-display text-2xl mb-4">4. Payment Method</h2>

                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-4 border border-border rounded-sm cursor-pointer hover:border-accent bg-secondary/20">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="upi" checked class="accent-accent">
                                <div>
                                    <div class="font-medium text-sm">UPI / QR (Google Pay, PhonePe, Paytm, BHIM)</div>
                                    <div class="text-xs text-muted-foreground">Instant zero-fee transfer via any UPI app</div>
                                </div>
                            </div>
                            <i class="ti ti-qrcode text-2xl text-accent"></i>
                        </label>

                        <label class="flex items-center justify-between p-4 border border-border rounded-sm cursor-pointer hover:border-accent bg-secondary/20">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="card" class="accent-accent">
                                <div>
                                    <div class="font-medium text-sm">Credit / Debit Card</div>
                                    <div class="text-xs text-muted-foreground">Visa, MasterCard, RuPay, American Express</div>
                                </div>
                            </div>
                            <i class="ti ti-credit-card text-2xl text-accent"></i>
                        </label>

                        <label class="flex items-center justify-between p-4 border border-border rounded-sm cursor-pointer hover:border-accent bg-secondary/20">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cod" class="accent-accent">
                                <div>
                                    <div class="font-medium text-sm">Cash On Delivery (COD)</div>
                                    <div class="text-xs text-muted-foreground">Pay with cash upon verified doorstep delivery</div>
                                </div>
                            </div>
                            <i class="ti ti-cash text-2xl text-accent"></i>
                        </label>
                    </div>

                    <div class="pt-4">
                        <label for="customer_notes" class="block text-xs uppercase tracking-wider text-muted-foreground mb-1.5 font-medium">Special Atelier Instructions or Gift Note</label>
                        <textarea id="customer_notes" name="customer_notes" rows="2" placeholder="e.g. Please wrap with personalized gift message: 'With love on your wedding day!'" class="w-full p-3 bg-secondary/30 border border-border focus:border-accent outline-none text-sm"></textarea>
                    </div>
                </section>
            </div>

            <!-- Order Summary Column -->
            <div class="bg-secondary/40 border border-border p-6 sm:p-8 space-y-6 sticky top-28 shadow-soft">
                <div class="flex items-center justify-between pb-4 border-b border-border">
                    <h3 class="font-display text-2xl">Your Order</h3>
                    <a href="{{ route('cart.index') }}" class="text-xs eyebrow text-accent hover:underline">Edit Bag</a>
                </div>

                <!-- Bag Items -->
                <div class="space-y-4 max-h-[360px] overflow-y-auto pr-2">
                    @foreach($items as $it)
                        <div class="flex items-center gap-4">
                            <div class="relative w-16 h-20 bg-secondary flex-shrink-0 border border-border overflow-hidden">
                                <img src="{{ $it['product']->main_image_url }}" alt="" class="h-full w-full object-cover">
                                <span class="absolute top-0 right-0 bg-primary text-primary-foreground text-[10px] h-4 w-4 grid place-items-center font-semibold">
                                    {{ $it['qty'] }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-display text-sm font-medium text-foreground truncate">{{ $it['product']->name }}</div>
                                <div class="text-[11px] text-muted-foreground">{{ $it['size'] }} · {{ $it['color'] }}</div>
                                <div class="text-xs font-semibold text-foreground mt-1">₹{{ number_format($it['total'], 0) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Price Breakdown -->
                <div class="pt-4 border-t border-border space-y-2.5 text-sm">
                    <div class="flex justify-between text-muted-foreground">
                        <span>Subtotal</span>
                        <span class="font-medium text-foreground">₹{{ number_format($subtotal, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-muted-foreground">
                        <span>Insured Delivery</span>
                        <span>{{ $defaultShipping == 0 ? 'FREE' : '₹' . number_format($defaultShipping, 0) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-emerald-700 font-medium text-sm">
                            <span>Privilege Discount</span>
                            <span>−₹{{ number_format($discount, 0) }}</span>
                        </div>
                    @endif
                    <div class="pt-3 border-t border-border flex justify-between items-baseline">
                        <span class="font-display text-xl font-semibold">Total to Pay</span>
                        <span class="font-display text-2xl font-bold text-foreground">
                            ₹{{ number_format($total, 0) }}
                        </span>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="btn-luxe w-full py-4 text-center tracking-[0.22em] text-xs font-semibold shadow-xl">
                        Complete Order & Authorize <i class="ti ti-lock text-sm"></i>
                    </button>
                </div>

                <div class="text-[11px] text-muted-foreground text-center space-y-1">
                    <div>By clicking complete, you accept ManaCouture's Terms & Return Policies.</div>
                    <div class="flex items-center justify-center gap-2 text-accent pt-2">
                        <i class="ti ti-shield-check text-base"></i>
                        <span>Guaranteed genuine handloom with Silk Mark India</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
