<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TeacherLoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (auth()->user()->last_name == '[T]' || auth()->user()->last_name == '[A]') {
            return $request;
        }
        elseif (auth()->user()->last_name == '[S]') {
            return redirect()->to('/student/dashboard');
        }
        else {
            return redirect()->to('/accessDenied');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}