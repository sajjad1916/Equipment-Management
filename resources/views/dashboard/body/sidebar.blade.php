
<nav class="sidenav shadow-right sidenav-light">
    <div class="sidenav-menu">
        <div class="nav accordion" id="accordionSidenav">
            <!-- Sidenav Menu Heading (Core)-->
            <div class="sidenav-menu-heading">Core</div>
            <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <div class="nav-link-icon"><i data-feather="activity"></i></div>
                Dashboard
            </a>

             <!-- Sidenav Heading (Products)-->
             <div class="sidenav-menu-heading">Warehousing</div>
            <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                Equipment
            </a>
            <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                Categories
            </a>
            <a class="nav-link {{ Request::is('units*') ? 'active' : '' }}" href="{{ route('units.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-folder"></i></div>
                Units
            </a>

            <!-- Sidenav Heading (Orders)-->
            <div class="sidenav-menu-heading">Procurement</div>
            <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{{ route('products.create') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-circle-check"></i></div>
                Procurement Application
            </a>
            <!-- Sidenav Heading (Purchases)-->
            <div class="sidenav-menu-heading">Maintenance</div>
            <a class="nav-link {{ Request::is('maintenance*') ? 'active' : '' }}" href="{{ route('maintenance') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-cash-register"></i></div>
                Equipment Maintenance
            </a>
            

             <!-- Sidenav Heading (Orders)-->
             <div class="sidenav-menu-heading">Scrapped</div>
            <a class="nav-link {{ Request::is('scrapped*') ? 'active' : '' }}" href="{{ route('scrapped') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-circle-check"></i></div>
                Scrapped equipment
            </a>


            <!-- Sidenav Heading (Settings)-->
            <div class="sidenav-menu-heading">Settings</div>
            <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                Users
            </a>
        </div>
    </div>

    <!-- Sidenav Footer-->
    <div class="sidenav-footer">
        <div class="sidenav-footer-content">
            <div class="sidenav-footer-subtitle">Logged in as:</div>
            <div class="sidenav-footer-title">{{ auth()->user()->name }}</div>
        </div>
    </div>
</nav>
