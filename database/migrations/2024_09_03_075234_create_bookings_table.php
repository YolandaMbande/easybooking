<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->timestamp('booking_date');
            $table->integer('num_tickets');
            $table->decimal('total_price', 8, 2);
            $table->enum('payment_status', ['Pending', 'Completed', 'Failed']);
            $table->enum('status', ['Pending', 'Paid', 'Cancelled'])->default('Pending');
            $table->string('qr_code')->nullable();
            $table->timestamps();

            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
