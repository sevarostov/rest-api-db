<?php

namespace App\Dto;

class NmsDto
{
	public function __construct(
		public readonly int $nmId,
	) {}

	public static function fromArray(array $data): self {
		return new self(
			nmId: (int)$data['nm_id'],
		);
	}
}
