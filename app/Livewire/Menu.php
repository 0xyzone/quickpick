<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Menu extends Component
{
    use WithPagination;
    public $activeCategory = 1;

    public function mount() {
    }

    public function changeCategory($id)
    {
        $this->activeCategory = $id;
    }
    public function render()
    {
        // $this->categories = Category::all();
        // $this->items = Item::paginate(4);
        $categories = Category::all();
        $items = Item::where('category_id', $this->activeCategory)->paginate(4);
        return view('livewire.menu', compact('categories', 'items'));
    }
}
