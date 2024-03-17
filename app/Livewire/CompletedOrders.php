<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class CompletedOrders extends Component
{
    public $orders;
    public $ordersPrepping;
    public function render()
    {
        $this->orders = Order::where('status', 'ready')->orderBy('updated_at', 'desc')->limit(3)->get();
        $this->ordersPrepping = Order::where('status', 'preparing')->orderBy('updated_at', 'desc')->limit(3)->get();
        return view('livewire.completed-orders');
    }
}
