<?php

namespace App\Dto;

use DateTime;
use DateTimeInterface;

class OrderDto
{
	public function __construct(
		public readonly string $gNumber,
		public readonly DateTimeInterface $date,
		public readonly DateTimeInterface $lastChangeDate,
		public readonly string $supplierArticle,
		public readonly string $techSize,
		public readonly int $barcode,
		public readonly float $totalPrice,
		public readonly float $discountPercent,
		public readonly int $warehouseId,
		public readonly string $oblast,
		public readonly int $incomeId,
		public readonly string $odid,
		public readonly int $nmId,
		public readonly int $subjectId,
		public readonly int $categoryId,
		public readonly string $brand,
		public readonly bool $isCancel,
		public readonly ?DateTimeInterface $cancelDt,
	) {}

	public static function fromArray(array $data, int $warehouseId, int $incomeId): self {
		return new self(
			gNumber: (string)$data['g_number'],
			date: new DateTime($data['date']),
			lastChangeDate: new DateTime($data['last_change_date']),
			supplierArticle: (string)$data['supplier_article'],
			techSize: (string)$data['tech_size'],
			barcode: (int)$data['barcode'],
			totalPrice: (float)$data['total_price'],
			discountPercent: (float)$data['discount_percent'],
			warehouseId: $warehouseId,
			oblast: (string)$data['oblast'],
			incomeId: $incomeId,
			odid: (string)$data['odid'],
			nmId: (int)$data['nm_id'],
			subjectId: (int)$data['subject_id'],
			categoryId: (int)$data['category_id'],
			brand: (string)$data['brand'],
			isCancel: (bool)$data['is_cancel'],
			cancelDt: isset($data['cancel_dt']) ? new DateTime($data['cancel_dt']) : null,
		);
	}
}
