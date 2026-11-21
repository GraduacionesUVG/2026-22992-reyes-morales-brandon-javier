<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('lesson_resources',function(Blueprint $table){
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('resource_type',20);
            $table->string('source_type',20);
            $table->text('file_path')->nullable();
            $table->text('external_url')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('original_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['lesson_id','resource_type','sort_order']);
        });
    }

    public function down(): void{
        Schema::dropIfExists('lesson_resources');
    }
};