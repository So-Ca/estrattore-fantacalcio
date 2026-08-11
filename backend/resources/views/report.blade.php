<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            padding-top: 60px;

        }

        html {
            scroll-behavior: smooth;
        }

        .sticky-menu {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 12px 0;
        }

        .menu-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
            display: flex;
            gap: 4px;
            overflow-x: auto;
        }

        .menu-item {
            white-space: nowrap;
            padding: 6px 12px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.2s;
            border-bottom: 2px solid transparent;
        }

        .menu-item:hover {
            color: #136af6;
        }

        .menu-item.active {
            color: #136af6;
            border-bottom-color: #136af6;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            padding: 24px;
            overflow-x: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 24px;
        }

        .responsive-table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
            display: table;
        }

        .responsive-table th,
        .responsive-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
            min-width: 120px;
        }

        .responsive-table th {
            background: #f1f3f4;
        }

        @media (max-width: 600px) {
            .container {
                padding: 8px;
            }

            .responsive-table th,
            .responsive-table td {
                padding: 6px 4px;
                font-size: 13px;
                min-width: 80px;
            }

            .responsive-table th:nth-child(1),
            .responsive-table td:nth-child(1) {
                min-width: 100px;
            }

            .responsive-table th:nth-child(2),
            .responsive-table td:nth-child(2) {
                min-width: 70px;
            }

            .responsive-table th:nth-child(3),
            .responsive-table td:nth-child(3) {
                min-width: 60px;
            }

            .responsive-table th:nth-child(4),
            .responsive-table td:nth-child(4) {
                min-width: 50px;
            }
        }

        tr.total-row {
            border-top: 2px solid;
            font-weight: 800;
        }

        tr:not(.total-row) td:nth-child(3) {
            display: flex;
            gap: 4px;
        }

        .ruolo {
            padding: 3px;
            width: 17px;
            height: 17px;
            display: flex;
            justify-content: center;
            align-items: center;
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            font-size: 0.8rem;
            text-align: center;
            border: 1px solid;
            color: white;
            font-weight: 700;
            padding: 2px;
        }

        .ruolo.por {
            background: #f8ab12;
        }

        .ruolo.dc,
        .ruolo.dd,
        .ruolo.ds,
        .ruolo.b {
            background: #65c723;
        }

        .ruolo.m,
        .ruolo.e,
        .ruolo.c {
            background: #136af6;
        }

        .ruolo.t,
        .ruolo.w {
            background: #a538ff;
        }

        .ruolo.a,
        .ruolo.pc {
            background: #f21c3c;
        }

        .filters-form {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 768px) {
            .filters-form {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .filters-form fieldset,
            .filters-form>div {
                grid-column: 1 !important;
            }
        }

        .notice {
            position: fixed;
            bottom: 0;
            right: 0;
            box-shadow: #e9f178 0px 0px 12px;
            padding: 16px;
            border-radius: 3px;
            transition: transform ease-in 200ms;
            transform: translateY(100%);
            z-index: 2;
            background: #e9f178;
            font-weight: 800;
        }

        .notice.active {
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <nav class="sticky-menu">
        <div class="menu-container">
            @for ($i = 0; $i < count($allenatori); $i++)
                <a href="#allenatore-{{ $i }}" class="menu-item">{{ $allenatori[$i]['Squadra'] }}</a>
            @endfor
            <a href="#non-assegnati" class="menu-item">Non assegnati</a>
        </div>
    </nav>

    @for ($i = 0; $i < count($allenatori); $i++)
<<<<<<< HEAD
        <div class="container">
            <h1>{{ $allenatori[$i]['Squadra'] }} <small style="font-size:1.3rem;">({{ $allenatori[$i]['Nome'] }})</small>
            </h1>
            <div style="overflow-x:auto;">
                <table class="responsive-table" data-id-allenatore="{{ $allenatori[$i]['Id'] }}">
=======

        <div class="wrapper" id="allenatore-{{ $i }}" style="position:relative;">
            <div class="container" style="padding-top:80px;">
                <h1>{{ $allenatori[$i]['Squadra'] }} <small
                        style="font-size:1.3rem;">({{ $allenatori[$i]['Nome'] }})</small>
                </h1>
                <div style="overflow-x:auto;">
                    <table class="responsive-table" data-id-allenatore="{{ $allenatori[$i]['Id'] }}">
                        <thead>
                            <tr>
                                <th>Giocatore</th>
                                <th>Squadra</th>
                                <th>Ruolo</th>
                                <th>Prezzo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totale = 0;

                                // Definisco l'ordine dei ruoli
$ordineRuoli = [
    'PC' => 1,
    'A' => 2,
    'W' => 3,
    'T' => 4,
    'C' => 5,
    'E' => 6,
    'M' => 7,
    'DD' => 8,
    'DS' => 9,
    'B' => 10,
    'DC' => 11,
    'POR' => 12,
];

// Funzione per ottenere il primo ruolo per l'ordinamento
                                $getPrimoRuolo = function ($ruoloString) use ($ordineRuoli) {
                                    $ruoli = explode(';', $ruoloString);
                                    $minOrdine = 999;
                                    foreach ($ruoli as $ruolo) {
                                        $ruolo = strtoupper(trim($ruolo));
                                        if (isset($ordineRuoli[$ruolo]) && $ordineRuoli[$ruolo] < $minOrdine) {
                                            $minOrdine = $ordineRuoli[$ruolo];
                                        }
                                    }
                                    return $minOrdine;
                                };

                                // Ordino i giocatori
                                $giocatoriOrdinati = $allenatori[$i]['giocatori'];
                                usort($giocatoriOrdinati, function ($a, $b) use ($getPrimoRuolo) {
                                    $ruoloA = $getPrimoRuolo($a['R']);
                                    $ruoloB = $getPrimoRuolo($b['R']);

                                    if ($ruoloA != $ruoloB) {
                                        return $ruoloA - $ruoloB;
                                    }

                                    // Se hanno lo stesso ruolo, ordino per Qt.A. (decrescente)
                                    return ($b['Qt.A.'] ?? 0) - ($a['Qt.A.'] ?? 0);
                                });
                            @endphp
                            @for ($j = 0; $j < count($giocatoriOrdinati); $j++)
                                @php
                                    $totale += $giocatoriOrdinati[$j]['Prezzo'];
                                @endphp
                                <tr data-id-giocatore="{{ $giocatoriOrdinati[$j]['Id'] }}">
                                    <td class="nome">{{ $giocatoriOrdinati[$j]['Nome'] }}</td>
                                    <td class="squadra">{{ $giocatoriOrdinati[$j]['Squadra'] }}</td>
                                    <td class="ruoli">
                                        @php
                                            $ruoloString = $giocatoriOrdinati[$j]['R'];
                                            $ruoloArray = explode(';', $ruoloString);
                                        @endphp
                                        @foreach ($ruoloArray as $ruolo)
                                            <span class="ruolo {{ strtolower($ruolo) }}"
                                                data-ruolo="{{ strtolower($ruolo) }}">{{ $ruolo }}</span>
                                        @endforeach
                                    </td>
                                    <td class="prezzo">{{ $giocatoriOrdinati[$j]['Prezzo'] }}</td>
                                </tr>
                            @endfor
                            <tr class="total-row">
                                <td>Totale speso</td>
                                <td></td>
                                <td></td>
                                <td class="totale-speso">{{ $totale }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endfor
    <div class="wrapper">


        <div class="container" id="non-assegnati" style="padding-top:80px;">
            <h1>Non assegnati</h1>
            <div class="filters">
                <form class="filters-form">
                    <fieldset style="border:none; margin:0; padding:0; grid-column: 1;">
                        <legend style="font-weight:700; margin-bottom:8px;">Ruoli</legend>
                        <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                            <label><input type="checkbox" name="ruolo[]" value="por" checked>POR</label>
                            <label><input type="checkbox" name="ruolo[]" value="dc" checked>DC</label>
                            <label><input type="checkbox" name="ruolo[]" value="dd" checked>DD</label>
                            <label><input type="checkbox" name="ruolo[]" value="ds" checked>DS</label>
                            <label><input type="checkbox" name="ruolo[]" value="b" checked>B</label>
                            <label><input type="checkbox" name="ruolo[]" value="m" checked>M</label>
                            <label><input type="checkbox" name="ruolo[]" value="e" checked>E</label>
                            <label><input type="checkbox" name="ruolo[]" value="c" checked>C</label>
                            <label><input type="checkbox" name="ruolo[]" value="t" checked>T</label>
                            <label><input type="checkbox" name="ruolo[]" value="w" checked>W</label>
                            <label><input type="checkbox" name="ruolo[]" value="a" checked>A</label>
                            <label><input type="checkbox" name="ruolo[]" value="pc" checked>PC</label>
                        </div>
                    </fieldset>
                    <div style="grid-column: 2; display: flex; flex-direction: column; gap: 8px;">
                        <label for="search" style="font-weight:700;">Cerca</label>
                        <input type="text" id="search" name="search" placeholder="Giocatore o squadra"
                            style="width:100%; padding:6px;">
                    </div>
                    <fieldset style="border:none; margin:0; padding:0; grid-column: 3;">
                        <legend style="font-weight:700; margin-bottom:8px;">Stato</legend>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <label><input type="radio" name="estratti" value="tutti" checked> Tutti</label>
                            <label><input type="radio" name="estratti" value="estratti"> Estratti</label>
                            <label><input type="radio" name="estratti" value="non-estratti"> Non estratti</label>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div style="overflow-x:auto;margin-top:20px;">
                <table class="responsive-table" data-id-allenatore="0">
>>>>>>> 3a216c5f7f8303861146d71f7540234e08cefec2
                    <thead>
                        <tr>
                            <th>Giocatore</th>
                            <th>Squadra</th>
                            <th>Ruolo</th>
                            <th>Prezzo base</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
<<<<<<< HEAD
                            $totale = 0;
                            
                            // Definisco l'ordine dei ruoli
                            $ordineRuoli = ['PC' => 1, 'A' => 2, 'W' => 3, 'T' => 4, 'C' => 5, 'E' => 6, 'M' => 7, 'DD' => 8, 'DS' => 9, 'B' => 10, 'DC' => 11, 'POR' => 12];
                            
                            // Funzione per ottenere il primo ruolo per l'ordinamento
                            $getPrimoRuolo = function($ruoloString) use ($ordineRuoli) {
=======
                            // Definisco l'ordine dei ruoli
$ordineRuoli = [
    'PC' => 1,
    'A' => 2,
    'W' => 3,
    'T' => 4,
    'C' => 5,
    'E' => 6,
    'M' => 7,
    'DD' => 8,
    'DS' => 9,
    'B' => 10,
    'DC' => 11,
    'POR' => 12,
];

// Funzione per ottenere il primo ruolo per l'ordinamento
                            $getPrimoRuolo = function ($ruoloString) use ($ordineRuoli) {
>>>>>>> 3a216c5f7f8303861146d71f7540234e08cefec2
                                $ruoli = explode(';', $ruoloString);
                                $minOrdine = 999;
                                foreach ($ruoli as $ruolo) {
                                    $ruolo = strtoupper(trim($ruolo));
                                    if (isset($ordineRuoli[$ruolo]) && $ordineRuoli[$ruolo] < $minOrdine) {
                                        $minOrdine = $ordineRuoli[$ruolo];
                                    }
                                }
                                return $minOrdine;
                            };
<<<<<<< HEAD
                            
                            // Ordino i giocatori
                            $giocatoriOrdinati = $allenatori[$i]['giocatori'];
                            usort($giocatoriOrdinati, function($a, $b) use ($getPrimoRuolo) {
                                $ruoloA = $getPrimoRuolo($a['R']);
                                $ruoloB = $getPrimoRuolo($b['R']);
                                
                                if ($ruoloA != $ruoloB) {
                                    return $ruoloA - $ruoloB;
                                }
                                
                                // Se hanno lo stesso ruolo, ordino per Qt.A. (decrescente)
                                return ($b['Qt.A.'] ?? 0) - ($a['Qt.A.'] ?? 0);
                            });
                        @endphp
                        @for ($j = 0; $j < count($giocatoriOrdinati); $j++)
                            @php
                                $totale += $giocatoriOrdinati[$j]['Prezzo'];
                            @endphp
                            <tr data-id-giocatore="{{ $giocatoriOrdinati[$j]['Id'] }}">
=======

                            // Ordino i giocatori non assegnati
                            $giocatoriOrdinati = $giocatori;
                            usort($giocatoriOrdinati, function ($a, $b) use ($getPrimoRuolo) {
                                $ruoloA = $getPrimoRuolo($a['R']);
                                $ruoloB = $getPrimoRuolo($b['R']);

                                if ($ruoloA != $ruoloB) {
                                    return $ruoloA - $ruoloB;
                                }

                                // Se hanno lo stesso ruolo, ordino per Qt.A. (decrescente)
                                return ($b['Qt.A'] ?? 0) - ($a['Qt.A'] ?? 0);
                            });
                            $giocatoriOrdinati = array_values(array_filter($giocatoriOrdinati, fn($giocatore) => !isset($giocatore['AllenatoreId'])));
                           //dd($giocatoriOrdinati);
                        @endphp
                        @for ($j = 0; $j < count($giocatoriOrdinati); $j++)
                            <tr data-id-giocatore="{{ $giocatoriOrdinati[$j]['Id'] }}"
                                data-ruolo-giocatore="{{ strtolower($giocatoriOrdinati[$j]['R']) }}"
                                data-estratto="{{ !empty($giocatoriOrdinati[$j]['Estratto']) ? 'true' : 'false' }}">
>>>>>>> 3a216c5f7f8303861146d71f7540234e08cefec2
                                <td class="nome">{{ $giocatoriOrdinati[$j]['Nome'] }}</td>
                                <td class="squadra">{{ $giocatoriOrdinati[$j]['Squadra'] }}</td>
                                <td class="ruoli">
                                    @php
                                        $ruoloString = $giocatoriOrdinati[$j]['R'];
                                        $ruoloArray = explode(';', $ruoloString);
                                    @endphp
                                    @foreach ($ruoloArray as $ruolo)
<<<<<<< HEAD
                                        <span class="ruolo {{ strtolower($ruolo) }}" data-ruolo="{{ strtolower($ruolo) }}">{{ $ruolo }}</span>
                                    @endforeach
                                </td>
                                <td class="prezzo">{{ $giocatoriOrdinati[$j]['Prezzo'] }}</td>
=======
                                        <span class="ruolo {{ strtolower($ruolo) }}"
                                            data-role="{{ strtolower($ruolo) }}">{{ $ruolo }}</span>
                                    @endforeach
                                </td>
                                <td class="prezzo">{{ $giocatoriOrdinati[$j]['Qt.A'] }}</td>
>>>>>>> 3a216c5f7f8303861146d71f7540234e08cefec2
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
<<<<<<< HEAD
    @endfor
    <div class="container">
        <h1>Non assegnati</h1>
        <div class="filters">
            <form class="filters-form">
                <fieldset style="border:none; margin:0; padding:0; grid-column: 1;">
                    <legend style="font-weight:700; margin-bottom:8px;">Ruoli</legend>
                    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                        <label><input type="checkbox" name="ruolo[]" value="por" checked>POR</label>
                        <label><input type="checkbox" name="ruolo[]" value="dc" checked>DC</label>
                        <label><input type="checkbox" name="ruolo[]" value="dd" checked>DD</label>
                        <label><input type="checkbox" name="ruolo[]" value="ds" checked>DS</label>
                        <label><input type="checkbox" name="ruolo[]" value="b" checked>B</label>
                        <label><input type="checkbox" name="ruolo[]" value="m" checked>M</label>
                        <label><input type="checkbox" name="ruolo[]" value="e" checked>E</label>
                        <label><input type="checkbox" name="ruolo[]" value="c" checked>C</label>
                        <label><input type="checkbox" name="ruolo[]" value="t" checked>T</label>
                        <label><input type="checkbox" name="ruolo[]" value="w" checked>W</label>
                        <label><input type="checkbox" name="ruolo[]" value="a" checked>A</label>
                        <label><input type="checkbox" name="ruolo[]" value="pc" checked>PC</label>
                    </div>
                </fieldset>
                <div style="grid-column: 2; display: flex; flex-direction: column; gap: 8px;">
                    <label for="search" style="font-weight:700;">Cerca</label>
                    <input type="text" id="search" name="search" placeholder="Giocatore o squadra"
                        style="width:100%; padding:6px;">
                </div>
                <fieldset style="border:none; margin:0; padding:0; grid-column: 3;">
                    <legend style="font-weight:700; margin-bottom:8px;">Stato</legend>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label><input type="radio" name="estratti" value="tutti" checked> Tutti</label>
                        <label><input type="radio" name="estratti" value="estratti"> Estratti</label>
                        <label><input type="radio" name="estratti" value="non-estratti"> Non estratti</label>
                    </div>
                </fieldset>
            </form>
        </div>
        <div style="overflow-x:auto;margin-top:20px;">
            <table class="responsive-table" data-id-allenatore="0">
                <thead>
                    <tr>
                        <th>Giocatore</th>
                        <th>Squadra</th>
                        <th>Ruolo</th>
                        <th>Prezzo base</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Definisco l'ordine dei ruoli
                        $ordineRuoli = ['PC' => 1, 'A' => 2, 'W' => 3, 'T' => 4, 'C' => 5, 'E' => 6, 'M' => 7, 'DD' => 8, 'DS' => 9, 'B' => 10, 'DC' => 11, 'POR' => 12];
                        
                        // Funzione per ottenere il primo ruolo per l'ordinamento
                        $getPrimoRuolo = function($ruoloString) use ($ordineRuoli) {
                            $ruoli = explode(';', $ruoloString);
                            $minOrdine = 999;
                            foreach ($ruoli as $ruolo) {
                                $ruolo = strtoupper(trim($ruolo));
                                if (isset($ordineRuoli[$ruolo]) && $ordineRuoli[$ruolo] < $minOrdine) {
                                    $minOrdine = $ordineRuoli[$ruolo];
                                }
                            }
                            return $minOrdine;
                        };
                        
                        // Ordino i giocatori non assegnati
                        $giocatoriOrdinati = $giocatori;
                        usort($giocatoriOrdinati, function($a, $b) use ($getPrimoRuolo) {
                            $ruoloA = $getPrimoRuolo($a['R']);
                            $ruoloB = $getPrimoRuolo($b['R']);
                            
                            if ($ruoloA != $ruoloB) {
                                return $ruoloA - $ruoloB;
                            }
                            
                            // Se hanno lo stesso ruolo, ordino per Qt.A. (decrescente)
                            return ($b['Qt.A'] ?? 0) - ($a['Qt.A'] ?? 0);
                        });
                    @endphp
                    @for ($j = 0; $j < count($giocatoriOrdinati); $j++)
                        <tr data-id-giocatore="{{ $giocatoriOrdinati[$j]['Id'] }}"
                            data-ruolo-giocatore="{{ strtolower($giocatoriOrdinati[$j]['R']) }}"
                            data-estratto="{{ !empty($giocatoriOrdinati[$j]['Estratto']) ? 'true' : 'false' }}">
                            <td class="nome">{{ $giocatoriOrdinati[$j]['Nome'] }}</td>
                            <td class="squadra">{{ $giocatoriOrdinati[$j]['Squadra'] }}</td>
                            <td class="ruoli">
                                @php
                                    $ruoloString = $giocatoriOrdinati[$j]['R'];
                                    $ruoloArray = explode(';', $ruoloString);
                                @endphp
                                @foreach ($ruoloArray as $ruolo)
                                    <span class="ruolo {{ strtolower($ruolo) }}" data-role="{{ strtolower($ruolo) }}">{{ $ruolo }}</span>
                                @endforeach
                            </td>
                            <td class="prezzo">{{ $giocatoriOrdinati[$j]['Qt.A'] }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
=======
>>>>>>> 3a216c5f7f8303861146d71f7540234e08cefec2
    </div>
    <div class="notice">notice</div>
    <script>
        const evtSource = new EventSource("https://swl3p7r9mx1sjklo0.run.place/api/sse");
        evtSource.onmessage = (event) => {

            if (typeof event.data !== 'undefined') {
                const allenatori = JSON.parse(event.data)
                allenatori.forEach(function(allenatore) {
                    allenatore.giocatori.forEach(function(giocatore) {
                        const rigaGiocatore = document.querySelector('[data-id-allenatore="' +
                            allenatore.Id + '"] [data-id-giocatore="' + giocatore.Id + '"]');
                        if (!rigaGiocatore) {
                            // 1) Aggiunge la riga alla tabella dell'allenatore
                            const tBody = document.querySelector('[data-id-allenatore="' + allenatore
                                .Id + '"] tbody');
                            const newRow = document.createElement('tr');
                            const firstTh = document.createElement('td');
                            firstTh.textContent = giocatore.Nome;
                            const secondTh = document.createElement('td');
                            secondTh.textContent = giocatore.Squadra;
                            const thirdTh = document.createElement('td');
                            const ruoliMantra = giocatore.R.split(';');
                            let playerMainRole
                            ruoliMantra.forEach(function(ruolo, index) {
                                if (!index)
                                    playerMainRole = ruolo.toLowerCase()
                                let span = document.createElement('span');
                                span.classList.add('ruolo');
                                span.classList.add(ruolo.toLowerCase());
                                span.dataset.ruolo = ruolo.toLowerCase();
                                span.innerHTML = ruolo;
                                thirdTh.appendChild(span);
                            });
                            const fourthTh = document.createElement('td');
                            fourthTh.textContent = giocatore.Prezzo;
                            fourthTh.classList.add('prezzo');
                            newRow.setAttribute('data-id-giocatore', giocatore.Id);
                            newRow.appendChild(firstTh);
                            newRow.appendChild(secondTh);
                            newRow.appendChild(thirdTh);
                            newRow.appendChild(fourthTh);
                            const currentRows = tBody.querySelectorAll('tr:not(.total-row)');
<<<<<<< HEAD
                            console.log(currentRows);
                            currentRows.forEach(function(currentRow) {
                                const roles = currentRow.querySelectorAll('.ruolo');
                                roles.forEach(function(role) {
                                    console.log(role.dataset.ruolo);
                                })
                            });
                            tBody.querySelector('.total-row').insertAdjacentElement('beforebegin',
                                newRow);
=======
                            ruoliOrdinati = [
                                'por',
                                'dc',
                                'b',
                                'ds',
                                'dd',
                                'm',
                                'e',
                                'c',
                                't',
                                'w',
                                'a',
                                'pc',
                            ];
                            if (!currentRows.length) {
                                tBody.querySelector('.total-row').insertAdjacentElement('beforebegin',
                                    newRow);
                            } else {
                                /* console.log('playerMainRole: ' + playerMainRole);
                                console.log('playerMainRole Position: ' + ruoliOrdinati.indexOf(
                                    playerMainRole));
                                let nearestRole
                                currentRows.forEach(function(currentRow, rowIndex) {
                                    const role = currentRow.querySelector('.ruolo');
                                    if (typeof position === 'undefined' || Math.abs(ruoliOrdinati.indexOf(
                                            playerMainRole) - nearestRole) >
                                        Math.abs(ruoliOrdinati.indexOf(
                                            playerMainRole) - ruoliOrdinati.indexOf(role
                                            .dataset.ruolo))) {

                                        nearestRole = ruoliOrdinati.indexOf(role
                                            .dataset.ruolo);
                                    }

                                });
                                console.log(nearestRole)
                                console.log(ruoliOrdinati[nearestRole])
                                tBody.querySelector('.total-row').insertAdjacentElement('beforebegin',
                                    newRow); */
                                let nearestRow = null;
                                let minDistance = Infinity;
                                currentRows.forEach(function(currentRow) {
                                    const role = currentRow.querySelector('.ruolo');
                                    if (!role) return;
                                    const roleIndex = ruoliOrdinati.indexOf(role.dataset.ruolo);
                                    const playerRoleIndex = ruoliOrdinati.indexOf(
                                        playerMainRole);
                                    const distance = Math.abs(playerRoleIndex - roleIndex);
                                    if (distance < minDistance) {
                                        minDistance = distance;
                                        nearestRow = currentRow;
                                    }
                                });
                                if (nearestRow) {
                                    nearestRow.insertAdjacentElement('afterend', newRow);
                                } else {
                                    tBody.querySelector('.total-row').insertAdjacentElement(
                                        'beforebegin', newRow);
                                }
                            }

>>>>>>> 3a216c5f7f8303861146d71f7540234e08cefec2

                            // 2) Rimuove la riga dalla tabella dei già estratti
                            const nonAssegnato = document.querySelector(
                                '[data-id-allenatore="0"] [data-id-giocatore="' + giocatore.Id +
                                '"]');
                            if (nonAssegnato) {
                                nonAssegnato.remove();
                            }

                            // 3) Ricalcola i totali
                            const righeGiocatori = tBody.querySelectorAll('[data-id-giocatore]');
                            let total = 0;
                            righeGiocatori.forEach(function(riga) {
                                const cellaPrezzo = riga.querySelector('.prezzo');
                                total += Number(cellaPrezzo.innerHTML.trim());
                            });
                            tBody.querySelector('.total-row .totale-speso').innerHTML = total;

                            const notice = document.querySelector('.notice');
                            notice.innerHTML = allenatore.Nome + ' ha preso ' + giocatore.Nome + ' (' +
<<<<<<< HEAD
                                giocatore.Squadra + ') a '+giocatore.Prezzo;
=======
                                giocatore.Squadra + ') a ' + giocatore.Prezzo;
>>>>>>> 3a216c5f7f8303861146d71f7540234e08cefec2
                            notice.classList.add('active');

                            setTimeout(() => {
                                notice.classList.remove('active');
                            }, 10000);
                        }
                    });
                })
            }
        };
        document.addEventListener('DOMContentLoaded', function() {
            const inputRuolo = document.querySelectorAll('[name="ruolo[]"]');
            const inputSearch = document.querySelector('[name="search"]');
            const inputState = document.querySelectorAll('[name="estratti"]');


            inputRuolo.forEach(input => {
                input.addEventListener('change', handleFilters);
            });
            inputSearch.addEventListener('input', handleFilters);
            inputState.forEach(input => {
                input.addEventListener('change', handleFilters);
            });

            function handleFilters() {

                const selectedRuoliInput = document.querySelectorAll('[name="ruolo[]"]:checked');
                const selectedestrattiInput = document.querySelector('[name="estratti"]:checked');
                const searchInputValue = document.querySelector('[name="search"]').value;
                const checkedRoles = [];
                selectedRuoliInput.forEach(function(ruoloInput) {
                    checkedRoles.push(ruoloInput.value.trim());
                });
                const righeNonestratti = document.querySelectorAll('[data-id-allenatore="0"] tbody tr');
                righeNonestratti.forEach(function(row) {
                    const nomeGiocatore = row.querySelector('td.nome').innerHTML.trim().toLowerCase();
                    const nomeSquadra = row.querySelector('td.squadra').innerHTML.trim().toLowerCase();

                    const ruoliGiocatore = row.dataset.ruoloGiocatore.toLowerCase().split(';');
<<<<<<< HEAD
                    if (searchInputValue && (nomeGiocatore.slice(0, searchInputValue.length) !==
                            searchInputValue) && nomeSquadra.slice(0, searchInputValue.length) !==
                        searchInputValue) {
=======
                    if (searchInputValue.toLowerCase() && (nomeGiocatore.slice(0, searchInputValue.length) !==
                            searchInputValue.toLowerCase()) && nomeSquadra.slice(0, searchInputValue.length) !==
                        searchInputValue.toLowerCase()) {
>>>>>>> 3a216c5f7f8303861146d71f7540234e08cefec2
                        // Il nome non inizia con la stringa
                        row.style.display = 'none';
                    } else if (!ruoliGiocatore.filter(x => checkedRoles.includes(x)).length) {
                        // L'intersezione è vuota
                        row.style.display = 'none';
                    } else if ((selectedestrattiInput.value === 'estratti' && row.dataset.estratto ===
                            'false') || (selectedestrattiInput.value === 'non-estratti' && row.dataset
                            .estratto === 'true')) {
                        row.style.display = 'none';
                    } else {
                        row.style.display = 'table-row';
                    }
                });

            }
        });
    </script>
</body>

</html>
