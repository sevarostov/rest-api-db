<?php

namespace App\Services;

use App\Dto\StockDto;
use App\Repositories\StockRepository;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Collection;

class StockService
{
	public function __construct(
		private readonly ApiService $apiService,
		private readonly StockRepository $stockRepository,
	) {}

	/**
	 * Получает все данные по запасам и сохраняет в БД
	 *
	 * @param DateTimeInterface $dateFrom
	 * @param DateTimeInterface $dateTo
	 *
	 * @return Collection<StockDto>
	 */
	public function syncStocks(DateTimeInterface $dateFrom, DateTimeInterface $dateTo): Collection {
		$rawStocks = $this->apiService->getAllData('stocks', $dateFrom, $dateTo);

		$stockDtos = $this->transformToDtos($rawStocks);

		$this->save($stockDtos);
		return $stockDtos;
	}

	private function transformToDtos(array $rawData): Collection {
		return collect($rawData)->map(function (array $item): StockDto {
			return new StockDto(
				date: new DateTime($item['date']),
				lastChangeDate: new DateTime($item['lastChangeDate']),
				supplierArticle: $item['supplierArticle'] ?? '',
				techSize: $item['techSize'] ?? '',
				barcode: (int)($item['barcode'] ?? 0),
				quantity: (int)($item['quantity'] ?? 0),
				isSupply: (bool)($item['isSupply'] ?? false),
				isRealization: (bool)($item['isRealization'] ?? false),
				quantityFull: (int)($item['quantityFull'] ?? 0),
				warehouseId: (int)($item['warehouseId'] ?? 0),
				inWayToClient: (int)($item['inWayToClient'] ?? 0),
				inWayFromClient: (int)($item['inWayFromClient'] ?? 0),
				nmId: (int)($item['nmId'] ?? 0),
				subjectId: (int)($item['subjectId'] ?? 0),
				categoryId: (int)($item['categoryId'] ?? 0),
				brand: $item['brand'] ?? '',
				scCode: (int)($item['scCode'] ?? 0),
				price: (float)($item['price'] ?? 0.0),
				discount: (float)($item['discount'] ?? 0.0),
			);
		});
	}

	private function save(Collection $stockDtos): void {
		foreach ($stockDtos as $dto) {
			/** @var StockDto $dto */
			$this->stockRepository->updateOrCreate([
				'nm' => $dto->nmId,
				'warehouse' => $dto->warehouseId,
			], [
				'quantity' => $dto->quantity,
			]);
		}
	}
}
