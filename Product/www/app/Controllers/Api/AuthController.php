<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use CodeIgniter\shield\Models\UserModel;

class AuthController extends BaseController
{
    use ResponseTrait;
    public function userLogin()
    {
        $crendentials =[
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];
        
    

        if(auth()->loggedIn())
        {
            auth()->logout();
        }
    

        $loginAttempt = auth()->attempt($crendentials);
        if(!$loginAttempt->isOk())
        {
            return $this->fail('Invalid Login', 400);
        }
        else
        {
            $user = new UserModel();
            $userData = $user->find(auth()->id());

            $token = $userData->generateAccessToken('thisismytoken');
            $auth_token = $token->raw_token;
            return $this->respond(['token' => $auth_token]);
        }
    }  
    
    public function loggedOut()
    {
        return $this->fail(['User not logged. Please log in', 400]);
    }

}

