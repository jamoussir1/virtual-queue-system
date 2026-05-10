<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code')->unique();
            $table->unsignedInteger('position');
            $table->enum('status', ['waiting', 'called', 'served', 'absent', 'cancelled'])->default('waiting');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('queue_id')->constrained('queues')->onDelete('cascade');
            $table->foreignId('window_id')->nullable()->constrained('service_windows')->onDelete('set null');
            $table->timestamp('called_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
