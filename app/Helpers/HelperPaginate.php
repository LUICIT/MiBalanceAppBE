<?php

namespace App\Helpers;

use App\Classes\CustomPaginate;
use App\Interfaces\PaginatorContract;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Pagination\Paginator;

class HelperPaginate implements PaginatorContract
{

    protected mixed $items;
    protected array $paginator;

    /**
     * Crea un nuevo páginador.
     *
     * @param mixed $items
     * @param int $perPage
     * @param ?int $currentPage
     * @throws BindingResolutionException
     */
    public function __construct(mixed $items, int $perPage, int $currentPage = null)
    {
        $this->paginator = $this->paginator(
            $items->forPage(
                $currentPage,
                $perPage
            ),
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => Paginator::resolveCurrentPath()
            ]
        );
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Obtenga la instancia como arreglo.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'current_page' => $this->currentPage(),
            'from' => $this->from() ?: 0,
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'to' => $this->lastItem() ?: 0,
            'total' => $this->total(),
            'items' => $this->items(),
        ];
    }

    public function items(): array
    {
        // Compatibilidad: CustomPaginate puede devolver 'data' (Laravel) o 'items' (custom)
        return $this->paginator['items'] ?? [];
    }

    public function currentPage(): int
    {
        return $this->paginator['current_page'] ?? 1;
    }

    public function from(): int
    {
        return $this->paginator['current_page'] ?? 1;
    }

    public function lastPage(): int
    {
        return $this->paginator['current_page'] ?? 1;
    }

    public function perPage(): int
    {
        return $this->paginator['current_page'] ?? 1;
    }

    public function lastItem(): int
    {
        return $this->paginator['to'] ?? 0;
    }

    public function total(): int
    {
        return $this->paginator['total'] ?? 0;
    }

    /**
     * @throws BindingResolutionException
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options = [])
    {
        return (Container::getInstance()->makeWith(
            CustomPaginate::class,
            compact(
                'items',
                'total',
                'perPage',
                'currentPage',
                'options'
            ))
        )->toArray();
    }
}
