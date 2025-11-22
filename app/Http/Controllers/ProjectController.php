<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $collection = ProjectResource::collection($this->service->all());
        $data = $collection->toArray(request());
        if (! auth()->check()) {
            $keys = ['id', 'title', 'status', 'imageUrl', 'createdAt'];
            $data = array_map(fn($item) => array_intersect_key($item, array_flip($keys)), $data);
        }
        return response()->json($data);
    }

    public function store(CreateProjectRequest $request)
    {
        $data = $request->validated();
        $image = $request->file('image') ?? null;
        $project = $this->service->create($data, $image);

        return (new ProjectResource($project))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $project = $this->service->find($id);
        if (! $project) return response()->json(['message' => 'Not found'], 404);
        return new ProjectResource($project);
    }

    public function update(Request $request, $id)
    {
        $project = $this->service->find($id);
        if (! $project) return response()->json(['message' => 'Not found'], 404);
        $image = $request->file('image') ?? null;
        $updated = $this->service->update($id, $request->all(), $image);
        return new ProjectResource($updated);
    }

    public function destroy($id)
    {
        if ($this->service->delete($id)) return response()->json(['message' => 'Deleted']);
        return response()->json(['message' => 'Not found'], 404);
    }
}
