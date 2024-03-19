<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalidadeCidade extends Model
{
    use HasFactory;

    public function localidadeEstado()
    {
        return $this->belongsTo(LocalidadeEstado::class);
    }
}
