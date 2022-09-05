<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in database.
     *
     * @param  StoreCommentRequest  $request
     * @return JsonResponse
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $comment = $request->user()->comments()->save($request->getComment());

        return response()->json(new CommentResource($comment), Response::HTTP_CREATED);
    }
}
