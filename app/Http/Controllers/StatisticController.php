<?php

namespace App\Http\Controllers;

use App\Models\Save;
use App\Models\SharedSave;
use App\Models\User;
use http\Client\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {

        // Tools statistics
        $anzahlAnalysen = Save::count();
        $davonGeteilt = SharedSave::select('save_id')->distinct()->count();
        $davonNutzwertanalyse = Save::where('tool_id', '=', 1)->count();
        $davonSwotAnalyse = Save::where('tool_id', '=', 2)->count();
        $davonPaarweiserVergleich = Save::where('tool_id', '=', '3')->count();
        $davonPortfolioAnalyse = Save::where('tool_id', '=', '4')->count();

        // Tools statistics from last Month
        $anzahlAnalysenVomLetztenMonat = Save::where('created_at', '>=', now()->subMonth())->count();
        $davonGeteiltVomLetztenMonat = SharedSave::where('created_at', '>=', now()->subMonth())
            ->select('save_id')->distinct()->count();
        $davonNutzwertanalyseVomLetztenMonat = Save::where('tool_id', 1)->where('created_at', '>=', now()->subMonth())->count();
        $davonSwotAnalyseVomLetztenMonat = Save::where('tool_id', 2)->where('created_at', '>=', now()->subMonth())->count();
        $davonPaarweiserVergleichVomLetztenMonat = Save::where('tool_id', 3)->where('created_at', '>=', now()->subMonth())->count();
        $davonPortfolioAnalyseVomLetztenMonat = Save::where('tool_id', 4)->where('created_at', '>=', now()->subMonth())->count();

        //User statistics
        $anzahlBenutzerMitKonto = User::whereHas('role', function ($query) {
            $query->where('name', '!=', 'anonym');
        })->count();
        $anzahlAnonymeBenutzer = User::whereHas('role', function ($query) {
            $query->where('name', 'anonym');
        })->count();
        $anzahlBenutzerOhneJadeHsAdressen = User::where('email', 'NOT LIKE', '%jade-hs.de%')->count();
        $anzahlNeuerBenutzerMitKontoSeitLetztemMonat = User::where('created_at', '>=', now()->subMonth())->count();
        $anzahlNeuerAnonymenBenutzerSeitLetztemMonat = User::where('anonymous', 1)->where('created_at', '>=', now()->subMonth())->count();
        $anzahlBenutzerOhneJadeHsAdressenSeitLetztemMonat = User::where('email', 'NOT LIKE', '%jade-hs.de%')->where('created_at', '>=', now()->subMonth())->count();


        return view('statistics.index', [
            'anzahlAnalysen' => $anzahlAnalysen,
            'davonGeteilt' => $davonGeteilt,
            'davonNutzwertanalyse' => $davonNutzwertanalyse,
            'davonSwotAnalyse' => $davonSwotAnalyse,
            'davonPaarweiserVergleich' => $davonPaarweiserVergleich,
            'davonPortfolioAnalyse' => $davonPortfolioAnalyse,
            'anzahlAnalysenVomLetztenMonat' => $anzahlAnalysenVomLetztenMonat,
            'davonGeteiltVomLetztenMonat' => $davonGeteiltVomLetztenMonat,
            'davonNutzwertanalyseVomLetztenMonat' => $davonNutzwertanalyseVomLetztenMonat,
            'davonSwotAnalyseVomLetztenMonat' => $davonSwotAnalyseVomLetztenMonat,
            'davonPaarweiserVergleichVomLetztenMonat' => $davonPaarweiserVergleichVomLetztenMonat,
            'davonPortfolioAnalyseVomLetztenMonat' => $davonPortfolioAnalyseVomLetztenMonat,
            'anzahlBenutzerMitKonto' => $anzahlBenutzerMitKonto,
            'anzahlAnonymeBenutzer' => $anzahlAnonymeBenutzer,
            'anzahlBenutzerOhneJadeHsAdressen' => $anzahlBenutzerOhneJadeHsAdressen,
            'anzahlNeuerBenutzerMitKontoSeitLetztemMonat' => $anzahlNeuerBenutzerMitKontoSeitLetztemMonat,
            'anzahlNeuerAnonymenBenutzerSeitLetztemMonat' => $anzahlNeuerAnonymenBenutzerSeitLetztemMonat,
            'anzahlBenutzerOhneJadeHsAdressenSeitLetztemMonat' => $anzahlBenutzerOhneJadeHsAdressenSeitLetztemMonat
        ]);
    }
}
