<?php

namespace App\Http\Requests;

use App\Enum\UserStateEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'id' => ['required', 'exists:users'],
            'email' => ['nullable', 'max:50', 'email', 'ends_with:dinamo.srv.br', Rule::unique('users')->ignore($this->id)],
            'funcionario_id' => [
                'required', 'exists:App\Models\Funcionario,id',
                Rule::unique('users')->ignore($this->funcionario_id, 'funcionario_id')
            ],
            'state' => ['required', Rule::in(collectEnum(UserStateEnum::cases())->map(fn ($row) => $row['id'])->toArray())],
            'centro_custos' => ['present', 'array', 'required'],
            'centro_custos.*' => ['required', 'string', 'exists:App\Models\CentroCusto,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
}
