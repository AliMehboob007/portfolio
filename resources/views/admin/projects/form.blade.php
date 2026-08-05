@extends('layouts.admin')
@section('admin-title', $project ? 'Edit Project' : 'Add New Project')

@push('styles')
<style>
  .gal-thumb{position:relative;display:inline-block;cursor:pointer;line-height:0}
  .gal-thumb input{position:absolute;opacity:0;pointer-events:none}
  .gal-thumb img{height:70px;width:100px;object-fit:cover;border-radius:6px;display:block;transition:opacity .15s,filter .15s}
  .gal-thumb-x{position:absolute;top:-6px;right:-6px;width:22px;height:22px;border-radius:50%;
    background:#dc2626;color:#fff;font-size:12px;line-height:22px;text-align:center;
    box-shadow:0 1px 4px rgba(0,0,0,.35);user-select:none}
  .gal-thumb input:checked ~ img{opacity:.35;filter:grayscale(1)}
  .gal-thumb input:checked ~ .gal-thumb-x{background:#16a34a}
  .gal-thumb input:checked ~ .gal-thumb-x::after{content:''}
  .gal-thumb input:checked ~ .gal-thumb-x{font-size:0}
  .gal-thumb input:checked ~ .gal-thumb-x::before{content:'↺';font-size:13px}
  .gal-thumb input:focus-visible ~ img{outline:2px solid var(--accent, #3b82f6);outline-offset:2px}
</style>
@endpush

@section('admin-content')

<div style="margin-bottom:1.5rem">
  <a href="{{ route('admin.projects') }}" class="btn btn-outline-sm">← Back to Projects</a>
</div>

<div class="admin-form-card" style="max-width:900px">
  <form action="{{ $project ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}"
        method="POST" enctype="multipart/form-data">
    @csrf
    @if($project) @method('PUT') @endif

    <div class="form-row-2">
      <div class="form-group">
        <label>Project Title *</label>
        <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" required placeholder="e.g. Monk Cables">
      </div>
      <div class="form-group">
        <label>Category *</label>
        <select name="category" required>
          <option value="">Select category</option>
          @foreach(['eCommerce','Web Application','EdTech','SaaS','Business / Corporate','Portfolio','CMS / WordPress','API / Backend','Other'] as $cat)
          <option value="{{ $cat }}" {{ old('category', $project->category ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="form-row-2">
      <div class="form-group">
        <label>Project Type / Subtitle</label>
        <input type="text" name="project_type" value="{{ old('project_type', $project->project_type ?? '') }}" placeholder="e.g. Full-Stack eCommerce Platform">
      </div>
      <div class="form-group">
        <label>Client Name</label>
        <input type="text" name="client_name" value="{{ old('client_name', $project->client_name ?? '') }}" placeholder="e.g. Eden Prime">
      </div>
    </div>

    <div class="form-group">
      <label>Description *</label>
      <textarea name="description" rows="5" required placeholder="Describe the project, your role, and what was built...">{{ old('description', $project->description ?? '') }}</textarea>
    </div>

    <div class="form-group">
      <label>Impact / Result</label>
      <input type="text" name="impact" maxlength="160" value="{{ old('impact', $project->impact ?? '') }}" placeholder="e.g. Serves 4,000+ students across 60 live exams">
      <small style="display:block;margin-top:.35rem;font-size:.78rem;color:var(--muted)">One measurable outcome. Shown as the highlighted line on project cards — leave blank to hide it.</small>
    </div>

    <div class="form-group">
      <label>Challenges & Solutions</label>
      <textarea name="challenges" rows="3" placeholder="Technical challenges faced and how you solved them...">{{ old('challenges', $project->challenges ?? '') }}</textarea>
    </div>

    <div class="form-group">
      <label>Tech Stack * (comma-separated)</label>
      <input type="text" name="tech_stack" value="{{ old('tech_stack', $project->tech_stack ?? '') }}" required placeholder="Laravel, Vue.js, MySQL, REST API, AWS">
    </div>

    <div class="form-row-2">
      <div class="form-group">
        <label>Live URL</label>
        <input type="url" name="live_url" value="{{ old('live_url', $project->live_url ?? '') }}" placeholder="https://example.com">
      </div>
      <div class="form-group">
        <label>GitHub URL</label>
        <input type="url" name="github_url" value="{{ old('github_url', $project->github_url ?? '') }}" placeholder="https://github.com/...">
      </div>
    </div>

    <div class="form-row-2">
      <div class="form-group">
        <label>Year</label>
        <input type="number" name="year" value="{{ old('year', $project->year ?? date('Y')) }}" min="2018" max="2030">
      </div>
      <div class="form-group">
        <label>Status</label>
        <select name="status">
          <option value="completed" {{ (old('status',$project->status??'completed'))=='completed'?'selected':'' }}>Completed</option>
          <option value="ongoing"   {{ (old('status',$project->status??''))=='ongoing'?'selected':'' }}>Ongoing</option>
          <option value="paused"    {{ (old('status',$project->status??''))=='paused'?'selected':'' }}>Paused</option>
        </select>
      </div>
    </div>

    <!-- MAIN IMAGE -->
    <div class="form-group">
      <label>Main Project Image</label>
      @if($project && $project->image)
        <div style="margin-bottom:.75rem">
          <img src="{{ asset('storage/'.$project->image) }}" style="height:120px;border-radius:8px;object-fit:cover">
          <p style="font-size:.75rem;color:var(--muted);margin-top:4px">Current image. Upload new to replace.</p>
        </div>
      @endif
      <div class="img-upload-wrap" onclick="document.getElementById('mainImg').click()">
        <img class="img-preview" alt="Preview">
        <div>🖼️</div>
        <p>Click to upload main project image</p>
        <p style="font-size:.75rem;color:var(--muted);margin-top:4px">Recommended: 1200×700px</p>
      </div>
      <input type="file" id="mainImg" name="image" accept="image/*" style="display:none">
    </div>

    <!-- GALLERY -->
    <div class="form-group">
      <label>Gallery Images (multiple)</label>
      @php $existingGallery = $project && $project->gallery ? (json_decode($project->gallery, true) ?: []) : []; @endphp
      @if($existingGallery)
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:.75rem">
          @foreach($existingGallery as $img)
            <label class="gal-thumb">
              <input type="checkbox" name="remove_gallery[]" value="{{ $img }}">
              <img src="{{ asset('storage/'.$img) }}" alt="">
              <span class="gal-thumb-x" title="Mark for deletion">✕</span>
            </label>
          @endforeach
        </div>
        <p style="font-size:.75rem;color:var(--muted);margin-bottom:.5rem">Click ✕ on an image to mark it for deletion, then save. New uploads are added to the existing gallery.</p>
      @endif
      <div class="img-upload-wrap">
        <div>📁</div>
        <p>Click to upload multiple gallery images</p>
        <input type="file" name="gallery[]" accept="image/*" multiple style="margin-top:.5rem">
      </div>
    </div>

    <div style="display:flex;gap:1rem;align-items:center;margin-top:1rem">
      <label class="form-check">
        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured',$project->is_featured??false) ? 'checked' : '' }}>
        Mark as Featured (shows on homepage)
      </label>
      <div class="form-group" style="margin:0;display:flex;align-items:center;gap:8px">
        <label style="margin:0">Sort Order:</label>
        <input type="number" name="sort_order" value="{{ old('sort_order',$project->sort_order??0) }}" style="width:80px">
      </div>
    </div>

    <div style="margin-top:1.5rem;display:flex;gap:1rem">
      <button type="submit" class="btn btn-primary-sm">{{ $project ? 'Update Project' : 'Create Project' }}</button>
      <a href="{{ route('admin.projects') }}" class="btn btn-outline-sm">Cancel</a>
    </div>
  </form>
</div>
@endsection
