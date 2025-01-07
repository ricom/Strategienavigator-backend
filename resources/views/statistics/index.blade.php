@extends('layouts.app')

@section('title', 'Statistics')

@section('header')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-center">
                <div class="p-6 text-gray-900">
                    <h1> {{ __("Datenbank Statistiken") }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card" style="width: 71.4rem; margin-bottom: 10px;">
                    <canvas id="myChart" style="width:100%;max-width:600px; margin:auto"></canvas>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Anzahl Analysen: {{$anzahlAnalysen}}</li>
                        <li class="list-group-item">Davon geteilt: {{$davonGeteilt}}</li>
                    </ul>
                </div>
            </div>
            <div class="col">
                <div class="card" style="width: 71.4rem">
                    <canvas id="myChart2" style="width:100%;max-width:600px; margin:auto"></canvas>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Anzahl Analysen: {{$anzahlAnalysenVomLetztenMonat}}</li>
                        <li class="list-group-item">Davon geteilt: {{$davonGeteiltVomLetztenMonat}}</li>
                    </ul>
                </div>
            </div>

            <div class="col">
                <div class="card" style="width: 71.4rem; margin-top: 10px">
                    <canvas id="myChart3" style="width:100%;max-width:600px; height: 300px; margin:auto" ></canvas>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Anzahl Benutzer mit Konto: {{$anzahlBenutzerMitKonto}}</li>
                        <li class="list-group-item">Anzahl Anonyme benutzer: {{$anzahlAnonymeBenutzer}}</li>
                        <li class="list-group-item">Anzahl Benutzer ohne JadeHS-Adressen: {{$anzahlBenutzerOhneJadeHsAdressen}}</li>
                        <li class="list-group-item">Anzahl neuer Benutzer mit Konto seit letztem Monat: {{$anzahlNeuerBenutzerMitKontoSeitLetztemMonat}}</li>
                        <li class="list-group-item">Anzahl neuer anonymen Benutzer seit letztem Monat: {{$anzahlNeuerAnonymenBenutzerSeitLetztemMonat}}</li>
                        <li class="list-group-item">Anzahl Benutzer ohne JadeHS-Adressen seit letztem Monat: {{$anzahlBenutzerOhneJadeHsAdressenSeitLetztemMonat}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script>
        //Tools Statistiken Chart
        let xValues = [
            "Davon Nutzwert Analyse",
            "Davon SWOT Analyse",
            "Davon PaarweiserVergleich",
            "Davon PortfolioAnalyse"
        ];
        let yValues = [
            {{$davonNutzwertanalyse}},
            {{$davonSwotAnalyse}},
            {{$davonPaarweiserVergleich}},
            {{$davonPortfolioAnalyse}}
        ];

        let barColors = [
            "#b91d47",
            "#00aba9",
            "#2b5797",
            "#e8c3b9",
            "#1e7145"
        ];

        new Chart("myChart", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Tools Statistiken"
                }
            }
        });

        // Tools Statistiken Chart from the last month

        let xValues2 = [

            "davon Nutzwertanalyse",
            "Davon SWOT-Analyse",
            "Davon Paarweiser-Vergleich",
            "Davon Portfolio-Analyse"
        ];
        let yValues2 = [
            {{$davonNutzwertanalyseVomLetztenMonat}},
            {{$davonSwotAnalyseVomLetztenMonat}},
            {{$davonPaarweiserVergleichVomLetztenMonat}},
            {{$davonPortfolioAnalyseVomLetztenMonat}}
        ];


        new Chart("myChart2", {
            type: "pie",
            data: {
                labels: xValues2,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues2
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Tools Statistiken seit letztem Monat"
                }
            }
        });

        // Benutzer Statistiken
        let xValues3 = [

            "Anzahl Benutzer mit Konto",
            "Anzahl Anonyme benutze",
            "Anzahl Benutzer ohne JadeHS-Adressen",
            "Anzahl neuer Benutzer mit Konto seit letztem Monat",
            "Anzahl neuer anonymen Benutzer seit letztem Monat",
            "Anzahl Benutzer ohne JadeHS-Adressen seit letztem Monat"

        ];
        let yValues3 = [
            {{$anzahlBenutzerMitKonto}},
            {{$anzahlAnonymeBenutzer}},
            {{$anzahlBenutzerOhneJadeHsAdressen}},
            {{$anzahlNeuerBenutzerMitKontoSeitLetztemMonat}},
            {{$anzahlNeuerAnonymenBenutzerSeitLetztemMonat}},
            {{$anzahlBenutzerOhneJadeHsAdressenSeitLetztemMonat}}
        ];

        var barColors3 = ["red", "green","blue","orange","brown", "yellow"];


        new Chart("myChart3", {
            type: "bar",
            data: {
                labels: xValues3,
                datasets: [{
                    backgroundColor: barColors3,
                    data: yValues3
                }]
            },
            options: {
                legend: {display: false},
                title: {
                    display: true,
                    text: "Benutzer Statistiken"
                }
            }
        });
    </script>
@endsection





