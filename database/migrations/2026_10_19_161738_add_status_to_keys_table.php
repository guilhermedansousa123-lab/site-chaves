<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keys', function (Blueprint $table): void {
            $table->string('status')->default('Disponível');
        });

        DB::table('keys')
            ->whereIn('id', DB::table('loans')->whereNull('returned_at')->select('key_id'))
            ->update(['status' => 'Emprestada']);
    }

    public function down(): void
    {
        Schema::table('keys', function (Blueprint $table): void {
            $table->dropColumn('status');
        });
    }
};
