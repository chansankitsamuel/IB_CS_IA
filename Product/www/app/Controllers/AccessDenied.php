<?php

namespace App\Controllers;

class AccessDenied extends BaseController
{
    public function index()
    {
        return view('Login/AccessDenied');
    }
}