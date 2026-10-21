<?php

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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('key_id')->constrained('keys')->restrictOnDelete();
            $table->string('person', 80);
            $table->string('note', 120)->nullable();
            $table->timestamp('loaned_at')->useCurrent();
            $table->timestamp('returned_at')->nullable()->index();
            $table->index(['key_id', 'returned_at']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
