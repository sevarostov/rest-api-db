<?php

namespace App\Models;

use EloquentTypeHinting;
use Illuminate\Database\Eloquent\Model;

/**
 * Расширенная модель с переопределенным Eloquent билдером
 * @mixin EloquentTypeHinting
 * @property int $id Primary key
 * @property string $endpoint
 * @property int $last_loaded_page
 *
 */
class ApiPageTracker extends Model
{
	protected $table = 'api_page_trackers';

	protected $fillable = [
		'endpoint',
		'last_loaded_page',
	];
}
