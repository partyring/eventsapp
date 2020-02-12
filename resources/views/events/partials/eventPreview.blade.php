<div class="card card--event-preview">
    <img class="card-img-top image--preview" src="{{ $event->mainImageURL() }}" alt="Card image cap">
    <h2><a href="{{route('viewEvent', $event)}}">{{ $event->name }}</a></h2>
    <p>{{ $event->description }}</p>
    <p>{{ $event->dateStartFriendly() }}</p>
    <p>X People {{ $event->isInFuture() ? 'Attending' : 'Attended' }}</p>

    @foreach($event->tags as $tag)
        <span class="tag">#{{ $tag->name }}</span>
    @endforeach
</div>