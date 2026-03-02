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
            $table->string('ticket_number')->unique();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constarained('users')->nullOnDelete();
            $table->foreignId('ticket_category_id')->constrained()->cascadOnDelete();

            $table->string('subject');
            $table->text('description');

            $table->enum('priority', ['low','medium','high','urgent'])->default('low');
            $table->enum('status', ['open','in_progress','resolved','close'])->default('open');

            $table->timestamp('sla_due_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
