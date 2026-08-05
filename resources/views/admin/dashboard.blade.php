@extends('layouts.admin')
@section('admin-title', 'Dashboard')
@section('admin-content')

{{-- Summary before detail. Only the tile that needs action carries the
     accent stripe — everything else stays quiet so it reads at a glance. --}}
<div class="stats-grid">

  <div class="stat-card {{ $messageCount > 0 ? 'needs-action' : '' }}">
    <div class="stat-head">
      <span class="stat-icon"><i class="fas fa-inbox" aria-hidden="true"></i></span>
      <span class="stat-l">Unread Messages</span>
    </div>
    <div class="stat-n">{{ $messageCount }}</div>
    <div class="stat-meta">
      @if($messageCount > 0)
        Needs a reply · <b>{{ $totalMessages }}</b> total
      @else
        All caught up · <b>{{ $totalMessages }}</b> total
      @endif
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-head">
      <span class="stat-icon"><i class="fas fa-folder-open" aria-hidden="true"></i></span>
      <span class="stat-l">Projects</span>
    </div>
    <div class="stat-n">{{ $projectCount }}</div>
    <div class="stat-meta"><b>{{ $featuredCount }}</b> featured on the homepage</div>
  </div>

  <div class="stat-card">
    <div class="stat-head">
      <span class="stat-icon"><i class="fas fa-pen-nib" aria-hidden="true"></i></span>
      <span class="stat-l">Blog Posts</span>
    </div>
    <div class="stat-n">{{ $postCount }}</div>
    <div class="stat-meta">
      <b>{{ $publishedCount }}</b> published ·
      <b>{{ $postCount - $publishedCount }}</b> draft
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-head">
      <span class="stat-icon"><i class="fas fa-quote-left" aria-hidden="true"></i></span>
      <span class="stat-l">Testimonials</span>
    </div>
    <div class="stat-n">{{ $testimonialCount }}</div>
    <div class="stat-meta"><b>{{ $activeTestimonials }}</b> showing on the site</div>
  </div>

</div>

{{-- A real, actionable prompt rather than decoration. Only appears when
     there is something to fix. --}}
@if($missingImpact > 0)
  <div class="alert alert-error" role="status" style="border-color:var(--accent-line);background:var(--accent-soft);color:var(--accent)">
    <i class="fas fa-circle-info" aria-hidden="true"></i>
    <span>
      <strong style="color:inherit">{{ $missingImpact }}</strong>
      {{ $missingImpact === 1 ? 'project has' : 'projects have' }} no impact line yet —
      the highlighted result on each project card stays hidden until you add one.
      <a href="{{ route('admin.projects') }}" style="color:inherit;text-decoration:underline;text-underline-offset:3px">Add them →</a>
    </span>
  </div>
@endif

{{-- ── QUICK ACTIONS ── --}}
<div class="section-block">
  <div class="quick-actions">
    <a href="{{ route('admin.projects.create') }}" class="quick-action">
      <span class="qa-icon"><i class="fas fa-plus" aria-hidden="true"></i></span>
      <span>
        <strong>Add Project</strong>
        <small>New case study</small>
      </span>
    </a>

    <a href="{{ route('admin.blog.create') }}" class="quick-action">
      <span class="qa-icon"><i class="fas fa-feather" aria-hidden="true"></i></span>
      <span>
        <strong>Write Post</strong>
        <small>New article</small>
      </span>
    </a>

    <a href="{{ route('admin.content.index', 'experiences') }}" class="quick-action">
      <span class="qa-icon"><i class="fas fa-briefcase" aria-hidden="true"></i></span>
      <span>
        <strong>Experience</strong>
        <small>Edit your timeline</small>
      </span>
    </a>

    <a href="{{ route('admin.settings') }}" class="quick-action">
      <span class="qa-icon"><i class="fas fa-sliders" aria-hidden="true"></i></span>
      <span>
        <strong>Settings &amp; Text</strong>
        <small>All site wording</small>
      </span>
    </a>
  </div>
</div>

{{-- ── LATEST MESSAGES ── --}}
<div class="admin-table-card">
  <div class="admin-table-header">
    <h3>Latest Messages</h3>
    <a href="{{ route('admin.messages') }}" class="btn btn-outline-sm">View all</a>
  </div>

  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Project Type</th>
          <th>Received</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($latestMessages as $msg)
          <tr>
            <td class="cell-strong">{{ $msg->name }}</td>
            <td><a href="mailto:{{ $msg->email }}" style="color:var(--accent)">{{ $msg->email }}</a></td>
            <td>{{ $msg->project_type ?: '—' }}</td>
            <td class="num">{{ $msg->created_at->format('d M Y') }}</td>
            <td>
              <span class="badge {{ $msg->read ? 'badge-gray' : 'badge-blue' }}">
                {{ $msg->read ? 'Read' : 'New' }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="table-empty">No messages yet — enquiries from the contact form land here.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ── RECENT PROJECTS ── --}}
<div class="admin-table-card">
  <div class="admin-table-header">
    <h3>Recent Projects</h3>
    <a href="{{ route('admin.projects') }}" class="btn btn-outline-sm">Manage</a>
  </div>

  <div class="table-scroll">
    <table>
      <thead>
        <tr>
          <th>Project</th>
          <th>Category</th>
          <th>Impact line</th>
          <th>Featured</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($latestProjects as $project)
          <tr>
            <td class="cell-strong">{{ $project->title }}</td>
            <td>{{ $project->category }}</td>
            <td>
              @if(!empty($project->impact))
                <span class="badge badge-green">Set</span>
              @else
                <span class="badge badge-amber">Missing</span>
              @endif
            </td>
            <td>
              @if($project->is_featured)
                <span class="badge badge-blue">Featured</span>
              @else
                <span class="badge badge-gray">Hidden</span>
              @endif
            </td>
            <td>
              <div class="cell-actions">
                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-outline-sm btn-icon-sm">Edit</a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="table-empty">No projects yet — add your first one to populate the portfolio.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
