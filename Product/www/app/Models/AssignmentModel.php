<?php

namespace App\Models;
use CodeIgniter\Model;

class AssignmentModel extends Model {

    protected $table = 'assignments';
    protected $primaryKey = 'assignmentID';
    protected $DBGroup = 'default';
    protected $allowedFields = ['assignmentID', 'classID', 'userID', 'assignmentName', 'topic', 'dueDate', 'totalMark', 'maxMark', 'studentProgress', 'teacherProgress', 'updated_at', 'created_at'];



    public function assignmentConfiguration($userID, $userType, $classID)
    {
        // array of assignmentIDs for the class
        $assignments = $this->where('classID', $classID)->findAll();

        // array of topicNames (concatenate topic and name) of assignments
        $topicNames = [];
        foreach ($assignments as $assignment) {
            $topicNames[] = ['topic' => $assignment['topic'], 'assignmentName' => $assignment['assignmentName']];
        }

        // remove duplicate in the multi-dimentional array
        $topicNames = array_map("unserialize", array_unique(array_map("serialize", $topicNames)));

        // for student
        if ($userType == 'student')
        {
            $ownedAssignments = [];

            // check if there is any assignment owned by the student for each topicName
            // always select the latest updated one if multiple records are found
            foreach ($topicNames as $topicName) {
                $owned = $this->where('classID', $classID)
                    ->where('topic', $topicName['topic'])
                    ->where('assignmentName', $topicName['assignmentName'])
                    ->where('userID', $userID)->orderBy('updated_at', 'desc')
                    ->first();
                if ( $owned != null) 
                {
                    $ownedAssignments[] = $owned;
                }
                // if not owned, assume the student owned a copy
                // (in future section, it is set that if student clicked into assignment that is not owned by himself/herself, 
                //  there will be a copy of the assingment created, which will be owned by the student)
                else {
                    $ownedAssignments[] = $this->where('classID', $classID)
                        ->where('topic', $topicName['topic'])
                        ->where('assignmentName', $topicName['assignmentName'])
                        ->where('userID !=', $userID)
                        ->orderBy('updated_at', 'desc')
                        ->first();
                }
            }
            $assignments = $ownedAssignments;

            // this array is not for students, just to define the variable for successful loading
            $studentAssignments = [];
        }
        // for teacher
        elseif ($userType == 'teacher')
        {
            $studentAssignments = [];
            $notOwneds = [];

            // create two arrays that store assignments that are owned and not owned by students
            // (not owned --> not updated by student)
            // aways select the latest updated one if multiple records are found
            foreach ($topicNames as $topicName) {
                $owned = $this->where('classID', $classID)
                    ->where('topic', $topicName['topic'])
                    ->where('assignmentName', $topicName['assignmentName'])
                    ->where('userID !=', $userID)
                    ->orderBy('updated_at', 'desc')
                    ->findAll();
                if ( $owned != null) 
                {
                    $studentAssignments[$topicName['topic']."/".$topicName['assignmentName']] = $owned;
                }
                // if assignments are not owned by students, it is owned by teacher, so the userID will be equal to $id
                $notOwneds[] = $this->where('classID', $classID)
                    ->where('topic', $topicName['topic'])
                    ->where('assignmentName', $topicName['assignmentName'])
                    ->where('userID', $userID)
                    ->orderBy('updated_at', 'desc')
                    ->first();
            }

            // teachers can only see assignments that are not owned by students on the classroom page, 
            // teacher has to enter assignment in order to see the assignments owned by students
            $assignments = $notOwneds;

            // array $studentAssignments is for teacher to mark students' works
        }
        

        // list of topic names for assignments
        $topics = [];
        foreach ($assignments as $assignment) {
            // check if assignment is null 
            // (theoretically, in normal use, it should not be null, i.e., when assignment is first created by teacher)
            if ($assignment == null)
            {
                continue;
            }
            $topics[] = $assignment['topic'];
        }

        // the array required to load the assignments
        $loadAssignments = [];
        foreach ($assignments as $assignment) {
            // check if assignment is null (theoretically, in normal use, it should not be null, 
            // i.e., when assignment is first created by teacher)
            if ($assignment == null)
            {
                continue;
            }
            $loadAssignments[] = [
                'assignmentID' => $assignment['assignmentID'],
                'assignmentName' => $assignment['assignmentName'],
                'topic' => $assignment['topic'],
                'dueDate' => $assignment['dueDate'],
                'totalMark' => $assignment['totalMark'],
                'maxMark' => $assignment['maxMark'],
                'studentProgress' => $assignment['studentProgress'],
                'teacherProgress' => $assignment['teacherProgress'],
                // 'assignmentTag' => $assignment['assignmentTag'],
            ];
        }

        return [$topics, $loadAssignments, $studentAssignments];
    }

    public function studentAssignmentConfiguration($assignmentID, $userID)
    {
        // find topics and assignment name according to assignmentID
        $assignment = $this->find($assignmentID);
        $topic = $assignment['topic'];
        $assignmentName = $assignment['assignmentName'];

        // find students' assignments with the same topic and assignment name (i.e., $assignments['userID'] != $userID of the teacher)
        $assignments = $this->where('topic', $topic)->where('assignmentName', $assignmentName)->where('userID !=', $userID)->findAll();

        // find the corresponding userIDs of the students
        $userIDs = [];
        foreach ($assignments as $assignment) {
            $userIDs[] = $assignment['userID'];
        }

        // return the final array with userIDs as the key, and the corresponding assignment as the value
        $loadStudentAssignments = [];
        foreach ($assignments as $assignment) {
            $loadStudentAssignments[$assignment['userID']] = [
                'assignmentID' => $assignment['assignmentID'],
                // 'assignmentName' => $assignment['assignmentName'],
                // 'topic' => $assignment['topic'],
                // 'dueDate' => $assignment['dueDate'],
                'totalMark' => $assignment['totalMark'],
                'maxMark' => $assignment['maxMark'],
            ];
        }

        return $loadStudentAssignments;
    }

    public function findClassID($assignmentID)
    {
        if ($assignmentID == null)
        {
            return null;
        }
        $classID = $this->find($assignmentID)['classID'];
        return $classID;
    }

    public function addAssignment($userID, $classID, $topic)
    {
        return $this->insert([
            'userID' => $userID,
            'classID' => $classID,
            'topic' => $topic,
            'assignmentName' => 'Untitled',
            'studentProgress' => '',
            'teacherProgress' => '',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function deleteAssignment($assignmentID)
    {
        return $this->delete($assignmentID);
    }

    public function checkNewAssignment($assignmentID, $userID)
    {
        $assignment = $this->find($assignmentID);
        if ($assignment['userID'] == $userID) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    public function copyAssignment($assignmentID, $userID)
    {
        $assignment = $this->find($assignmentID);
    
        unset($assignment['assignmentID']); // Remove the ID to insert it as a new assignment
        $assignment['userID'] = $userID; // Modify the userID column with the new value
        $assignment['updated_at'] = date('Y-m-d H:i:s'); // Set the updated_at field to the current time
    
        // Insert the new assignment
        $this->insert($assignment);
    
        // Return the new assignment ID
        return $this->getInsertID();
    }

    public function updateNameDate($assignmentID, $assignmentName, $dueDate)
    {
        $this->where('assignmentID', $assignmentID)
            ->set(['assignmentName' => $assignmentName])
            ->set(['dueDate' => $dueDate])
            ->set(['updated_at' => date('Y-m-d H:i:s')])
            ->update();
    }
    

    public function updateTopic($classID, $oldTopic, $newTopic)
    {
        $this->where('classID', $classID)
            ->where('topic', $oldTopic)
            ->set(['topic' => $newTopic])
            ->set(['updated_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    public function updateAssignmentMark($assignmentID, $assignmentTotalMark, $assignmentMaxMark)
    {
        $this->where('assignmentID', $assignmentID)
            ->set(['totalMark' => $assignmentTotalMark])
            ->set(['maxMark' => $assignmentMaxMark])
            ->set(['updated_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    public function updateAssignmentProgress($assignmentID, $studentProgress, $teacherProgress)
    {
        $this->where('assignmentID', $assignmentID)
            ->set(['studentProgress' => $studentProgress])
            ->set(['teacherProgress' => $teacherProgress])
            ->set(['updated_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    // find average performance of the class
    public function findClassMeanPerformanceScore($classID)
    {
        // get all assigments in the class
        $assignments = $this->where('classID', $classID)->findAll();
        $totalMark = 0;
        $maxMark = 0;
        foreach ($assignments as $assignment) {
            $totalMark += $assignment['totalMark'];
            $maxMark += $assignment['maxMark'];
        }
        // skip if max mark of the assignment is zero
        if ($maxMark == 0) {
            return 0;
        }
        else {
            // round the output value to 2 significant figures
            return round($totalMark/$maxMark*100, 2);
        }
    }

    // find average performance of all classes
    public function findAllClassesMeanPerformaceScore($classIDs)
    {
        $totalMark = 0;
        $maxMark = 0;
        foreach ($classIDs as $classID) {
            // get all assigments in all class
            $assignments = $this->where('classID', $classID)->findAll();
            foreach ($assignments as $assignment) {
                $totalMark += $assignment['totalMark'];
                $maxMark += $assignment['maxMark'];
            }
        }
        // skip if max mark of the assignment is zero
        if ($maxMark == 0) {
            return 0;
        }
        else {
            // round the output value to 2 significant figures
            return round($totalMark/$maxMark*100, 2);
        }
    }
}