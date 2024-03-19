<?php

namespace App\Http\Requests;

use App\Enum\DefaultStateEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name' => 'Nome',
            'email' => 'E-mail',
            'funcionario_id' => 'Colaborador',
            'state' => 'Status',
            'centro_custos' => 'Centros de Custos',
            'centro_custos.*' => 'ID Centros de Custo',
            'role_id' => 'Grupo de Acesso',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users', 'ends_with:dinamo.srv.br'],
            'funcionario_id' => ['required', 'exists:App\Models\Funcionario,id', 'unique:users'],
            'state' =>  Rule::in(collectEnum(DefaultStateEnum::cases())->map(fn ($row) => $row['id'])->toArray()),
            'centro_custos' => ['present', 'array', 'required'],
            'centro_custos.*' => ['required', 'string', 'exists:App\Models\CentroCusto,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
}
