@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Events</h1>

    <a href="{{route('createEvent')}}">Create a new event</a>

    @each('events\partials\eventPreview', $events, 'event')

</div>

@endsection
