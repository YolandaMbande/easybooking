
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->text('summary');
            $table->longText('description');
            $table->string('venue');
            $table->string('city');
            $table->dateTime('starts_at');
            $table->string('cover_image')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('featured')->default(false);
            $table->timestamps();
            $table->index(['status', 'featured']);
            $table->index('starts_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
