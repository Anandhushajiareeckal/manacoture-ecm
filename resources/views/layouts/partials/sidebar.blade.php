<div class="sidenav-menu">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="logo">
        <span class="logo logo-light">
            <span class="logo-lg"><img src="{{ asset('assets/images/logo.png') }}" alt="logo" /></span>
            <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" /></span>
        </span>

        <span class="logo logo-dark">
            <span class="logo-lg"><img src="{{ asset('assets/images/logo-black.png') }}" alt="dark logo" /></span>
            <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" /></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-on-hover">
        <span class="btn-on-hover-icon"></span>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-offcanvas">
        <i class="ti ti-menu-4 align-middle"></i>
    </button>

    <div class="scrollbar" data-simplebar="">
        <div id="user-profile-settings" class="sidenav-user">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ auth()->user()->avatar_url }}" alt="avatar" class="rounded-circle avatar-sm object-fit-cover" />
                    <div>
                        <h6 class="mb-0 text-white fs-sm">{{ auth()->user()->name }}</h6>
                        <small class="text-white-50">{{ auth()->user()->role?->name ?? 'Admin' }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <ul class="side-nav">
            <li class="side-nav-title">Main</li>

            <li class="side-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <li class="side-nav-title mt-2">Catalog</li>

            <li class="side-nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#menuProducts" aria-expanded="{{ request()->routeIs('admin.products.*') ? 'true' : 'false' }}" aria-controls="menuProducts" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-package"></i></span>
                    <span class="menu-text">Products</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.products.*') ? 'show' : '' }}" id="menuProducts">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.products.index') }}" class="side-nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                <span class="menu-text">All Products</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.products.create') }}" class="side-nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                <span class="menu-text">Add New Product</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-category"></i></span>
                    <span class="menu-text">Categories</span>
                </a>
            </li>

            <li class="side-nav-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                <a href="{{ route('admin.brands.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-tag"></i></span>
                    <span class="menu-text">Brands</span>
                </a>
            </li>

            <li class="side-nav-item {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
                <a href="{{ route('admin.attributes.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-adjustments-horizontal"></i></span>
                    <span class="menu-text">Attributes & Variants</span>
                </a>
            </li>

            <li class="side-nav-title mt-2">Sales & Orders</li>

            <li class="side-nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#menuOrders" aria-expanded="{{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }}" aria-controls="menuOrders" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-shopping-cart"></i></span>
                    <span class="menu-text">Orders</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.orders.*') ? 'show' : '' }}" id="menuOrders">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.orders.index') }}" class="side-nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                                <span class="menu-text">Orders List</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.orders.create') }}" class="side-nav-link {{ request()->routeIs('admin.orders.create') ? 'active' : '' }}">
                                <span class="menu-text">Create Manual Order</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
                <a href="{{ route('admin.returns.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-rotate-clockwise"></i></span>
                    <span class="menu-text">Returns</span>
                </a>
            </li>

            <li class="side-nav-title mt-2">Customers</li>

            <li class="side-nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#menuCustomers" aria-expanded="{{ request()->routeIs('admin.customers.*') ? 'true' : 'false' }}" aria-controls="menuCustomers" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-users"></i></span>
                    <span class="menu-text">Customers</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.customers.*') ? 'show' : '' }}" id="menuCustomers">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.customers.index') }}" class="side-nav-link {{ request()->routeIs('admin.customers.index') ? 'active' : '' }}">
                                <span class="menu-text">All Customers</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.customers.create') }}" class="side-nav-link {{ request()->routeIs('admin.customers.create') ? 'active' : '' }}">
                                <span class="menu-text">Add Customer</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-title mt-2">Operations</li>

            <li class="side-nav-item {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#menuInventory" aria-expanded="{{ request()->routeIs('admin.inventory.*') ? 'true' : 'false' }}" aria-controls="menuInventory" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-building-warehouse"></i></span>
                    <span class="menu-text">Inventory</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.inventory.*') ? 'show' : '' }}" id="menuInventory">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.inventory.index') }}" class="side-nav-link {{ request()->routeIs('admin.inventory.index') ? 'active' : '' }}">
                                <span class="menu-text">Stock Levels</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.inventory.movements') }}" class="side-nav-link {{ request()->routeIs('admin.inventory.movements') ? 'active' : '' }}">
                                <span class="menu-text">Movement Log</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}">
                <a data-bs-toggle="collapse" href="#menuShipping" aria-expanded="{{ request()->routeIs('admin.shipping.*') ? 'true' : 'false' }}" aria-controls="menuShipping" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-truck"></i></span>
                    <span class="menu-text">Shipping & Delivery</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.shipping.*') ? 'show' : '' }}" id="menuShipping">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a href="{{ route('admin.shipping.shipments') }}" class="side-nav-link {{ request()->routeIs('admin.shipping.shipments') ? 'active' : '' }}">
                                <span class="menu-text">Shipments</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="{{ route('admin.shipping.methods') }}" class="side-nav-link {{ request()->routeIs('admin.shipping.methods') ? 'active' : '' }}">
                                <span class="menu-text">Shipping Methods</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-title mt-2">Marketing</li>

            <li class="side-nav-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <a href="{{ route('admin.coupons.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-discount-2"></i></span>
                    <span class="menu-text">Coupons & Discounts</span>
                </a>
            </li>

            <li class="side-nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <a href="{{ route('admin.reviews.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-star"></i></span>
                    <span class="menu-text">Customer Reviews</span>
                </a>
            </li>

            <li class="side-nav-title mt-2">Reports & System</li>

            <li class="side-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.sales') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-chart-bar"></i></span>
                    <span class="menu-text">Sales Analytics</span>
                </a>
            </li>

            <li class="side-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-settings"></i></span>
                    <span class="menu-text">Store Settings</span>
                </a>
            </li>

            <li class="side-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="ti ti-shield-lock"></i></span>
                    <span class="menu-text">Staff & Roles</span>
                </a>
            </li>
        </ul>
    </div>
</div>
