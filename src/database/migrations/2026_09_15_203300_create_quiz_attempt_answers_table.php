<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('quiz_attempt_answers',function(Blueprint $table){
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_option_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            $table->unique(['quiz_attempt_id','question_id']);
            $table->index(['quiz_attempt_id','is_correct']);
        });
    }

    public function down(): void{
        Schema::dropIfExists('quiz_attempt_answers');
    }
};