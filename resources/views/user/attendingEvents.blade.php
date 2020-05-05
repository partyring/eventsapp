@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="title--events">Events You're Attending</h1>

    <div class="card-columns cards-events">

        <!-- Attending -->
        @foreach ($eventsAttending as $event)
            @include('events\partials\eventPreview', ['event' => $event, 'attending' => true])
        @endforeach
    </div>
    

</div>

@endsection
