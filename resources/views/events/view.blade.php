@extends('layouts.app')

@section('content')
<div class="container">

    
    <a href={{route('event.index')}}>Back to all events</a>
    
    @include('layouts.partials.messages', ['session' => $session])

    <div class="text-center">
        <img src="{{ $imageURL }}" class="event-image--full">
    </div>

    <div class="event-details">
        <h1 class="event-title">{{ $event->name }}</h1>
        <p class="event-host">Hosted by {{ $userIsCreator ? 'you' : $event->user->username }}.</p>
        <p class="event-timings">From {{ $event->date_start }} to {{ $event->date_end }}</p>
        <p class="event-description">{{ $event->description }}</p>
        
        <div class="tags">
            @foreach ($event->tags as $tag)
                <span class="hashtag">#{{ $tag->name }}</span>
            @endforeach
        </div>

        <p>Number of people attending: {{ $event->numberOfAttendees() }}</p>

        @if($userIsCreator)

            <a href="{{ route('invitation.create', ['event' => $event]) }}">
                Invite users to your event.
            </a>

        @elseif (Auth::user()->isAttendingEvent($event))
            <p>You are attending this event!</p>
            <form action="{{ route('attendee.update', ['event' => $event, 'user' => Auth::user()]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Can't attend</button>
            </form>
            {{-- @if(Auth::user()->canRemoveAttendance($event))
            @endif --}}
        @else
            <form action="{{ route('attendee.create', ['event' => $event, 'user' => Auth::user()]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Attend this event</button>
            </form>
            
        @endif

        @if (Auth::user()->canEditEvent($event))
            <a href={{route('event.edit', ['event' => $event])}} class="event-edit">
                Edit
            </a>
        @endif

    </div>
</div>

@endsection
