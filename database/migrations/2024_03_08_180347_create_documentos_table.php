<?php

use App\Domain\CreateCustomPermission;
use App\Enum\DefaultStateEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nome_abreviado', 20);
            $table->string('status')->default(DefaultStateEnum::ATIVO->value)->index();
            $table->bigInteger('documento_pai_id')->nullable()->index();
            $table->bigInteger('prazo_em_dias')->nullable();
            $table->timestamps();
        });
        CreateCustomPermission::findOrCreate('documentos.cadastro', 'cadastro');
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
