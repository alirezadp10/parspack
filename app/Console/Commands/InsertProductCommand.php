<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class InsertProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:product {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to insert new products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->argument('name')) {
            $this->error('The name of product is required');
            return;
        }

        $product = Product::firstOrCreate(['name' => $this->argument('name')])->toArray();

        $this->table(array_keys($product), [$product]);
    }
}
