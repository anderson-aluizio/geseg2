<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcaoTipoClassificacao extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function nome(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value)
        );
    }
}
