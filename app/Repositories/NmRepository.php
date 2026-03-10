<?php

namespace App\Repositories;

use App\Models\Nm;
use Illuminate\Database\Eloquent\Model;

class NmRepository
{
	public function updateOrCreate(array $where, array $data): Model
	{
		return Nm::updateOrCreate($where, $data);
	}

	public function findByNmId(int $nmId): ?Nm
	{
		return Nm::where('nm_id', $nmId)->first();
	}
}
