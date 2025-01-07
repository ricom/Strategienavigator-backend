@extends('layouts.app')

@section('title', 'index')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __('Benutzer verwalten') }}</h1>
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
    <div class="container d-flex align-items-center justify-content-center ">

        <table class="table table-hover">
            <caption style="caption-side: top;"><a href="{{ route('users.create') }}" type="button"
                    class="btn btn-primary">Erstellen</a></caption>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Benutzername</th>
                    <th scope="col">Email</th>
                    <th scope="col">Rolle</th>
                    <th colspan="3">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}
                        <td>{{ $user->role->name }}</td>

                        <td class="mr-1">
                            <a href="users/{{ $user->id }}" class="btn btn-warning">Anzeigen</a>
                        </td>
                        <td>
                            <a href="users/{{ $user->id }}/edit" class="btn btn-success">Bearbeiten</a>
                        </td>
                        <td>
                            <form action="users/{{ $user->id }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick=" return confirm('Sind Sie sicher, dass Sie diesen Benutzer {{ $user->username }} löschen möchten ?');">löschen</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="container">
        {{ $users->links() }}
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

            // Event listener for visibility change
            document.addEventListener('visibilitychange', onVisibilityChange);
        }

        onVisibilityChange();
    </script>
@endsection
