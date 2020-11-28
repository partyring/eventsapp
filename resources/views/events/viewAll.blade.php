@extends('layouts.app')

@section('content')
<div class="container">
    @if ($past)
        <h1 class="title--events" >All Past Events</h1>
        <a href="{{ route('event.index') }}">Show future events.</a>
    @else
        <h1 class="title--events" >All Future Events</h1>
        <a href="{{ route('event.index', ['past' => true]) }}">Show past events.</a>
    @endif

    <a class="mb-3" href="{{ route('event.create')}} ">Create a new event</a>

    <p>
        You currently have {{ $pendingInvitations }} event {{ Str::plural('invitation', $pendingInvitations) }} - 
        <a href="{{ route('invitation.index', ['user' => Auth::user()]) }}">
            check them out
        </a>
        .
    </p>

    <div class="card-columns cards-events">
        @each ('events\partials\_event-preview', $events, 'event')
    </div>

    {{ $events->appends(['past' => $past])->links() }}

</div>

@endsection
