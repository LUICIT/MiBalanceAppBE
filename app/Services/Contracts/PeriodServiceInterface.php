<?php

namespace App\Services\Contracts;

use App\Helpers\HelperPaginate;
use App\Models\Period;

interface PeriodServiceInterface
{

    public function paginate(int $userId, array $filters = [], int $perPage = 20): HelperPaginate;

    public function getOrFail(int $userId, int $id): Period;

    public function create(int $userId, array $data): Period;

    public function update(int $userId, int $id, array $data): Period;

    public function delete(int $userId, int $id): void;

}
