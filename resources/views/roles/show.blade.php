@extends('layouts.app')

@section('title', 'Anzeigen')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __("Rolle anzeigen") }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">ID: {{$role->id}}</h5>
                <p class="card-text">Name: {{$role->name}}</p>
                <p class="card-text">Beschreibung: {{$role->description}}</p>
                <a href="{{route('roles.index')}}" class="card-link">Zurück</a>
            </div>
        </div>
    </div>
@endsection




