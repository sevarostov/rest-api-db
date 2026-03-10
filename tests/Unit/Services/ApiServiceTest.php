<?php

namespace Tests\Unit\Services;

use App\Services\ApiService;
use DateTime;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApiServiceTest extends TestCase
{
	private ApiService $apiService;
	protected function setUp(): void {
		parent::setUp();
		$this->truncateDb();
		$this->apiService = new ApiService();

	}

	public function testGetsAllStocksDataWithValidEndpoint(): void {
		$endpointKey = 'stocks';
		$dateFrom = new DateTime('2026-03-10');
		$dateTo = new DateTime('2026-03-10');

		$stocks = $this->apiService->getAllData($endpointKey, $dateFrom, $dateTo);

		$this->assertIsArray($stocks);
		$this->assertCount( 3173, $stocks);
	}

	public function testGetsAllIncomesDataWithValidEndpoint(): void {
		$endpointKey = 'incomes';
		$dateFrom = new DateTime('2021-03-10');
		$dateTo = new DateTime('2026-03-10');

		$incomes = $this->apiService->getAllData($endpointKey, $dateFrom, $dateTo);

		$this->assertIsArray($incomes);
	}

	public function testGetsAllSalesDataWithValidEndpoint(): void {
		$endpointKey = 'sales';
		$dateFrom = new DateTime('2021-03-10');
		$dateTo = new DateTime('2026-03-10');

		$page = 1;
		$limit = 500;
		do {
			$response = $this->apiService->getData($endpointKey, $dateFrom, $dateTo, $page, $limit);

			if (!$response || $response['status_code'] !== 200) {
				break;
			}

			$data = $response['data']['data'] ?? [];

			$this->assertIsArray($data);
			$hasNext = $response['data']['links']['next'] ?? false;

			$page++;

		} while ($hasNext);

		$this->assertIsArray($response);
	}

	public function testGetsAllOrdersDataWithValidEndpoint(): void {
		$endpointKey = 'orders';
		$dateFrom = new DateTime('2021-03-10');
		$dateTo = new DateTime('2026-03-10');

		$page = 1;
		$limit = 500;
		do {
			$response = $this->apiService->getData($endpointKey, $dateFrom, $dateTo, $page, $limit);
			if (!$response || $response['status_code'] !== 200) {
				break;
			}

			$data = $response['data']['data'] ?? [];
			$this->assertIsArray($data);
			$hasNext = $response['data']['links']['next'] ?? false;

			$page++;

		} while ($hasNext);

		$this->assertIsArray($response);
	}

	private function truncateDb() {
		DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

		$tables = [
			'nms', 'warehouses', 'subjects', 'categories',
			'stocks', 'incomes', 'orders'
		];

		foreach ($tables as $table) {
			DB::table($table)->truncate();
		}

		DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
	}

}
