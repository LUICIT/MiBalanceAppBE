<?php

namespace App\Services;

use App\Helpers\HelperPaginate;
use App\Models\Period;
use App\Repositories\Contracts\PeriodRepositoryInterface;
use App\Services\Contracts\PeriodServiceInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class PeriodService implements PeriodServiceInterface
{

    public function __construct(private readonly PeriodRepositoryInterface $repository)
    {
    }

    public function paginate(int $userId, array $filters = [], int $perPage = 20): HelperPaginate
    {
        return $this->repository->paginateForUser($userId, $filters, $perPage);
    }

    public function getOrFail(int $userId, int $id): Period
    {
        $p = $this->repository->findForUser($userId, $id);
        if (!$p) {
            throw new HttpException(404, 'Periodo no encontrado'); // Handler lo formatea
        }
        return $p;
    }

    /**
     * @throws Throwable
     */
    public function create(int $userId, array $data): Period
    {
        // Validaciones de dominio/negocio adicionales
        if ($this->repository->existsCodeForUser($userId, $data['code'])) {
            throw new HttpException(409, 'La clave del periodo ya existe');
        }

        return DB::transaction(function () use ($userId, $data) {
            $data['user_id'] = $userId;
            return $this->repository->create($data);
        });
    }

    public function update(int $userId, int $id, array $data): Period
    {
        $periodo = $this->getOrFail($userId, $id);

        if (isset($data['code']) && $this->repository->existsCodeForUser($userId, $data['code'], $id)) {
            throw new HttpException(409, 'La clave del periodo ya existe');
        }

        return DB::transaction(fn() => $this->repository->update($periodo, $data));
    }

    public function delete(int $userId, int $id): void
    {
        $periodo = $this->getOrFail($userId, $id);
        DB::transaction(fn() => $this->repository->delete($periodo));
    }
}
