@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Future Events</h1>

    @if($past)
        <a href="{{route('allEvents')}}">Show future events.</a>
    @else
        <a href="{{route('allEvents', ['past' => true])}}">Show past events.</a>
    @endif

    <a href="{{route('createEvent')}}">Create a new event</a>

    @each('events\partials\eventPreview', $events, 'event')

    {{ $events->appends(['past' => $past])->links() }}

</div>

@endsection
