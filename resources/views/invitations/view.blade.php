@extends('layouts.app')

@section('content')
<div class="container">

    @include('layouts.partials.messages', ['session' => $session])

    <h1>Invite people to your event: {{ $event->name }}.</h1>

    <div class="invited-users">
        <p>Invited Users:</p>
        @if($invitedUsers)
            <ul>
                @foreach($invitedUsers as $invitedUser)
                    <li>{{ $invitedUser->username }}</li>
                @endforeach
            </ul>
        @else
            <p>You have not invited any other users yet.</p>
        @endif
    </div>

    <p>You can invite anybody by searching their username in the search field below.</p>

    <form method="GET" action="{{ route('inviteUsers', ['event' => $event]) }}">
        <div class="form-group row">
            <label for="username" class="col-form-label text-md-right">{{ __('Username') }}</label>

            <div class="col-md-6">
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}"  autofocus>

                @error('eventName')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Find User</button>

    </form>

    @if($users)
        <div class="username-results">
            @foreach($users as $user)
                <form method="POST" action="{{ route('sendInvitation', ['event' => $event, 'username' => $user->username]) }}">
                    @csrf
                    <span>Photo placeholder</span>{{ $user->username }} <button type="submit">Invite</button>
                </form>
            @endforeach
        </div>
    @endif
</div>

@endsection
