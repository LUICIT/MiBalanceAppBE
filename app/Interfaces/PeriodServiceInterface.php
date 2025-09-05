<?php

namespace App\Interfaces;

use App\Helpers\HelperPaginate;
use App\Models\Period;

interface PeriodServiceInterface
{

    public function paginate(array $filters = [], int $perPage = 20): HelperPaginate;

    public function getOrFail(int $id): Period;

    public function create(array $data): Period;

    public function update(int $id, array $data): Period;

    public function delete(int $id): void;

}
