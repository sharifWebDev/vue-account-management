<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountIdRequest;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Services\AccountService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        protected AccountService $accountService,

    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $accounts = $this->accountService->get($request);

            return success('Records retrieved successfully', AccountResource::collection($accounts));
        } catch (Exception $e) {
            info('Error retrieved Accounts!', [$e]);

            return error('Accounts retrieved failed!.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request): JsonResponse
    {
        try {

            $account = $this->accountService->store($request->validated());

            return success('Records saved successfully.');
        } catch (Exception $e) {
            info('Accounts data insert failed!', [$e]);

            return error('Accounts insert failed!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function find(AccountIdRequest $accountId): JsonResponse
    {
        try {

            $account = $this->accountService->find($accountId->id);

            return success('Records retrieved successfully', new AccountResource($account));
        } catch (\Exception $e) {
            info('Accounts data showing failed!', [$e]);

            return error('Accounts retrieved failed!.'.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, AccountIdRequest $accountId): JsonResponse
    {
        try {

            $account = $this->accountService->update($accountId->id, $request->validated());

            return success('Records updated successfully.');
        } catch (\Exception $e) {
            info('Accounts update failed!', [$e]);

            return error('Accounts update failed!.'.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountIdRequest $accountId): JsonResponse
    {
        try {

            $account = $this->accountService->delete($accountId->id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Accounts delete failed!', [$e]);

            return error('Accounts delete failed!.');
        }
    }

    /**
     * Get all Teams for form dropdowns.
     */
    public function getAll(): JsonResponse
    {
        try {
            $accounts = $this->accountService->getAll();

            return success('Records rectrived successfully', AccountResource::collection($accounts));
        } catch (\Exception $e) {
            info('Accounts rectrived failed!', [$e]);

            return error('Accounts rectrived failed!.');
        }
    }

    public function trash(Request $request): JsonResponse
    {
        try {
            $accounts = $this->accountService->trash($request);

            return success('Records retrieved successfully', AccountResource::collection($accounts));
        } catch (\Exception $e) {
            info('Accounts trash retrieval failed!', [$e]);

            return error('Accounts trash retrieval failed! '.$e->getMessage());
        }
    }

    public function restore(AccountIdRequest $accountId): JsonResponse
    {
        try {
            $this->accountService->restore($accountId->id);

            return success('Records restored successfully');
        } catch (\Exception $e) {
            info('Accounts restored failed!', [$e]);

            return error('Accounts restored failed!.');
        }
    }

    public function forceDelete($id): JsonResponse
    {
        try {
            $this->accountService->forceDelete($id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Accounts rectrived failed!', [$e]);

            return error($e->getMessage());
        }
    }
}
