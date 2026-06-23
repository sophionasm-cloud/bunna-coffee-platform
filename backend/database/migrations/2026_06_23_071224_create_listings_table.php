<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // farmer
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('origin');
            $table->string('grade')->nullable();
            $table->string('process')->nullable();
            $table->integer('quantity_kg');
            $table->decimal('asking_price_per_kg', 10, 2);
            $table->date('harvest_date')->nullable();
            $table->enum('status', ['draft','pending','approved','sold'])->default('pending');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};