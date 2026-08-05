{{--
  Shared shell for 403 / 404 / 419 / 503.
  Extends the public layout so a lost visitor keeps the nav and footer and
  can actually get somewhere. 500 deliberately does NOT use this — see
  errors/500.blade.php for why.
--}}
@extends('layouts.app')
@section('title', $title . ' — Muhammad Ali')

@section('content')

    <section class="error-page">
        <div class="container">
            <div class="error-inner">

                <div class="error-code">
                    {{ $code }}
                    <span>{{ $label }}</span>
                </div>

                <div class="error-body">
                    <h1 class="error-title">{{ $title }}</h1>
                    <p class="error-desc">{{ $message }}</p>

                    <div class="error-actions">
                        @yield('actions')
                    </div>

                    <div class="error-links">
                        <h2>Try one of these</h2>
                        <ul>
                            <li>
                                <a href="{{ route('home') }}">
                                    <i class="fas fa-house" aria-hidden="true"></i> Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('projects.index') }}">
                                    <i class="fas fa-folder-open" aria-hidden="true"></i> Projects
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('blog.index') }}">
                                    <i class="fas fa-pen-nib" aria-hidden="true"></i> Blog
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('home') }}#contact">
                                    <i class="fas fa-envelope" aria-hidden="true"></i> Contact
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
