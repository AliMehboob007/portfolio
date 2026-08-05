@extends('layouts.admin')
@section('admin-title', isset($post) ? 'Edit Post' : 'Write New Post')
@section('admin-content')

<div style="margin-bottom:1.5rem">
  <a href="{{ route('admin.blog') }}" class="btn btn-outline-sm">← Back to Posts</a>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:2rem;align-items:start">

  <!-- MAIN FORM -->
  <div class="admin-form-card" style="max-width:100%">
    <form action="{{ isset($post) ? route('admin.blog.update', $post->id) : route('admin.blog.store') }}"
          method="POST" enctype="multipart/form-data">
      @csrf
      @if(isset($post)) @method('PUT') @endif

      <div class="form-group">
        <label>Post Title *</label>
        <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}"
               required placeholder="e.g. Building Scalable APIs with Laravel" style="font-size:1.05rem;padding:1rem">
      </div>

      <div class="form-group">
        <label>Excerpt / Summary *</label>
        <textarea name="excerpt" rows="2" required
          placeholder="Short summary shown in blog listings (max 300 chars)...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
      </div>

      <div class="form-group">
        <label>Full Content *</label>
        <textarea name="body" id="postBody" rows="16" required
          placeholder="Write your full article here...&#10;&#10;You can use basic HTML if needed."
          style="font-size:.92rem;line-height:1.7">{{ old('body', $post->body ?? '') }}</textarea>
      </div>

      <div style="display:flex;gap:1rem;margin-top:1.5rem">
        <button type="submit" class="btn btn-primary-sm" style="flex:1;justify-content:center;padding:.85rem">
          {{ isset($post) ? '✓ Update Post' : '✓ Publish Post' }}
        </button>
        <a href="{{ route('admin.blog') }}" class="btn btn-outline-sm" style="padding:.85rem 1.5rem">Cancel</a>
      </div>
    </form>
  </div>

  <!-- SIDEBAR OPTIONS -->
  <div style="display:flex;flex-direction:column;gap:1rem">

    <!-- PUBLISH OPTIONS -->
    <div class="admin-form-card" style="max-width:100%">
      <h4 style="font-size:.85rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem">Publish Settings</h4>
      <div class="form-group">
        <label>Category *</label>
        <select name="category" form="{{ isset($post) ? 'update-form' : '' }}" required>
          @foreach(['Laravel','Vue.js','PHP','Web Development','DevOps','eCommerce','Career','Tutorial','Other'] as $cat)
          <option value="{{ $cat }}" {{ old('category', $post->category ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Read Time (minutes)</label>
        <input type="number" name="read_time" value="{{ old('read_time', $post->read_time ?? 5) }}" min="1" max="60">
      </div>
      <label class="form-check" style="margin-top:.5rem">
        <input type="checkbox" name="published" value="1" {{ old('published', $post->published ?? false) ? 'checked' : '' }}>
        <span style="color:var(--text)">Publish immediately</span>
      </label>
    </div>

    <!-- FEATURED IMAGE -->
    <div class="admin-form-card" style="max-width:100%">
      <h4 style="font-size:.85rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem">Featured Image</h4>
      @if(isset($post) && $post->image)
        <img src="{{ asset('storage/'.$post->image) }}" style="width:100%;height:120px;object-fit:cover;border-radius:8px;margin-bottom:.75rem">
        <p style="font-size:.75rem;color:var(--muted);margin-bottom:.75rem">Upload new to replace current</p>
      @endif
      <div class="img-upload-wrap" onclick="document.getElementById('blogImg').click()">
        <img class="img-preview" alt="Preview" style="width:100%;object-fit:cover;border-radius:6px">
        <div style="{{ (isset($post) && $post->image) ? 'display:none' : '' }}">
          <div>🖼️</div>
          <p>Click to upload image</p>
          <p style="font-size:.72rem;color:var(--muted);margin-top:4px">Recommended: 1200×630px</p>
        </div>
      </div>
      <input type="file" id="blogImg" name="image" accept="image/*" style="display:none"
             onchange="document.querySelector('.img-preview').src=URL.createObjectURL(this.files[0]);document.querySelector('.img-preview').style.display='block'">
    </div>

    <!-- TIPS -->
    <div style="background:rgba(74,144,245,.06);border:1px solid rgba(74,144,245,.15);border-radius:10px;padding:1.25rem">
      <h4 style="font-size:.82rem;font-weight:700;color:var(--accent);margin-bottom:.75rem">✏️ Writing Tips</h4>
      <ul style="list-style:none;font-size:.8rem;color:var(--muted2);line-height:1.9">
        <li>• Write for both local and international readers</li>
        <li>• Keep paragraphs short and scannable</li>
        <li>• Include code snippets when relevant</li>
        <li>• Add a clear call-to-action at the end</li>
      </ul>
    </div>

  </div>
</div>

@endsection
