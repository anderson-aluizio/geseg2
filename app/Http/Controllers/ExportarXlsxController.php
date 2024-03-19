<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Str;

class ExportarXlsxController extends Controller
{
    public function __invoke(Request $request)
    {

        try {
            $request->validate([
                'type' => 'required|string',
            ]);
            $data = match ($request->get('type')) {
                default => abort(404),
            };
            return (new FastExcel($data))->download(Str::title($request->get('type')) . '.xlsx');
        } catch (\Throwable $th) {
            throw $th;
            abort(404);
        }
    }
}
