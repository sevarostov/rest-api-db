<?php

namespace App\Services;

use App\Dto\IncomeDto;
use App\Dto\NmDto;
use App\Dto\WarehouseDto;
use App\Models\Nm;
use App\Models\Warehouse;
use App\Repositories\IncomeRepository;
use App\Repositories\NmRepository;
use App\Repositories\WarehouseRepository;
use DateTimeInterface;
use Illuminate\Support\Collection;

class IncomeService
{
	public function __construct(
		private readonly ApiService $apiService,
		private readonly IncomeRepository $incomeRepository,
		private readonly WarehouseRepository $warehouseRepository,
		private readonly NmRepository $nmRepository,
	) {}

	/**
	 * Получает все данные по запасам и сохраняет в БД
	 *
	 * @param DateTimeInterface $dateFrom
	 * @param DateTimeInterface $dateTo
	 *
	 * @return Collection<IncomeDto>
	 */
	public function sync(DateTimeInterface $dateFrom, DateTimeInterface $dateTo): Collection {
		$rawStocks = $this->apiService->getAllData('incomes', $dateFrom, $dateTo);
		$incomeDtos = $this->transformToDtos($rawStocks);

		$this->save($incomeDtos);
		return $incomeDtos;
	}

	private function transformToDtos(array $rawData): Collection {
		return collect($rawData)->map(function (array $item): IncomeDto {
			$warehouseDto = WarehouseDto::fromArray($item);
			$nmDto = NmDto::fromArray($item);

			return  IncomeDto::fromArray($item,
				$warehouseDto,
				$nmDto);
		});
	}

	private function save(Collection $incomeDtos): void {
		foreach ($incomeDtos as $dto) {
			/** @var IncomeDto $dto */
			/** @var Nm $nm */
			$nm = $this->nmRepository->updateOrCreate([
				'nm_id' => $dto->nm->nmId,
			], [
				'nm_id' => $dto->nm->nmId,
			]);

			/** @var Warehouse $warehouse */
			$warehouse = $this->warehouseRepository->updateOrCreate([
				'name' => $dto->warehouse->name,
			], [
				'name' => $dto->warehouse->name,
			]);

			$this->incomeRepository->create([
				'income_id' => $dto->incomeId,
				'number' => $dto->number,
				'date' => $dto->date,
				'last_change_date' => $dto->lastChangeDate,
				'supplier_article' => $dto->supplierArticle,
				'tech_size' => $dto->techSize,
				'barcode' => $dto->barcode,
				'quantity' => $dto->quantity,
				'total_price' => $dto->totalPrice,
				'date_close' => $dto->dateClose,
				'warehouse' => $warehouse->id,
				'nm' => $nm->id,
			]);
		}
	}
}
