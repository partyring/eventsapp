@extends('layouts.app')

@section('content')
<div class="container">

    <a href={{route('allEvents')}}>Back to all events</a>
</div>
<div class="container">
    <div class="text-center">
        <img src="{{ $imageURL }}" class="event-image--full">
    </div>
    <div class="event-details">
        <h1 class="event-title">{{ $event->name }}</h1>
        <p class="event-host">Hosted by {{ $userIsCreator ? 'you' : $event->user->username }}.</p>
        <p class="event-timings">From {{ $event->date_start }} to {{ $event->date_end }}</p>
        <p class="event-description">{{ $event->description }}</p>
        
        <div class="tags">
            @foreach($event->tags as $tag)
                <span class="hashtag">#{{ $tag->name }}</span>
            @endforeach
        </div>

        @if(Auth::user()->canEditEvent($event))

            <a href={{route('editEvent', ['event' => $event])}} class="event-edit">Edit</a>
        @endif

    </div>
</div>


@endsection