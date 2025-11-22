<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="Fatahi API",
 *   version="1.0.0",
 *   description="Fatahi service API documentation"
 * )
 * @OA\Server(
 *   url="/api",
 *   description="API Base"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    // Base controller for shared Swagger annotations
}
