<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCustoAnalitico extends Model
{
    use HasFactory;

    public function centroCusto()
    {
        return $this->belongsTo(CentroCusto::class);
    }
}
