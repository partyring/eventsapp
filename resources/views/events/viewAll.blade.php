@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="title--events" >All Future Events</h1>

    @if($past)
        <a href="{{route('allEvents')}}">Show future events.</a>
    @else
        <a href="{{route('allEvents', ['past' => true])}}">Show past events.</a>
    @endif

    <a href="{{route('createEvent')}}">Create a new event</a>

    <div class="card-columns cards-events">

        @each('events\partials\eventPreview', $events, 'event')

    </div>

    {{ $events->appends(['past' => $past])->links() }}

</div>

@endsection
