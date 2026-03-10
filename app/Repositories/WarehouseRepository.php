<?php

namespace App\Repositories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;

class WarehouseRepository
{
	public function updateOrCreate(array $where, array $data): Model
	{
		return Warehouse::updateOrCreate($where, $data);
	}

	public function findByName(string $name): ?Warehouse
	{
		return Warehouse::where('name', $name)->first();
	}
}
