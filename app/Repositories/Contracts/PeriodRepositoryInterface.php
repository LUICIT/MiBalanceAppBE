<?php

namespace App\Repositories\Contracts;

use App\Helpers\HelperPaginate;
use App\Models\Period;

interface PeriodRepositoryInterface
{

    public function paginateForUser(int $userId, array $filters = [], int $perPage = 20): HelperPaginate;

    public function findForUser(int $userId, int $id): ?Period;

    public function existsCodeForUser(int $userId, string $code, ?int $exceptId = null): bool;

    public function create(array $data): Period;

    public function update(Period $period, array $data): Period;

    public function delete(Period $period): void; // soft delete

}
