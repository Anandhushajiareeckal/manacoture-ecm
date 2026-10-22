<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                &copy; {{ date('Y') }} <strong>INSPINIA E-Commerce Admin</strong> — Handcrafted for perfection.
            </div>
            <div class="col-md-6">
                <div class="text-md-end footer-links d-none d-md-block">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.settings.index') }}">Settings</a>
                    <a href="{{ route('admin.orders.index') }}">Orders</a>
                </div>
            </div>
        </div>
    </div>
</footer>
