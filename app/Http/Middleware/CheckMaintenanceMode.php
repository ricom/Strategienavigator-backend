<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class CheckMaintenanceMode
{
    /**
     * The URIs that should be excluded from maintenance mode.
     *
     * @var array
     */
    protected $except = [
        'admin/*', // Hier definieren Sie die Routen, die vom Wartungsmodus ausgeschlossen werden sollen
    ];

    public function handle($request, Closure $next)
    {
        // Überprüfen, ob die Anfrage eine der ausgeschlossenen Routen ist
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return $next($request);
            }
        }

        // Überprüfen, ob der Wartungsmodus aktiviert ist
        if (App::isDownForMaintenance()) {
            // Wartungsmodus aktiviert
            return response()->json(['message' => 'Die Anwendung wird gewartet. Bitte versuchen Sie es später erneut.'], 503);
        }

        // Wartungsmodus ist nicht aktiviert, führe die nächste Middleware aus
        return $next($request);
    }
}
