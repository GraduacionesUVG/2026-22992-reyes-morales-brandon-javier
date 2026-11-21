<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('questions',function(Blueprint $table){
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->text('explanation')->nullable();
            $table->unsignedSmallInteger('points')->default(1);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->index(['module_id','status']);
            $table->index(['module_id','sort_order']);
        });
    }

    public function down(): void{
        Schema::dropIfExists('questions');
    }
};