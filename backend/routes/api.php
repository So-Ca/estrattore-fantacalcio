<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Http\Controllers\AllenatoreController;
use App\Http\Controllers\GiocatoreController;

Route::get('/giocatori/{tipo?}', [GiocatoreController::class, 'getGiocatori'])
    ->name('list-giocatori');
Route::get('/giocatore/{id}', [GiocatoreController::class, 'showGiocatore'])
->where('id', '[0-9]+')
->name('giocatore');
Route::post('estrai', [GiocatoreController::class, 'extractGiocatore'])->name('estrai');
Route::post('riponi', [GiocatoreController::class, 'riponiGiocatore'])->name('riponi');
Route::post('acquista', [GiocatoreController::class, 'buyGiocatore'])->name('acquista');
Route::post('svincola', [GiocatoreController::class, 'svincolaGiocatore'])->name('svincola');


Route::get('/allenatori', [AllenatoreController::class, 'getAllenatori'])->name('allenatori');
Route::get('/allenatore/{id}', [AllenatoreController::class, 'showAllenatore'])->where('id', '[0-9]+')->name('allenatore');