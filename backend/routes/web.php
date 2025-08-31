<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllenatoreController;


Route::get('/', function () {
    $allenatoreController = new AllenatoreController();
    $allenatori = $allenatoreController->getAllenatori();
    $viewAllenatori = [];
    for ($i = 0; $i < count($allenatori); $i++) {
        $viewAllenatori[$i] = $allenatoreController->showAllenatore($allenatori[$i]['Id']);
    }

    return view('report', ['allenatori' => $viewAllenatori]);
});
