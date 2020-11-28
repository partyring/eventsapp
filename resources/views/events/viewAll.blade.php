@extends('layouts.app')

@section('content')
<div class="container">
    @if ($past)
        <h1 class="title--events" >All Past Events</h1>
        <a href="{{ route('allEvents') }}">Show future events.</a>
    @else
        <h1 class="title--events" >All Future Events</h1>
        <a href="{{ route('allEvents', ['past' => true]) }}">Show past events.</a>
    @endif

    <a class="mb-3" href="{{ route('createEvent')}} ">Create a new event</a>

    <p>
        You currently have {{ $pendingInvitations }} event {{ Str::plural('invitation', $pendingInvitations) }} - 
        <a href="{{ route('viewEventInvitations', ['user' => Auth::user()]) }}">
            check them out
        </a>
        .
    </p>

    <div class="card-columns cards-events">
        @each ('events\partials\eventPreview', $events, 'event')
    </div>

    {{ $events->appends(['past' => $past])->links() }}

</div>

@endsection
