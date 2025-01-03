<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\shield\Models\UserModel;

class ApiController extends BaseController
{
    public function getUsers()
    {
        $user = new UserModel();
        $find = $user->find(1);
        
    }
}
