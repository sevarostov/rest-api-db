<?php

namespace App\Dto;

use DateMalformedStringException;
use DateTime;
use DateTimeInterface;

class StockDto
{
	public function __construct(
		public readonly DateTimeInterface $date,
		public readonly DateTimeInterface $lastChangeDate,
		public readonly string $supplierArticle,
		public readonly string $techSize,
		public readonly int $barcode,
		public readonly int $quantity,
		public readonly bool $isSupply,
		public readonly bool $isRealization,
		public readonly int $quantityFull,
		public readonly WarehouseDto $warehouse,
		public readonly int $inWayToClient,
		public readonly int $inWayFromClient,
		public readonly NmDto $nm,
		public readonly SubjectDto $subject,
		public readonly CategoryDto $category,
		public readonly string $brand,
		public readonly int $scCode,
		public readonly float $price,
		public readonly float $discount,
	) {}

	/**
	 * @throws DateMalformedStringException
	 */
	public static function fromArray(array $data,
		WarehouseDto $warehouseDto,
		SubjectDto $subjectDto,
		CategoryDto $categoryDto,
		NmDto $nmDto,
	): self {
		return new self(
			date: new DateTime($data['date']),
			lastChangeDate: new DateTime($data['last_change_date']),
			supplierArticle: (string)$data['supplier_article'],
			techSize: (string)$data['tech_size'],
			barcode: (int)$data['barcode'],
			quantity: (int)$data['quantity'],
			isSupply: (bool)$data['is_supply'],
			isRealization: (bool)$data['is_realization'],
			quantityFull: (int)$data['quantity_full'],
			warehouse: $warehouseDto,
			inWayToClient: (int)$data['in_way_to_client'],
			inWayFromClient: (int)$data['in_way_from_client'],
			nm: $nmDto,
			subject: $subjectDto,
			category: $categoryDto,
			brand: (string)$data['brand'],
			scCode: (int)$data['sc_code'],
			price: (float)$data['price'],
			discount: (float)$data['discount'],
		);
	}
}
