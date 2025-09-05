<?php

namespace App\Repositories;

use App\Helpers\HelperPaginate;
use App\Interfaces\PeriodRepositoryInterface;
use App\Models\Period;
use Illuminate\Contracts\Container\BindingResolutionException;

class PeriodRepository implements PeriodRepositoryInterface
{

    /**
     * @throws BindingResolutionException
     */
    public function paginateForUser(int $userId, array $filters = [], int $perPage = 20): HelperPaginate
    {
        $items = Period::query()
            ->where('user_id', $userId)
            ->when(!empty($filters['type_period']), fn($q) => $q->where('type_period', $filters['type_period']))
            ->when(!empty($filters['since']), fn($q) => $q->whereDate('payment_date', '>=', $filters['since']))
            ->when(!empty($filters['to']), fn($q) => $q->whereDate('payment_date', '<=', $filters['to']))
            ->orderByDesc('payment_date')->get();

        $page = (int)request()->query('page', 0);

        return new HelperPaginate($items, $perPage, $page);
    }

    public function findForUser(int $userId, int $id): ?Period
    {
        return Period::query()->where('user_id', $userId)->find($id);
    }

    public function create(array $data): Period
    {
        return Period::query()->create($data); // Versionable + Observer registran change_log
    }

    public function update(Period $period, array $data): Period
    {
        $period->fill($data)->save(); // sube version y registra change_log
        return $period;
    }

    public function delete(Period $period): void
    {
        $period->delete(); // soft delete → change_log 'delete'
    }
}
