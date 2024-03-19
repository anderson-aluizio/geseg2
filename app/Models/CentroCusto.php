<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCusto extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(('centroCusto'), function (Builder $builder) {
            $builder->whereIn('centro_custos.id', function ($query) {
                $query->selectRaw('centro_custo_user.centro_custo_id')
                    ->from('centro_custo_user')
                    ->where('centro_custo_user.user_id', auth()->id());
            });
        });
    }

    protected function nome(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => mb_strtoupper($value),
            get: fn ($value) => mb_strtoupper($value),
        );
    }

    public function localidadeEstado()
    {
        return $this->belongsTo(LocalidadeEstado::class);
    }
}
