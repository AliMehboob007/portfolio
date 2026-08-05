@extends('layouts.admin')
@section('admin-title', 'Testimonials')
@section('admin-content')

<div style="display:grid;grid-template-columns:1fr 400px;gap:2rem;align-items:start">

  <!-- LIST -->
  <div>
    <div class="admin-table-card">
      <div class="admin-table-header">
        <h3>All Testimonials ({{ $testimonials->count() }})</h3>
      </div>
      <table>
        <thead>
          <tr>
            <th>Avatar</th><th>Name</th><th>Company</th><th>Rating</th><th>Status</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($testimonials as $t)
          <tr>
            <td>
              @if($t->avatar)
                <img src="{{ asset('storage/'.$t->avatar) }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover">
              @else
                <div style="width:40px;height:40px;border-radius:50%;background:rgba(74,144,245,.15);display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;color:var(--accent)">
                  {{ substr($t->name,0,1) }}
                </div>
              @endif
            </td>
            <td style="color:var(--text);font-weight:600">
              {{ $t->name }}<br>
              <span style="font-size:.8rem;color:var(--muted);font-weight:400">{{ $t->position }}</span>
            </td>
            <td>{{ $t->company }}</td>
            <td style="color:#f6a052;letter-spacing:2px">
              @for($i=0;$i<$t->rating;$i++)★@endfor
            </td>
            <td>
              <span class="badge {{ $t->active ? 'badge-green' : 'badge-gray' }}">
                {{ $t->active ? 'Active' : 'Hidden' }}
              </span>
            </td>
            <td>
              <form method="POST" action="{{ route('admin.testimonials.delete', $t->id) }}" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger-sm" style="padding:.3rem .75rem;font-size:.76rem">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align:center;color:var(--muted);padding:2rem">
              No testimonials yet. Add your first one →
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- ADD FORM -->
  <div class="admin-form-card" style="max-width:100%">
    <h3 style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;margin-bottom:1.5rem;color:#fff">
      ➕ Add Testimonial
    </h3>
    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label>Client Name *</label>
        <input type="text" name="name" required placeholder="e.g. John Smith">
      </div>
      <div class="form-group">
        <label>Position / Title *</label>
        <input type="text" name="position" required placeholder="e.g. CEO">
      </div>
      <div class="form-group">
        <label>Company *</label>
        <input type="text" name="company" required placeholder="e.g. Eden Prime">
      </div>
      <div class="form-group">
        <label>Testimonial Message *</label>
        <textarea name="message" rows="4" required placeholder="What the client said..."></textarea>
      </div>
      <div class="form-group">
        <label>Rating (1–5)</label>
        <select name="rating">
          <option value="5">★★★★★ (5)</option>
          <option value="4">★★★★ (4)</option>
          <option value="3">★★★ (3)</option>
          <option value="2">★★ (2)</option>
          <option value="1">★ (1)</option>
        </select>
      </div>
      <div class="form-group">
        <label>Client Avatar (optional)</label>
        <div class="img-upload-wrap" onclick="document.getElementById('avatarInput').click()">
          <img class="img-preview" alt="Preview">
          <div>👤</div>
          <p>Click to upload avatar</p>
          <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none"
                 onchange="this.closest('.img-upload-wrap').querySelector('.img-preview').src=URL.createObjectURL(this.files[0]);this.closest('.img-upload-wrap').querySelector('.img-preview').style.display='block'">
        </div>
      </div>
      <button type="submit" class="btn btn-primary-sm" style="width:100%;justify-content:center">
        Add Testimonial
      </button>
    </form>
  </div>

</div>
@endsection
