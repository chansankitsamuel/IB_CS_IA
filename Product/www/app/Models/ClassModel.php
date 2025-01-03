<?php

namespace App\Models;
use CodeIgniter\Model;

class ClassModel extends Model {

    protected $table = 'classes';
    protected $primaryKey = 'classID';
    protected $DBGroup = 'default';
    protected $allowedFields = ['classID', 'form', 'className', 'studentNumber'];

    public function classroomConfiguration_1($classIDs)
    {
        // find classes according to classID
        $classes = $this->find($classIDs);

        // find class names according to classID
        $classNames = [];
        foreach ($classes as $class) {
            $classNames[] = $class['className'];
        }

        return [$classes, $classNames];
    }
    
    public function classroomConfiguration_2($classes, $classNames, $teacherNames)
    { 
        // Output the array required to load the classrooms (combine the three arrays)
        $loadClassrooms = [];
        for ($i = 0; $i < count($classes); $i++) {
            $loadClassroom = [
                'classID' => $classes[$i]['classID'],
                'className' => $classNames[$i],
                'teacherName' => $teacherNames[$i]
            ];
            $loadClassrooms[$i] = $loadClassroom;
        }
        return $loadClassrooms;
    }

    // output the array of classNames associated by classIDs
    public function getClassNames($classIDs)
    {
        $classes = $this->find($classIDs);
        $classNames = [];
        foreach ($classes as $class) {
            $classNames[] = $class['className'];
        }
        return $classNames;
    }

    // get class details according to classIDs, with classID as key and others as values
    public function getClassDetails($classIDs)
    {
        $classes = $this->find($classIDs);
        $classDetails = [];
        foreach ($classes as $class) {
            $classDetails[$class['classID']] = [
                'className' => $class['className'],
                'teacherID' => $class['teacherID']
            ];
        }
        return $classDetails;
    }
}