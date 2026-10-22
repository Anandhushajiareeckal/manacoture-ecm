<header class="app-topbar">
    <div class="container-fluid topbar-menu">
        <div class="d-flex align-items-center gap-2">
            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <a href="{{ route('admin.dashboard') }}" class="logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" />
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" />
                    </span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="logo-dark">
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-black.png') }}" alt="dark logo" />
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="small logo" />
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="sidenav-toggle-button btn btn-default btn-icon">
                <i class="ti ti-menu-4"></i>
            </button>

            <form action="{{ route('admin.orders.index') }}" method="GET" class="app-search d-none d-xl-flex">
                <input type="search" class="form-control topbar-search" name="search" placeholder="Search orders, customers, SKU..." />
                <i class="ti ti-search app-search-icon text-muted"></i>
            </form>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Theme Toggler (Dark/Light) -->
            <div id="theme-toggler" class="topbar-item d-none d-sm-flex">
                <button class="topbar-link" id="light-dark-mode" type="button">
                    <i class="ti ti-moon topbar-link-icon mode-light-moon"></i>
                    <i class="ti ti-sun topbar-link-icon mode-light-sun"></i>
                </button>
            </div>

            <!-- Fullscreen Toggler -->
            <div id="fullscreen-toggler" class="topbar-item d-none d-md-flex">
                <button class="topbar-link" type="button" data-toggle="fullscreen">
                    <i class="ti ti-maximize topbar-link-icon"></i>
                    <i class="ti ti-minimize topbar-link-icon d-none"></i>
                </button>
            </div>

            <!-- Quick Links -->
            <div class="topbar-item">
                <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary d-none d-sm-inline-flex align-items-center">
                    <i class="ti ti-plus me-1"></i> Add Product
                </a>
            </div>

            <!-- User Dropdown -->
            <div id="simple-user-dropdown" class="topbar-item nav-user">
                <div class="dropdown">
                    <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown" href="#!" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ auth()->user()->avatar_url }}" width="32" height="32" class="rounded-circle me-lg-2 d-flex object-fit-cover" alt="{{ auth()->user()->name }}" />
                        <div class="d-lg-flex align-items-center gap-1 d-none text-start">
                            <div>
                                <h6 class="my-0 fs-sm">{{ auth()->user()->name }}</h6>
                                <span class="fs-xs text-muted">{{ auth()->user()->role?->name ?? 'Administrator' }}</span>
                            </div>
                            <i class="ti ti-chevron-down align-middle ms-1"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome, {{ auth()->user()->name }}!</h6>
                        </div>

                        <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                            <i class="ti ti-user-circle me-1 fs-lg align-middle"></i>
                            <span class="align-middle">My Profile</span>
                        </a>

                        <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                            <i class="ti ti-settings-2 me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Store Settings</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger fw-semibold">
                                <i class="ti ti-logout me-1 fs-lg align-middle"></i>
                                <span class="align-middle">Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
