@extends('layouts.app')

@section('content')
<div class="container">
    <h1>View All Events</h1>

    @each('events\partials\eventPreivew', $events, 'event')

</div>

@endsection
