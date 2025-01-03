<?php

namespace App\Controllers\Admin;

class Classroom extends \App\Controllers\BaseController
{
    private $model;

    public function __construct()
    {
        $this->UserModel = new \App\Models\UserModel();
        $this->ClassModel = new \App\Models\ClassModel();
    }

    public function index()
    {
		$users = $this->UserModel->orderBy('id')->paginate(10);
        $pager = $this->UserModel->pager;
        
        $data = [
            'users' => $users,
            'pager' => $pager
        ];

        return view('Admin/Classroom', $data);
    }

    // Load users
    public function loadUsers()
    {
        // get showUserType from ajax request
        $classID = $this->request->getVar('classID');
        if ($classID != '') {
            // $debug = 'entered if, classID: '.$classID; //debug
            $students = $this->UserModel
                ->where('last_name', '[S]')
                ->groupStart()
                    ->where('classID_1', $classID)
                    ->orWhere('classID_2', $classID)
                    ->orWhere('classID_3', $classID)
                    ->orWhere('classID_4', $classID)
                    ->orWhere('classID_5', $classID)
                    ->orWhere('classID_6', $classID)
                ->groupEnd()
                    ->orderBy('updated_at')
                ->paginate(10);
            $teachers = $this->UserModel
                ->where('last_name', '[T]')
                ->groupStart()
                    ->where('classID_1', $classID)
                    ->orWhere('classID_2', $classID)
                    ->orWhere('classID_3', $classID)
                    ->orWhere('classID_4', $classID)
                    ->orWhere('classID_5', $classID)
                    ->orWhere('classID_6', $classID)
                ->groupEnd()
                    ->orderBy('updated_at')
                ->paginate(10);      
            }
        else {
            $students = $this->UserModel->where('last_name', '[S]')->orderBy('updated_at')->paginate(10);
            $teachers = $this->UserModel->where('last_name', '[T]')->orderBy('updated_at')->paginate(10);
        }

        $pager = $this->UserModel->pager;
        
        $data = [
            'students' => $students,
            'teachers' => $teachers,
            'pager' => $pager, 
            // 'debug' => $debug, //debug
        ];

        // response to ajax request
        return $this->response->setJSON($data);
    }

    // handle ajax request to get classID by className
    public function getClassID()
    {
        $className = $this->request->getVar('className');
        $classID = $this->ClassModel->where('className', $className)->orderBy('created_at', 'DESC')->first(true)['classID'];
        return $this->response->setJSON(['classID' => $classID]);
    }

    // handle ajax request to get className by classID
    public function getClassName()
    {
        $classID = $this->request->getVar('classID');
        $className = $this->ClassModel->where('classID', $classID)->orderBy('created_at', 'DESC')->first(true)['className'];
        return $this->response->setJSON(['className' => $className]);
    }


    // Add user according to userType
    public function addUser()
    {
        // get userType from ajax request
        $classID = $this->request->getVar('classID');
        $user = [
            'classID_1' => $classID,
            'last_name' => '[S]',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert model
        $this->UserModel->insert($user);

        // Return a response indicating success
        $response = array('success' => true);
        return $this->response->setJSON($response);
    }

    // Delete user from current classroom --> set classID_1/classID_2/classID_3/classID_4/classID_5/classID_6 to null (depends on which classID is equal to currentClassroomID)
    public function deleteUser()
    {
        // Get id and classID from ajax request
        $id = $this->request->getVar('id');
        $classID = $this->request->getVar('classID');

        // Delete the user from the current classroom
        $user = $this->UserModel->where('id', $id)->first(true);

        // Check each classID and set it to null if it matches the current classID
        if ($user['classID_1'] == $classID) {
            $user['classID_1'] = null;
        }
        elseif ($user['classID_2'] == $classID) {
            $user['classID_2'] = null;
        }
        elseif ($user['classID_3'] == $classID) {
            $user['classID_3'] = null;
        }
        elseif ($user['classID_4'] == $classID) {
            $user['classID_4'] = null;
        }
        elseif ($user['classID_5'] == $classID) {
            $user['classID_5'] = null;
        }
        elseif ($user['classID_6'] == $classID) {
            $user['classID_6'] = null;
        }

        $this->UserModel->update($id, $user);

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

//     public function classUsers()
//     {
//         $teachers = null;
//         $students = null;

//         $classID = intval($this->request->getPost('classID'));
//         $form = intval($this->request->getPost('form'));
//         $className = $this->request->getPost('className');
        
//         if (!empty($classID)) {
//             $this->ClassModel->where('classID', $classID);
//         }
//         if (!empty($form)) {
//             $this->ClassModel->where('form', $form);
//         }
//         if (!empty($className)) {
//             $this->ClassModel->like('className', $className);
//         }
//         if (!empty($classID) || !empty($form) || !empty($className)) {
//             // it returns an array after passing through `true`
//             $class = $this->ClassModel->first(true);
//             if ($class) {
//                 $classID = $class['classID'];

//                 $teachers = $this->UserModel->where('last_name', '[T]')
//                     ->where('classID_1', $classID)
//                     ->orWhere('classID_2', $classID)
//                     ->orWhere('classID_3', $classID)
//                     ->orWhere('classID_4', $classID)
//                     ->orWhere('classID_5', $classID)
//                     ->orWhere('classID_6', $classID)
//                     ->orderBy('id')
//                     ->findAll(); // Fetch the results

//                 $students = $this->UserModel->where('last_name', '[S]')
//                     ->where('classID_1', $classID)
//                     ->orWhere('classID_2', $classID)
//                     ->orWhere('classID_3', $classID)
//                     ->orWhere('classID_4', $classID)
//                     ->orWhere('classID_5', $classID)
//                     ->orWhere('classID_6', $classID)
//                     ->orderBy('id')
//                     ->findAll(); // Fetch the results
//             }
//         }


//         $data = [
//             'teachers' => $teachers,
//             'students' => $students,
//         ];

//         return view('Admin/Classroom/class_users', $data);
//     }


// }