{{-- Laravel returns 419 when the CSRF token expires — most often when the
     contact form has been left open in a tab for a long time. --}}
@extends('errors.layout', [
  'code'    => '419',
  'label'   => 'Session expired',
  'title'   => 'Your session timed out.',
  'message' => "The page sat idle too long and the security token expired. Go back and send the form again — it should work straight away.",
])

@section('actions')
  <a href="{{ url()->previous() }}" class="btn-primary">Go back and retry →</a>
  <a href="{{ route('home') }}" class="btn-ghost">Back to home</a>
@endsection
