<?php

namespace App\Repositories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;

class StockRepository
{
	public function create(array $data): Model
	{
		return Stock::create($data);
	}

	public function findByNmAndWarehouse(string $nm, string $warehouse): ?Stock
	{
		return Stock::where('nm', $nm)
			->where('warehouse', $warehouse)
			->first();
	}
}
