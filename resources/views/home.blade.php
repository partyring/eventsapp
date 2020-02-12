@extends('layouts.app')

@section('content')
<div class="image-full-width image-full-width--index">
    <h2 class="text--welcome-back">Welcome back, {{ Auth::user()->username }}.</h2>
</div>
<div class="container">
    
</div>
@endsection
