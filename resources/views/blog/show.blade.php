@extends('layouts.app')
@section('title', $post->title . ' — Muhammad Ali Blog')

@section('content')

    <section class="page-hero page-hero-sm">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('blog.index') }}">Blog</a>
                <span>/</span>
                <span>{{ Str::limit($post->title, 40) }}</span>
            </div>
        </div>
    </section>

    <section class="blog-post-page section-pad">
        <div class="container">
            <div class="bp-grid">

                {{-- ── POST ── --}}
                <article class="bp-content">
                    <span class="blog-category-tag">{{ $post->category }}</span>

                    <h1 class="bp-title">{{ $post->title }}</h1>

                    <div class="bp-meta">
                        <span>{{ $post->created_at->format('F d, Y') }}</span>
                        <span>{{ $post->read_time ?? 5 }} min read</span>
                        <span>Muhammad Ali</span>
                    </div>

                    @if ($post->image)
                        <div class="bp-hero-img">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                        </div>
                    @endif

                    <div class="bp-body">
                        {!! nl2br(e($post->body)) !!}
                    </div>

                    <div class="bp-author">
                        <div class="bp-author-avatar" aria-hidden="true">{{ setting('initials') }}</div>
                        <div>
                            <strong>{{ setting('name') }}</strong>
                            <p>{{ setting('author_bio') }}</p>
                            <div class="bp-author-links">
                                @if (setting('linkedin'))
                                    <a href="{{ setting('linkedin') }}" target="_blank" rel="noopener noreferrer">LinkedIn
                                        ↗</a>
                                @endif
                                @if (setting('github'))
                                    <a href="{{ setting('github') }}" target="_blank" rel="noopener noreferrer">GitHub
                                        ↗</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>

                {{-- ── SIDEBAR ── --}}
                <aside class="bp-sidebar">

                    <div class="bp-widget">
                        <h4>Share Article</h4>
                        <div class="bp-widget-stack">
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                                target="_blank" rel="noopener noreferrer" class="share-btn">Share on LinkedIn</a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&amp;text={{ urlencode($post->title) }}"
                                target="_blank" rel="noopener noreferrer" class="share-btn">Share on X</a>
                            <button type="button" class="share-btn" id="copyLinkBtn">Copy Link</button>
                        </div>
                    </div>

                    <div class="bp-widget">
                        <h4>About the Author</h4>
                        <div class="bp-author-card">
                            <div class="bp-author-avatar" aria-hidden="true">AA</div>
                            <strong>Muhammad Ali</strong>
                            <p>Senior Full-Stack Developer with 3+ years experience in Laravel &amp; Vue.js.</p>
                            <a href="{{ route('home') }}#contact" class="btn-primary">Hire Me →</a>
                        </div>
                    </div>

                    @if ($related->count() > 0)
                        <div class="bp-widget">
                            <h4>Related Articles</h4>
                            @foreach ($related as $r)
                                <a href="{{ route('blog.show', $r->slug) }}" class="related-post">
                                    @if ($r->image)
                                        <img src="{{ asset('storage/' . $r->image) }}" alt="" loading="lazy">
                                    @else
                                        <div class="rp-placeholder" aria-hidden="true">✍</div>
                                    @endif
                                    <div>
                                        <span>{{ $r->category }}</span>
                                        <p>{{ Str::limit($r->title, 55) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </aside>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        (function() {
            var btn = document.getElementById('copyLinkBtn');
            if (!btn) return;
            btn.addEventListener('click', function() {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    btn.textContent = '✓ Copied';
                    setTimeout(function() {
                        btn.textContent = 'Copy Link';
                    }, 2000);
                });
            });
        })();
    </script>
@endpush
