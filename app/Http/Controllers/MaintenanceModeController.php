<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SwitchLog;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;

class MaintenanceModeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {

        $switch_logs = SwitchLog::paginate(20);

        if (App::isDownForMaintenance()) {
            $checked = true;
        } else {
            $checked = false;
        }
        return view('maintenanceMode.index', [
            'checked' => $checked,
            'switch_logs' => $switch_logs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toggleMaintenanceMode(Request $request): JsonResponse
    {
        $request->validate([
            'isActive' => 'required|boolean',
        ]);

        try {
            $isActive = $request->input('isActive');

            if ($isActive) {

                Artisan::call('down');
                $message = 'Maintenance mode activated';

                // Annahme: Der eingeloggte Benutzer aktiviert den Switch-Button
                $userId = Auth::id(); // ID des eingeloggten Benutzers

                // Eintrag in die Datenbanktabelle hinzufügen
                // Log the user action using the insert method
                DB::table('switch_logs')->insert([
                    'user_id' => $userId,
                    'action' => 'activate',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {

                Artisan::call('up');
                $message = 'Maintenance mode deactivated';
                // Annahme: Der eingeloggte Benutzer aktiviert den Switch-Button
                $userId = Auth::id(); // ID des eingeloggten Benutzers

                // Eintrag in die Datenbanktabelle hinzufügen
                DB::table('switch_logs')->insert([
                    'user_id' => $userId,
                    'action' => 'deactivate',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error toggling maintenance mode: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
