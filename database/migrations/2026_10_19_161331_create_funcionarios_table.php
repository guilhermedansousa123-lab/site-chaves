<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('cargo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
