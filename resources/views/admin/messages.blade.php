@extends('layouts.admin')
@section('admin-title', 'Contact Messages')
@section('admin-content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
  <p style="color:var(--muted);font-size:.9rem">{{ $messages->total() }} messages total</p>
</div>

<div class="admin-table-card">
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Project Type</th>
        <th>Message</th>
        <th>Date</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse($messages as $msg)
      <tr>
        <td style="color:var(--text);font-weight:600">{{ $msg->name }}</td>
        <td><a href="mailto:{{ $msg->email }}" style="color:var(--accent);text-decoration:none">{{ $msg->email }}</a></td>
        <td>
          @if($msg->phone)
            <a href="tel:{{ $msg->phone }}" style="color:var(--muted2);text-decoration:none">{{ $msg->phone }}</a>
          @else
            <span style="color:var(--muted)">—</span>
          @endif
        </td>
        <td>{{ $msg->project_type ?? '—' }}</td>
        <td style="max-width:250px">
          <div style="position:relative">
            <p style="font-size:.85rem;color:var(--muted2);overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">
              {{ $msg->message }}
            </p>
          </div>
        </td>
        <td style="white-space:nowrap;font-size:.82rem">{{ $msg->created_at->format('M d, Y') }}<br><span style="color:var(--muted)">{{ $msg->created_at->format('h:i A') }}</span></td>
        <td>
          <span class="badge {{ $msg->read ? 'badge-gray' : 'badge-blue' }}">
            {{ $msg->read ? 'Read' : 'New' }}
          </span>
        </td>
        <td>
          <form method="POST" action="{{ route('admin.messages.delete', $msg->id) }}" onsubmit="return confirm('Delete this message?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger-sm" style="padding:.3rem .75rem;font-size:.76rem">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8" style="text-align:center;color:var(--muted);padding:3rem">
          <div style="font-size:2rem;margin-bottom:1rem">📨</div>
          No messages yet. Your contact form submissions will appear here.
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="pagination-wrap">{{ $messages->links() }}</div>

@endsection
