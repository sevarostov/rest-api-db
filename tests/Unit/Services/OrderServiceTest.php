<?php

namespace Tests\Unit\Services;

use App\Dto\OrderDto;
use App\Repositories\CategoryRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\NmRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\WarehouseRepository;
use App\Services\ApiPageTrackerService;
use App\Services\ApiService;
use App\Services\OrderService;
use DateTime;
use Illuminate\Support\Collection;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
	private ApiService $apiService;

	protected function setUp(): void {
		parent::setUp();

		$this->apiService = new ApiService();
		$this->apiPageTrackerService = new ApiPageTrackerService();

		$this->orderRepository = new OrderRepository();
		$this->nmRepository = new NmRepository();
		$this->categoryRepository = new CategoryRepository();
		$this->subjectRepository = new SubjectRepository();
		$this->warehouseRepository = new WarehouseRepository();
		$this->incomeRepository = new IncomeRepository();
		$this->orderService = new OrderService(
			$this->apiService,
			$this->orderRepository,
			$this->warehouseRepository,
			$this->nmRepository,
			$this->categoryRepository,
			$this->subjectRepository,
			$this->incomeRepository,
			$this->apiPageTrackerService,
		);
	}

	public function testSyncOrdersWithValidData(): void
	{
		$dateFrom = new DateTime('2021-03-10');
		$dateTo = new DateTime('2026-03-10');

		$result = $this->orderService->sync($dateFrom, $dateTo);

		$this->assertInstanceOf(Collection::class, $result);
		$this->assertContainsOnlyInstancesOf(OrderDto::class, $result);
	}
}
