@extends('errors.layout', [
  'code'    => '503',
  'label'   => 'Under maintenance',
  'title'   => 'Back in a few minutes.',
  'message' => "The site is being updated right now. Nothing is broken — try again shortly, or email me if it's urgent.",
])

@section('actions')
  <a href="mailto:amarjafri1472@gmail.com" class="btn-primary">Email me →</a>
  <a href="https://linkedin.com/in/amar-abbas-jafri" target="_blank" rel="noopener noreferrer" class="btn-ghost">LinkedIn</a>
@endsection
