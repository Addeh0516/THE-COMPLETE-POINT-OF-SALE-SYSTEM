<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Afterhours Arcade' }} · Afterhours Arcade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/arcade.css') }}">
</head>
<body class="app-body">
    <div class="app-noise"></div>
    <div class="app-frame">
        <aside class="sidebar" id="sidebar">
            <a class="brand" href="{{ route('dashboard') }}" aria-label="Afterhours Arcade dashboard">
                <span class="brand-mark"><span></span><span></span><span></span></span>
                <span><strong>afterhours</strong><small>arcade / pos</small></span>
            </a>
            <div class="rail-label">Operator console</div>
            <nav class="nav-stack" aria-label="Main navigation">
                <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">@include('components.icon', ['name' => 'grid'])<span>Floor view</span></a>
                <a class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}">@include('components.icon', ['name' => 'receipt'])<span>Sales history</span></a>
                <a class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">@include('components.icon', ['name' => 'box'])<span>Products</span></a>
                <a class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">@include('components.icon', ['name' => 'users'])<span>Customers</span></a>
                <a class="nav-item {{ request()->routeIs('staff.*') ? 'active' : '' }}" href="{{ route('staff.index') }}">@include('components.icon', ['name' => 'spark'])<span>Staff</span></a>
            </nav>
            <div class="sidebar-bottom">
                <div class="permit-label"><span class="status-dot"></span> FEU pilot <b>ACTIVE</b></div>
                <div class="operator-card">
                    <div class="avatar avatar-sm">{{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}</div>
                    <div><strong>{{ auth()->user()->full_name }}</strong><span>@ {{ auth()->user()->username }}</span></div>
                    <form method="POST" action="{{ route('logout') }}" novalidate><button class="icon-button" type="submit" aria-label="Log out">@include('components.icon', ['name' => 'logout'])</button></form>
                </div>
            </div>
        </aside>
        <div class="app-main">
            <header class="topbar">
                <button class="mobile-menu" type="button" aria-label="Open navigation" aria-controls="sidebar" aria-expanded="false" data-sidebar-toggle>@include('components.icon', ['name' => 'grid'])</button>
                <div class="crumbs"><span>AFTERHOURS</span><i>/</i><strong>{{ $section ?? 'FLOOR VIEW' }}</strong></div>
                <div class="topbar-meta"><span class="online-dot"></span> Terminal 01 <span class="topbar-divider"></span><span class="mono">{{ now()->format('D · H:i') }}</span></div>
            </header>
            <main class="content-canvas">
                @if(session('success'))<div class="flash flash-success" role="status">@include('components.icon', ['name' => 'spark'])<span>{{ session('success') }}</span></div>@endif
                @if(session('error'))<div class="flash flash-error" role="alert">@include('components.icon', ['name' => 'pin'])<span>{{ session('error') }}</span></div>@endif
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        const toggle = document.querySelector('[data-sidebar-toggle]');
        const sidebar = document.getElementById('sidebar');
        toggle?.addEventListener('click', () => { const open = sidebar.classList.toggle('open'); toggle.setAttribute('aria-expanded', open); });
    </script>
</body>
</html>
