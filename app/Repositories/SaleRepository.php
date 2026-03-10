<?php

namespace App\Repositories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;

class SaleRepository
{
	public function create(array $data): Model
	{
		return Sale::create($data);
	}

	public function findBySaleId(int $SaleId): ?Sale
	{
		return Sale::where('sale_id', $SaleId)
			->first();
	}
}
