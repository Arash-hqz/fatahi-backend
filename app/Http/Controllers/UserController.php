<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\UserResource;
use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    protected $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * List users (requires authentication and proper permission).
     * @OA\Get(
     *   path="/admin/users",
     *   tags={"Users"},
     *   summary="List users",
     *   description="Return a collection of users. Only accessible to admins with 'manage users' permission.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User")))
     * )
     */
    public function index()
    {
        return UserResource::collection($this->repo->all());
    }

    /**
     * Show a user by ID.
     * @OA\Get(
     *   path="/admin/users/{id}",
     *   tags={"Users"},
     *   summary="Show user",
     *   description="Retrieve a single user by its identifier. Returns 404 if not found.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="User", @OA\JsonContent(ref="#/components/schemas/User")),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $user = $this->repo->find($id);
        if (! $user) return response()->json(['message' => 'Not found'], 404);
        return new UserResource($user);
    }

    /**
     * Delete a user by ID.
     * @OA\Delete(
     *   path="/admin/users/{id}",
     *   tags={"Users"},
     *   summary="Delete user",
     *   description="Delete the specified user if it exists, otherwise returns 404.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Deleted"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        if ($this->repo->delete($id)) return response()->json(['message' => 'Deleted']);
        return response()->json(['message' => 'Not found'], 404);
    }

    /**
     * Update a user's role.
     * @OA\Patch(
     *   path="/admin/users/role/{id}",
     *   tags={"Users"},
     *   summary="Change role",
     *   description="Change user role to one of the allowed values (admin,user). Returns 404 if user not found.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="role", type="string", example="admin"))),
     *   @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/User")),
     *   @OA\Response(response=404, description="Not found"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function updateRole(UpdateRoleRequest $request, $id)
    {
        $user = $this->repo->updateRole($id, $request->validated()['role']);
        if (! $user) return response()->json(['message' => 'Not found'], 404);
        return new UserResource($user);
    }
}
