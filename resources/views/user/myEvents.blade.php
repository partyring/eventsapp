@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Events</h1>

    @each('events\partials\eventPreview', $events, 'event')

</div>

@endsection
