<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investment_opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('type');               // Farm, Cooperative, Export, Processing
            $table->string('location');
            $table->decimal('min_investment', 14, 2);
            $table->decimal('expected_return_pct', 5, 2)->nullable();
            $table->integer('duration_months')->nullable();
            $table->enum('status', ['open','closed','funded'])->default('open');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_opportunities');
    }
};