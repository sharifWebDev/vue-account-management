<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankIdRequest;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Http\Resources\BankResource;
use App\Services\BankService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function __construct(
        protected BankService $bankService,

    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $banks = $this->bankService->get($request);

            return success('Records retrieved successfully', BankResource::collection($banks));
        } catch (Exception $e) {
            info('Error retrieved Banks!', [$e]);

            return error('Banks retrieved failed!.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankRequest $request): JsonResponse
    {
        try {

            $bank = $this->bankService->store($request->validated());

            return success('Records saved successfully.');
        } catch (Exception $e) {
            info('Banks data insert failed!', [$e]);

            return error('Banks insert failed!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function find(BankIdRequest $bankId): JsonResponse
    {
        try {

            $bank = $this->bankService->find($bankId->id);

            return success('Records retrieved successfully', new BankResource($bank));
        } catch (\Exception $e) {
            info('Banks data showing failed!', [$e]);

            return error('Banks retrieved failed!.'.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankRequest $request, BankIdRequest $bankId): JsonResponse
    {
        try {

            $bank = $this->bankService->update($bankId->id, $request->validated());

            return success('Records updated successfully.');
        } catch (\Exception $e) {
            info('Banks update failed!', [$e]);

            return error('Banks update failed!.'.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankIdRequest $bankId): JsonResponse
    {
        try {

            $bank = $this->bankService->delete($bankId->id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Banks delete failed!', [$e]);

            return error('Banks delete failed!.');
        }
    }

    /**
     * Get all Teams for form dropdowns.
     */
    public function getAll(): JsonResponse
    {
        try {
            $banks = $this->bankService->getAll();

            return success('Records rectrived successfully', BankResource::collection($banks));
        } catch (\Exception $e) {
            info('Banks rectrived failed!', [$e]);

            return error('Banks rectrived failed!.');
        }
    }

    public function trash(Request $request): JsonResponse
    {
        try {
            $banks = $this->bankService->trash($request);

            return success('Records retrieved successfully', BankResource::collection($banks));
        } catch (\Exception $e) {
            info('Banks trash retrieval failed!', [$e]);

            return error('Banks trash retrieval failed! '.$e->getMessage());
        }
    }

    public function restore(AccountIdRequest $accountId): JsonResponse
    {
        try {
            $this->bankService->restore($accountId->id);

            return success('Records restored successfully');
        } catch (\Exception $e) {
            info('Banks restored failed!', [$e]);

            return error('Banks restored failed!.');
        }
    }

    public function forceDelete($id): JsonResponse
    {
        try {
            $this->bankService->forceDelete($id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Banks rectrived failed!', [$e]);

            return error($e->getMessage());
        }
    }
}
