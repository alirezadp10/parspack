<?php

namespace App\Http\Requests;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|exists:products,name',
            'comment'      => 'required|string|max:255'
        ];
    }

    /**
     * Get the comment object according to request.
     *
     * @return Comment
     */
    public function getComment(): Comment
    {
        $comment = new Comment();

        $comment->body = strip_tags(nl2br($this->comment));

        $comment->product_id = Product::whereName($this->product_name)->first()->id;

        return $comment;
    }
}
