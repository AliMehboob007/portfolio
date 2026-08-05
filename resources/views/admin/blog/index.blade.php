@extends('layouts.admin')
@section('admin-title', 'Blog Posts')
@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
  <p style="color:var(--muted);font-size:.9rem">{{ $posts->total() }} posts total</p>
  <a href="{{ route('admin.blog.create') }}" class="btn btn-primary-sm">✍️ Write New Post</a>
</div>

<div class="admin-table-card">
  <table>
    <thead>
      <tr>
        <th>Image</th><th>Title</th><th>Category</th><th>Read Time</th><th>Status</th><th>Date</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($posts as $post)
      <tr>
        <td>
          @if($post->image)
            <img src="{{ asset('storage/'.$post->image) }}" style="width:70px;height:45px;object-fit:cover;border-radius:6px">
          @else
            <div style="width:70px;height:45px;background:var(--bg2);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.8rem;color:var(--muted)">✍️</div>
          @endif
        </td>
        <td style="color:var(--text);font-weight:500;max-width:220px">
          {{ Str::limit($post->title, 55) }}
        </td>
        <td><span class="badge badge-blue">{{ $post->category }}</span></td>
        <td style="color:var(--muted2)">{{ $post->read_time ?? '5' }} min</td>
        <td>
          <span class="badge {{ $post->published ? 'badge-green' : 'badge-gray' }}">
            {{ $post->published ? 'Published' : 'Draft' }}
          </span>
        </td>
        <td style="font-size:.82rem;color:var(--muted)">{{ $post->created_at->format('M d, Y') }}</td>
        <td>
          <div style="display:flex;gap:6px">
            <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-outline-sm" style="padding:.3rem .75rem;font-size:.76rem">Edit</a>
            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-outline-sm" style="padding:.3rem .75rem;font-size:.76rem">View</a>
            <form method="POST" action="{{ route('admin.blog.delete', $post->id) }}" onsubmit="return confirm('Delete this post?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger-sm" style="padding:.3rem .75rem;font-size:.76rem">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" style="text-align:center;color:var(--muted);padding:3rem">
          <div style="font-size:2rem;margin-bottom:1rem">✍️</div>
          No blog posts yet.
          <a href="{{ route('admin.blog.create') }}" style="color:var(--accent)">Write your first post →</a>
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="pagination-wrap">{{ $posts->links() }}</div>
@endsection
