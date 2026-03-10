<?php

namespace App\Models;

use EloquentTypeHinting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Расширенная модель с переопределенным Eloquent билдером
 * @mixin EloquentTypeHinting
 *
 * @property int $id Primary key
 * @property string $name Warehouse name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 */
class Warehouse extends Model
{
	protected $table = 'warehouses';

	protected $fillable = [
		'name',
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
