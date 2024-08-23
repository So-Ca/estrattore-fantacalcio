<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class GiocatoreController extends Controller
{

    private $allenatori_path = 'public\allenatori.json';
    private $giocatori_path = 'public\giocatori.json';

    /**
     * Restituisce un sottoinsieme dei giocatori.
     * Il sottoinsieme è filtrato in base al parametro 'tipo' passato nella richiesta
     * e può coincidere con i giocatori estratti o con i giocatori non estratti
     * o con tutti i giocatori.
     * 
     * @author Valerio Porporato
     * 
     * @param Request $request
     * @return array $giocatori
     */
    function getGiocatori(Request $request)
    {
        $giocatori = Storage::json($this->giocatori_path);

        if (isset($request->tipo) && $request->tipo === 'estratti') {
            $filter = fn($item) => isset($item['Estratto']);
        } elseif (isset($request->tipo) && $request->tipo === 'non-estratti') {
            $filter = fn($item) => !isset($item['Estratto']);
        } else {
            $filter = fn($item) => true;
        }

        $giocatori = array_values(array_filter($giocatori, $filter));
        return $giocatori;
    }

    function showGiocatore($id)
    {
        $giocatori = Storage::json($this->giocatori_path);
        return array_filter($giocatori, fn($item) => $item['Id'] == $id);
    }

    function extractGiocatore(Request $request)
    {

        $giocatori = Storage::json($this->giocatori_path);
        $giocatore = array_values(array_filter($giocatori, fn($item) => $item['Id'] == $request->input('id_giocatore')));

        foreach ($giocatori as $key => $giocatore) {
            if ($giocatore['Id'] == $request->input('id_giocatore')) {
                $giocatore_risposta = $giocatori[$key];
                if (!isset($giocatori[$key]['Estratto'])) {
                    $giocatori[$key]['Estratto'] = true;
                    $giocatore_risposta = $giocatori[$key];
                    Storage::disk('local')->put($this->giocatori_path, json_encode($giocatori));

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

    function riponiGiocatore(Request $request)
    {

       /*  if (!$request->has('id_giocatore')) {
            return response()->json([
                'code' => 'missing_param',
                'message' => 'No \'id_giocatore\' provided'
            ], 400);
        } elseif ($request->input('id_giocatore') != (int) $request->input('id_giocatore')) {
            return response()->json([
                'code' => 'bad_param',
                'message' => 'Param \'id_giocatore\' must be integer string'
            ], 400);
        } */
         $giocatori = Storage::json($this->giocatori_path);
       // $giocatore = array_values(array_filter($giocatori, fn($item) => $item['Id'] == $request->input('id_giocatore')));
        /*if (empty($giocatore)) {
            return response()->json([
                'code' => 'not_found',
                'message' => 'No player found with id ' . $request->input('id_giocatore')
            ], 404);
        } else { */
            foreach ($giocatori as $key => $giocatore) {
                if ($giocatore['Id'] == $request->input('id_giocatore')) {
                    $giocatore_risposta = $giocatori[$key];
                    if (isset($giocatori[$key]['Estratto'])) {
                        unset($giocatori[$key]['Estratto']);
                        Storage::disk('local')->put($this->giocatori_path, json_encode($giocatori));
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
       // }
    }

    function buyGiocatore(Request $request)
    {

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
        $giocatori = Storage::json($this->giocatori_path);
        $giocatore = array_values(array_filter($giocatori, fn($item) => $item['Id'] == $request->input('id_giocatore')));
        $allenatori = Storage::json($this->allenatori_path);
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
                    Storage::disk('local')->put($this->giocatori_path, json_encode($giocatori));

                    return response()->json([
                        'code' => 'success',
                        'message' => 'Player with id ' . $request->input('id_giocatore') . ' assigned to manager with id ' . $request->input('id_allenatore') . ' for ' . $request->input('prezzo') . ' credits',
                        'player' => $giocatore_risposta
                    ], 200);
                }
            }
        }
    }

    function svincolaGiocatore(Request $request)
    {

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
        $giocatori = Storage::json($this->giocatori_path);
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
                        Storage::disk('local')->put($this->giocatori_path, json_encode($giocatori));
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
    }
}
