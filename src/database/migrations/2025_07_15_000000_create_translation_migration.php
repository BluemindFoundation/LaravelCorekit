<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->ulid()->primary();
            $table->string('table_name');
            $table->string('row_id');
            $table->string('column_name');
            $table->string('locale', 10);
            $table->text('value');
            $table->timestamps();

            $table->index(['table_name', 'row_id', 'column_name', 'locale'], 'translations_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};