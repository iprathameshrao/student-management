<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function teacher(){
            return view('Dashboard');
            // return ['user'=>auth()->user()];
    }
    public function admin(){
            return view('Admin.dashboard');
            // return ['user'=>auth()->user()];
    }public function student(){
            return view('Student.dashboard');
            // return ['user'=>auth()->user()];
    }
}
