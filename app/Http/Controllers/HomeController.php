<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('layouts.admin-lte');
        // return redirect()->route('panel.dashboard');
        // $role = Auth::user()->access_id;

        // if ($role == '1') {
        //     return redirect()->route('panel.dashboard');
        // } elseif ($role == '2') {
        //     return "halow";
        // } elseif ($role == '3') {
        //     return redirect()->route('home');
        // };
    }
}
