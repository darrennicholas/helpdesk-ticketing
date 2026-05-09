<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() { Schema::create('tickets', function (Blueprint $table) { $table->id(); $table->string('ticket_no')->unique(); $table->foreignId('user_id')->constrained()->onDelete('cascade'); $table->foreignId('category_id')->constrained(); $table->string('subject'); $table->text('description'); $table->enum('status', ['Open','On Progress','Resolved','Closed'])->default('Open'); $table->enum('priority', ['Low','Medium','High'])->default('Medium'); $table->timestamps(); $table->index('status'); $table->index('created_at'); }); }
    public function down() { Schema::dropIfExists('tickets'); }
};