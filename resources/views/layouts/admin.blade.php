<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>@yield('admin-title', 'Dashboard') — Admin</title>

  {{-- Apply stored theme before first paint --}}
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
  @stack('styles')
</head>

<body>

  <div class="sidebar-scrim" id="sidebarScrim"></div>

  <div class="admin-shell">

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-logo">
        <span class="logo-line">
          <span class="logo-text">{{ setting('initials') }}</span><span class="logo-dot"></span>
        </span>
        <p>Admin Panel</p>
      </div>

      <nav aria-label="Admin sections">
        <div class="nav-section">Overview</div>
        <ul>
          <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <i class="fas fa-chart-simple nav-icon" aria-hidden="true"></i> Dashboard
            </a>
          </li>
        </ul>

        <div class="nav-section">Content</div>
        <ul>
          <li>
            <a href="{{ route('admin.projects') }}" class="{{ request()->routeIs('admin.projects*') ? 'active' : '' }}">
              <i class="fas fa-folder-open nav-icon" aria-hidden="true"></i> Projects
            </a>
          </li>
          <li>
            <a href="{{ route('admin.blog') }}" class="{{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
              <i class="fas fa-pen-nib nav-icon" aria-hidden="true"></i> Blog Posts
            </a>
          </li>
          <li>
            <a href="{{ route('admin.testimonials') }}" class="{{ request()->routeIs('admin.testimonials') ? 'active' : '' }}">
              <i class="fas fa-quote-left nav-icon" aria-hidden="true"></i> Testimonials
            </a>
          </li>
        </ul>

        <div class="nav-section">Site Content</div>
        <ul>
          @foreach(\App\Http\Controllers\ContentController::menu() as $entry)
            <li>
              <a href="{{ route('admin.content.index', $entry['key']) }}"
                 class="{{ request()->is('admin/content/' . $entry['key'] . '*') ? 'active' : '' }}">
                <i class="{{ $entry['icon'] }} nav-icon" aria-hidden="true"></i> {{ $entry['label'] }}
              </a>
            </li>
          @endforeach
        </ul>

        <div class="nav-section">Inbox</div>
        <ul>
          <li>
            <a href="{{ route('admin.messages') }}" class="{{ request()->routeIs('admin.messages') ? 'active' : '' }}">
              <i class="fas fa-inbox nav-icon" aria-hidden="true"></i> Messages
              @if(!empty($unreadCount))
                <span class="nav-count">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
              @endif
            </a>
          </li>
        </ul>

        <div class="nav-section">Site</div>
        <ul>
          <li>
            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
              <i class="fas fa-sliders nav-icon" aria-hidden="true"></i> Settings &amp; Text
            </a>
          </li>
          <li>
            <a href="{{ route('home') }}" target="_blank" rel="noopener">
              <i class="fas fa-arrow-up-right-from-square nav-icon" aria-hidden="true"></i> View Portfolio
            </a>
          </li>
        </ul>
      </nav>

      <div class="sidebar-bottom">
        <div class="sidebar-user">
          <span class="avatar" aria-hidden="true">{{ setting('initials') }}</span>
          <span>
            <strong>{{ setting('name') }}</strong>
            <small>Administrator</small>
          </span>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit"><i class="fas fa-power-off" aria-hidden="true"></i> Log out</button>
        </form>
      </div>
    </aside>

    {{-- ── MAIN ── --}}
    <div class="admin-main">
      <header class="admin-topbar">
        <button type="button" class="icon-btn sidebar-toggle" id="sidebarToggle" aria-label="Open menu" aria-expanded="false" aria-controls="sidebar">
          <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        <h1>@yield('admin-title', 'Dashboard')</h1>

        <div class="topbar-actions">
          <button type="button" class="icon-btn" id="themeToggle" aria-label="Switch colour theme">
            <i class="fas fa-moon icon-moon" aria-hidden="true"></i>
            <i class="fas fa-sun icon-sun" aria-hidden="true"></i>
          </button>
          <a href="{{ route('home') }}" target="_blank" rel="noopener" class="icon-btn" aria-label="View portfolio in a new tab">
            <i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i>
          </a>
        </div>
      </header>

      <div class="admin-content">
        @if(session('success'))
          <div class="alert alert-success" role="status">
            <i class="fas fa-check" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-error" role="alert">
            <i class="fas fa-triangle-exclamation" aria-hidden="true"></i>
            <span>{{ session('error') }}</span>
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-error" role="alert">
            <i class="fas fa-triangle-exclamation" aria-hidden="true"></i>
            <span>
              @foreach($errors->all() as $e)
                {{ $e }}@if(!$loop->last)<br>@endif
              @endforeach
            </span>
          </div>
        @endif

        @yield('admin-content')
      </div>
    </div>

  </div>

  <script>
    (function () {
      // ── Theme ──
      var themeBtn = document.getElementById('themeToggle');
      if (themeBtn) {
        themeBtn.addEventListener('click', function () {
          var root = document.documentElement;
          var current = root.getAttribute('data-theme');
          if (!current) {
            current = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
          }
          var next = current === 'dark' ? 'light' : 'dark';
          root.setAttribute('data-theme', next);
          try { localStorage.setItem('theme', next); } catch (e) {}
        });
      }

      // ── Sidebar drawer (mobile) ──
      var sidebar = document.getElementById('sidebar');
      var scrim = document.getElementById('sidebarScrim');
      var toggle = document.getElementById('sidebarToggle');

      function closeSidebar() {
        sidebar.classList.remove('open');
        scrim.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }

      if (sidebar && scrim && toggle) {
        toggle.addEventListener('click', function () {
          var open = sidebar.classList.toggle('open');
          scrim.classList.toggle('open', open);
          toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        scrim.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape' && sidebar.classList.contains('open')) closeSidebar();
        });
      }

      // ── Image upload preview ──
      document.querySelectorAll('input[type="file"]').forEach(function (input) {
        input.addEventListener('change', function () {
          var group = this.closest('.form-group');
          var preview = group ? group.querySelector('.img-preview') : null;
          if (preview && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              preview.src = e.target.result;
              preview.style.display = 'block';
            };
            reader.readAsDataURL(this.files[0]);
          }
        });
      });

      // ── Confirm destructive actions ──
      document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
          if (!window.confirm(form.dataset.confirm)) e.preventDefault();
        });
      });
    })();
  </script>

  @stack('scripts')
</body>

</html>
