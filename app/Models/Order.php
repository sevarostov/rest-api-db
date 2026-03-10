<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $fillable = [
		'g_number',
		'date',
		'last_change_date',
		'supplier_article',
		'tech_size',
		'barcode',
		'total_price',
		'discount_percent',
		'warehouse',
		'oblast',
		'income',
		'odid',
		'nm',
		'subject',
		'category',
		'brand',
		'is_cancel',
		'cancel_dt',
	];

	protected $casts = [
		'date' => 'date',
		'last_change_date' => 'date',
	];
}
