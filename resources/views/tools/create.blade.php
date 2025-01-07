@extends('layouts.app')

@section('title', 'Erstellen')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __("Tool erstelen") }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <form action={{ route('tools.store') }} method="post">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name"  name="name" placeholder="Tool name">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" aria-label="Default select example" name="status">
                    <option selected value="1">An</option>
                    <option value="0">Aus</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tutorial" class="form-label">Tutorial</label>
                <textarea class="form-control" id="tutorial" name="tutorial" rows="3">Info Text</textarea>
            </div>
            <button type="submit" class="btn btn-info" >Erstellen</button>
        </form>
    </div>
@endsection


