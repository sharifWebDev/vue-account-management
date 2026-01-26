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
            return error('Transactions retrieved failed: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {
            $transaction = $this->transactionService->store($request->validated());
            return success('Transaction saved successfully.', new TransactionResource($transaction));
        } catch (Exception $e) {
            return error('Transaction insert failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function find(TransactionIdRequest $transactionId): JsonResponse
    {
        try {
            $transaction = $this->transactionService->find($transactionId->id);
            return success('Record retrieved successfully', new TransactionResource($transaction));
        } catch (\Exception $e) {
            return error('Transaction not found: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, TransactionIdRequest $transactionId): JsonResponse
    {
        try {
            $transaction = $this->transactionService->update($transactionId->id, $request->validated());
            return success('Transaction updated successfully.', new TransactionResource($transaction));
        } catch (\Exception $e) {
            return error('Transaction update failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionIdRequest $transactionId): JsonResponse
    {
        try {
            $this->transactionService->delete($transactionId->id);
            return success('Transaction deleted successfully');
        } catch (\Exception $e) {
            return error('Transaction delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete transaction.
     */
    public function softDelete(TransactionIdRequest $transactionId): JsonResponse
    {
        try {
            $this->transactionService->softDelete($transactionId->id);
            return success('Transaction moved to trash successfully');
        } catch (\Exception $e) {
            return error('Transaction soft delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Undo soft delete transaction.
     */
    public function undoSoftDelete(TransactionIdRequest $transactionId): JsonResponse
    {
        try {
            $this->transactionService->undoSoftDelete($transactionId->id);
            return success('Transaction restored from trash successfully');
        } catch (\Exception $e) {
            return error('Transaction restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Force delete transaction.
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $this->transactionService->forceDelete($id);
            return success('Transaction permanently deleted successfully');
        } catch (\Exception $e) {
            return error('Transaction force delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Restore transaction from trash.
     */
    public function restore(TransactionIdRequest $transactionId): JsonResponse
    {
        try {
            $this->transactionService->restore($transactionId->id);
            return success('Transaction restored successfully');
        } catch (\Exception $e) {
            return error('Transaction restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Change transaction status.
     */
    public function changeStatus(TransactionIdRequest $transactionId): JsonResponse
    {
        try {
            $request = request();
            $status = $request->input('status', 'active');

            $this->transactionService->changeStatus($transactionId->id, $status);

            $message = $status === 'active'
                ? 'Transaction activated successfully'
                : 'Transaction deactivated successfully';

            return success($message);
        } catch (\Exception $e) {
            return error('Status change failed: ' . $e->getMessage());
        }
    }

    /**
     * Get trashed transactions.
     */
    public function trash(Request $request): JsonResponse
    {
        try {
            $transactions = $this->transactionService->trash($request);
            return success('Trashed transactions retrieved successfully', TransactionResource::collection($transactions));
        } catch (\Exception $e) {
            return error('Trash list retrieval failed: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction history with filters.
     */
    public function history(Request $request): JsonResponse
    {
        try {
            $transactions = $this->transactionService->getTransactionHistory($request);

            // Calculate running balance
            $balance = 0;
            $transactions->getCollection()->transform(function ($transaction) use (&$balance) {
                $deposit = $transaction->deposit ? (float) $transaction->deposit : 0;
                $withdraw = $transaction->withdraw ? (float) $transaction->withdraw : 0;
                $balance += ($deposit - $withdraw);
                $transaction->balance = $balance;
                return $transaction;
            });

            return success('Transaction history retrieved successfully', TransactionResource::collection($transactions));
        } catch (\Exception $e) {
            return error('History retrieval failed: ' . $e->getMessage());
        }
    }

    /**
     * Get account balance.
     */
    public function getBalance(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'account_number' => 'required|string'
            ]);

            $balance = $this->transactionService->getBalance($request->account_number);
            return success('Balance retrieved successfully', ['balance' => $balance]);
        } catch (\Exception $e) {
            return error('Balance retrieval failed: ' . $e->getMessage());
        }
    }

    /**
     * Get all transactions for dropdowns.
     */
    public function getAll(): JsonResponse
    {
        try {
            $transactions = $this->transactionService->getAll();
            return success('Records retrieved successfully', TransactionResource::collection($transactions));
        } catch (\Exception $e) {
            return error('Transactions retrieval failed: ' . $e->getMessage());
        }
    }
}
