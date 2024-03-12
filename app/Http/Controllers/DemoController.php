<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DemoController extends Controller
{
    public function index()
    {
        $heroes = Hero::all();
        return view('demo', compact('heroes'));
    }
}
