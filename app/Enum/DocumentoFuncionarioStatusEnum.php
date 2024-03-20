<?php

namespace App\Enum;

enum DocumentoFuncionarioStatusEnum: string
{
    case CONFORME = 'CONFORME';
    case PENDENTE = 'PENDENTE';
    case PRAZO = 'PRAZO';
    case VENCIDO = 'VENCIDO';
}
