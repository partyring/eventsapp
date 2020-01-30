@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->name }}</h1>
    <p>Hosted by {{ $event->user->username }}</p>
    <p>From {{ $event->date_start }} to {{ $event->date_end }}</p>
    <p>{{ $event->description }}</p>
</div>

<div class="container">

    @if(Auth::user()->canEditEvent($event))

        <a href={{route('editEvent', ['event' => $event])}}>Edit</a>
    @endif
</div>

<div class="container">

    <a href={{route('allEvents')}}>Back to all events</a>
</div>
@endsection