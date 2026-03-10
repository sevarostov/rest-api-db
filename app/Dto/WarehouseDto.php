<?php

namespace App\Dto;

class WarehouseDto
{
	public function __construct(
		public readonly string $name,
	) {}

	public static function fromArray(array $data): self {
		return new self(
			name: (string)$data['warehouse_name'],
		);
	}
}
