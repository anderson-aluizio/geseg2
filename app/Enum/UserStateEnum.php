<?php

namespace App\Enum;

enum UserStateEnum: string
{
    case ATIVO = 'ATIVO';
    case INATIVO = 'INATIVO';
    case RESET = 'RESET';
}
