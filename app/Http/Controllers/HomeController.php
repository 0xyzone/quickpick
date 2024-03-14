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
            if(Auth::user()->hasRole('Super Admin'))
            {
                return redirect()->intended(RouteServiceProvider::ADMIN);
            } else {
                return redirect()->route('dashboard');
            }
        }
    }

    public function dashboard()
    {
        if(Auth::user()->hasRole('Super Admin'))
        {
            return redirect()->intended(RouteServiceProvider::ADMIN);
        }

        if(Auth::user()->hasRole('Staff'))
        {
            return redirect()->intended(RouteServiceProvider::STAFF);
        }

        return view('dashboard');
    }
}
