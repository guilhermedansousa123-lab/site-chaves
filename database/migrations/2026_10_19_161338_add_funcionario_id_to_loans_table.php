<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table): void {
            $table->foreignId('funcionario_id')->nullable()->constrained('funcionarios')->nullOnDelete();
            $table->foreignId('chaves_id')->nullable()->constrained('keys')->nullOnDelete();
            $table->date('data_retirada')->nullable();
            $table->time('hora_retirada')->nullable();
            $table->date('data_devolucao')->nullable();
            $table->time('hora_devolucao')->nullable();
            $table->string('status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('funcionario_id');
            $table->dropConstrainedForeignId('chaves_id');
            $table->dropColumn([
                'data_retirada',
                'hora_retirada',
                'data_devolucao',
                'hora_devolucao',
                'status',
            ]);
        });
    }
};
