<?php

namespace App\Services;

use App\Contracts\Services\ProjectServiceInterface;
use App\Repositories\ProjectRepository;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class ProjectService implements ProjectServiceInterface
{
    protected $repo;

    public function __construct(ProjectRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data, $imageFile = null)
    {
        if ($imageFile) {
            $path = $imageFile->store('projects', 'public');
            $data['image'] = $path;
        }
        if (! isset($data['user_id'])) {
            $userId = auth()->id();
            Log::info('ProjectService: resolving user', ['authId' => $userId, 'token' => JWTAuth::getToken()]);
            if (! $userId && JWTAuth::getToken()) {
                try {
                    $user = JWTAuth::parseToken()->authenticate();
                    $userId = $user?->id;
                } catch (\Exception $e) {
                    $userId = null;
                }
            }
            $data['user_id'] = $userId;
        }
        return $this->repo->create($data);
    }

    public function update($id, array $data, $imageFile = null)
    {
        if ($imageFile) {
            $path = $imageFile->store('projects', 'public');
            $data['image'] = $path;
        }
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
