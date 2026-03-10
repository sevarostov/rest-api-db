<?php

namespace Tests\Unit\Services;

use App\Dto\IncomeDto;
use App\Repositories\NmRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\WarehouseRepository;
use App\Services\ApiService;
use App\Services\IncomeService;
use DateTime;
use Illuminate\Support\Collection;
use Tests\TestCase;

class IncomeServiceTest extends TestCase
{
	private ApiService $apiService;

	protected function setUp(): void {
		parent::setUp();

		$this->apiService = new ApiService();

		$this->incomeRepository = new IncomeRepository();
		$this->nmRepository = new NmRepository();
		$this->warehouseRepository = new WarehouseRepository();
		$this->incomeService = new IncomeService(
			$this->apiService,
			$this->incomeRepository,
			$this->warehouseRepository,
			$this->nmRepository
		);
	}

	public function testSyncIncomesWithValidData(): void
	{
		$dateFrom = new DateTime('2021-03-10');
		$dateTo = new DateTime('2026-03-10');

		$result = $this->incomeService->sync($dateFrom, $dateTo);

		$this->assertInstanceOf(Collection::class, $result);
		$this->assertContainsOnlyInstancesOf(IncomeDto::class, $result);
	}
}
