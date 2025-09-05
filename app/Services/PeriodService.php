<?php

namespace App\Services;

use App\Helpers\HelperPaginate;
use App\Interfaces\PeriodRepositoryInterface;
use App\Interfaces\PeriodServiceInterface;
use App\Models\Period;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

readonly class PeriodService implements PeriodServiceInterface
{

    public function __construct(private PeriodRepositoryInterface $repository)
    {
    }

    public function paginate(array $filters = [], int $perPage = 20): HelperPaginate
    {
        return $this->repository->paginateForUser($this->userIdOrFail(), $filters, $perPage);
    }

    public function getOrFail(int $id): Period
    {
        $p = $this->repository->findForUser($this->userIdOrFail(), $id);
        if (!$p) {
            throw new NotFoundHttpException();
        }
        return $p;
    }

    /**
     * @throws Throwable
     */
    public function create(array $data): Period
    {
        // Validaciones de dominio/negocio adicionales

        return DB::transaction(function () use ($data) {
            $data['user_id'] = $this->userIdOrFail();
            return $this->repository->create($data);
        });
    }

    /**
     * @throws Throwable
     */
    public function update(int $id, array $data): Period
    {
        $period = $this->getOrFail($id);
        return DB::transaction(fn() => $this->repository->update($period, $data));
    }

    /**
     * @throws Throwable
     */
    public function delete(int $id): void
    {
        $period = $this->getOrFail($id);
        DB::transaction(fn() => $this->repository->delete($period));
    }

    private function userIdOrFail(): int
    {
        return (int)Auth::id();
    }
}
