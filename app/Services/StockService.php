<?php

namespace App\Services;

use App\Dto\CategoryDto;
use App\Dto\NmDto;
use App\Dto\StockDto;
use App\Dto\SubjectDto;
use App\Dto\WarehouseDto;
use App\Models\Category;
use App\Models\Nm;
use App\Models\Subject;
use App\Models\Warehouse;
use App\Repositories\CategoryRepository;
use App\Repositories\NmRepository;
use App\Repositories\StockRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\WarehouseRepository;
use DateTimeInterface;
use Illuminate\Support\Collection;

class StockService
{
	public function __construct(
		private readonly ApiService $apiService,
		private readonly StockRepository $stockRepository,
		private readonly NmRepository $nmRepository,
		private readonly CategoryRepository $categoryRepository,
		private readonly SubjectRepository $subjectRepository,
		private readonly WarehouseRepository $warehouseRepository,
	) {}

	/**
	 * Получает все данные по запасам и сохраняет в БД
	 *
	 * @param DateTimeInterface $dateFrom
	 * @param DateTimeInterface $dateTo
	 *
	 * @return Collection<StockDto>
	 */
	public function sync(DateTimeInterface $dateFrom, DateTimeInterface $dateTo): Collection {
		$rawStocks = $this->apiService->getAllData('stocks', $dateFrom, $dateTo);
		$stockDtos = $this->transformToDtos($rawStocks);
		$this->save($stockDtos);
		return $stockDtos;
	}

	private function transformToDtos(array $rawData): Collection {
		return collect($rawData)->map(function (array $item): StockDto {
			$warehouseDto = WarehouseDto::fromArray($item);
			$subjectDto = SubjectDto::fromArray($item);
			$categoryDto = CategoryDto::fromArray($item);
			$nmDto = NmDto::fromArray($item);

			return  StockDto::fromArray($item,
				$warehouseDto,
				$subjectDto,
				$categoryDto,
				$nmDto);
		});
	}

	private function save(Collection $stockDtos): void {
		foreach ($stockDtos as $dto) {
			/** @var StockDto $dto */
			/** @var Nm $nm */
			$nm = $this->nmRepository->updateOrCreate([
				'nm_id' => $dto->nm->nmId,
			], [
				'nm_id' => $dto->nm->nmId,
			]);
			/** @var Category $category */
			$category = $this->categoryRepository->updateOrCreate([
				'name' => $dto->category->name,
			], [
				'name' => $dto->category->name,
			]);
			/** @var Subject $subject */
			$subject = $this->subjectRepository->updateOrCreate([
				'name' => $dto->subject->name,
			], [
				'name' => $dto->subject->name,
			]);
			/** @var Warehouse $warehouse */
			$warehouse = $this->warehouseRepository->updateOrCreate([
				'name' => $dto->warehouse->name,
			], [
				'name' => $dto->warehouse->name,
			]);

			$this->stockRepository->create([
				'date' => $dto->date,
				'last_change_date' => $dto->lastChangeDate,
				'supplier_article' => $dto->supplierArticle,
				'tech_size' => $dto->techSize,
				'barcode' => $dto->barcode,
				'quantity' => $dto->quantity,
				'is_supply' => $dto->isSupply,
				'is_realization' => $dto->isRealization,
				'quantity_full' => $dto->quantityFull,
				'in_way_to_client' => $dto->inWayToClient,
				'in_way_from_client' => $dto->inWayFromClient,
				'brand' => $dto->brand,
				'sc_code' => $dto->scCode,
				'price' => $dto->price,
				'discount' => $dto->discount,
				'nm' => $nm->id,
				'category' => $category->id,
				'subject' => $subject->id,
				'warehouse' => $warehouse->id,
			]);
		}
	}
}
