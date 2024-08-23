<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AllenatoreController extends Controller
{

    private $allenatori_path = 'public\allenatori.json';
    private $giocatori_path = 'public\giocatori.json';
    
    public function getAllenatori()
    {
        $allenatori = Storage::json($this->allenatori_path);
        return $allenatori;
    }

    public function showAllenatore($id)
    {
        $allenatori = Storage::json($this->allenatori_path);
        $giocatori = Storage::json($this->giocatori_path);
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
