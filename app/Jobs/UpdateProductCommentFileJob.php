<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Str;

class UpdateProductCommentFileJob
{
    use Dispatchable;

    /**
     * An instance of product model.
     *
     * @var Product
     */
    private Product $product;

    /**
     * Create a new job instance.
     *
     * @param  Product  $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = config('filesystems.files.product_comment.path');

        exec("grep " . escapeshellarg($this->product->name . ':') . " $file", $rows);

        if (empty($rows)) {
            exec("echo " . escapeshellarg($this->product->name . ': 1 ') . " >> $file");
            return;
        }

        $commentCount = (int) Str::afterLast($rows[0], ' ') + 1;

        exec(sprintf("sed -i 's/%s/%s: %s/' %s", $rows[0], escapeshellarg($this->product->name), $commentCount, $file));
    }
}
