<?php

namespace App\Dto;

use DateMalformedStringException;
use DateTime;
use DateTimeInterface;

class SaleDto
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
		public readonly bool $isSupply,
		public readonly bool $isRealization,
		public readonly ?string $promoCodeDiscount,
		public readonly int $warehouseId,
		public readonly string $countryName,
		public readonly string $oblastOkrugName,
		public readonly string $regionName,
		public readonly int $incomeId,
		public readonly string $saleId,
		public readonly ?string $odid,
		public readonly float $spp,
		public readonly float $forPay,
		public readonly float $finishedPrice,
		public readonly float $priceWithDisc,
		public readonly int $nmId,
		public readonly int $subjectId,
		public readonly int $categoryId,
		public readonly string $brand,
		public readonly ?bool $isStorno,
	) {}

	/**
	 * @throws DateMalformedStringException
	 */
	public static function fromArray(array $data, int $warehouseId, int $subjectId, int $categoryId): self {
		return new self(
			gNumber: (string)$data['g_number'],
			date: new DateTime($data['date']),
			lastChangeDate: new DateTime($data['last_change_date']),
			supplierArticle: (string)$data['supplier_article'],
			techSize: (string)$data['tech_size'],
			barcode: (int)$data['barcode'],
			totalPrice: (float)$data['total_price'],
			discountPercent: (float)$data['discount_percent'],
			isSupply: (bool)$data['is_supply'],
			isRealization: (bool)$data['is_realization'],
			promoCodeDiscount: $data['promo_code_discount'] ?? null,
			warehouseId: $warehouseId,
			countryName: (string)$data['country_name'],
			oblastOkrugName: (string)$data['oblast_okrug_name'],
			regionName: (string)$data['region_name'],
			incomeId: (int)$data['income_id'],
			saleId: (string)$data['sale_id'],
			odid: $data['odid'] ?? null,
			spp: (float)$data['spp'],
			forPay: (float)$data['for_pay'],
			finishedPrice: (float)$data['finished_price'],
			priceWithDisc: (float)$data['price_with_disc'],
			nmId: (int)$data['nm_id'],
			subjectId: $subjectId,
			categoryId: $categoryId,
			brand: (string)$data['brand'],
			isStorno: $data['is_storno'] ?? null,
		);
	}
}
