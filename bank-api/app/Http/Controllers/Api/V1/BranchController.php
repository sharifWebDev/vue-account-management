<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchIdRequest;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Services\BranchService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(
        protected BranchService $branchService,

    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $branchs = $this->branchService->get($request);

            return success('Records retrieved successfully', BranchResource::collection($branchs));
        } catch (Exception $e) {
            info('Error retrieved Branches!', [$e]);

            return error('Branches retrieved failed!.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request): JsonResponse
    {
        try {

            $branch = $this->branchService->store($request->validated());

            return success('Records saved successfully.');
        } catch (Exception $e) {
            info('Branches data insert failed!', [$e]);

            return error('Branches insert failed!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function find(BranchIdRequest $branchId): JsonResponse
    {
        try {

            $branch = $this->branchService->find($branchId->id);

            return success('Records retrieved successfully', new BranchResource($branch));
        } catch (\Exception $e) {
            info('Branches data showing failed!', [$e]);

            return error('Branches retrieved failed!.'.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, BranchIdRequest $branchId): JsonResponse
    {
        try {

            $branch = $this->branchService->update($branchId->id, $request->validated());

            return success('Records updated successfully.');
        } catch (\Exception $e) {
            info('Branches update failed!', [$e]);

            return error('Branches update failed!.'.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BranchIdRequest $branchId): JsonResponse
    {
        try {

            $branch = $this->branchService->delete($branchId->id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Branches delete failed!', [$e]);

            return error('Branches delete failed!.');
        }
    }

    /**
     * Get all Teams for form dropdowns.
     */
    public function getAll(): JsonResponse
    {
        try {
            $branchs = $this->branchService->getAll();

            return success('Records rectrived successfully', BranchResource::collection($branchs));
        } catch (\Exception $e) {
            info('Branches rectrived failed!', [$e]);

            return error('Branches rectrived failed!.');
        }
    }

    public function trash(Request $request): JsonResponse
    {
        try {
            $branch = $this->branchService->trash($request);

            return success('Records retrieved successfully', BranchResource::collection($branch));
        } catch (\Exception $e) {
            info('Branchs trash retrieval failed!', [$e]);

            return error('Branchs trash retrieval failed! '.$e->getMessage());
        }
    }

    public function restore(BranchIdRequest $branchId): JsonResponse
    {
        try {
            $branch = $this->branchService->restore($branchId->id);

            return success('Records restored successfully.');
        } catch (\Exception $e) {
            info('Branchs restored failed!', [$e]);

            return error('Branchs restored failed!.');
        }
    }

    public function forceDelete($id): JsonResponse
    {
        try {
            $branch = $this->branchService->forceDelete($id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Branchs deleted failed!', [$e]);

            return error('Branchs deleted failed!.');
        }
    }
}
