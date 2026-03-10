<?php

namespace App\Repositories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class SubjectRepository
{
	public function updateOrCreate(array $where, array $data): Model
	{
		return Subject::updateOrCreate($where, $data);
	}

	public function findByName(string $name): ?Subject
	{
		return Subject::where('name', $name)->first();
	}
}
