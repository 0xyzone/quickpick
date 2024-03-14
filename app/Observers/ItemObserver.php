<?php

namespace App\Observers;

use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemObserver
{
    /**
     * Handle the Item "created" event.
     */
    public function created(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "updated" event.
     */
    public function updated(Item $item): void
    {
        if ($item->isDirty('item_photo_path')) {
            if (null) {

            } else {
                Storage::disk('public')->delete($item->getOriginal('item_photo_path'));
            }
        }
    }

    public function saved(Item $item): void
    {
        if ($item->isDirty('item_photo_path') && !is_null($item->item_photo_path)) {
            if (null) {

            } else {
                Storage::disk('public')->delete($item->getOriginal('item_photo_path'));
            }
        }
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        if (!is_null($item->item_photo_path)) {
            Storage::disk('public')->delete($item->item_photo_path);
        }
    }

    /**
     * Handle the Item "restored" event.
     */
    public function restored(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "force deleted" event.
     */
    public function forceDeleted(Item $item): void
    {
        //
    }
}
