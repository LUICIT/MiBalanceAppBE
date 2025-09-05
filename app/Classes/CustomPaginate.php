<?php

namespace App\Classes;

use Illuminate\Pagination\LengthAwarePaginator;

class CustomPaginate extends LengthAwarePaginator
{

    public function toArray(): array
    {
        return [
            'current_page' => $this->currentPage(),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
            'items' => $this->items(),
        ];
    }

}
