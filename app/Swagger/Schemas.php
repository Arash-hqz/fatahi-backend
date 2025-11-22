<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *   schema="PaginatedArticles",
 *   type="object",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Article")),
 *   @OA\Property(property="current_page", type="integer"),
 *   @OA\Property(property="last_page", type="integer"),
 *   @OA\Property(property="per_page", type="integer"),
 *   @OA\Property(property="total", type="integer"),
 *   @OA\Property(property="first_page_url", type="string"),
 *   @OA\Property(property="last_page_url", type="string"),
 *   @OA\Property(property="next_page_url", type="string", nullable=true),
 *   @OA\Property(property="prev_page_url", type="string", nullable=true),
 * )
 *
 *   schema="User",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="email", type="string"),
 *   @OA\Property(property="roles", type="array", @OA\Items(type="string")),
 *   @OA\Property(property="createdAt", type="string", format="date-time"),
 * )
 *
 * @OA\Schema(
 *   schema="AuthResponse",
 *   type="object",
 *   @OA\Property(property="access_token", type="string"),
 *   @OA\Property(property="token_type", type="string"),
 * )
 *
 * @OA\Schema(
 *   schema="Product",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="title", type="string"),
 *   @OA\Property(property="price", type="number", format="float"),
 *   @OA\Property(property="imageUrl", type="string"),
 *   @OA\Property(property="createdAt", type="string", format="date-time"),
 * )
 *
 * @OA\Schema(
 *   schema="Project",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="title", type="string"),
 *   @OA\Property(property="status", type="string"),
 *   @OA\Property(property="imageUrl", type="string"),
 *   @OA\Property(property="createdAt", type="string", format="date-time"),
 * )
 *
 * @OA\Schema(
 *   schema="Article",
 *   type="object",
 *   @OA\Property(property="id", type="integer"),
 *   @OA\Property(property="title", type="string"),
 *   @OA\Property(property="slug", type="string"),
 *   @OA\Property(property="coverUrl", type="string"),
 *   @OA\Property(property="createdAt", type="string", format="date-time"),
 * )
 */
class Schemas
{
    // Holder class for OpenAPI schema annotations
}
