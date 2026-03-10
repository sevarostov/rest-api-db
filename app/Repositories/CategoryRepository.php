<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository
{
	public function updateOrCreate(array $where, array $data): Model
	{
		return Category::updateOrCreate($where, $data);
	}

	public function findByName(string $name): ?Category
	{
		return Category::where('name', $name)->first();
	}
}
