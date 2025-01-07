@extends('layouts.app')

@section('title', 'Anzeigen')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __("Tool anzeigen") }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">ID: {{$tool->id}}</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Name: {{$tool->name}}</h6>
                <p class="card-text">Status:  @if($tool->status == 1) An  @else Aus @endif</p>
                <p class="card-text">Tutorial: {{$tool->tutorial}}</p>
                <a href="{{route('tools.index')}}" class="card-link">Zurück</a>
            </div>
        </div>
    </div>
@endsection



