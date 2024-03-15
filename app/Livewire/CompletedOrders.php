<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class CompletedOrders extends Component
{
    public $orders;
    public function render()
    {
        $this->orders = Order::where('status', 'ready')->orderBy('updated_at', 'desc')->get();
        return view('livewire.completed-orders');
    }
}
