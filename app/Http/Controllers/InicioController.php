<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class InicioController extends Controller
{
    public function __invoke()
    {
        checkPermission('inicio');

        $user = loadUser();
        return Inertia::render('Inicio/Index', [
            'user' => $user,
        ]);
    }
}
