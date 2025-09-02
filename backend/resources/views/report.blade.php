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
    </style>
</head>

<body>
    @for ($i = 0; $i < count($allenatori); $i++)
        <div class="container">
            <h1>{{ $allenatori[$i]['Squadra'] }} <small>{{ $allenatori[$i]['Nome'] }}</small></h1>
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
                        @endphp
                        @for ($j = 0; $j < count($allenatori[$i]['giocatori']); $j++)
                            @php
                                $totale += $allenatori[$i]['giocatori'][$j]['Prezzo'];
                            @endphp
                            <tr data-id-giocatore="{{ $allenatori[$i]['giocatori'][$j]['Id'] }}">
                                <td class="nome">{{ $allenatori[$i]['giocatori'][$j]['Nome'] }}</td>
                                <td class="squadra">{{ $allenatori[$i]['giocatori'][$j]['Squadra'] }}</td>
                                <td class="ruolo">
                                    @php
                                        $ruoloString = $allenatori[$i]['giocatori'][$j]['RM'];
                                        $ruoloArray = explode(';', $ruoloString);
                                    @endphp
                                    @foreach ($ruoloArray as $ruolo)
                                        <span class="ruolo {{ strtolower($ruolo) }}">{{ $ruolo }}</span>
                                    @endforeach
                                </td>
                                <td class="prezzo">{{ $allenatori[$i]['giocatori'][$j]['Prezzo'] }}</td>
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
    @endfor
    <div class="container">
        <h1>Non assegnati</h1>
        <div class="filters">
            <form class="filters-form">
                <fieldset style="border:none; margin:0; padding:0; grid-column: 1;">
                    <legend style="font-weight:700; margin-bottom:8px;">Ruoli</legend>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        <label><input type="checkbox" name="ruolo[]" value="por"> POR</label>
                        <label><input type="checkbox" name="ruolo[]" value="dc"> DC</label>
                        <label><input type="checkbox" name="ruolo[]" value="dd"> DD</label>
                        <label><input type="checkbox" name="ruolo[]" value="ds"> DS</label>
                        <label><input type="checkbox" name="ruolo[]" value="b"> B</label>
                        <label><input type="checkbox" name="ruolo[]" value="m"> M</label>
                        <label><input type="checkbox" name="ruolo[]" value="e"> E</label>
                        <label><input type="checkbox" name="ruolo[]" value="c"> C</label>
                        <label><input type="checkbox" name="ruolo[]" value="t"> T</label>
                        <label><input type="checkbox" name="ruolo[]" value="w"> W</label>
                        <label><input type="checkbox" name="ruolo[]" value="a"> A</label>
                        <label><input type="checkbox" name="ruolo[]" value="pc"> PC</label>
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
                        <label><input type="radio" name="assegnati" value="tutti" checked> Tutti</label>
                        <label><input type="radio" name="assegnati" value="assegnati"> Assegnati</label>
                        <label><input type="radio" name="assegnati" value="non-assegnati"> Non assegnati</label>
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
                    @for ($j = 0; $j < count($giocatori); $j++)
                        <tr data-id-giocatore="{{ $giocatori[$j]['Id'] }}">
                            <td class="nome">{{ $giocatori[$j]['Nome'] }}</td>
                            <td class="squadra">{{ $giocatori[$j]['Squadra'] }}</td>
                            <td class="ruolo">
                                @php
                                    $ruoloString = $giocatori[$j]['RM'];
                                    $ruoloArray = explode(';', $ruoloString);
                                @endphp
                                @foreach ($ruoloArray as $ruolo)
                                    <span class="ruolo {{ strtolower($ruolo) }}">{{ $ruolo }}</span>
                                @endforeach
                            </td>
                            <td class="prezzo">{{ $giocatori[$j]['Qt.A'] }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    <script>
        const evtSource = new EventSource("https://www.swl3p7r9mx1sjklo0.run.place/api/sse");
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
                            const ruoliMantra = giocatore.RM.split(';');
                            ruoliMantra.forEach(function(ruolo) {
                                let span = document.createElement('span');
                                span.classList.add('ruolo');
                                span.classList.add(ruolo.toLowerCase());
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
                            tBody.querySelector('.total-row').insertAdjacentElement('beforebegin',
                                newRow);

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

                        }
                    });
                })
            }
        };
        document.addEventListener('DOMContentLoaded', function() {
            const inputRuolo = document.querySelectorAll('[name="ruolo[]"]');
            const inputSearch = document.querySelector('[name="search"]');
            const inputState = document.querySelectorAll('[name="assegnati"]');
            console.log({
                inputRuolo,
                inputSearch,
                inputState
            });

            inputRuolo.forEach(input => {
                input.addEventListener('change', handleFilters);
            });
            inputSearch.addEventListener('input', handleFilters);
            inputState.forEach(input => {
                input.addEventListener('change', handleFilters);
            });

            function handleFilters() {

                const selectedRuoliInput = document.querySelectorAll('[name="ruolo[]"]:checked');
                const selectedAssegnatiInput = document.querySelector('[name="assegnati"]:checked');
                const searchInputValue = document.querySelector('[name="search"]').value;

                const righeNonAssegnati = document.querySelectorAll('[data-id-allenatore="0"] tbody tr');
                righeNonAssegnati.forEach(function(row) {
                    const nomeGiocatore = row.querySelector('td.nome').innerHTML.trim().toLowerCase();
                    const nomeSquadra = row.querySelector('td.squadra').innerHTML.trim().toLowerCase();
                    if(searchInputValue && (nomeGiocatore.slice(0, searchInputValue.length) !== searchInputValue) && nomeSquadra.slice(0, searchInputValue.length) !== searchInputValue) {
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
