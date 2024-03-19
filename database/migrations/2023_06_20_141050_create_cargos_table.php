<?php

use App\Domain\CreateCustomPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->string('id', 6)->primary();
            $table->string('nome');
            $table->string('area')->nullable();
            $table->string('grupo')->nullable();
            $table->string('subgrupo')->nullable();
            $table->string('setor')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->timestamps();
        });

        CreateCustomPermission::findOrCreate('cargo.visualizar', 'cadastro');
        CreateCustomPermission::findOrCreate('cargo.vincular_documento', 'cadastro');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
