<?php

namespace App\Models;

use EloquentTypeHinting;
use Illuminate\Database\Eloquent\Model;

/**
 * Расширенная модель с переопределенным Eloquent билдером
 * @mixin EloquentTypeHinting
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
