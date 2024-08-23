<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AllenatoreController extends Controller
{
    public function getAllenatori()
    {
        $allenatori = Storage::json('public\allenatori.json');
        return $allenatori;
    }

    public function showAllenatore($id)
    {
        $allenatori = Storage::json('public\allenatori.json');
        $giocatori = Storage::json('public\giocatori.json');
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
    }
}
