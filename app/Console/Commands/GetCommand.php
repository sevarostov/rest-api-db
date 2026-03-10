<?php

namespace App\Console\Commands;


use App\Services\ApiService;
use App\Services\IncomeService;
use App\Services\StockService;
use DateTime;
use Illuminate\Console\Command;
use function PHPUnit\Framework\isInstanceOf;

class GetCommand extends Command
{
	public function __construct(StockService $stocksService, IncomeService $incomesService) {
		parent::__construct();
		$this->stocksService = $stocksService;
		$this->incomesService = $incomesService;
	}

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'get';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gets data from Api';

	/**
	 * Execute the console command.
	 */
	public function handle(): int {
		foreach (array_keys(ApiService::ENDPOINTS) as $endpoint) {
			$subst = substr($endpoint, 0, -1);
			$service = $this->{$endpoint . "Service"};
			$dateFrom = new DateTime('2026-03-10');
			$dateTo = new DateTime('2026-03-10');

			if(!isInstanceOf("App\Services\\".$subst."Service", $service)) break;
			$result = $service->sync($dateFrom, $dateTo);
			$this->info(sprintf("Successfully stored %s results of endpoint \\'%s'",
				count($result),
				$endpoint));
		}

		return self::SUCCESS;
	}
}
