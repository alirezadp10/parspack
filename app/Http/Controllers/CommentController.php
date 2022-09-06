<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Jobs\UpdateProductCommentFileJob;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
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
        $product = Product::firstOrCreate(['name' => $request->product_name]);

        $comment = Cache::lock(__METHOD__)->block(60, function () use ($request, $product) {

            abort_if(!$request->user()->canLeaveCommentOn($product->id), Response::HTTP_FORBIDDEN);

            $comment = new Comment();

            $comment->body = $request->comment;

            $comment->product_id = $product->id;

            return $request->user()->comments()->save($comment);
        });

        UpdateProductCommentFileJob::dispatch($product);

        return response()->json(new CommentResource($comment), Response::HTTP_CREATED);
    }
}
