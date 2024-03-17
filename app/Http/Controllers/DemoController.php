<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DemoController extends Controller
{
    public function index()
    {
        $heroes = Hero::all();
        $items = Item::paginate(8);
        return view('demo', compact('heroes', 'items'));
    }
}
