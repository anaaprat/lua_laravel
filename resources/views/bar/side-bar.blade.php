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
        @if(auth()->user()->qr_path)
            <div class="qr-container">
                <a href="{{ asset('storage/' . auth()->user()->qr_path) }}" download="qr_bar_{{ auth()->user()->id }}.svg">
                    <img src="{{ asset('storage/' . auth()->user()->qr_path) }}" alt="QR Code" class="qr-img">
                </a>

                <p style="color: white; font-size: 12px; text-align: center; margin-top: 8px;">
                    Click on the QR to download it
                </p>
            </div>
        @endif

        <a href="{{ route('logout') }}" class="logout-link"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>