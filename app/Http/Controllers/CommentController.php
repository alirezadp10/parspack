<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Product;
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
        $product = Product::whereName($request->product_name)->first();

        abort_if(!$request->user()->canLeaveCommentOn($product->id), Response::HTTP_FORBIDDEN);

        $comment = new Comment();

        $comment->body = $request->comment;

        $comment->product_id = $product->id;

        $comment = $request->user()->comments()->save($comment);

        return response()->json(new CommentResource($comment), Response::HTTP_CREATED);
    }
}
