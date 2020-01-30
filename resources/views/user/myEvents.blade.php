@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Events</h1>

    @each('events\partials\eventPreivew', $events, 'event')

</div>

@endsection
