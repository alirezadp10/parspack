<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProductFilters extends QueryFilter
{
    /**
     * Filter product based on comment id
     *
     * @param  int  $commentId
     * @return Builder
     */
    public function commentId(int $commentId): Builder
    {
        return $this->builder->whereHas('comment', fn($comment) => $comment->whereId($commentId));
    }
}