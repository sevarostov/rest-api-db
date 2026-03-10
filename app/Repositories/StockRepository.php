<?php

namespace App\Repositories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;

class StockRepository
{
	public function updateOrCreate(array $where, array $data): Model
	{
		return Stock::updateOrCreate($where, $data);
	}

	public function findByNsAndWarehouse(string $ns, string $warehouse): ?Stock
	{
		return Stock::where('ns', $ns)
			->where('warehouse', $warehouse)
			->first();
	}
}
