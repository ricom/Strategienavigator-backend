@extends('layouts.app')

@section('title', 'Create')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __("Email senden") }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <form action={{ route('send.email') }} method="post">
            @csrf

            @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            <div class="mb-3">
                <label for="subject" class="form-label">Betreff</label>
                <input type="text" class="form-control" id="subject"  name="subject" placeholder="Geben Sie den Betreff ein" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Gruppe auswählen</label>
                <select class="form-select" aria-label="Default select example" name="group" required>
                    <option selected value="admin">Admin</option>
                    <option value="normal">Normal</option>
                    <option value="anonym">Anonym</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Nachricht</label>
                <textarea class="form-control" id="body" name="body" rows="3" placeholder="Geben Sie Ihre Nachricht ein" required></textarea>
            </div>
            <button type="submit" class="btn btn-info" >senden</button>
        </form>
    </div>
@endsection





