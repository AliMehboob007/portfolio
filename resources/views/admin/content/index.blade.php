@extends('layouts.admin')
@section('admin-title', $config['label'])
@section('admin-content')

<div class="admin-table-card">
  <div class="admin-table-header">
    <div>
      <h3>{{ $config['label'] }} ({{ $items->count() }})</h3>
      @if(!empty($config['intro']))
        <p style="margin:.4rem 0 0;font-size:.82rem;color:var(--muted);max-width:60ch">{{ $config['intro'] }}</p>
      @endif
    </div>
    <a href="{{ route('admin.content.create', $type) }}" class="btn btn-primary-sm">
      <i class="fas fa-plus" aria-hidden="true"></i> Add {{ $config['singular'] }}
    </a>
  </div>

  <form method="POST" action="{{ route('admin.content.reorder', $type) }}" id="reorderForm">
    @csrf
    <div class="table-scroll">
      <table>
        <thead>
          <tr>
            <th style="width:80px">Order</th>
            @foreach($config['columns'] as $label)
              <th>{{ $label }}</th>
            @endforeach
            <th style="width:90px">Status</th>
            <th style="width:190px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $item)
            <tr>
              <td>
                <input type="number" name="order[{{ $item->id }}]" value="{{ $item->sort_order }}"
                       min="0" max="9999" style="width:64px;padding:.35rem .5rem;text-align:center"
                       aria-label="Sort order for {{ $item->{array_key_first($config['columns'])} }}">
              </td>

              @foreach($config['columns'] as $field => $label)
                <td class="{{ $loop->first ? 'cell-strong' : '' }}">
                  @if($field === 'icon')
                    <i class="{{ $item->icon }}" aria-hidden="true"></i>
                    <code style="font-size:.75rem;color:var(--muted)">{{ $item->icon }}</code>
                  @else
                    {{ \Illuminate\Support\Str::limit((string) $item->{$field}, 70) }}
                  @endif
                </td>
              @endforeach

              <td>
                <span class="badge {{ $item->active ? 'badge-green' : 'badge-gray' }}">
                  {{ $item->active ? 'Visible' : 'Hidden' }}
                </span>
              </td>

              <td class="cell-actions">
                <a href="{{ route('admin.content.edit', [$type, $item->id]) }}" class="btn btn-outline-sm">Edit</a>

                <button type="submit" form="toggle-{{ $item->id }}" class="btn btn-outline-sm">
                  {{ $item->active ? 'Hide' : 'Show' }}
                </button>

                <button type="submit" form="delete-{{ $item->id }}" class="btn btn-danger-sm">Delete</button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ count($config['columns']) + 3 }}" class="table-empty">
                Nothing here yet — add your first {{ strtolower($config['singular']) }}.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($items->count())
      <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end">
        <button type="submit" class="btn btn-outline-sm">Save Order</button>
      </div>
    @endif
  </form>
</div>

{{-- Kept outside the reorder form: nested forms are invalid HTML, so these are
     declared separately and reached with the `form` attribute above. --}}
@foreach($items as $item)
  <form id="toggle-{{ $item->id }}" method="POST" action="{{ route('admin.content.toggle', [$type, $item->id]) }}" hidden>
    @csrf
  </form>
  <form id="delete-{{ $item->id }}" method="POST" action="{{ route('admin.content.delete', [$type, $item->id]) }}"
        data-confirm="Delete this {{ strtolower($config['singular']) }}? This cannot be undone." hidden>
    @csrf @method('DELETE')
  </form>
@endforeach

@endsection
