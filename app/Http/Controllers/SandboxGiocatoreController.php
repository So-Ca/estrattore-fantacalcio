<?php

namespace App\Http\Controllers;

class SandboxGiocatoreController extends GiocatoreController
{
    protected $allenatori_path = 'public/allenatori_sandbox.json';
    protected $giocatori_path = 'public/giocatori_sandbox.json';

    protected function isAdmin(): bool
    {
        return true;
    }
}
