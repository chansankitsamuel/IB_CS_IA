<?php

namespace App\Controllers\Admin;

class Classes extends \App\Controllers\BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new \App\Models\ClassModel;
    }

    public function index()
    {          
        $classes = $this->model->orderBy('classID')->paginate(10);
        $pager = $this->model->pager;

        $data = [
            'classes' => $classes,
            'pager' => $pager
        ];

        return view('Admin/Classes', $data);
    }

    // Ajax requests


    // Load classes
    public function loadClasses()
    {
        // get showClassForm from ajax request
        $showClassForm = $this->request->getVar('showClassForm');
        if ($showClassForm != '') {
            $classes = $this->model->where('form', $showClassForm)->orderBy('classID')->paginate(10);
        }
        else {
            $classes = $this->model->orderBy('classID')->paginate(10);
        }

        $pager = $this->model->pager;
        
        $data = [
            'classes' => $classes,
            'pager' => $pager
        ];

        // response to ajax request
        return $this->response->setJSON($data);

    }



    // Add class according to classType
    public function addClass()
    {
        // get classType from ajax request
        $classForm = $this->request->getVar('classForm');
        $class = [
            'form' => $classForm,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert model
        $this->model->insert($class);

        // Return a response indicating success
        $response = array('success' => true);
        return $this->response->setJSON($response);
    }




    // Delete class
    public function deleteClass()
    {
        // get classID from ajax request
        $classID = $this->request->getVar('classID');

        // Delete model
        $this->model->delete($classID);

        // Return a response indicating success
        $response = array('success' => true);
        return $this->response->setJSON($response);
    }



    // Edit class
    public function editClass()
    {
        // get data from ajax request
        $classID = $this->request->getVar('classID');
        $form = $this->request->getVar('form');
        $className = $this->request->getVar('className');
        $studentNumber = $this->request->getVar('studentNumber');

        // Update model
        $class = [
            'form' => intval($form),
            'className' => $className,
            'studentNumber' => intval($studentNumber),
        ];
        $this->model->update($classID, $class);

        // Return a response indicating success
        $response = array('success' => true);
        return $this->response->setJSON($response);
    }
}