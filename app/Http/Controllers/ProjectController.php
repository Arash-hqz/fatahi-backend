<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ProjectController extends Controller
{
    protected $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    /**
     * List projects.
     * @OA\Get(
     *   path="/guest/projects",
     *   tags={"Projects"},
     *   summary="List projects",
     *   description="Retrieve registered projects. If unauthenticated only core fields are returned.",
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Project")))
     * )
     */
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

    /**
     * Create a new project.
     * @OA\Post(
     *   path="/admin/projects",
     *   tags={"Projects"},
     *   summary="Create project",
     *   description="Create a project with title, optional description and status.",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(
     *       required={"title"},
     *       @OA\Property(property="title", type="string", example="Sales Platform"),
     *       @OA\Property(property="description", type="string", example="Development of sales platform"),
     *       @OA\Property(property="status", type="string", example="ongoing"),
     *     )
     *   ),
     *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/Project")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(CreateProjectRequest $request)
    {
        $data = $request->validated();
        $image = $request->file('image') ?? null;
        $project = $this->service->create($data, $image);

        return (new ProjectResource($project))->response()->setStatusCode(201);
    }

    /**
     * Show a project.
     * @OA\Get(
     *   path="/guest/projects/{id}",
     *   tags={"Projects"},
     *   summary="Show project",
     *   description="Retrieve project details by ID.",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Project", @OA\JsonContent(ref="#/components/schemas/Project")),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $project = $this->service->find($id);
        if (! $project) return response()->json(['message' => 'Not found'], 404);
        return new ProjectResource($project);
    }

    /**
     * Update a project.
     * @OA\Put(
     *   path="/admin/projects/{id}",
     *   tags={"Projects"},
     *   summary="Update project",
     *   description="Update existing project fields.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(@OA\JsonContent(
     *       @OA\Property(property="title", type="string"),
     *       @OA\Property(property="description", type="string"),
     *       @OA\Property(property="status", type="string"),
     *     )
     *   ),
     *   @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/Project")),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $project = $this->service->find($id);
        if (! $project) return response()->json(['message' => 'Not found'], 404);
        $image = $request->file('image') ?? null;
        $updated = $this->service->update($id, $request->all(), $image);
        return new ProjectResource($updated);
    }

    /**
     * Delete a project.
     * @OA\Delete(
     *   path="/admin/projects/{id}",
     *   tags={"Projects"},
     *   summary="Delete project",
     *   description="Permanently delete a project.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Deleted"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        if ($this->service->delete($id)) return response()->json(['message' => 'Deleted']);
        return response()->json(['message' => 'Not found'], 404);
    }
}
