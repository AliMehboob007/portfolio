@extends('layouts.app')
@section('title', $project->title . ' — Muhammad Ali')

@section('content')

    <section class="page-hero page-hero-sm">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a> /
                <a href="{{ route('projects.index') }}">Projects</a> /
                <span>{{ $project->title }}</span>
            </div>
        </div>
    </section>

    <section class="project-detail section-pad">
        <div class="container">
            <div class="pd-grid">

                <!-- LEFT: CONTENT -->
                <div class="pd-content">
                    <span class="pd-category">{{ $project->category }}</span>
                    <h1 class="pd-title">{{ $project->title }}</h1>
                    <p class="pd-type">{{ $project->project_type }}</p>

                    @if (!empty($project->impact))
                        <p class="proj-impact">{{ $project->impact }}</p>
                    @endif

                    <!-- MAIN IMAGE -->
                    <div class="pd-main-img">
                        @if ($project->image)
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}">
                        @else
                            <div class="pd-img-placeholder"><span>{{ substr($project->title, 0, 2) }}</span></div>
                        @endif
                    </div>

                    <!-- GALLERY -->
                    @if ($project->gallery && count(json_decode($project->gallery)) > 0)
                        <div class="pd-gallery">
                            <h4>Project Gallery</h4>
                            <div class="gallery-grid">
                                @foreach (json_decode($project->gallery) as $img)
                                    <button type="button" class="gallery-item"
                                        onclick="openLightbox('{{ asset('storage/' . $img) }}')"
                                        aria-label="Open image {{ $loop->iteration }} full size">
                                        <img src="{{ asset('storage/' . $img) }}" alt="" loading="lazy">
                                        <span class="gallery-overlay" aria-hidden="true"><i
                                                class="fas fa-expand"></i></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- DESCRIPTION -->
                    <div class="pd-description">
                        <h3>Project Overview</h3>
                        <div class="pd-body">{!! nl2br(e($project->description)) !!}</div>
                    </div>

                    @if ($project->challenges)
                        <div class="pd-description">
                            <h3>Challenges & Solutions</h3>
                            <div class="pd-body">{!! nl2br(e($project->challenges)) !!}</div>
                        </div>
                    @endif
                </div>

                <!-- RIGHT: SIDEBAR -->
                <div class="pd-sidebar">
                    <div class="pd-info-card">
                        <h4>Project Details</h4>
                        <div class="pd-info-row">
                            <span>Client</span><strong>{{ $project->client_name ?? 'Confidential' }}</strong></div>
                        <div class="pd-info-row"><span>Year</span><strong>{{ $project->year ?? date('Y') }}</strong></div>
                        <div class="pd-info-row"><span>Category</span><strong>{{ $project->category }}</strong></div>
                        <div class="pd-info-row"><span>Status</span><strong
                                class="status-{{ $project->status ?? 'completed' }}">{{ ucfirst($project->status ?? 'Completed') }}</strong>
                        </div>
                    </div>

                    <div class="pd-info-card">
                        <h4>Tech Stack</h4>
                        <div class="pd-tech-pills">
                            @foreach (explode(',', $project->tech_stack) as $tech)
                                <span class="pd-tech-pill">{{ trim($tech) }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="pd-actions">
                        @if ($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer"
                                class="btn-primary pd-btn">Visit Live Site ↗</a>
                        @endif
                        @if ($project->github_url)
                            <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer"
                                class="btn-ghost pd-btn">View on GitHub</a>
                        @endif
                        <a href="{{ route('home') }}#contact" class="btn-outline pd-btn">Discuss Similar Project</a>
                    </div>
                </div>
            </div>

            <!-- RELATED PROJECTS -->
            @if ($related->count() > 0)
                <div class="related-section">
                    <h3 class="related-title">More Projects</h3>
                    <div class="related-grid">
                        @foreach ($related as $r)
                            <a href="{{ route('projects.show', $r->slug) }}" class="related-card">
                                @if ($r->image)
                                    <img src="{{ asset('storage/' . $r->image) }}" alt="{{ $r->title }}">
                                @else
                                    <div class="related-placeholder">{{ substr($r->title, 0, 2) }}</div>
                                @endif
                                <div class="related-info">
                                    <span>{{ $r->category }}</span>
                                    <strong>{{ $r->title }}</strong>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- LIGHTBOX -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <img src="" id="lightboxImg" alt="Gallery">
        <button class="lightbox-close" onclick="closeLightbox()">✕</button>
    </div>

@endsection

@push('scripts')
    <script>
        function openLightbox(src) {
            document.getElementById('lightboxImg').src = src;
            document.getElementById('lightbox').classList.add('active');
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('active');
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });
    </script>
@endpush
