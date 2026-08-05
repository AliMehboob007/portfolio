@extends('layouts.app')
@section('title', setting('blog_page_meta'))

@section('content')

<section class="page-hero">
  <div class="container">
    <div class="page-hero-content">
      <div class="section-tag">{{ setting('blog_page_tag') }}</div>
      <h1 class="page-hero-title">{{ setting('blog_page_title') }}</h1>
      <p class="page-hero-desc">{{ setting('blog_page_desc') }}</p>
    </div>
  </div>
</section>

<section class="blog-page section-pad">
  <div class="container">

    <!-- FEATURED POST -->
    @if($featuredPost = $posts->first())
    <div class="blog-featured reveal">
      <div class="bf-img-wrap">
        @if($featuredPost->image)
          <img src="{{ asset('storage/' . $featuredPost->image) }}" alt="{{ $featuredPost->title }}">
        @else
          <div class="bf-img-placeholder"><span>✍️</span></div>
        @endif
      </div>
      <div class="bf-content">
        <span class="blog-category-tag">{{ $featuredPost->category }}</span>
        <h2 class="bf-title">{{ $featuredPost->title }}</h2>
        <p class="bf-excerpt">{{ Str::limit($featuredPost->excerpt, 180) }}</p>
        <div class="bf-meta">
          <span>{{ $featuredPost->created_at->format('M d, Y') }}</span>
          <span>·</span>
          <span>{{ $featuredPost->read_time ?? '5' }} min read</span>
        </div>
        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="btn-primary">Read Article →</a>
      </div>
    </div>
    @endif

    <!-- BLOG GRID -->
    <div class="blog-filter-bar reveal" role="group" aria-label="Filter articles by category">
      <button type="button" class="filter-btn active" data-filter="all" aria-pressed="true">All</button>
      @foreach($categories as $cat)
      <button type="button" class="filter-btn" data-filter="{{ Str::slug($cat) }}" aria-pressed="false">{{ $cat }}</button>
      @endforeach
    </div>

    <div class="blog-full-grid">
      @foreach($posts->skip(1) as $post)
      <a href="{{ route('blog.show', $post->slug) }}" class="blog-full-card reveal" data-category="{{ Str::slug($post->category) }}">
        <div class="bfc-img-wrap">
          @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="bfc-img">
          @else
            <div class="bfc-img-placeholder"><span>✍️</span></div>
          @endif
          <span class="blog-category-tag">{{ $post->category }}</span>
        </div>
        <div class="bfc-info">
          <span class="bfc-date">{{ $post->created_at->format('M d, Y') }} · {{ $post->read_time ?? '5' }} min read</span>
          <h3 class="bfc-title">{{ $post->title }}</h3>
          <p class="bfc-excerpt">{{ Str::limit($post->excerpt, 100) }}</p>
          <span class="bfc-read">Read Article →</span>
        </div>
      </a>
      @endforeach
    </div>

    <div class="pagination-wrap">
      {{ $posts->links('pagination::simple-default') }}
    </div>

  </div>
</section>
@endsection
