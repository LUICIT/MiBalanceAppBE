<?php

namespace App\Interfaces;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

interface PaginatorContract extends Arrayable, JsonSerializable
{

    /** Página actual */
    public function currentPage(): int;

    /** Índice inicial (from) de la página */
    public function from(): int;

    /** Última página */
    public function lastPage(): int;

    /** Tamaño de página */
    public function perPage(): int;

    /** Índice final (to) de la página */
    public function lastItem(): int;

    /** Total de registros */
    public function total(): int;

    /** Items de la página (ya transformados si aplica) */
    public function items(): array;

    /** Estructura final serializable para la API */
    public function toArray(): array;

    /** Para `response()->json()` sin llamar a toArray() explícitamente */
    public function jsonSerialize(): mixed;

}
