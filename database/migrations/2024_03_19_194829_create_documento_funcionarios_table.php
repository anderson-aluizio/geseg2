<?php

use App\Enum\DocumentoFuncionarioStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('documento_id')->index();
            $table->bigInteger('funcionario_id')->index();
            $table->string('funcionario_cpf', 11)->index();
            $table->date('prazo_inicial')->index()->nullable();
            $table->date('prazo_final')->index()->nullable();
            $table->string('onde_treinou')->nullable();
            $table->string('obs')->nullable();
            $table->bigInteger('created_by')->index();
            $table->bigInteger('updated_by')->index();
            $table->string('status')->default(DocumentoFuncionarioStatusEnum::PENDENTE->value)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_funcionarios');
    }
};
