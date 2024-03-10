<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class HomeController extends Controller
{
    public function index()
    {
        if(Auth::id())
        {
            if(Auth::user()->is_admin == true)
            {
                return redirect()->intended(RouteServiceProvider::ADMIN);
            } else {
                return redirect()->route('dashboard');
            }
        }
    }
}
