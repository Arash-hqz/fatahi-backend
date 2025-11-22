<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\UserResource;
use App\Contracts\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return UserResource::collection($this->repo->all());
    }

    public function show($id)
    {
        $user = $this->repo->find($id);
        if (! $user) return response()->json(['message' => 'Not found'], 404);
        return new UserResource($user);
    }

    public function destroy($id)
    {
        if ($this->repo->delete($id)) return response()->json(['message' => 'Deleted']);
        return response()->json(['message' => 'Not found'], 404);
    }

    public function updateRole(UpdateRoleRequest $request, $id)
    {
        $user = $this->repo->updateRole($id, $request->validated()['role']);
        if (! $user) return response()->json(['message' => 'Not found'], 404);
        return new UserResource($user);
    }
}
