<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserIdRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,

    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $users = $this->userService->get($request);

            return success('Records retrieved successfully', UserResource::collection($users));
        } catch (Exception $e) {
            info('Error retrieved Users!', [$e]);

            return error('Users retrieved failed!.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {

            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = saveImage(
                    $request->file('image'),
                    'users/images',
                    $request->company_name
                );
            }

            $this->userService->store($data);

            return success('Records saved successfully.');
        } catch (Exception $e) {
            info('Users data insert failed!', [$e]);

            return error('Users insert failed!.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function find(UserIdRequest $userId): JsonResponse
    {
        try {

            $user = $this->userService->find($userId->id);

            return success('Records retrieved successfully', new UserResource($user));
        } catch (\Exception $e) {
            info('Users data showing failed!', [$e]);

            return error('Users retrieved failed!.' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, UserIdRequest $userId): JsonResponse
    {
        try {

            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = saveImage(
                    $request->file('image'),
                    'users/images',
                    $request->company_name
                );
            }

            $this->userService->update($userId->id, $data);

            return success('Records updated successfully.');
        } catch (\Exception $e) {
            info('Users update failed!', [$e]);

            return error('Users update failed!.' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserIdRequest $userId): JsonResponse
    {
        try {

            $this->userService->delete($userId->id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Users delete failed!', [$e]);

            return error('Users delete failed!.');
        }
    }

    /**
     * Get all Teams for form dropdowns.
     */
    public function getAll(): JsonResponse
    {
        try {
            $users = $this->userService->getAll();

            return success('Records rectrived successfully', UserResource::collection($users));
        } catch (\Exception $e) {
            info('Users rectrived failed!', [$e]);

            return error('Users rectrived failed!.');
        }
    }

    public function trash(Request $request): JsonResponse
    {
        try {
            $users = $this->userService->trash($request);

            return success(
                'Records retrieved successfully',
                UserResource::collection($users)
            );
        } catch (\Exception $e) {
            info('Accounts trash list retrieval failed!', [$e]);

            return error('Accounts trash list retrieval failed! ' . $e->getMessage());
        }
    }

    public function restore(UserIdRequest $userId): JsonResponse
    {
        try {
            $user = $this->userService->restore($userId->id);

            return success('Records restored successfully');
        } catch (\Exception $e) {
            info('Accounts restored failed!', [$e]);

            return error('Accounts restored failed!.');
        }
    }

    public function forceDelete($id): JsonResponse
    {
        try {
            $user = $this->userService->forceDelete($id);

            return success('Records deleted successfully');
        } catch (\Exception $e) {
            info('Accounts deleted failed!', [$e]);

            return error('Accounts deleted failed!.');
        }
    }
}
