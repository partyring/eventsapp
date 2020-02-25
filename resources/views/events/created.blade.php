@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Success!</h1>
    <p>You have created the event: {{ $event->name }}.</p>
    <a href="{{route('viewEvent', ['event' => $event])}}">View your event here.</a>
</div>

<div class="container">
    <a href="{{route('inviteUsers', ['event' => $event])}}">Invite users to your event.</a>
</div>

@endsection
