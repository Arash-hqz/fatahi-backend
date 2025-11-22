<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ArticleController extends Controller
{
    protected $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    /**
     * List articles with optional pagination.
     * @OA\Get(
     *   path="/guest/articles",
     *   tags={"Articles"},
     *   summary="List articles",
     *   description="Retrieve article list. Supports pagination via `page` and `per_page` query parameters. If unauthenticated only core fields are returned.",
     *   @OA\Parameter(name="page", in="query", @OA\Schema(type="integer"), description="Page number"),
     *   @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer"), description="Items per page"),
    *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PaginatedArticles"))
     * )
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        if ($perPage <= 0) $perPage = 15;

        $paginator = $this->service->paginate($perPage);

        // Use ArticleCollection to format the paginated result
        $collection = new \App\Http\Resources\ArticleCollection($paginator);

        return $collection;
    }

    /**
     * Create an article.
     * @OA\Post(
     *   path="/admin/articles",
     *   tags={"Articles"},
     *   summary="Create article",
     *   description="Create an article with title, content and optional image.",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(
     *       required={"title","content"},
     *       @OA\Property(property="title", type="string", example="Intro to Laravel"),
     *       @OA\Property(property="content", type="string", example="Full article body"),
     *     )
     *   ),
     *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/Article")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(CreateArticleRequest $request)
    {
        $data = $request->validated();
        $image = $request->file('image') ?? null;
        $article = $this->service->create($data, $image);

        return (new ArticleResource($article))->response()->setStatusCode(201);
    }

    /**
     * Show an article.
     * @OA\Get(
     *   path="/guest/articles/{id}",
     *   tags={"Articles"},
     *   summary="Show article",
     *   description="Retrieve an article by ID.",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Article", @OA\JsonContent(ref="#/components/schemas/Article")),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $article = $this->service->find($id);
        if (! $article) return response()->json(['message' => 'Not found'], 404);
        return new ArticleResource($article);
    }

    /**
     * Update an article.
     * @OA\Put(
     *   path="/admin/articles/{id}",
     *   tags={"Articles"},
     *   summary="Update article",
     *   description="Update article fields (title, content or image).",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(@OA\JsonContent(
     *       @OA\Property(property="title", type="string"),
     *       @OA\Property(property="content", type="string"),
     *     )
     *   ),
     *   @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/Article")),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $article = $this->service->find($id);
        if (! $article) return response()->json(['message' => 'Not found'], 404);
        $image = $request->file('image') ?? null;
        $updated = $this->service->update($id, $request->all(), $image);
        return new ArticleResource($updated);
    }

    /**
     * Delete an article.
     * @OA\Delete(
     *   path="/admin/articles/{id}",
     *   tags={"Articles"},
     *   summary="Delete article",
     *   description="Permanently delete an existing article.",
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
