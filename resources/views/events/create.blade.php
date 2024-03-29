@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create a New Event</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('event.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="eventName" class="col-md-4 col-form-label text-md-right">{{ __('Event Name') }}</label>

                            <div class="col-md-6">
                                <input id="eventName" type="text" class="form-control @error ('eventName') is-invalid @enderror" name="eventName" value="{{ old('eventName') }}"  autofocus>

                                @error ('eventName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" type="text" class="form-control @error ('description') is-invalid @enderror" name="description" value="" autofocus>{{ old('description') }}</textarea>
                                @error ('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="privacyType" class="col-md-4 col-form-label text-md-right">{{ __('Event Privacy') }}</label>

                            <div class="col-md-6">

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="privacyType" id="publicEvent" value="public" checked>
                                    <label class="form-check-label" for="publicEvent">
                                    Public Event
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="privacyType" id="privateEvent" value="private">
                                    <label class="form-check-label" for="exampleRadios2">
                                    Private Event
                                    </label>
                                </div>
                            </div>

                        </div>


                        <div class="form-group row">
                            <label for="dateStart" class="col-md-4 col-form-label text-md-right">{{ __('Date Start') }}</label>

                            <div class="col-md-6">
                                <input id="dateStart" type="date" class="form-control @error ('dateStart') is-invalid @enderror" name="dateStart" value="{{ old('dateStart') }}"  autofocus>

                                @error ('dateStart')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="timeStart" class="col-md-4 col-form-label text-md-right">{{ __('Time Start') }}</label>

                            <div class="col-md-6">
                                <input id="timeStart" type="text" class="form-control @error ('timeStart') is-invalid @enderror" name="timeStart" value="{{ old('timeStart') }}"  autofocus>

                                @error ('timeStart')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="dateEnd" class="col-md-4 col-form-label text-md-right">{{ __('Date End') }}</label>

                            <div class="col-md-6">
                                <input id="dateEnd" type="date" class="form-control @error ('dateEnd') is-invalid @enderror" name="dateEnd" value="{{ old('dateEnd') }}"  autofocus>

                                @error ('dateEnd')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="timeEnd" class="col-md-4 col-form-label text-md-right">{{ __('Time End') }}</label>

                            <div class="col-md-6">
                                <input id="timeEnd" type="text" class="form-control @error ('timeEnd') is-invalid @enderror" name="timeEnd" value="{{ old('timeEnd') }}"  autofocus>

                                @error ('timeEnd')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tags" class="col-md-4 col-form-label text-md-right">{{ __('Tags') }}</label>
                            
                            <div class="col-md-6">
                                @foreach($tags as $tag)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{$tag->id}}" id="tags">
                                        <label class="form-check-label" for="tags">
                                            #{{ $tag->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            
                            <label for="coverImage" class="col-md-4 col-form-label text-md-right">{{ __('Upload Cover Image') }}</label>

                            <div class="col-md-6">
                                <input type="file" class="form-control @error ('coverImage') is-invalid @enderror" id="coverImage" name="coverImage" class="form-control">

                                @error ('coverImage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
            
                            </div>
                        </div>

                        <button type="submit">Create</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection
