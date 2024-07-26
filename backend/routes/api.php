<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::get('/giocatori/{tipo?}', function (Request $r) {

    $giocatori = Storage::json('giocatori.json');
    if ($r->tipo === 'estratti') {
        $giocatori = array_values(array_filter($giocatori, fn($item) => isset($item['Estratto'])));
    } elseif ($r->tipo === 'non-estratti') {
        $giocatori = array_values(array_filter($giocatori, fn($item) => !isset($item['Estratto'])));
    }
    return $giocatori;
})->whereIn('tipo', ['estratti', 'non-estratti'])
    ->name('list-giocatori');

Route::get('/giocatore/{id}', function ($id) {
    $giocatori = Storage::json('giocatori.json');
    return array_filter($giocatori, fn($item) => $item['Id'] == $id);
})->where('id', '[0-9]+');

Route::get('/allenatori', function () {
    $allenatori = Storage::json('allenatori.json');
    return $allenatori;
})->name('allenatori');

Route::get('/allenatore/{id}', function ($id) {
    $allenatori = Storage::json('allenatori.json');
    $giocatori = Storage::json('giocatori.json');
    $allenatore = array_values(array_filter($allenatori, fn($item) => $item['Id'] == $id));
    if (!empty($allenatore)) {
        $allenatore = $allenatore[0];
        $giocatori = array_values(array_filter($giocatori, fn($item) => (isset($item['AllenatoreId']) && $item['AllenatoreId'] == $id)));
        $allenatore['giocatori'] = $giocatori;

        $crediti_spesi = 0;
        foreach ($giocatori as $giocatore) {
            if (isset($giocatore['Prezzo'])) {
                $crediti_spesi += (int)$giocatore['Prezzo'];
            }
        }
        $allenatore['CreditiSpesi'] = $crediti_spesi;
        return $allenatore;
    }
})->where('id', '[0-9]+')->name('allenatore');

Route::post('estrai', function (Request $request) {

    if (!$request->has('id_giocatore')) {
        return response()->json([
            'code' => 'missing_param',
            'message' => 'No \'id_giocatore\' provided'
        ], 400);
    } elseif ($request->input('id_giocatore') != (int) $request->input('id_giocatore')) {
        return response()->json([
            'code' => 'bad_param',
            'message' => 'Param \'id_giocatore\' must be integer string'
        ], 400);
    }
    $giocatori = Storage::json('giocatori.json');
    $giocatore = array_values(array_filter($giocatori, fn($item) => $item['Id'] == $request->input('id_giocatore')));
    if (empty($giocatore)) {
        return response()->json([
            'code' => 'not_found',
            'message' => 'no player found with id ' . $request->input('id_giocatore')
        ], 404);
    } else {
        foreach ($giocatori as $key => $giocatore) {
            if ($giocatore['Id'] == $request->input('id_giocatore')) {
                $giocatore_risposta = $giocatori[$key];
                if (!isset($giocatori[$key]['Estratto'])) {
                    $giocatori[$key]['Estratto'] = true;
                    $giocatore_risposta = $giocatori[$key];
                    Storage::disk('local')->put('giocatori.json', json_encode($giocatori));

                    return response()->json([
                        'code' => 'success',
                        'message' => 'Player with id ' . $request->input('id_giocatore') . ' drawn',
                        'player' => $giocatore_risposta
                    ], 200);
                }
            }
        }
        return response()->json([
            'code' => 'success',
            'message' => 'Player with id ' . $request->input('id_giocatore') . ' has already been drawn',
            'player' => $giocatore_risposta
        ], 200);
    }
})->name('estrai');

Route::post('riponi', function (Request $request) {

    if (!$request->has('id_giocatore')) {
        return response()->json([
            'code' => 'missing_param',
            'message' => 'No \'id_giocatore\' provided'
        ], 400);
    } elseif ($request->input('id_giocatore') != (int) $request->input('id_giocatore')) {
        return response()->json([
            'code' => 'bad_param',
            'message' => 'Param \'id_giocatore\' must be integer string'
        ], 400);
    }
    $giocatori = Storage::json('giocatori.json');
    $giocatore = array_values(array_filter($giocatori, fn($item) => $item['Id'] == $request->input('id_giocatore')));
    if (empty($giocatore)) {
        return response()->json([
            'code' => 'not_found',
            'message' => 'No player found with id ' . $request->input('id_giocatore')
        ], 404);
    } else {
        foreach ($giocatori as $key => $giocatore) {
            if ($giocatore['Id'] == $request->input('id_giocatore')) {
                $giocatore_risposta = $giocatori[$key];
                if (isset($giocatori[$key]['Estratto'])) {
                    unset($giocatori[$key]['Estratto']);
                    Storage::disk('local')->put('giocatori.json', json_encode($giocatori));
                    $giocatore_risposta = $giocatori[$key];
                    return response()->json([
                        'code' => 'success',
                        'message' => 'Player with id ' . $request->input('id_giocatore') . ' has been put away',
                        'player' => $giocatore_risposta
                    ], 200);
                }
            }
        }
        return response()->json([
            'code' => 'success',
            'message' => 'Player with id ' . $request->input('id_giocatore') . ' is not drawn',
            'player' => $giocatore_risposta
        ], 200);
    }
})->name('riponi');


Route::post('acquista', function (Request $request) {

    if (!$request->has('id_giocatore') || !$request->has('id_allenatore') || !$request->has('prezzo')) {
        return response()->json([
            'code' => 'missing_param',
            'message' => 'One of \'id_giocatore\', \'id_allenatore\' and \'prezzo\' is missing'
        ], 400);
    } elseif ($request->input('id_giocatore') != (int) $request->input('id_giocatore')) {
        return response()->json([
            'code' => 'bad_param',
            'message' => 'Param \'id_giocatore\' must be integer string'
        ], 400);
    } elseif ($request->input('id_allenatore') != (int) $request->input('id_allenatore')) {
        return response()->json([
            'code' => 'bad_param',
            'message' => 'Param \'id_allenatore\' must be integer string'
        ], 400);
    } elseif ($request->input('prezzo') != (int) $request->input('prezzo')) {
        return response()->json([
            'code' => 'bad_param',
            'message' => 'Param \'prezzo\' must be integer string'
        ], 400);
    }
    $giocatori = Storage::json('giocatori.json');
    $giocatore = array_values(array_filter($giocatori, fn($item) => $item['Id'] == $request->input('id_giocatore')));
    $allenatori = Storage::json('allenatori.json');
    $allenatore = array_values(array_filter($allenatori, fn($item) => $item['Id'] == $request->input('id_allenatore')));
    if (empty($giocatore)) {
        return response()->json([
            'code' => 'not_found',
            'message' => 'no player found with id ' . $request->input('id_giocatore')
        ], 404);
    } elseif (empty($allenatore)) {
        return response()->json([
            'code' => 'not_found',
            'message' => 'no manager found with id ' . $request->input('id_allenatore')
        ], 404);
    } elseif ($request->input('prezzo') < 1) {
        return response()->json([
            'code' => 'invalid_price',
            'message' => '\'prezzo\' must be greater than 0'
        ], 404);
    } else {
        foreach ($giocatori as $key => $giocatore) {
            if ($giocatore['Id'] == $request->input('id_giocatore')) {
                $giocatore_risposta = $giocatori[$key];

                $giocatori[$key]['AllenatoreId'] = $request->input('id_allenatore');
                $giocatori[$key]['Prezzo'] = (int)$request->input('prezzo');
                $giocatore_risposta = $giocatori[$key];
                Storage::disk('local')->put('giocatori.json', json_encode($giocatori));

                return response()->json([
                    'code' => 'success',
                    'message' => 'Player with id ' . $request->input('id_giocatore') . ' assigned to manager with id ' . $request->input('id_allenatore') . ' for '.$request->input('prezzo').' credits',
                    'player' => $giocatore_risposta
                ], 200);


            }
        }

    }
})->name('acquista');

Route::post('svincola', function (Request $request) {

    if (!$request->has('id_giocatore')) {
        return response()->json([
            'code' => 'missing_param',
            'message' => 'No \'id_giocatore\' provided'
        ], 400);
    } elseif ($request->input('id_giocatore') != (int) $request->input('id_giocatore')) {
        return response()->json([
            'code' => 'bad_param',
            'message' => 'Param \'id_giocatore\' must be integer string'
        ], 400);
    }
    $giocatori = Storage::json('giocatori.json');
    $giocatore = array_values(array_filter($giocatori, fn($item) => $item['Id'] == $request->input('id_giocatore')));
    if (empty($giocatore)) {
        return response()->json([
            'code' => 'not_found',
            'message' => 'No player found with id ' . $request->input('id_giocatore')
        ], 404);
    } else {
        foreach ($giocatori as $key => $giocatore) {
            if ($giocatore['Id'] == $request->input('id_giocatore')) {
                $giocatore_risposta = $giocatori[$key];
                if (isset($giocatori[$key]['Prezzo'])) {
                    unset($giocatori[$key]['Prezzo']);
                    unset($giocatori[$key]['AllenatoreId']);
                    Storage::disk('local')->put('giocatori.json', json_encode($giocatori));
                    $giocatore_risposta = $giocatori[$key];
                    return response()->json([
                        'code' => 'success',
                        'message' => 'Player with id ' . $request->input('id_giocatore') . ' has been released',
                        'player' => $giocatore_risposta
                    ], 200);
                }
            }
        }
        return response()->json([
            'code' => 'success',
            'message' => 'Player with id ' . $request->input('id_giocatore') . ' is not from any team',
            'player' => $giocatore_risposta
        ], 200);
    }
})->name('svincola');
