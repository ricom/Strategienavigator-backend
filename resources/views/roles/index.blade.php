@extends('layouts.app')

@section('title', 'Index')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __('Rollen verwalten') }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div id="flash-message" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <a href="{{ route('roles.create') }}" type="button" class="btn btn-primary">Erstellen</a>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Beschreibung</th>
                    <th scope="col">Aktionen</th>
                    <th colspan="3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <th scope="row">{{ $role->id }}</th>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->description }}</td>

                        <td class="mr-1">
                            <a href="{{ route('roles.show', $role->id) }}" class="btn btn-warning">Anzeigen</a>
                        </td>
                        <td>
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-success">Bearbeiten</a>
                        </td>
                        <td>
                            <form action="{{ route('roles.destroy', $role->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick=" return confirm('Sind Sie sicher, dass Sie diese Rolle {{ $role->name }} löschen möchten ?');">Löschen</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $roles->links() }}
    </div>
    <script>
        // Function to hide flash messages after 5 seconds if the page is visible
        function hideFlashMessage() {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.style.display = 'none';
            }
        }

        // Check if the page is visible
        function onVisibilityChange() {
            if (document.hidden) {
                // Page is not visible, do nothing
                return;
            }
            // Page is visible, set a timeout to hide the flash message
            setTimeout(hideFlashMessage, 5000); // 5000 milliseconds = 5 seconds

        }

        // Register the event listener for visibility change only once
        document.addEventListener('visibilitychange', onVisibilityChange);


        onVisibilityChange();

    </script>
@endsection
