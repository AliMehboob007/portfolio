@extends('errors.layout', [
  'code'    => '404',
  'label'   => 'Page not found',
  'title'   => "This page doesn't exist.",
  'message' => "The link may be broken, or the page may have been moved or renamed. Nothing is wrong on your end.",
])

@section('actions')
  <a href="{{ route('home') }}" class="btn-primary">Back to home →</a>
  <a href="{{ route('projects.index') }}" class="btn-ghost">Browse projects</a>
@endsection
