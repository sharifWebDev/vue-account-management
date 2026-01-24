<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyIdRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService,

    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $companys = $this->companyService->get($request);

            return success('Records retrieved successfully', CompanyResource::collection($companys));
        } catch (Exception $e) {
            info('Error retrieved Companies!', [$e]);

            return error('Companies retrieved failed!.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        try {

            $company = $this->companyService->store($request->validated());

            return success('Records saved successfully.');
        } catch (Exception $e) {
            info('Companies data insert failed!', [$e]);

            return error('Companies insert failed!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function find(CompanyIdRequest $companyId): JsonResponse
    {
        try {

            $company = $this->companyService->find($companyId->id);

            return success('Records retrieved successfully', new CompanyResource($company));
        } catch (\Exception $e) {
            info('Companies data showing failed!', [$e]);

            return error('Companies retrieved failed!.'.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, CompanyIdRequest $companyId): JsonResponse
    {
        try {

            $company = $this->companyService->update($companyId->id, $request->validated());

            return success('Records updated successfully.');
        } catch (\Exception $e) {
            info('Companies update failed!', [$e]);

            return error('Companies update failed!.'.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyIdRequest $companyId): JsonResponse
    {
        try {

            $company = $this->companyService->delete($companyId->id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Companies delete failed!', [$e]);

            return error('Companies delete failed!.');
        }
    }

    /**
     * Get all Teams for form dropdowns.
     */
    public function getAll(): JsonResponse
    {
        try {
            $companys = $this->companyService->getAll();

            return success('Records rectrived successfully', CompanyResource::collection($companys));
        } catch (\Exception $e) {
            info('Companies rectrived failed!', [$e]);

            return error('Companies rectrived failed!.');
        }
    }

    public function trash(Request $request): JsonResponse
    {
        try {
            $company = $this->companyService->trash($request);

            return success(
                'Records retrieved successfully',
                CompanyResource::collection($company)
            );
        } catch (\Exception $e) {
            info('Company trash list retrieval failed!', [$e]);

            return error('Company trash list retrieval failed! '.$e->getMessage());
        }
    }

    public function restore(CompanyIdRequest $companyId): JsonResponse
    {
        try {
            $company = $this->companyService->restore($companyId->id);

            return success('Records restored successfully');
        } catch (\Exception $e) {
            info('Company restored failed!', [$e]);

            return error('Company restored failed!.');
        }
    }

    public function forceDelete($id): JsonResponse
    {
        try {
            $company = $this->companyService->forceDelete($id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Company deleted failed!', [$e]);

            return error('Company deleted failed!.');
        }
    }
}
