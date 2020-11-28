@extends('layouts.app')

@section('content')
<div class="container">
    
    <h1>Your Event Invitations <span class="badge badge-success">{{ $pendingInvitations }}</span></h1>

    <a href="{{route('event.index')}}">Back to all events</a><a href="{{ route('event.create') }}">
        Create a new event
    </a>
    <br /><br />

    {{-- todo :: show different preview which allows for 1 click attend --}}
    <div class="card-columns cards-events">

        @each('events\partials\_event-preview', $events, 'event')

    </div>


</div>

@endsection
