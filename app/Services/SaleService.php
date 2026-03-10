<?php

namespace App\Services;

use App\Dto\CategoryDto;
use App\Dto\SaleDto;
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
use App\Repositories\SaleRepository;
use App\Repositories\NmRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\WarehouseRepository;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Collection;

class SaleService
{
	public function __construct(
		private readonly ApiService $apiService,
		private readonly SaleRepository $saleRepository,
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
	 * @return Collection<SaleDto>
	 */
	public function sync(DateTimeInterface $dateFrom, DateTimeInterface $dateTo): Collection {

		$page = $this->apiPageTrackerService->getTracker('sales')->last_loaded_page ?? 1;
		$limit = 500;
		$saleDtos = collect();
		do {
			$response = $this->apiService->getData('sales', $dateFrom, $dateTo, $page, $limit);

			if (!$response || $response['status_code'] !== 200) {
				break;
			}

			$rawStocks = $response['data']['data'] ?? [];
			$saleDtos = $this->transformToDtos($rawStocks);

			$this->save($saleDtos);

			$hasNext = $response['data']['links']['next'] ?? false;
			$this->apiPageTrackerService->setPage('sales', $page);
			$page++;
			gc_collect_cycles();
		} while ($hasNext);


		return $saleDtos;
	}

	private function transformToDtos(array $rawData): Collection {
		return collect($rawData)->map(function (array $item): SaleDto {
			$warehouseDto = WarehouseDto::fromArray($item);
			$nmDto = NmDto::fromArray($item);
			$subjectDto = SubjectDto::fromArray($item);
			$categoryDto = CategoryDto::fromArray($item);

			return  SaleDto::fromArray($item,
				$warehouseDto,
				$subjectDto,
				$categoryDto,
				$nmDto,
			);
		});
	}

	private function save(Collection $saleDtos): void {
		foreach ($saleDtos as $dto) {
			/** @var SaleDto $dto */
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


			$this->saleRepository->create([
				'sale_id' => $dto->saleId,
				'g_number' => $dto->gNumber,
				'date' => $dto->date,
				'last_change_date' => $dto->lastChangeDate,
				'supplier_article' => $dto->supplierArticle,
				'tech_size' => $dto->techSize,
				'barcode' => $dto->barcode,
				'total_price' => $dto->totalPrice,
				'discount_percent' => $dto->discountPercent,
				'is_supply' => $dto->isSupply,
				'is_realization' => $dto->isRealization,
				'promo_code_discount' => $dto->promoCodeDiscount,
				'warehouse_id' => $warehouse->id,
				'country_name' => $dto->countryName,
				'oblast_okrug_name' => $dto->oblastOkrugName,
				'region_name' => $dto->regionName,
				'income' => $income->id,
				'odid' => $dto->odid,
				'spp' => $dto->spp,
				'for_pay' => $dto->forPay,
				'finished_price' => $dto->finishedPrice,
				'price_with_disc' => $dto->priceWithDisc,
				'nm' => $nm->id,
				'subject' => $subject->id,
				'category' => $category->id,
				'brand' => $dto->brand,
				'is_storno' => $dto->isStorno,
			]);
		}
	}
}
