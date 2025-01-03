<?php

namespace App\Controllers\Admin;

class Users extends \App\Controllers\BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new \App\Models\UserModel;
    }

    public function index()
    {          
        $users = $this->model->orderBy('id')->paginate(10);
        $pager = $this->model->pager;
        
        $data = [
            'users' => $users,
            'pager' => $pager
        ];

        return view('Admin/Users', $data);
    }

    // Ajax requests

    // Load users
    public function loadUsers()
    {
        // get showUserType from ajax request
        $showUserType = $this->request->getVar('showUserType');
        if ($showUserType != '') {
            $users = $this->model->where('last_name', $showUserType)->orderBy('id')->paginate(10);
        }
        else {
            $users = $this->model->orderBy('id')->paginate(10);
        }

        $pager = $this->model->pager;
        
        $data = [
            'users' => $users,
            'pager' => $pager
        ];

        // response to ajax request
        return $this->response->setJSON($data);

    }

    // Add user according to userType
    public function addUser()
    {
        // get userType from ajax request
        $userType = $this->request->getVar('userType');
        $user = [
            'last_name' => $userType,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert model
        $this->model->insert($user);

        // Return a response indicating success
        $response = array('success' => true);
        return $this->response->setJSON($response);
    }

    // Delete user
    public function deleteUser()
    {
        // get id from ajax request
        $id = $this->request->getVar('id');

        // Delete model
        $this->model->delete($id);

        // Return a response indicating success
        $response = array('success' => true);
        return $this->response->setJSON($response);
    }

    // Edit user
    public function editUser()
    {
        // get data from ajax request
        $id = $this->request->getVar('id');
        $username = $this->request->getVar('username');
        $first_name = $this->request->getVar('first_name');
        $last_name = $this->request->getVar('last_name');
        $classID_1 = $this->request->getVar('classID_1');
        $classID_2 = $this->request->getVar('classID_2');
        $classID_3 = $this->request->getVar('classID_3');
        $classID_4 = $this->request->getVar('classID_4');
        $classID_5 = $this->request->getVar('classID_5');
        $classID_6 = $this->request->getVar('classID_6');

        // Update model
        $user = [
            'username' => $username,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'classID_1' => $classID_1,
            'classID_2' => $classID_2,
            'classID_3' => $classID_3,
            'classID_4' => $classID_4,
            'classID_5' => $classID_5,
            'classID_6' => $classID_6,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->model->update($id, $user);

        // Return a response indicating success
        $response = array('success' => true);
        return $this->response->setJSON($response);
    }
}