<?php

namespace App\Repositories;

use App\Models\Income;
use Illuminate\Database\Eloquent\Model;

class IncomeRepository
{
	public function updateOrCreate(array $where, array $data): Model
	{
		return Income::updateOrCreate($where, $data);
	}

	public function create(array $data): Model
	{
		return Income::create($data);
	}

	public function findByIncomeId(int $incomeId): ?Income
	{
		return Income::where('income_id', $incomeId)
			->first();
	}
}
