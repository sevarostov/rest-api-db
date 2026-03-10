<?php

namespace App\Services;

use App\Dto\CategoryDto;
use App\Dto\OrderDto;
use App\Dto\NmDto;
use App\Dto\SubjectDto;
use App\Dto\WarehouseDto;
use App\Models\Category;
use App\Models\Income;
use App\Models\Nm;
use App\Models\Subject;
use App\Models\Warehouse;
use App\Repositories\CategoryRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\OrderRepository;
use App\Repositories\NmRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\WarehouseRepository;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Collection;

class OrderService
{
	public function __construct(
		private readonly ApiService $apiService,
		private readonly OrderRepository $orderRepository,
		private readonly WarehouseRepository $warehouseRepository,
		private readonly NmRepository $nmRepository,
		private readonly CategoryRepository $categoryRepository,
		private readonly SubjectRepository $subjectRepository,
		private readonly IncomeRepository $incomeRepository,
		private readonly ApiPageTrackerService $apiPageTrackerService,
	) {}

	/**
	 * Получает все данные по запасам и сохраняет в БД
	 *
	 * @param DateTimeInterface $dateFrom
	 * @param DateTimeInterface $dateTo
	 *
	 * @return Collection<OrderDto>
	 */
	public function sync(DateTimeInterface $dateFrom, DateTimeInterface $dateTo): Collection {

		$page = $this->apiPageTrackerService->getTracker('orders')->last_loaded_page ?? 1;
		$limit = 500;
		$OrderDtos = collect();
		do {
			$response = $this->apiService->getData('orders', $dateFrom, $dateTo, $page, $limit);

			if (!$response || $response['status_code'] !== 200) {
				break;
			}

			$rawStocks = $response['data']['data'] ?? [];
			$OrderDtos = $this->transformToDtos($rawStocks);

			$this->save($OrderDtos);

			$hasNext = $response['data']['links']['next'] ?? false;
			$this->apiPageTrackerService->setPage('orders', $page);
			$page++;
			gc_collect_cycles();
		} while ($hasNext);


		return $OrderDtos;
	}

	private function transformToDtos(array $rawData): Collection {
		return collect($rawData)->map(function (array $item): OrderDto {
			$warehouseDto = WarehouseDto::fromArray($item);
			$nmDto = NmDto::fromArray($item);
			$subjectDto = SubjectDto::fromArray($item);
			$categoryDto = CategoryDto::fromArray($item);

			return  OrderDto::fromArray(
				$item,
				$warehouseDto,
				$nmDto,
				$subjectDto,
				$categoryDto,
			);
		});
	}

	private function save(Collection $OrderDtos): void {
		foreach ($OrderDtos as $dto) {
			/** @var OrderDto $dto */
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
			/** @var Income $income */
			$income = $this->incomeRepository->updateOrCreate([
				'income_id' => $dto->incomeId,
			], [
				'income_id' => $dto->incomeId,
				'number' => $dto->number ?? '',
				'date' => $dto->date ?? new DateTime(),
				'last_change_date' => $dto->lastChangeDate ?? new DateTime(),
				'supplier_article' => $dto->supplierArticle ?? '',
				'tech_size' => $dto->techSize ?? '',
				'barcode' => $dto->barcode ?? '',
				'quantity' => $dto->quantity ?? 0,
				'total_price' => $dto->totalPrice ?? 0,
				'date_close' => $dto->dateClose ?? new DateTime(),
				'warehouse' => $warehouse->id,
				'nm' => $nm->id,
			]);

			$this->orderRepository->create([
				'g_number' => $dto->gNumber,
				'date' => $dto->date,
				'last_change_date' => $dto->lastChangeDate,
				'supplier_article' => $dto->supplierArticle,
				'tech_size' => $dto->techSize,
				'barcode' => $dto->barcode,
				'total_price' => $dto->totalPrice,
				'discount_percent' => $dto->discountPercent,
				'warehouse' => $warehouse->id,
				'oblast' => $dto->oblast,
				'income' => $income->id,
				'odid' => $dto->odid,
				'nm' => $nm->id,
				'subject' => $subject->id,
				'category' => $category->id,
				'brand' => $dto->brand,
				'is_cancel' => $dto->isCancel,
				'cancel_dt' => $dto->cancelDt,
			]);
		}
	}
}
