<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <!-- Logo -->
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}" style="font-size: 30px;">
        <i class="fa-solid fa-chart-pie text-primary"></i>
    </a>

    <!-- Hamburger Button (for mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse d-flex justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
            <!-- Dashboard Link -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    {{ __('Dashboard') }}
                </a>
            </li>


            <!-- User Management Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user-pen"></i> Benutzer verwalten
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fa-solid fa-arrow-right"></i> Benutzer verwalten</a></li>
                    <li><a class="dropdown-item" href="{{ route('roles.index') }}"><i class="fa-solid fa-arrow-right"></i> Rollen verwalten</a></li>
                </ul>
            </li>

            <!-- Rundmail Link -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('email.form') ? 'active' : '' }}" href="{{ route('email.form') }}">
                    <i class="fa-solid fa-envelope"></i> {{ __('Rundmail senden') }}
                </a>
            </li>

            <!-- Statistics Link -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('statistics.index') ? 'active' : '' }}" href="{{ route('statistics.index') }}">
                    <i class="fa-solid fa-chart-column"></i> {{ __('Datenbank Statistik anzeigen') }}
                </a>
            </li>

            <!-- Tools Link -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tools.index') ? 'active' : '' }}" href="{{ route('tools.index') }}">
                    <i class="fa-solid fa-gear"></i> {{ __('Tools verwalten') }}
                </a>
            </li>

            <!-- Maintenance Mode Link -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('maintenance.mode') ? 'active' : '' }}" href="{{ route('maintenance.mode') }}">
                    <i class="fa-solid fa-toggle-off"></i> {{ __('Wartungsmodus an/aus') }}
                </a>
            </li>
        </ul>
    </div>

    <!-- Authentication Dropdown (aligned right) -->
    <div class="navbar-nav ms-auto">
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="authDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->email }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="authDropdown">
                <li>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="dropdown-item" type="submit">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
