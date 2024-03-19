<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalidadeEstado extends Model
{
    use HasFactory;

    public function localidadeCidades()
    {
        return $this->hasMany(LocalidadeCidade::class);
    }
}
