<?php

namespace App\Models;

use App\Enum\DocumentoFuncionarioStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoFuncionario extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'status' => DocumentoFuncionarioStatusEnum::class,
    ];

    public function scopeConformes($query)
    {
        $query->where('status', DocumentoFuncionarioStatusEnum::CONFORME);
    }

    public function scopeVencidos($query)
    {
        $query->where('status', DocumentoFuncionarioStatusEnum::VENCIDO);
    }

    public function scopeVencidosEAVencer($query)
    {
        $query->where('status', DocumentoFuncionarioStatusEnum::VENCIDO);
    }

    public function criador()
    {
        return $this->belongsTo(Funcionario::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(Funcionario::class, 'updated_by');
    }

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }
}
