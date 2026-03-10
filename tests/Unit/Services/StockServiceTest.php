<?php

namespace Tests\Unit\Services;

use App\Dto\StockDto;
use App\Repositories\CategoryRepository;
use App\Repositories\NmRepository;
use App\Repositories\StockRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\WarehouseRepository;
use App\Services\ApiService;
use App\Services\StockService;
use DateTime;
use Illuminate\Support\Collection;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
	private ApiService $apiService;

	protected function setUp(): void {
		parent::setUp();

		$this->apiService = new ApiService();

		$this->stockRepository = new StockRepository();
		$this->nmRepository = new NmRepository();
		$this->categoryRepository = new CategoryRepository();
		$this->subjectRepository = new SubjectRepository();
		$this->warehouseRepository = new WarehouseRepository();
		$this->stockService = new StockService(
			$this->apiService,
			$this->stockRepository,
			$this->nmRepository,
			$this->categoryRepository,
			$this->subjectRepository,
			$this->warehouseRepository,
		);
	}

	public function testSyncStocksWithValidData(): void
	{
		$dateFrom = new DateTime('2026-03-10');
		$dateTo = new DateTime('2026-03-10');

		$result = $this->stockService->sync($dateFrom, $dateTo);

		$this->assertInstanceOf(Collection::class, $result);
		$this->assertContainsOnlyInstancesOf(StockDto::class, $result);

//		$this->assertGreaterThanOrEqual(1, $result->count());
//
//		$firstStock = $result->first();
//		$this->assertNotNull($firstStock);
//		$this->assertIsString($firstStock->supplierArticle);
//		$this->assertIsInt($firstStock->barcode);
//		$this->assertIsFloat($firstStock->price);
	}
}
