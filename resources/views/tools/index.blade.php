@extends('layouts.app')

@section('title', 'Index')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __('Tools und Infotexte bearbeiten') }}</h1>
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
        <a href="{{ route('tools.create') }}" type="button" class="btn btn-primary">Erstellen</a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Tutorial</th>
                    <th scope="col">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tools as $tool)
                    <tr>
                        <th scope="row">{{ $tool->id }}</th>
                        <td>{{ $tool->name }}</td>
                        <td>
                            @if ($tool->status)
                                An
                            @else
                                Aus
                            @endif


                        </td>
                        <td>{{ $tool->tutorial }}</td>

                        <td class="mr-1">
                            <a href="{{ route('tools.show', $tool->id) }}" class="btn btn-warning">Anzeigen</a>
                        </td>
                        <td>
                            <a href="{{ route('tools.edit', $tool->id) }}" class="btn btn-success">Bearbeiten</a>
                        </td>
                        <td>
                            <form action="{{ route('tools.destroy', $tool->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick=" return confirm('Sind Sie sicher, dass Sie diesen Tool {{ $tool->name }} löschen möchten ?');">Löschen</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tools->links() }}
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
