<div class="card card--event-preview">
    @isSet($invited)
        @if($invited)
            <span class="badge--invited">You've been invited</span>
        @endif
    @endisSet
    @isSet($attending)
        @if($attending)
            <span class="badge--attending">You're attending</span>
        @endif
    @endisSet
    <img class="card-img-top image--preview" src="{{ $event->mainImageURL() }}" alt="Card image cap">
    <h2><a href="{{route('viewEvent', $event)}}">{{ $event->name }}</a></h2>
    <p>{{ $event->description }}</p>
    <p>{{ $event->dateStartFriendly() }}</p>
    @php 
        $attendees = $event->numberOfAttendees();
    @endphp
    <p>{{ $attendees }} {{ Str::plural('Person', $attendees) }} {{ $event->isInFuture() ? 'Attending' : 'Attended' }}</p>

    @foreach($event->tags as $tag)
        <span class="tag">#{{ $tag->name }}</span>
    @endforeach
</div>