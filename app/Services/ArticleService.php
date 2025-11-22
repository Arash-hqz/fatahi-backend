<?php

namespace App\Services;

use App\Contracts\Services\ArticleServiceInterface;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class ArticleService implements ArticleServiceInterface
{
    protected $repo;

    public function __construct(ArticleRepository $repo)
    {
        $this->repo = $repo;
    }

    public function all()
    {
        return $this->repo->all();
    }

    public function paginate($perPage = 15)
    {
        return $this->repo->paginate($perPage);
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data, $imageFile = null)
    {
        if ($imageFile) {
            $path = $imageFile->store('articles', 'public');
            $data['image'] = $path;
        }
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        if (! isset($data['user_id'])) {
            $userId = auth()->id();
            Log::info('ArticleService: resolving user', ['authId' => $userId, 'token' => JWTAuth::getToken()]);
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
            $path = $imageFile->store('articles', 'public');
            $data['image'] = $path;
        }
        if (isset($data['title']) && ! isset($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        return $this->repo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}
