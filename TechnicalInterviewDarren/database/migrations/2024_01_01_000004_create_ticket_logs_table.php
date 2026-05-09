<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() { Schema::create('ticket_logs', function (Blueprint $table) { $table->id(); $table->foreignId('ticket_id')->constrained()->onDelete('cascade'); $table->foreignId('user_id')->constrained(); $table->string('action'); $table->string('old_status')->nullable(); $table->string('new_status')->nullable(); $table->text('note')->nullable(); $table->string('attachment')->nullable(); $table->timestamps(); $table->index('ticket_id'); }); }
    public function down() { Schema::dropIfExists('ticket_logs'); }
};