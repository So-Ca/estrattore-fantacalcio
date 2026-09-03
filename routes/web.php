<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllenatoreController;
use App\Http\Controllers\GiocatoreController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


Route::get('/', function (Request $request) {
    if (!$request->input('role')) {
        if (session()->get('privilege') === 'admin') {
            $role = 'admin';
        } else {
            $role = 'visitor';
        }

        return to_route('estrattore', ['role' => $role]);
    }

    return file_get_contents(public_path('build/index.html'));
})->name('estrattore');

Route::get('/logout', function (Request $request) {
    $request->session()->forget('privilege');
    if ($request->input('current_url'))
        return redirect()->to($request->input('current_url'));
    else
        return redirect('/');
});

Route::get('/login', function (Request $request) {
    return '<form method="POST" action="/login" style="position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    display: flex;
    justify-content: left;
    align-items: start;
    gap: 1rem;
    flex-direction: column;">' .
        ($request->input('current_url') ? '<input name="current_url" type="hidden" value="' . urlencode($request->input('current_url')) . '" />' : '')
        . '
    <div>
   <span style="display:block;text-align:center;font-size:20px;">🔒</span>
   <label for="user" style="font-size:20px;">Utente</label><br>
   <input id="user" style="font-size:20px;" type="text" name="user" />
   </div>
   <div>
   <label for="password" style="font-size:20px;">Password</label><br>
   <input id="password" style="font-size:20px;" type="password" name="password" />
   </div>
   <div>
   <input id="submit" type="submit" value="Accedi" />

   <input type="hidden" name="_token" value="' . csrf_token() . '" />
   </div>

   </form>';
})->name('login');

Route::post('/login', function (Request $request) {
    if ($request->input('password') === '26_f4nt4Favar0_27' && $request->input('user') === 'fantafavaro') {
        session()->put('privilege', 'admin');
    }
    //dd($request);
    if ($request->input('current_url'))
        return redirect()->to(urldecode($request->input('current_url')));
    else
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


Route::get('/{any}', function (Request $request) {

    if (!$request->input('role')) {
        if (session()->get('privilege') === 'admin') {
            $role = 'admin';
        } else {
            $role = 'visitor';
        }

        return redirect($request->fullUrlWithQuery(['role' => $role]));
    }
    return file_get_contents(public_path('build/index.html'));
})->where('any', '^(?!api).*$');
