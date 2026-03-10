<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderRepository
{
	public function create(array $data): Model
	{
		return Order::create($data);
	}
}
