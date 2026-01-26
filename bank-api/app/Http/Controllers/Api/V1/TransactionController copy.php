<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\TransactionIdRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService,

    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $transactions = $this->transactionService->get($request);

            return success('Records retrieved successfully', TransactionResource::collection($transactions));
        } catch (Exception $e) {
            info('Error retrieved Transactions!', [$e]);

            return error('Transactions retrieved failed!.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {

            $transaction = $this->transactionService->store($request->validated());

            return success('Records saved successfully.');
        } catch (Exception $e) {
            info('Transactions data insert failed!', [$e]);

            return error('Transactions insert failed!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function find(TransactionIdRequest $transactionId): JsonResponse
    {
        try {

            $transaction = $this->transactionService->find($transactionId->id);

            return success('Records retrieved successfully', new TransactionResource($transaction));
        } catch (\Exception $e) {
            info('Transactions data showing failed!', [$e]);

            return error('Transactions retrieved failed!.'.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, TransactionIdRequest $transactionId): JsonResponse
    {
        try {

            $transaction = $this->transactionService->update($transactionId->id, $request->validated());

            return success('Records updated successfully.');
        } catch (\Exception $e) {
            info('Transactions update failed!', [$e]);

            return error('Transactions update failed!.'.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionIdRequest $transactionId): JsonResponse
    {
        try {

            $transaction = $this->transactionService->delete($transactionId->id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Transactions delete failed!', [$e]);

            return error('Transactions delete failed!.');
        }
    }

    /**
     * Get all Teams for form dropdowns.
     */
    public function getAll(): JsonResponse
    {
        try {
            $transactions = $this->transactionService->getAll();

            return success('Records rectrived successfully', TransactionResource::collection($transactions));
        } catch (\Exception $e) {
            info('Transactions rectrived failed!', [$e]);

            return error('Transactions rectrived failed!.');
        }
    }

    public function trash(Request $request): JsonResponse
    {
        try {
            $transaction = $this->transactionService->trash($request);

            return success(
                'Records retrieved successfully',
                TransactionResource::collection($transaction)
            );
        } catch (\Exception $e) {
            info('Transactions trash list retrieval failed!', [$e]);

            return error('Transactions trash list retrieval failed! '.$e->getMessage());
        }
    }

    public function restore(TransactionIdRequest $transactionId): JsonResponse
    {
        try {
            $transaction = $this->transactionService->restore($transactionId->id);

            return success('Records restored successfully');
        } catch (\Exception $e) {
            info('Transactions restored failed!', [$e]);

            return error('Transactions restored failed!.');
        }
    }

    public function forceDelete($id): JsonResponse
    {
        try {
            $transaction = $this->transactionService->forceDelete($id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Transactions deleted failed!', [$e]);

            return error('Transactions deleted failed!.');
        }
    }
}
