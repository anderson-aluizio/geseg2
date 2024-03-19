<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcaoTipo extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function periodo(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value)
        );
    }

    public function acaoTiposClassificacoes()
    {
        return $this->hasMany(AcaoTipoClassificacao::class);
    }
}
