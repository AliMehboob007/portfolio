<!DOCTYPE html>
<html lang="en">

@php
  // Shared by the layouts.app view composer — declared here so the layout still
  // renders if it is ever included outside the normal request cycle.
  $navLinks    = $navLinks    ?? collect();
  $socialLinks = $socialLinks ?? collect();

  $headerLinks  = $navLinks->where('in_header', true);
  $footerLinks  = $navLinks->where('in_footer', true);
  $footerSocial = $socialLinks->where('in_footer', true);
  $socialBtns   = $socialLinks->where('is_social_btn', true);
@endphp

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="{{ setting('meta_description') }}">
  <meta name="keywords" content="{{ setting('meta_keywords') }}">
  <meta name="author" content="{{ setting('name') }}">
  <meta name="theme-color" content="#12161a" media="(prefers-color-scheme: dark)">
  <meta name="theme-color" content="#fbfaf8" media="(prefers-color-scheme: light)">

  <meta property="og:type" content="website">
  <meta property="og:title" content="@yield('title', setting('site_title'))">
  <meta property="og:description" content="{{ setting('og_description') }}">
  <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta name="twitter:card" content="summary_large_image">

  <title>@yield('title', setting('site_title'))</title>

  {{-- Apply the stored theme before first paint so there is no flash. --}}
  <script>
    (function () {
      try {
        var t = localStorage.getItem('theme');
        if (t === 'dark' || t === 'light') {
          document.documentElement.setAttribute('data-theme', t);
        }
      } catch (e) {}
    })();
  </script>

  {{-- One font request: Fraunces (display) · Inter Tight (body) · JetBrains Mono (meta) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500..700&family=Inter+Tight:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  @stack('styles')
</head>

<body>

  <!-- NAV -->
  <header class="nav-wrap" id="navbar">
    <nav class="nav-inner" aria-label="Primary">
      <a href="{{ route('home') }}" class="nav-logo" aria-label="{{ setting('name') }} — home">
        <span class="logo-text">{{ setting('nav_logo') }}</span>
        <span class="logo-dot"></span>
      </a>

      <ul class="nav-links" id="navLinks">
        @foreach($headerLinks as $link)
          <li><a href="{{ $link->href() }}" class="{{ $link->isActive() ? 'active' : '' }}">{{ $link->label }}</a></li>
        @endforeach
      </ul>

      <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch colour theme">
        <i class="fas fa-moon icon-moon" aria-hidden="true"></i>
        <i class="fas fa-sun icon-sun" aria-hidden="true"></i>
      </button>

      <a href="{{ route('home') }}#contact" class="nav-hire">{{ setting('nav_hire_label') }}</a>

      <button class="nav-hamburger" id="hamburger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobileOverlay">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </header>

  <!-- MOBILE MENU -->
  <div class="mobile-overlay" id="mobileOverlay" aria-hidden="true">
    <button class="mobile-close" id="mobileClose" type="button" aria-label="Close menu">✕</button>
    <ul>
      @foreach($headerLinks as $link)
        <li><a href="{{ $link->href() }}">{{ $link->label }}</a></li>
      @endforeach
      <li><a href="{{ route('home') }}#contact" class="mobile-hire">{{ setting('nav_hire_label') }} →</a></li>
    </ul>
  </div>

  <!-- PAGE CONTENT -->
  <main>
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-inner">
      <div class="footer-brand">
        <span class="logo-text">{{ setting('nav_logo') }}</span><span class="logo-dot"></span>
        <p>
          @foreach(setting_lines('footer_brand_desc') as $line)
            {{ $line }}@if(!$loop->last)<br>@endif
          @endforeach
        </p>
      </div>

      @if($footerLinks->count())
        <div class="footer-links">
          <h4>{{ setting('footer_nav_title') }}</h4>
          <ul>
            @foreach($footerLinks as $link)
              <li><a href="{{ $link->href() }}">{{ $link->label }}</a></li>
            @endforeach
          </ul>
        </div>
      @endif

      @if($footerSocial->count())
        <div class="footer-links">
          <h4>{{ setting('footer_connect_title') }}</h4>
          <ul>
            @foreach($footerSocial as $link)
              <li>
                <a href="{{ $link->url }}"
                   @if($link->isExternal()) target="_blank" rel="noopener noreferrer" @endif>{{ $link->label }}</a>
              </li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="footer-contact">
        <h4>{{ setting('footer_location_title') }}</h4>
        <p>{{ setting('location') }}</p>
        <p>{{ setting('footer_availability') }}</p>

        @if($socialBtns->count())
          <div class="footer-social">
            @foreach($socialBtns as $link)
              <a href="{{ $link->url }}"
                 @if($link->isExternal()) target="_blank" rel="noopener noreferrer" @endif
                 class="social-btn" aria-label="{{ $link->label }}">
                <i class="{{ $link->icon }}" aria-hidden="true"></i>
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <div class="footer-bottom">
      <p>© {{ date('Y') }} {{ setting('footer_copyright') }}</p>
    </div>
  </footer>

  <script src="{{ asset('js/app.js') }}?v=2"></script>
  @stack('scripts')
</body>

</html>
