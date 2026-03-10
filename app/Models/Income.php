<?php

namespace App\Models;

use EloquentTypeHinting;
use Illuminate\Database\Eloquent\Model;

/**
 * Расширенная модель с переопределенным Eloquent билдером
 * @mixin EloquentTypeHinting
 *
 * @property int $id Primary key
 */
class Income extends Model
{
	protected $fillable = [
		'income_id',
		'date',
		'number',
		'last_change_date',
		'supplier_article',
		'tech_size',
		'barcode',
		'quantity',
		'total_price',
		'date_close',
		'warehouse',
		'nm',
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
