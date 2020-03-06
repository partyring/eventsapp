@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="title--events">Events You're Hosting</h1>

    <!-- TODO : Include dropdown of - future/past, events created by me,
        events attended by me -->

    
    <div class="card-columns cards-events">
        @each('events\partials\eventPreview', $events, 'event')
    </div>

</div>

@endsection
