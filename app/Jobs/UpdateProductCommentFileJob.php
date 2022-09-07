<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;
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
     * Product comments file path.
     *
     * @var string
     */
    private string $file;

    /**
     * Create a new job instance.
     *
     * @param  Product  $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;

        $this->file = config('filesystems.files.product_comment.path');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::lock(__METHOD__)->block(60, function () {
            $row = $this->findProductCommentInFile();

            is_null($row) ? $this->insertNewProductCommentToFile() : $this->updateProductComments($row);
        });
    }

    /**
     * Find product comment in file.
     *
     * @return mixed
     */
    protected function findProductCommentInFile(): mixed
    {
        exec("grep ".escapeshellarg($this->product->name.':')." $this->file", $rows);

        return $rows[0] ?? null;
    }

    /**
     * Insert a new product comment to file.

     * @return void
     */
    protected function insertNewProductCommentToFile(): void
    {
        exec("echo ".escapeshellarg($this->product->name.': 1 ')." >> $this->file");
    }

    /**
     * Update product comments.
     *
     * @param string $row
     */
    protected function updateProductComments(string $row): void
    {
        $commentCount = $this->getCommentCount($row);

        exec(sprintf(
            "sed -i 's/%s/%s: %s/' %s",
            $row, escapeshellarg($this->product->name), $commentCount, $this->file
        ));
    }

    /**
     * Get comment count.
     *
     * @param $subject
     * @return int
     */
    protected function getCommentCount($subject): int
    {
        return (int) Str::afterLast($subject, ' ') + 1;
    }
}
