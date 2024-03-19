<?php

use App\Domain\CreateCustomPermission;
use App\Enum\FuncionarioSituacaoEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('cpf', 11)->unique()->index();
            $table->string('matricula', 6)->index();
            $table->tinyInteger('cadastro_manual')->default(false)->index();
            $table->string('filial', 4);
            $table->string('codunic', 125)->nullable();
            $table->string('centro_custo_id', 6)->index();
            $table->string('centro_custo_analitico_id', 15)->index();
            $table->string('nome');
            $table->string('cargo_id', 6)->index();
            $table->string('situacao', 2)->default(FuncionarioSituacaoEnum::Ativo->value)->index();
            $table->date('data_admissao');
            $table->date('data_demissao')->nullable();
            $table->timestamps();
        });

        CreateCustomPermission::findOrCreate('colaborador.visualizar', 'cadastro');
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
