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
        Schema::create('incomes', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('income_id')->comment('external ID');
			$table->string('number', 255)->nullable();
			$table->date('date');
			$table->date('last_change_date');
			$table->string('supplier_article', 255);
			$table->string('tech_size', 255);
			$table->bigInteger('barcode');
			$table->integer('quantity');
			$table->decimal('total_price', 10, 2);
			$table->date('date_close');
			$table->foreignId('warehouse')->constrained('warehouses')->onDelete('cascade');
			$table->foreignId('nm')->constrained('nms')->onDelete('cascade');
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')
				->useCurrent()
				->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
