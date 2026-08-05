@extends('layouts.admin')
@section('admin-title', 'Projects')
@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
  <p style="color:var(--muted);font-size:.9rem">{{ $projects->total() }} projects total</p>
  <a href="{{ route('admin.projects.create') }}" class="btn btn-primary-sm">➕ Add New Project</a>
</div>

<div class="admin-table-card">
  <table>
    <thead>
      <tr>
        <th>Image</th><th>Title</th><th>Category</th><th>Featured</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($projects as $project)
      <tr>
        <td>
          @if($project->image)
            <img src="{{ asset('storage/'.$project->image) }}" style="width:60px;height:40px;object-fit:cover;border-radius:6px">
          @else
            <div style="width:60px;height:40px;background:var(--bg2);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.7rem;color:var(--muted)">No img</div>
          @endif
        </td>
        <td style="color:var(--text);font-weight:500">{{ $project->title }}</td>
        <td>{{ $project->category }}</td>
        <td><span class="badge {{ $project->is_featured ? 'badge-green' : 'badge-gray' }}">{{ $project->is_featured ? 'Yes' : 'No' }}</span></td>
        <td><span class="badge badge-blue">{{ ucfirst($project->status) }}</span></td>
        <td>
          <div style="display:flex;gap:6px">
            <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-outline-sm" style="padding:.35rem .8rem;font-size:.78rem">Edit</a>
            <a href="{{ route('projects.show', $project->slug) }}" target="_blank" class="btn btn-outline-sm" style="padding:.35rem .8rem;font-size:.78rem">View</a>
            <form method="POST" action="{{ route('admin.projects.delete', $project->id) }}" onsubmit="return confirm('Delete this project?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-danger-sm" style="padding:.35rem .8rem;font-size:.78rem">Delete</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:2rem">No projects yet. <a href="{{ route('admin.projects.create') }}" style="color:var(--accent)">Add your first project →</a></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="pagination-wrap">{{ $projects->links() }}</div>
@endsection
