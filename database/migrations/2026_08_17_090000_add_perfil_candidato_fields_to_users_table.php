<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('anos_experiencia')->nullable()->after('tipo');
            $table->string('localizacao')->nullable()->after('anos_experiencia');
            $table->string('formacao')->nullable()->after('localizacao');
            $table->string('disponibilidade')->nullable()->after('formacao');
            $table->text('bio')->nullable()->after('disponibilidade');
            $table->string('curriculo_path')->nullable()->after('bio');
            $table->string('curriculo_nome_original')->nullable()->after('curriculo_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'anos_experiencia',
                'localizacao',
                'formacao',
                'disponibilidade',
                'bio',
                'curriculo_path',
                'curriculo_nome_original',
            ]);
        });
    }
};
