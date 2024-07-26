<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <script>
        fetch({!! "'" . route('allenatore', ['id' => 3]) ."'" !!})
            .then((data) => data.json())
            .then(function(json_data) {
                console.log(json_data)
            })

        // fetch({!! "'" . route('riponi') . "'" !!}, {
        // //fetch({!! "'" . route('svincola') . "'" !!}, {
        //         method: 'POST',
        //         body: JSON.stringify({
        //             id_giocatore: '256',
        //             id_allenatore: '3',
        //             prezzo: '9'
        //         }),
        //         headers: {
        //             'Content-Type': 'application/json'
        //         }
        //     })
        //     .then((data) => data.json())
        //     .then(function(json_data) {
        //         console.log(json_data)
        //     })
    </script>
</body>

</html>
