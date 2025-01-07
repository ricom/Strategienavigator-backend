@extends('layouts.app')

@section('title', 'Erstellen')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __("Benutzer erstellen") }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <form action={{ route('users.store') }} method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Benutzername</label>
                <input type="text" class="form-control" id="name"  name="name" placeholder="">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email"  name="email" placeholder="">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Passwort</label>
                <input type="password" class="form-control" id="email"  name="password" placeholder="">
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Rolle</label>
                <select class="form-select" select example" name="role">
                    @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-info" >Erstellen</button>
        </form>
    </div>
@endsection



