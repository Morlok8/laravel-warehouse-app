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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('completed_at')->nullable();
            /*$table->foreignId('warehouse_id')->constrained(
                table: 'warehouses', indexName: 'id' 
            );*/
            $table->unsignedBigInteger('warehouse_id');

            $table->foreign('warehouse_id')->references('id')->on('warehouses');

            $table->string('status');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
