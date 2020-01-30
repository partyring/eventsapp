<div>
    <h2><a href="{{route('viewEvent', $event)}}">{{ $event->name }}</a></h2>
    <p>{{ $event->description }}</p>
    <p>X People Attending</p>

    @foreach($event->tags as $tag)
        <span class="tag">#{{ $tag->name }}</span>
    @endforeach
</div>