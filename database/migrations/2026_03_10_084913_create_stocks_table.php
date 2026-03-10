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
        Schema::create('stocks', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->date('date');
			$table->date('last_change_date');
			$table->string('supplier_article', 255);
			$table->string('tech_size', 255);
			$table->bigInteger('barcode');
			$table->integer('quantity');
			$table->boolean('is_supply');
			$table->boolean('is_realization');
			$table->integer('quantity_full');
			$table->foreignId('warehouse')->constrained('warehouses')->onDelete('cascade');
			$table->integer('in_way_to_client');
			$table->integer('in_way_from_client');
			$table->foreignId('nm')->constrained('nms')->onDelete('cascade');
			$table->foreignId('subject')->constrained('subjects')->onDelete('cascade');
			$table->foreignId('category')->constrained('categories')->onDelete('cascade');
			$table->string('brand', 255);
			$table->bigInteger('sc_code');
			$table->decimal('price', 10, 2);
			$table->decimal('discount', 5, 2);
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
        Schema::dropIfExists('stocks');
    }
};
