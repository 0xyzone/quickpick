<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        if ($category->isDirty('category_photo_path')) {
            Storage::disk('public')->delete($category->getOriginal('category_photo_path'));
        }
    }

    public function saved(Category $category): void
    {
        if ($category->isDirty('category_photo_path')) {
            Storage::disk('public')->delete($category->getOriginal('category_photo_path'));
        }
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        if(! is_null($category->category_photo_path)) {
            Storage::disk('public')->delete($category->category_photo_path);
        }
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
