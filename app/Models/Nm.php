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
 * @property int $nm_id External ID
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class Nm extends Model
{
	protected $table = 'nms';

	protected $fillable = [
		'nm_id',
	];

	protected $casts = [
		'nm_id' => 'integer',
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
