@extends('layouts.app')

@section('title', 'Bearbeiten')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __("Rolle bearbeiten") }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <form action={{ route('roles.update', $role->id) }} method="post">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="id" class="form-label">ID</label>
                <input type="text" class="form-control" id="id" name="id" placeholder="{{$role->id}}" readonly>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name"  name="name" placeholder="{{$role->name}}">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Beschreibung</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{$role->description}}</textarea>
            </div>
            <button type="submit" class="btn btn-info" >Aktualisieren</button>
        </form>
    </div>
@endsection





