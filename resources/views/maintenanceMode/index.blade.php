@extends('layouts.app')

@section('title', 'Maintenance Mode')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __("Wartungsmodus") }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="maintenanceSwitch" name="maintenanceSwitch" {{$checked ? 'checked': ''}}>
            <label class="form-check-label" for="maintenanceSwitch">Wartungsmodus aktivieren</label>
        </div>
    </div>
    <div class="container">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Benutzername</th>
                    <th scope="col">Aktion</th>
                    <th scope="col">Aktionszeit</th>
                </tr>
            </thead>
            <tbody>
            @foreach($switch_logs as $switch_log)
                <tr>
                    <td>{{$switch_log->getUserNameAttribute()}}</td>
                    <td>{{$switch_log->action}}</td>
                    <td>{{$switch_log->action_time}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $switch_logs->links() }}
    </div>

    <script>
        const maintenanceSwitch = document.getElementById('maintenanceSwitch');
        maintenanceSwitch.addEventListener('change', function() {
            const isActive = this.checked;
            // Send AJAX request to Laravel backend
            fetch('/admin/toggle-maintenance-mode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if needed
                },
                body: JSON.stringify({ isActive })
            })
                .then(response => response.json())
                .then(data => {
                   alert(data.message);
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@endsection





