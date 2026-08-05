@extends('layouts.app')
@section('title', setting('projects_page_meta'))

@section('content')

<section class="page-hero">
  <div class="container">
    <div class="page-hero-content">
      <div class="section-tag">{{ setting('projects_page_tag') }}</div>
      <h1 class="page-hero-title">{{ setting('projects_page_title') }}</h1>
      <p class="page-hero-desc">{{ setting('projects_page_desc') }}</p>
    </div>
  </div>
</section>

<section class="projects-page section-pad">
  <div class="container">

    <!-- FILTERS -->
    <div class="proj-filters reveal" role="group" aria-label="Filter projects by category">
      <button type="button" class="filter-btn active" data-filter="all" aria-pressed="true">All Projects</button>
      @foreach($categories as $cat)
      <button type="button" class="filter-btn" data-filter="{{ Str::slug($cat) }}" aria-pressed="false">{{ $cat }}</button>
      @endforeach
    </div>

    <!-- PROJECTS GRID -->
    <div class="proj-full-grid" id="projectsGrid">
      @foreach($projects as $project)
      <article class="proj-full-card reveal" data-category="{{ Str::slug($project->category) }}">
        <div class="pfc-img-wrap">
          @if($project->image)
            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" class="pfc-img" loading="lazy">
          @else
            <div class="pfc-img-placeholder">
              <span>{{ Str::substr($project->title, 0, 2) }}</span>
            </div>
          @endif
          <div class="pfc-overlay">
            <a href="{{ route('projects.show', $project->slug) }}" class="pfc-btn">View Details</a>
            @if($project->live_url)
            <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="pfc-btn pfc-btn-outline">Live Site ↗</a>
            @endif
          </div>
          @if($project->is_featured)
          <span class="pfc-badge">Featured</span>
          @endif
        </div>
        <div class="pfc-info">
          <span class="pfc-category">{{ $project->category }}</span>
          <h3 class="pfc-title">{{ $project->title }}</h3>
          <p class="pfc-desc">{{ Str::limit($project->description, 110) }}</p>
          @if(!empty($project->impact))
          <p class="proj-impact">{{ $project->impact }}</p>
          @endif
          <div class="pfc-tech">
            @foreach(array_slice(explode(',', $project->tech_stack), 0, 5) as $tech)
            <span>{{ trim($tech) }}</span>
            @endforeach
          </div>
        </div>
      </article>
      @endforeach
    </div>

    <!-- PAGINATION -->
    <div class="pagination-wrap">
      {{ $projects->links('pagination::simple-default') }}
    </div>

  </div>
</section>

@endsection

{{-- Filtering is handled by initFilters() in public/js/app.js --}}
