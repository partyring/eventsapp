@extends('layouts.app')

@section('content')
<div class="container">
    <h1>View All Events</h1>

    @each('events\partials\eventPreview', $events, 'event')

</div>

@endsection
