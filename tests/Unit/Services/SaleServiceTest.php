<?php

namespace Tests\Unit\Services;

use App\Dto\SaleDto;
use App\Repositories\CategoryRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\NmRepository;
use App\Repositories\SaleRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\WarehouseRepository;
use App\Services\ApiPageTrackerService;
use App\Services\ApiService;
use App\Services\SaleService;
use DateTime;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SaleServiceTest extends TestCase
{
	private ApiService $apiService;

	protected function setUp(): void {
		parent::setUp();

		$this->apiService = new ApiService();
		$this->apiPageTrackerService = new ApiPageTrackerService();

		$this->saleRepository = new SaleRepository();
		$this->nmRepository = new NmRepository();
		$this->categoryRepository = new CategoryRepository();
		$this->subjectRepository = new SubjectRepository();
		$this->warehouseRepository = new WarehouseRepository();
		$this->incomeRepository = new IncomeRepository();
		$this->saleService = new SaleService(
			$this->apiService,
			$this->saleRepository,
			$this->warehouseRepository,
			$this->nmRepository,
			$this->categoryRepository,
			$this->subjectRepository,
			$this->incomeRepository,
			$this->apiPageTrackerService,
		);
	}

	public function testSyncSalesWithValidData(): void
	{
		$dateFrom = new DateTime('2021-03-10');
		$dateTo = new DateTime('2026-03-10');

		$result = $this->saleService->sync($dateFrom, $dateTo);

		$this->assertInstanceOf(Collection::class, $result);
		$this->assertContainsOnlyInstancesOf(SaleDto::class, $result);
	}
}
