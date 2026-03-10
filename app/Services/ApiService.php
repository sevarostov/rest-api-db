<?php

namespace App\Services;

use DateTimeInterface;
use Exception;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Сервис для получения данных из API (stocks, incomes, sales, orders)
 */
class ApiService
{
	private const ENDPOINTS = [
		'stocks' => '/api/stocks',
		'incomes' => '/api/incomes',
		'sales' => '/api/sales',
		'orders' => '/api/orders',
	];

	public function __construct(
		private readonly HttpClientInterface $httpClient,
		private readonly string $protocol,
		private readonly string $host,
		private readonly int $port,
		private readonly string $apiKey,
	) {}

	/**
	 * Получает данные по эндпоинту с пагинацией
	 *
	 * @param string $endpointKey Ключ эндпоинта (stocks, incomes, sales, orders)
	 * @param DateTimeInterface $dateFrom Начальная дата
	 * @param DateTimeInterface $dateTo Конечная дата
	 * @param int $page Номер страницы
	 * @param int $limit Лимит записей на страницу
	 *
	 * @return array|null
	 * @throws Exception
	 */
	public function getData(
		string $endpointKey,
		DateTimeInterface $dateFrom,
		DateTimeInterface $dateTo,
		int $page = 1,
		int $limit = 500,
	): ?array {
		if (!isset(self::ENDPOINTS[$endpointKey])) {
			throw new Exception("Invalid endpoint key: $endpointKey", 400);
		}

		$url = $this->buildUrl(
			self::ENDPOINTS[$endpointKey],
			$dateFrom,
			$dateTo,
			$page,
			$limit,
		);

		return $this->apiGetRequest($url);
	}

	/**
	 * Получает все данные по эндпоинту (со всеми страницами)
	 *
	 * @param string $endpointKey
	 * @param DateTimeInterface $dateFrom
	 * @param DateTimeInterface $dateTo
	 * @param int $limit
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getAllData(
		string $endpointKey,
		DateTimeInterface $dateFrom,
		DateTimeInterface $dateTo,
		int $limit = 500,
	): array {
		$allData = [];
		$page = 1;

		do {

			$response = $this->getData($endpointKey, $dateFrom, $dateTo, $page, $limit);
			if (!$response || $response['status_code'] !== 200) {
				break;
			}

			$data = $response['data']['data'] ?? [];
			$allData = array_merge($allData, $data);

			$hasNext = $response['data']['links']['next'] ?? false;

			$page++;

		} while ($hasNext);

		return $allData;
	}

	/**
	 * Строит полный URL для запроса
	 *
	 * @param string $path
	 * @param DateTimeInterface $dateFrom
	 * @param DateTimeInterface $dateTo
	 * @param int $page
	 * @param int $limit
	 *
	 * @return string
	 */
	private function buildUrl(
		string $path,
		DateTimeInterface $dateFrom,
		DateTimeInterface $dateTo,
		int $page,
		int $limit,
	): string {
		$baseUrl = "{$this->protocol}{$this->host}:{$this->port}";
		$queryParams = [
			'dateFrom' => $dateFrom->format('Y-m-d'),
			'dateTo' => $dateTo->format('Y-m-d'),
			'page' => $page,
			'key' => $this->apiKey,
			'limit' => $limit,
		];

		return $baseUrl . $path . '?' . http_build_query($queryParams);
	}

	/**
	 * Отправляет GET‑запрос к API
	 *
	 * @param string $url
	 *
	 * @return array|null
	 * @throws ClientExceptionInterface
	 * @throws DecodingExceptionInterface
	 * @throws RedirectionExceptionInterface
	 * @throws ServerExceptionInterface
	 * @throws TransportExceptionInterface
	 */
	private function apiGetRequest(string $url): ?array {

		try {
			$response = $this->httpClient->request('GET', $url);
			$statusCode = $response->getStatusCode();
			$data = $response->toArray(false);

			return ['status_code' => $statusCode, 'data' => $data];
		} catch (ClientException $exception) {

			return [
				'status_code' => $exception->getResponse()->getStatusCode(),
				'data' => $exception->getResponse()->toArray(false),
			];
		} catch (ServerException $serverException) {

			return [
				'status_code' => $serverException->getResponse()->getStatusCode(),
				'data' => $serverException->getResponse()->toArray(false),
			];
		} catch (TransportExceptionInterface|DecodingExceptionInterface $exception) {

			return [
				'status_code' => 500,
				'data' => ['error' => $exception->getMessage()],
			];
		}
	}
}
