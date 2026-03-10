<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create('orders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('g_number', 255);
			$table->dateTime('date');
			$table->date('last_change_date');
			$table->string('supplier_article', 255);
			$table->string('tech_size', 255);
			$table->bigInteger('barcode');
			$table->decimal('total_price', 10, 2);
			$table->decimal('discount_percent', 5, 2);
			$table->foreignId('warehouse')->constrained('warehouses')->onDelete('cascade');
			$table->string('oblast', 255);
			$table->foreignId('income')->constrained('incomes')->onDelete('cascade');
			$table->string('odid', 255);
			$table->foreignId('nm')->constrained('nms')->onDelete('cascade');
			$table->foreignId('subject')->constrained('subjects')->onDelete('cascade');
			$table->foreignId('category')->constrained('categories')->onDelete('cascade');
			$table->string('brand', 255);
			$table->boolean('is_cancel');
			$table->dateTime('cancel_dt')->nullable();
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')
				->useCurrent()
				->useCurrentOnUpdate();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists('orders');
	}
};
