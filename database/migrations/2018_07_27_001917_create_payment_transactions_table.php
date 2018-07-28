<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');

            $table->unsignedInteger('gateway_id')->index();
            $table->unsignedInteger('order_id')->index();
            $table->string('provider_id')->nullable();

            $table->unsignedInteger('amount');
            $table->string('status', 40)->nullable();

            $table->string('ip', 20)->nullable(); // 192.168.100.100
            $table->text('data')->nullable();

            $table->boolean('is_test')->default(0);

            $table->index(['order_id', 'id']);
            $table->unique(['gateway_id', 'provider_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
}
