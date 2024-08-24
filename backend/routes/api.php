<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AllenatoreController;
use App\Http\Controllers\GiocatoreController;

use App\Http\Middleware\EnsureGiocatoriSubsetIsValid;
use App\Http\Middleware\EnsureGiocatoreIdIsValid;
use App\Http\Middleware\EnsureAllenatoreIdIsValid;
use App\Http\Middleware\EnsurePrezzoIsValid;

Route::get('/giocatori/{tipo?}', [GiocatoreController::class, 'getGiocatori'])
    ->middleware(EnsureGiocatoriSubsetIsValid::class)
    ->name('list-giocatori');

Route::get('/giocatore/{id_giocatore}', [GiocatoreController::class, 'showGiocatore'])
    ->middleware(EnsureGiocatoreIdIsValid::class)
    ->name('giocatore');

Route::post('estrai', [GiocatoreController::class, 'extractGiocatore'])
    ->middleware(EnsureGiocatoreIdIsValid::class)
    ->name('estrai');
Route::post('riponi', [GiocatoreController::class, 'riponiGiocatore'])
    ->middleware(EnsureGiocatoreIdIsValid::class)
    ->name('riponi');
Route::post('acquista', [GiocatoreController::class, 'buyGiocatore'])
    ->middleware([
        EnsureGiocatoreIdIsValid::class,
        EnsureAllenatoreIdIsValid::class,
        EnsurePrezzoIsValid::class
    ])
    ->name('acquista');
Route::post('svincola', [GiocatoreController::class, 'svincolaGiocatore'])->name('svincola');


Route::get('/allenatori', [AllenatoreController::class, 'getAllenatori'])->name('allenatori');
Route::get('/allenatore/{id_allenatore}', [AllenatoreController::class, 'showAllenatore'])
    ->middleware(EnsureAllenatoreIdIsValid::class)
    ->name('allenatore');
