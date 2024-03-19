<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $guarded = [];

    public function documentos()
    {
        return $this->belongsToMany(Documento::class)->withTimestamps();
    }
}
