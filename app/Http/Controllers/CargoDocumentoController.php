<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Services\BannerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CargoDocumentoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Cargo::class, 'cargo');
    }

    public function store(Request $request, Cargo $cargo)
    {
        $documentoId = $request->validate([
            'documento_id' => ['required', 'exists:App\Models\Documento,id']
        ])['documento_id'];
        if ($cargo->documentos()->where('documento_id', $documentoId)->exists()) {
            BannerMessage::message("Este registro já foi cadastrado", "danger");
            return Redirect::back();
        }

        $cargo->documentos()->attach($documentoId);
        BannerMessage::message("Registro cadastrado");
        return Redirect::back();
    }

    public function delete(Request $request, Cargo $cargo)
    {
        $documentoId = $request->validate([
            'documento_id' => ['required', 'exists:App\Models\Documento,id']
        ])['documento_id'];

        $cargo->documentos()->detach($documentoId);
        BannerMessage::message("Registro apagado");
        return Redirect::back();
    }
}
