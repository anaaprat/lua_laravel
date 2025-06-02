<aside>
    <div class="sidebar-header">
        <img src="{{ asset('storage/images/lualogo.jpeg') }}" alt="Lua Logo" class="sidebar-logo">
        <div class="bar-sidebar-name">{{ auth()->user()->name }}</div>
        <div class="bar-role">Bar Manager</div>
    </div>

    <div class="nav-links">
        <a href="{{ route('bar.account') }}" class="{{ request()->routeIs('bar.account') ? 'active' : '' }}">
            <i class="fas fa-user-cog"></i>
            <span>My Account</span>
        </a>
        <a href="{{ route('bar.dashboard') }}" class="{{ request()->routeIs('bar.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('bar-products.index') }}" class="{{ request()->routeIs('bar-products.*') ? 'active' : '' }}">
            <i class="fas fa-cocktail"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('bar.statistics') }}" class="{{ request()->routeIs('bar.statistics') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Statistics</span>
        </a>
        <a href="{{ route('bar.rechargesUser') }}"
            class="{{ request()->routeIs('bar.rechargesUser') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i>
            <span>Recharge user's credit</span>
        </a>
    </div>

    <div class="bottom-section">
        @if(session('success'))
            <div class="alert alert-success" style="font-size: 12px; margin-bottom: 10px;">
                {{ session('success') }}
            </div>
        @endif

        @if(auth()->user()->qr_path)
            <div class="qr-container">
                <a href="{{ asset('storage/' . auth()->user()->qr_path) }}" download="qr_bar_{{ auth()->user()->id }}.svg">
                    <img src="{{ asset('storage/' . auth()->user()->qr_path) }}" alt="QR Code" class="qr-img">
                </a>
                <p style="color: white; font-size: 12px; text-align: center; margin-top: 8px;">
                    Click on the QR to download it
                </p>

                <!-- Botón discreto de regenerar -->
                <form action="{{ route('regenerate-qr') }}" method="POST" style="text-align: center; margin-top: 5px;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light" style="font-size: 10px;">
                        🔄 Regenerate
                    </button>
                </form>
            </div>
        @else
            <div class="qr-container">
                <p style="color: white; text-align: center;">QR not available</p>
                <form action="{{ route('regenerate-qr') }}" method="POST" style="text-align: center;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">Generate QR</button>
                </form>
            </div>
        @endif

        <a href="#" class="logout-link" onclick="return confirmLogout()">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>

<script>
    function confirmLogout() {
        if (confirm('Are you sure you want to logout?\n\nYour current session will be closed and you will need to log in again.')) {
            document.getElementById('logout-form').submit();
            return false;
        }
        return false;
    }
</script>