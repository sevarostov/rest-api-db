<?php

namespace App\Dto;

use DateTime;
use DateTimeInterface;

class IncomeDto
{
	public function __construct(
		public readonly int $incomeId,
		public readonly ?string $number,
		public readonly DateTimeInterface $date,
		public readonly DateTimeInterface $lastChangeDate,
		public readonly string $supplierArticle,
		public readonly string $techSize,
		public readonly int $barcode,
		public readonly int $quantity,
		public readonly float $totalPrice,
		public readonly DateTimeInterface $dateClose,
		public readonly WarehouseDto $warehouse,
		public readonly NmDto $nm,
	) {}

	public static function fromArray(array $data, WarehouseDto $warehouse, NmDto $nmDto): self {
		return new self(
			incomeId: (int)$data['income_id'],
			number: $data['number'] ?? null,
			date: new DateTime($data['date']),
			lastChangeDate: new DateTime($data['last_change_date']),
			supplierArticle: (string)$data['supplier_article'],
			techSize: (string)$data['tech_size'],
			barcode: (int)$data['barcode'],
			quantity: (int)$data['quantity'],
			totalPrice: (float)$data['total_price'],
			dateClose: new DateTime($data['date_close']),
			warehouse: $warehouse,
			nm: $nmDto,
		);
	}
}
