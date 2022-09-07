<?php

namespace App\Http\Requests;

use App\Models\Comment;
use App\Models\Product;
use HTMLPurifier;
use HTMLPurifier_Config;
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
            'product_name' => 'required|string',
            'comment'      => 'required|string|max:255'
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        $data = $this->all();

        $purifier = new HTMLPurifier(HTMLPurifier_Config::createDefault());

        $data['comment'] = $purifier->purify($data['comment']);

        $data['product_name'] = $purifier->purify($data['product_name']);

        return $data;
    }
}
