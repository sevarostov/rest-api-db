<?php

namespace App\Models;

use EloquentTypeHinting;
use Illuminate\Database\Eloquent\Model;

/**
 * Расширенная модель с переопределенным Eloquent билдером
 * @mixin EloquentTypeHinting
 *
 */
class Stock extends Model
{
	protected $fillable = [
		'date',
		'last_change_date',
		'supplier_article',
		'tech_size',
		'barcode',
		'quantity',
		'is_supply',
		'is_realization',
		'quantity_full',
		'warehouse',
		'in_way_to_client',
		'in_way_from_client',
		'nm',
		'category',
		'brand',
		'sc_code',
		'price',
		'discount'
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
