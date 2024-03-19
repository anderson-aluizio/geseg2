<?php

namespace App\Models;

use App\Enum\DefaultStateEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'nome_abreviado', 'status', 'documento_pai_id', 'prazo_em_dias'];

    public function nomeAbreviado(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value)
        );
    }

    public function documentoPai()
    {
        return $this->belongsTo(Documento::class);
    }

    public function scopeAtivos($query)
    {
        $query->where('status', DefaultStateEnum::ATIVO);
    }

    public function cargos()
    {
        return $this->belongsToMany(Cargo::class);
    }
}
