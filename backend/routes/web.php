<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllenatoreController;
use App\Http\Controllers\GiocatoreController;
use Illuminate\Support\Facades\Storage;


Route::get('/', function () {
    $allenatoreController = new AllenatoreController();
    
    $giocatori = Storage::json('public\giocatori.json');

    $allenatori = $allenatoreController->getAllenatori();
    $viewAllenatori = [];
    for ($i = 0; $i < count($allenatori); $i++) {
        $viewAllenatori[$i] = $allenatoreController->showAllenatore($allenatori[$i]['Id']);
    }

    return view('report', ['allenatori' => $viewAllenatori, 'giocatori' => $giocatori]);
});
