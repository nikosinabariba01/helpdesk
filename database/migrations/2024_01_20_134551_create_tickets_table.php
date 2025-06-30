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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->enum('Jenis_Pengaduan', ['perbaikan','permintaan']);
            $table->string('Lokasi');
            $table->date('Tanggal_Pengaduan')->default(now());
            $table->date('Tanggal_Selesai')->nullable()->nullable();
            $table->enum('status', ['open', 'on process', 'close'])->default('open');
            $table->text('Detail');
            $table->string('gambar')->nullable();
            $table->foreignId('user_id')->constrained(table: 'users', indexName: 'id');
            $table->foreignId('asignee_id')->nullable()->constrained('users')->index('fk_asignee_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
