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
                padding: 8px 4px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    @for ($i = 0; $i < count($allenatori); $i++)
        <div class="container">
            <h1>{{ $allenatori[$i]['Squadra'] }} <small>{{ $allenatori[$i]['Nome'] }}</small></h1>
            <div style="overflow-x:auto;">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Giocatore</th>
                            <th>Squadra</th>
                            <th>Ruolo</th>
                            <th>Prezzo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($j = 0; $j < count($allenatori[$i]['giocatori']); $j++)
                            <tr data-id="{{ $allenatori[$i]['giocatori'][$j]['Id'] }}">
                                <td>{{ $allenatori[$i]['giocatori'][$j]['Nome'] }}</td>
                                <td>{{ $allenatori[$i]['giocatori'][$j]['Squadra'] }}</td>
                                <td>{{ $allenatori[$i]['giocatori'][$j]['RM'] }}</td>
                                <td>{{ $allenatori[$i]['giocatori'][$j]['Prezzo'] }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    @endfor
    <script>
        const evtSource = new EventSource("https://www.swl3p7r9mx1sjklo0.run.place/api/sse");
        evtSource.onmessage = (event) => {

            if (typeof event.data !== 'undefined') {
                const allenatori = JSON.parse(event.data)
                allenatori.forEach(function(allenatore) {
                    console.log(allenatore);
                })
            }
        };
    </script>
</body>

</html>
