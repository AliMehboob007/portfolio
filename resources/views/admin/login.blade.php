<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Sign in — Admin</title>

  <script>
    (function () {
      try {
        var t = localStorage.getItem('theme');
        if (t === 'dark' || t === 'light') document.documentElement.setAttribute('data-theme', t);
      } catch (e) {}
    })();
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500..700&family=Inter+Tight:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="login-body">

  <div class="login-wrap">

    <div class="brand">
      <span class="brand-logo">AA<span class="logo-dot"></span></span>
      <p>Portfolio Admin</p>
    </div>

    <div class="login-card">
      <h1>Sign in</h1>
      <p>Manage your projects, posts and enquiries.</p>

      @if($errors->any() || session('error'))
        <div class="alert alert-error" role="alert">
          <i class="fas fa-triangle-exclamation" aria-hidden="true"></i>
          <span>{{ $errors->first() ?: session('error') }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm">
        @csrf

        <div class="form-group">
          <label for="email">Email address</label>
          <div class="input-wrap">
            <i class="fas fa-envelope input-icon" aria-hidden="true"></i>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   placeholder="you@example.com" required autofocus autocomplete="username">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <i class="fas fa-lock input-icon" aria-hidden="true"></i>
            <input type="password" id="password" name="password"
                   placeholder="Enter your password" required autocomplete="current-password">
            <button type="button" class="pw-toggle" id="pwToggle" aria-label="Show password">
              <i class="fas fa-eye" aria-hidden="true"></i>
            </button>
          </div>
        </div>

        <div class="form-footer">
          <label class="remember">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))> Remember me
          </label>
        </div>

        <button type="submit" class="btn-login" id="loginBtn">
          <span id="btnText">Sign in</span>
          <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </button>
      </form>

      <div class="divider">Secure area</div>

      <div class="back-link">
        <a href="{{ route('home') }}">← Back to portfolio</a>
      </div>
    </div>

    <div class="security-badge">
      <i class="fas fa-shield-halved" aria-hidden="true"></i> Protected area
    </div>

  </div>

  <script>
    (function () {
      var toggle = document.getElementById('pwToggle');
      var input = document.getElementById('password');
      if (toggle && input) {
        toggle.addEventListener('click', function () {
          var show = input.type === 'password';
          input.type = show ? 'text' : 'password';
          toggle.innerHTML = show
            ? '<i class="fas fa-eye-slash" aria-hidden="true"></i>'
            : '<i class="fas fa-eye" aria-hidden="true"></i>';
          toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
      }

      var form = document.getElementById('loginForm');
      var btn = document.getElementById('loginBtn');
      var text = document.getElementById('btnText');
      if (form && btn) {
        form.addEventListener('submit', function () {
          btn.disabled = true;
          text.textContent = 'Signing in…';
        });
      }
    })();
  </script>

</body>

</html>
