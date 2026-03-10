<?php

namespace App\Services;

use App\Models\ApiPageTracker;
use Illuminate\Support\Facades\DB;

class ApiPageTrackerService
{
	/**
	 * Создаёт или обновляет запись трекера для эндпоинта
	 *
	 * @param string $endpoint Название API‑эндпоинта
	 * @param int $page Номер загруженной страницы
	 * @return ApiPageTracker
	 */
	public function updateTracker(string $endpoint, int $page): ApiPageTracker
	{
		return DB::transaction(function () use ($endpoint, $page) {
			return ApiPageTracker::updateOrCreate(
				['endpoint' => $endpoint],
				['last_loaded_page' => $page]
			);
		});
	}

	/**
	 * Получает текущий номер последней загруженной страницы для эндпоинта
	 *
	 * @param string $endpoint
	 * @return int|null
	 */
	public function getLastLoadedPage(string $endpoint): ?int
	{
		$tracker = ApiPageTracker::where('endpoint', $endpoint)->first();

		return $tracker?->last_loaded_page;
	}

	/**
	 * Сбрасывает трекер для эндпоинта (устанавливает последнюю страницу в 0)
	 *
	 * @param string $endpoint
	 * @return void
	 */
	public function resetTracker(string $endpoint): void
	{
		ApiPageTracker::where('endpoint', $endpoint)
			->update(['last_loaded_page' => 0]);
	}

	/**
	 * Удаляет трекер для указанного эндпоинта
	 *
	 * @param string $endpoint
	 * @return bool
	 */
	public function deleteTracker(string $endpoint): bool
	{
		$result = ApiPageTracker::where('endpoint', $endpoint)->delete();

		return $result > 0;
	}

	/**
	 * Получает все записи трекеров
	 *
	 * @return \Illuminate\Support\Collection<ApiPageTracker>
	 */
	public function getAllTrackers(): \Illuminate\Support\Collection
	{
		return ApiPageTracker::orderBy('endpoint')->get();
	}

	/**
	 * Получает трекер по названию эндпоинта
	 *
	 * @param string $endpoint
	 * @return ApiPageTracker|null
	 */
	public function getTracker(string $endpoint): ?ApiPageTracker
	{
		return ApiPageTracker::where('endpoint', $endpoint)->first();
	}

	/**
	 * Увеличивает номер последней загруженной страницы на 1
	 *
	 * @param string $endpoint
	 * @return ApiPageTracker
	 */
	public function incrementPage(string $endpoint): ApiPageTracker
	{
		return DB::transaction(function () use ($endpoint) {
			$tracker = $this->getTracker($endpoint);

			if (!$tracker) {
				$tracker = ApiPageTracker::create([
					'endpoint' => $endpoint,
					'last_loaded_page' => 1
				]);
			} else {
				$tracker->increment('last_loaded_page');
				$tracker->refresh();
			}

			return $tracker;
		});
	}

	/**
	 * Устанавливает конкретный номер страницы для трекера
	 *
	 * @param string $endpoint
	 * @param int $page
	 * @return ApiPageTracker
	 */
	public function setPage(string $endpoint, int $page): ApiPageTracker
	{
		return DB::transaction(function () use ($endpoint, $page) {
			$tracker = $this->getTracker($endpoint);

			if (!$tracker) {
				$tracker = ApiPageTracker::create([
					'endpoint' => $endpoint,
					'last_loaded_page' => $page
				]);
			} else {
				$tracker->update(['last_loaded_page' => $page]);
				$tracker->refresh();
			}

			return $tracker;
		});
	}

	/**
	 * Проверяет, существует ли трекер для эндпоинта
	 *
	 * @param string $endpoint
	 * @return bool
	 */
	public function hasTracker(string $endpoint): bool
	{
		return ApiPageTracker::where('endpoint', $endpoint)->exists();
	}
}
