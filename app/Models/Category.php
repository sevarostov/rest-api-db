<?php

namespace App\Models;

use EloquentTypeHinting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @mixin EloquentTypeHinting
 * @property int $id
 * @property string $name Category name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 */
class Category extends Model
{
	protected $table = 'categories';

	protected $fillable = [
		'name',
	];

	protected $casts = [
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
