@extends('layouts.storefront')

@section('title', ($mode === 'login' ? 'Sign In' : 'Create Account') . ' — ManaCouture')

@section('content')
<div class="min-h-[80vh] grid lg:grid-cols-2">
    <!-- Editorial Visual Column -->
    <div class="hidden lg:block relative bg-secondary min-h-full">
        <img
            src="{{ asset('images/manacoture/hero-saree.jpg') }}"
            alt="ManaCouture Atelier Visual"
            class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-primary/30"></div>
        <div class="absolute bottom-16 left-16 text-primary-foreground max-w-sm">
            <div class="eyebrow text-gold-soft mb-3">The Atelier</div>
            <div class="font-display text-4xl leading-tight">
                Sign in to unlock private previews & atelier appointments.
            </div>
        </div>
    </div>

    <!-- Auth Form Column -->
    <div class="flex items-center justify-center p-8 lg:p-16">
        <div class="w-full max-w-md space-y-8">
            <div>
                <a href="{{ route('home') }}" class="font-display text-3xl">ManaCouture</a>
                <h1 class="font-display text-3xl md:text-4xl mt-8">
                    {{ $mode === 'login' ? 'Welcome Back' : 'Join The Atelier' }}
                </h1>
                <p class="text-sm text-muted-foreground mt-2">
                    {{ $mode === 'login' ? 'Access your patron orders, wishlist & loyalty rewards.' : 'Create an account for personalized recommendations and private previews.' }}
                </p>
            </div>

            <form action="{{ route('account') }}" method="GET" class="space-y-4">
                @if($mode === 'register')
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-muted-foreground mb-1 font-medium">Full Name</label>
                        <input type="text" required placeholder="Aparna Menon" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                    </div>
                @endif

                <div>
                    <label class="block text-xs uppercase tracking-wider text-muted-foreground mb-1 font-medium">Email Address</label>
                    <input type="email" required placeholder="you@example.com" value="aparna.menon@example.com" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-wider text-muted-foreground mb-1 font-medium">Password</label>
                    <input type="password" required value="password" class="w-full h-12 px-4 bg-secondary/30 border border-border focus:border-accent outline-none text-sm">
                </div>

                <button type="submit" class="btn-luxe w-full py-3.5 tracking-[0.2em] shadow-md">
                    {{ $mode === 'login' ? 'Sign In To Atelier' : 'Create Account' }}
                </button>
            </form>

            <div class="pt-6 border-t border-border flex items-center justify-between text-xs text-muted-foreground">
                @if($mode === 'login')
                    <span>Don't have an account?</span>
                    <a href="{{ route('customer.auth', ['mode' => 'register']) }}" class="text-foreground font-semibold hover:text-accent underline">Create one</a>
                @else
                    <span>Already a patron?</span>
                    <a href="{{ route('customer.auth', ['mode' => 'login']) }}" class="text-foreground font-semibold hover:text-accent underline">Sign in</a>
                @endif
            </div>

            <!-- Admin Login Link -->
            <div class="p-4 bg-secondary/50 border border-border text-center text-xs">
                <span class="text-muted-foreground">Store administrator?</span>
                <a href="{{ route('login') }}" class="text-accent font-semibold hover:underline ml-1">Admin Portal Sign In →</a>
            </div>
        </div>
    </div>
</div>
@endsection
