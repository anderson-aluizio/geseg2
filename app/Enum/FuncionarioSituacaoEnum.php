<?php

namespace App\Enum;

enum FuncionarioSituacaoEnum: string
{
    case Ativo = ' ';
    case Afastado = 'A';
    case Ferias = 'F';
    case Demitido = 'D';
}
