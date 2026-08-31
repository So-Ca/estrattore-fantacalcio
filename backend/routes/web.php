<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllenatoreController;
use App\Http\Controllers\GiocatoreController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


Route::get('/', function (Request $request) {
    if (session()->get('privilege') === 'admin') {
        return file_get_contents(public_path('main.html'));
    }
    return redirect('login');
});

Route::get('/login', function () {
    return '<form method="POST" action="/login" style="position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    /* width: 100vw; */
    /* height: 100vh; */
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    /* row-gap: 1rem; */">
    <div>

   <label for="user">Utente</label><br>
   <input id="user" type="text" name="user" />
   </div>
   <div>
   <label for="password">Password</label><br>
   <input id="password" type="password" name="password" />
   </div>
   <div>
   <input id="submit" type="submit" />

   <input type="hidden" name="_token" value="'.csrf_token().'" />
   </div>

   </form>';
})->name('login');

Route::post('/login', function (Request $request) {
    if($request->input('password') === '26_f4nt4Favar0_27' && $request->input('user') === 'fantafavaro') {
        session()->put('privilege', 'admin');
    }
    return redirect('/');
});

Route::get('/report', function () {
    $allenatoreController = new AllenatoreController();

    $giocatori = Storage::json('public\giocatori.json');


    $allenatori = $allenatoreController->getAllenatori();
    $viewAllenatori = [];
    for ($i = 0; $i < count($allenatori); $i++) {
        $viewAllenatori[$i] = $allenatoreController->showAllenatore($allenatori[$i]['Id']);
    }

    return view('report', ['allenatori' => $viewAllenatori, 'giocatori' => $giocatori]);
});


Route::get('/{any}', function () {
    return file_get_contents(public_path('main.html'));
})->where('any', '^(?!api).*$');
