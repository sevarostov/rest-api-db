<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
	protected $fillable = [
		'sale_id',
		'g_number',
		'date',
		'last_change_date',
		'supplier_article',
		'tech_size',
		'barcode',
		'total_price',
		'discount_percent',
		'is_supply',
		'is_realization',
		'promo_code_discount',
		'warehouse_id',
		'country_name',
		'oblast_okrug_name',
		'region_name',
		'income',
		'odid',
		'spp',
		'for_pay',
		'finished_price',
		'price_with_disc',
		'nm',
		'subject',
		'category',
		'brand',
		'is_storno',
	];

	protected $casts = [
		'date' => 'date',
		'last_change_date' => 'date',
	];
}
