<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Periods\PeriodStoreRequest;
use App\Http\Requests\Periods\PeriodUpdateRequest;
use App\Services\PeriodService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PeriodController extends Controller
{
    public function __construct(private readonly PeriodService $svc)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $page = $this->svc->paginate(Auth::user()->id, $request->only('type_period', 'since', 'to'), perPage: (int)$request->get('per_page', 20));
        return ApiResponse::ok($page);
    }

    /**
     * @throws Throwable
     */
    public function store(PeriodStoreRequest $request): JsonResponse
    {
        $p = $this->svc->create(Auth::user()->id, $request->validated());
        return ApiResponse::ok($p, 201, 'created');
    }

    public function show(int $id): JsonResponse
    {
        $p = $this->svc->getOrFail(Auth::user()->id, $id);
        return ApiResponse::ok($p);
    }

    public function update(PeriodUpdateRequest $request, int $id): JsonResponse
    {
        $p = $this->svc->update(Auth::user()->id, $id, $request->validated());
        return ApiResponse::ok($p, 200, 'updated');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->svc->delete(Auth::user()->id, $id);
        return ApiResponse::ok(null, 204, 'deleted');
    }

}
