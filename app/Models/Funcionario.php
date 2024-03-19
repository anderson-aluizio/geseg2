<?php

namespace App\Models;

use App\Enum\FuncionarioSituacaoEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'cadastro_manual' => 'boolean',
        'situacao' => FuncionarioSituacaoEnum::class,
    ];

    protected static function booted()
    {
        static::addGlobalScope(('centroCusto'), function (Builder $builder) {
            $builder->whereIn('funcionarios.centro_custo_id', function ($query) {
                $query->selectRaw('centro_custo_user.centro_custo_id')
                    ->from('centro_custo_user')
                    ->where('centro_custo_user.user_id', auth()->id());
            });
        });
    }

    public function scopeAtivos($query)
    {
        $query->where('situacao', FuncionarioSituacaoEnum::Ativo->value);
    }

    public function scopeContratados($query)
    {
        $query->where('situacao', "!=", FuncionarioSituacaoEnum::Demitido->value);
    }

    public function scopeDemitidos($query)
    {
        $query->where('situacao', FuncionarioSituacaoEnum::Demitido->value);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id', 'id');
    }

    public function centroCusto()
    {
        return $this->belongsTo(CentroCusto::class, 'centro_custo_id', 'id')->withoutGlobalScope('centroCusto');
    }

    public function centroCustoAnalitico()
    {
        return $this->belongsTo(CentroCustoAnalitico::class, 'centro_custo_analitico_id', 'id');
    }
}
