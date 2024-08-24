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
     * @author Valerio Porporato <valerio.porpo@gmail.com>
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

    /**
     * Restituisce il giocatore con l'id passato nella richiesta.
     * 
     * @author Valerio Porporato <valerio.porpo@gmail.com>
     * 
     * @param int $id
     * @return string
     */
    function showGiocatore($id)
    {
        $giocatori = Storage::json($this->giocatori_path);
        return array_values(array_filter($giocatori, fn($item) => $item['Id'] == $id))[0];
    }

    /**
     * Marca come estratto il giocatore con l'id passato nella richiesta.
     * Se il giocatore è già stato estratto, restituisce un messaggio di errore.
     * 
     * @author Valerio Porporato <valerio.porpo@gmail.com>
     * 
     * @param Request $request
     * @return Response
     */
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
                        'message' => 'Giocatore con id ' . $request->input('id_giocatore') . ' estratto',
                        'giocatore' => $giocatore_risposta
                    ], 200);
                }
            }
        }

        return response()->json([
            'code' => 'bad_request',
            'message' => 'Il giocatore con id ' . $request->input('id_giocatore') . ' è già stato estratto'
        ], 400);
    }

    /**
     * Ripone il giocatore con l'id passato nella richiesta.
     * Se il giocatore non è stato estratto, restituisce un messaggio di errore.
     * 
     * @author Valerio Porporato <valerio.porpo@gmail.com>
     * 
     * @param Request $request
     * @return Response
     */
    function riponiGiocatore(Request $request)
    {

        $giocatori = Storage::json($this->giocatori_path);

        foreach ($giocatori as $key => $giocatore) {
            if ($giocatore['Id'] == $request->input('id_giocatore')) {
                $giocatore_risposta = $giocatori[$key];
                if (isset($giocatori[$key]['Estratto'])) {
                    unset($giocatori[$key]['Estratto']);
                    Storage::disk('local')->put($this->giocatori_path, json_encode($giocatori));
                    $giocatore_risposta = $giocatori[$key];
                    return response()->json([
                        'code' => 'success',
                        'message' => 'Giocatore con id ' . $request->input('id_giocatore') . ' riposto',
                        'giocatore' => $giocatore_risposta
                    ], 200);
                }
            }
        }
        return response()->json([
            'code' => 'bad_request',
            'message' => 'Il giocatore con id ' . $request->input('id_giocatore') . ' non è stato estratto'
        ], 400);
    }

    /**
     * Assegna il giocatore con l'id passato nella richiesta all'allenatore con l'id passato nella richiesta.
     * Se il giocatore è già stato assegnato ad un allenatore, restituisce un messaggio di errore.
     * 
     * @author Valerio Porporato <valerio.porpo@gmail.com>
     * 
     * @param Request $request
     * @return Response
     */
    function buyGiocatore(Request $request)
    {

        $giocatori = Storage::json($this->giocatori_path);

        foreach ($giocatori as $key => $giocatore) {
            if ($giocatore['Id'] == $request->input('id_giocatore') && !isset($giocatore['AllenatoreId']) && !isset($giocatore['Prezzo'])) {
                $giocatore_risposta = $giocatori[$key];

                $giocatori[$key]['AllenatoreId'] = $request->input('id_allenatore');
                $giocatori[$key]['Prezzo'] = (int)$request->input('prezzo');
                $giocatore_risposta = $giocatori[$key];
                Storage::disk('local')->put($this->giocatori_path, json_encode($giocatori));

                return response()->json([
                    'code' => 'success',
                    'message' => 'Giocatore con id ' . $request->input('id_giocatore') . ' assegnato all\'allenatore con id ' . $request->input('id_allenatore') . ' per ' . $request->input('prezzo') . ' crediti',
                    'giocatore' => $giocatore_risposta
                ], 200);
            }
        }

        return response()->json([
            'code' => 'bad_request',
            'message' => 'Il giocatore con id ' . $request->input('id_giocatore') . ' ha già una squadra',
        ], 400);
    }

    /**
     * Svincola il giocatore con l'id passato nella richiesta.
     * Se il giocatore non è assegnato ad un allenatore, restituisce un messaggio di errore.
     * 
     * @author Valerio Porporato <valerio.porpo@gmail.com>
     * 
     * @param Request $request
     * @return Response
     */
    function svincolaGiocatore(Request $request)
    {

        $giocatori = Storage::json($this->giocatori_path);

        foreach ($giocatori as $key => $giocatore) {
            if ($giocatore['Id'] == $request->input('id_giocatore') && isset($giocatore['AllenatoreId']) && isset($giocatore['Prezzo'])) {
                $giocatore_risposta = $giocatori[$key];
                if (isset($giocatori[$key]['Prezzo'])) {

                    unset($giocatori[$key]['Prezzo']);
                    unset($giocatori[$key]['AllenatoreId']);
                    Storage::disk('local')->put($this->giocatori_path, json_encode($giocatori));

                    $giocatore_risposta = $giocatori[$key];
                    return response()->json([
                        'code' => 'success',
                        'message' => 'Giocatore con id ' . $request->input('id_giocatore') . ' svincolato',
                        'giocatore' => $giocatore_risposta
                    ], 200);
                }
            }
        }

        return response()->json([
            'code' => 'bad_request',
            'message' => 'Il giocatore con id ' . $request->input('id_giocatore') . ' non appartiene a nessuna squadra'
        ], 400);
    }
}
