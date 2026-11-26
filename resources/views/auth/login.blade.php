<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in · Afterhours Arcade</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/arcade.css') }}">
</head>
<body class="login-body">
    <div class="app-noise"></div>
    <div class="login-grid">
        <section class="login-art" style="background-image: linear-gradient(180deg, rgba(11,11,18,.22), rgba(11,11,18,.94)), url('{{ asset('media/arcade-hero.jpg') }}');">
            <div class="login-art-copy"><div class="brand brand-light"><span class="brand-mark"><span></span><span></span><span></span></span><span><strong>afterhours</strong><small>arcade / pos</small></span></div><p>Keep the floor moving.<br><em>Leave the lights on.</em></p><div class="scanline"></div><span class="mono art-caption">SESSION 00:00:00 · TERMINAL 01</span></div>
        </section>
        <section class="login-panel">
            <div class="login-inner">
                <div class="login-kicker"><span class="online-dot"></span> STAFF ACCESS / MIDNIGHT SHIFT</div>
                <h1>The space between<br><em>insert coin</em> and sold out.</h1>
                <p class="lede">Sign in to run the floor, record sales, and keep every last token accounted for.</p>
                @if(session('success'))<div class="flash flash-success" role="status">{{ session('success') }}</div>@endif
                <form method="POST" action="{{ route('login.store') }}" class="auth-form">
                    @csrf
                    <label class="field-label" for="username">Username</label>
                    <input class="field-input" id="username" name="username" type="text" value="{{ old('username') }}" placeholder="arcade.admin" autocomplete="username" required autofocus>
                    @error('username')<span class="field-error">{{ $message }}</span>@enderror
                    <label class="field-label" for="password">Password</label>
                    <input class="field-input" id="password" name="password" type="password" placeholder="8 characters minimum" autocomplete="current-password" required>
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                    <label class="check-row"><input type="checkbox" name="remember" value="1"><span>Keep me signed in on this terminal</span></label>
                    <button class="button button-primary button-wide" type="submit">Unlock shift @include('components.icon', ['name' => 'arrow'])</button>
                </form>
                <div class="login-foot"><span>LAB BUILD / LARAVEL</span><span>v1.0.0</span></div>
            </div>
        </section>
    </div>
</body>
</html>
