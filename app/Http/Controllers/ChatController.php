<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ChatController extends Controller
{
    /**
     * Retrieve recent chat messages (currently empty array).
     * @OA\Get(
     *   path="/admin/chat/recent",
     *   tags={"Chat"},
     *   summary="Recent messages",
     *   description="Return an array of recent chat messages. Currently returns an empty list.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(type="array", @OA\Items(type="object")))
     * )
     */
    public function recent()
    {
        return response()->json([]);
    }
}
