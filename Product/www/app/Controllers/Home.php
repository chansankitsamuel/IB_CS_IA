<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (auth()->user()->last_name == '[S]') {
            return redirect()->to('/student/dashboard');
        }
        elseif (auth()->user()->last_name == '[T]') {
            return redirect()->to('/teacher/dashboard');
        }
        elseif (auth()->user()->last_name == '[A]') {
            return redirect()->to('/admin/dashboard');}
        return view('Home/index');
    }
}
