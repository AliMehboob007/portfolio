@extends('layouts.admin')
@section('admin-title', ($item->exists ? 'Edit ' : 'Add ') . $config['singular'])
@section('admin-content')

<a href="{{ route('admin.content.index', $type) }}" class="admin-back">
  <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to {{ $config['label'] }}
</a>

<div class="admin-form-card">
  <form method="POST"
        action="{{ $item->exists ? route('admin.content.update', [$type, $item->id]) : route('admin.content.store', $type) }}">
    @csrf
    @if($item->exists) @method('PUT') @endif

    @php
      // Half-width fields are paired up so two inputs share a row.
      $rows = [];
      $pending = null;
      foreach ($config['fields'] as $name => $field) {
          if (($field['width'] ?? null) === 'half') {
              if ($pending) { $rows[] = [$pending, [$name, $field]]; $pending = null; }
              else { $pending = [$name, $field]; }
          } else {
              if ($pending) { $rows[] = [$pending]; $pending = null; }
              $rows[] = [[$name, $field]];
          }
      }
      if ($pending) { $rows[] = [$pending]; }
    @endphp

    @foreach($rows as $row)
      <div class="{{ count($row) === 2 ? 'form-row-2' : '' }}">
        @foreach($row as [$name, $field])
          @php
            $type_    = $field['type'] ?? 'text';
            $current  = old($name, $item->exists ? $item->{$name} : ($field['default'] ?? null));
            $required = str_contains($field['rules'] ?? '', 'required');
          @endphp

          @if($type_ === 'checkbox')
            <div class="form-group">
              <label class="form-check">
                <input type="checkbox" name="{{ $name }}" value="1" @checked((bool) $current)>
                <span>{{ $field['label'] }}</span>
              </label>
              @if(!empty($field['hint']))
                <small style="display:block;color:var(--muted);font-size:.78rem;margin-top:.25rem">{{ $field['hint'] }}</small>
              @endif
            </div>

          @elseif($type_ === 'textarea')
            <div class="form-group">
              <label for="f-{{ $name }}">{{ $field['label'] }}@if($required) *@endif</label>
              <textarea id="f-{{ $name }}" name="{{ $name }}" rows="{{ $field['rows'] ?? 4 }}"
                        placeholder="{{ $field['placeholder'] ?? '' }}" @required($required)>{{ $current }}</textarea>
              @if(!empty($field['hint']))
                <small style="display:block;color:var(--muted);font-size:.78rem;margin-top:.25rem">{{ $field['hint'] }}</small>
              @endif
            </div>

          @else
            <div class="form-group">
              <label for="f-{{ $name }}">{{ $field['label'] }}@if($required) *@endif</label>
              <input id="f-{{ $name }}"
                     type="{{ $type_ === 'number' ? 'number' : 'text' }}"
                     name="{{ $name }}"
                     value="{{ $current }}"
                     placeholder="{{ $field['placeholder'] ?? '' }}"
                     @required($required)
                     @if($type_ === 'icon') data-icon-input="1" @endif>

              @if($type_ === 'icon')
                <small style="display:block;color:var(--muted);font-size:.78rem;margin-top:.25rem">
                  Preview: <i class="{{ $current ?: 'fas fa-question' }}" data-icon-preview aria-hidden="true"></i>
                  — any <a href="https://fontawesome.com/search?o=r&m=free" target="_blank" rel="noopener">Font&nbsp;Awesome 6</a> class,
                  e.g. <code>fab fa-laravel</code>
                </small>
              @elseif(!empty($field['hint']))
                <small style="display:block;color:var(--muted);font-size:.78rem;margin-top:.25rem">{{ $field['hint'] }}</small>
              @endif
            </div>
          @endif
        @endforeach
      </div>
    @endforeach

    <div class="form-row-2">
      <div class="form-group">
        <label for="f-sort">Sort Order</label>
        <input id="f-sort" type="number" name="sort_order" min="0" max="9999"
               value="{{ old('sort_order', $item->exists ? $item->sort_order : 0) }}">
        <small style="display:block;color:var(--muted);font-size:.78rem;margin-top:.25rem">Lowest number shows first.</small>
      </div>

      <div class="form-group">
        <label class="form-check" style="margin-top:2rem">
          <input type="checkbox" name="active" value="1" @checked(old('active', $item->exists ? $item->active : true))>
          <span>Visible on the site</span>
        </label>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary-sm">
        {{ $item->exists ? 'Save Changes' : 'Add ' . $config['singular'] }}
      </button>
      <a href="{{ route('admin.content.index', $type) }}" class="btn btn-outline-sm">Cancel</a>
    </div>
  </form>
</div>

@push('scripts')
<script>
  // Live icon preview next to any Font Awesome class field.
  document.querySelectorAll('[data-icon-input]').forEach(function (input) {
    var preview = input.parentElement.querySelector('[data-icon-preview]');
    if (!preview) return;
    input.addEventListener('input', function () {
      preview.className = input.value.trim() || 'fas fa-question';
    });
  });
</script>
@endpush

@endsection
