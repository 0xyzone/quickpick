<?php

namespace App\Console\Commands;

use App\Models\Hero;
use App\Models\Item;
use App\Models\Company;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteUnusedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unused-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $categories = Category::pluck('category_photo_path')->toArray();
        $items = Item::pluck('item_photo_path')->toArray();
        $heroes = Hero::pluck('background_photo_path')->toArray();
        $companies = Company::pluck('company_logo_path')->toArray();
        $expenseBills = Expense::pluck('bill_photo_path')->toArray();

        collect(Storage::disk('public')->allFiles())
        ->reject(fn (string $file) => $file === '.gitignore')
        ->reject(fn (string $file) => $file === 'default.jpg')
        ->reject(fn (string $file) => $file === 'favicon.png')
        ->reject(fn (string $file) => in_array($file, $categories))
        ->reject(fn (string $file) => in_array($file, $items))
        ->reject(fn (string $file) => in_array($file, $heroes))
        ->reject(fn (string $file) => in_array($file, $companies))
        ->reject(fn (string $file) => in_array($file, $expenseBills))
        ->each(fn ($file) => Storage::disk('public')->delete($file));
    }
}
