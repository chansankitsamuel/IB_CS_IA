<?php

namespace App\Models;
use CodeIgniter\Model;

class QuestionModel extends Model {

    protected $table = 'questions';
    protected $primaryKey = 'questionID';
    protected $DBGroup = 'default';
    protected $allowedFields = ['questionID', 'assignmentID', 'totalMark', 'maxMark', 'question', 'answer', 'markingScheme', 'comment', 'updated_at', 'created_at'];

    

    public function questionConfiguration($assignmentID) 
    {
        // array of questions belong to the class
        $questions = $this->where('assignmentID', $assignmentID)->orderBy('created_at', 'asc')->findAll();

        // the array required to load the questions
        $loadQuestions = [];
        foreach ($questions as $question) {
            $loadQuestions[] = [
                'questionID' => $question['questionID'],
                'assignmentID' => $question['assignmentID'],
                'totalMark' => $question['totalMark'],
                'maxMark' => $question['maxMark'],
                'question' => $question['question'],
                'answer' => $question['answer'],
                'markingScheme' => $question['markingScheme'],
                'comment' => $question['comment'],
            ];
        }
        
        return $loadQuestions;
    }



    public function addQuestion($assignmentID)
    {
        return $this->insert([
            'assignmentID' => $assignmentID,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }



    public function deleteQuestion($questionID)
    {
        return $this->delete($questionID);
    }



    public function copyQuestion($sourceQuestionID, $sourceAssignmentID, $targetAssignmentID)
    {
        // Create a copy of the Source Question
        $sourceQuestion = $this->find($sourceQuestionID);
        unset($sourceQuestion['questionID']);
        $sourceQuestion['assignmentID'] = $targetAssignmentID;
        $sourceQuestion['updated_at'] = date('Y-m-d H:i:s');
    
        // Insert the new question
        $this->insert($sourceQuestion);
        $newQuestionID = $this->getInsertID();
    
        // Return the New Question ID
        return $newQuestionID;
    }

    public function copyAllOtherQuestions($sourceAssignmentID, $targetAssignmentID, $copiedQuestionID)
    {
        // Create a copy of the Source Assignment
        $sourceQuestions = $this->where('assignmentID', $sourceAssignmentID)->findAll();
        // loop through all questions in the source assignment
        foreach ($sourceQuestions as $sourceQuestion) {
            // if the question is not the copied question, copy it
            if ($sourceQuestion['questionID'] != $copiedQuestionID) {
                unset($sourceQuestion['questionID']);
                $sourceQuestion['assignmentID'] = $targetAssignmentID;
                $sourceQuestion['updated_at'] = date('Y-m-d H:i:s');
                $this->insert($sourceQuestion);
            }
        }
    }
    
    public function updateQuestion($questionID, $question, $answer, $markingScheme, $comment, $totalMark, $maxMark)
    {
        // to avoid '' being updated as 0, so teachProgress can be calculated correctly 
        if ($totalMark == '') {
            $totalMark = NULL;
        }
        if ($maxMark == '') {
            $maxMark = NULL;
        }
        return $this->update($questionID, [
            'question' => $question,
            'answer' => $answer,
            'markingScheme' => $markingScheme,
            'comment' => $comment,
            'totalMark' => $totalMark,
            'maxMark' => $maxMark,
            'updated_at' => date('Y-m-d H:i:s'), 
        ]);
    }

    public function findAssignmentTotalMark($assignmentID)
    {
        $questions = $this->where('assignmentID', $assignmentID)->findAll();
        $totalMark = 0;
        foreach ($questions as $question) {
            $totalMark += $question['totalMark'];
        }
        return $totalMark;
    }

    public function findAssignmentMaxMark($assignmentID)
    {
        $questions = $this->where('assignmentID', $assignmentID)->findAll();
        $maxMark = 0;
        foreach ($questions as $question) {
            $maxMark += $question['maxMark'];
        }
        return $maxMark;
    }

    // find the studentProgress of assignment by assignmentID (calculating the percentage of questions answered by students)
    public function findAssignmentStudentProgress($assignmentID)
    {
        $questions = $this->where('assignmentID', $assignmentID)->findAll();
        $totalQuestionNumber = count($questions);
        $completedQuestionNumber = 0;
        foreach ($questions as $question) {
            // if the anwer is not empty, the question is completed
            if ($question['answer'] != '') {
                $completedQuestionNumber++;
            }
        }
        if ($totalQuestionNumber == 0) {
            return 0;
        } else {
            return round($completedQuestionNumber / $totalQuestionNumber * 100);
        }
    }

    public function findAssignmentTeacherProgress($assignmentID)
    {
        $questions = $this->where('assignmentID', $assignmentID)->findAll();
        $totalQuestionNumber = count($questions);
        $completedQuestionNumber = 0;
        foreach ($questions as $question) {
            if ($question['totalMark'] != '') {
                $completedQuestionNumber++;
            }
        }
        if ($totalQuestionNumber == 0) {
            return 0;
        } else {
            return round($completedQuestionNumber / $totalQuestionNumber * 100);
        }
    }
}