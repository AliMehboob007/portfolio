@extends('errors.layout', [
  'code'    => '403',
  'label'   => 'Access denied',
  'title'   => "You don't have access to this page.",
  'message' => "This area is restricted. If you think you should be able to see it, get in touch and I'll sort it out.",
])

@section('actions')
  <a href="{{ route('home') }}" class="btn-primary">Back to home →</a>
  <a href="{{ route('home') }}#contact" class="btn-ghost">Contact me</a>
@endsection
