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
                <p style="font-size:10px; color:white;">
                    Path: {{ auth()->user()->qr_path }}<br>
                    URL: {{ asset('storage/' . auth()->user()->qr_path) }}
                </p>

                <img src="{{ asset('storage/' . auth()->user()->qr_path) }}" alt="QR Code" class="qr-img">
<!-- 
                <form action="{{ route('regenerate-qr') }}" method="POST" style="margin-top:10px;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary">Regenerate QR</button>
                </form> -->
            </div>
        @else
            <div class="qr-container">
                <p>QR not generated</p>
                <form action="{{ route('regenerate-qr') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Generate QR</button>
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