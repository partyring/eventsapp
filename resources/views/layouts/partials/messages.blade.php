@isSet($session['error'])
    <div class="alert alert-danger" role="alert">
        {{ $session['error'] }}
    </div>
@endiSset

@isSet($session['success'])
    <div class="alert alert-primary" role="alert">
    {{ $session['success'] }}
    </div>
@endisSet