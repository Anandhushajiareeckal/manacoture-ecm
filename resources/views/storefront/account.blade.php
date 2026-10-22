@extends('layouts.storefront')

@section('title', 'My Atelier Account — ManaCouture')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-16">
    <div class="eyebrow mb-2">My Atelier</div>
    <h1 class="font-display text-4xl md:text-5xl mb-2">Welcome Back, {{ $customer?->first_name ?? 'Patron' }}</h1>
    <p class="text-muted-foreground text-sm">Gold Member · 2,480 loyalty privileges</p>

    <div class="grid lg:grid-cols-[260px_1fr] gap-10 lg:gap-14 mt-12">
        <!-- Sidebar Navigation -->
        <aside class="space-y-6">
            <nav class="space-y-1">
                <a href="#overview" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-left bg-secondary border-l-2 border-accent font-medium">
                    <i class="ti ti-user text-base"></i>
                    <span class="flex-1">Account Overview</span>
                </a>
                <a href="{{ route('wishlist.index') }}" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-left hover:bg-secondary/50 border-l-2 border-transparent transition-colors">
                    <i class="ti ti-heart text-base"></i>
                    <span class="flex-1">Wishlist ({{ $wishlistCount }})</span>
                </a>
                <a href="{{ route('cart.index') }}" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-left hover:bg-secondary/50 border-l-2 border-transparent transition-colors">
                    <i class="ti ti-shopping-bag text-base"></i>
                    <span class="flex-1">Shopping Bag</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-left hover:bg-secondary/50 border-l-2 border-transparent text-accent font-medium transition-colors">
                    <i class="ti ti-settings text-base"></i>
                    <span class="flex-1">Store Admin Panel</span>
                </a>
            </nav>

            <div class="pt-4 border-t border-border">
                <a href="{{ route('home') }}" class="text-xs eyebrow text-muted-foreground hover:text-foreground">
                    ← Return to Boutique
                </a>
            </div>
        </aside>

        <!-- Main Account Content -->
        <section class="space-y-10">
            <!-- Stats -->
            <div class="grid sm:grid-cols-3 gap-4">
                <div class="bg-secondary/40 p-6 border border-border">
                    <div class="eyebrow text-[10px] mb-1">Orders Completed</div>
                    <div class="font-display text-3xl font-semibold">{{ $recentOrders->count() }}</div>
                </div>
                <div class="bg-secondary/40 p-6 border border-border">
                    <div class="eyebrow text-[10px] mb-1">Saved In Wishlist</div>
                    <div class="font-display text-3xl font-semibold">{{ $wishlistCount }}</div>
                </div>
                <div class="bg-secondary/40 p-6 border border-border">
                    <div class="eyebrow text-[10px] mb-1">Loyalty Privileges</div>
                    <div class="font-display text-3xl font-semibold text-accent">2,480 pts</div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-background border border-border p-6 sm:p-8 shadow-soft">
                <div class="flex items-center justify-between pb-4 border-b border-border mb-6">
                    <h3 class="font-display text-2xl">Recent Orders</h3>
                    <span class="text-xs eyebrow text-muted-foreground">Order History</span>
                </div>

                @if($recentOrders->isEmpty())
                    <p class="text-sm text-muted-foreground py-6 text-center">
                        You have not placed any orders yet. Discover our newest collections to begin.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="text-xs uppercase tracking-wider text-muted-foreground border-b border-border pb-2">
                                <tr>
                                    <th class="py-3 font-medium">Order Number</th>
                                    <th class="py-3 font-medium">Date</th>
                                    <th class="py-3 font-medium">Payment</th>
                                    <th class="py-3 font-medium">Status</th>
                                    <th class="py-3 font-medium text-right">Grand Total</th>
                                    <th class="py-3 font-medium text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60">
                                @foreach($recentOrders as $ord)
                                    <tr>
                                        <td class="py-4 font-semibold text-foreground">
                                            #{{ $ord->order_number }}
                                        </td>
                                        <td class="py-4 text-muted-foreground text-xs">
                                            {{ $ord->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-4">
                                            <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded {{ $ord->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $ord->payment_status }}
                                            </span>
                                        </td>
                                        <td class="py-4">
                                            <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                                                {{ $ord->fulfillment_status }}
                                            </span>
                                        </td>
                                        <td class="py-4 font-semibold text-right">
                                            ₹{{ number_format($ord->grand_total, 0) }}
                                        </td>
                                        <td class="py-4 text-right">
                                            <a href="{{ route('orders.confirmation', $ord->order_number) }}" class="text-xs text-accent hover:underline">
                                                View Receipt
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Saved Address -->
            <div class="bg-background border border-border p-6 sm:p-8 shadow-soft">
                <div class="flex items-center justify-between pb-4 border-b border-border mb-4">
                    <h3 class="font-display text-2xl">Default Atelier Address</h3>
                    <span class="text-xs eyebrow text-muted-foreground">Primary Destination</span>
                </div>
                <div class="text-sm leading-relaxed text-muted-foreground">
                    <strong class="text-foreground text-base block mb-1">Aparna Menon</strong>
                    <div>Atelier House, Princess Street</div>
                    <div>Fort Kochi, Kerala 682001, India</div>
                    <div class="mt-2 text-foreground font-medium"><i class="ti ti-phone text-xs"></i> +91 98470 11223</div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
