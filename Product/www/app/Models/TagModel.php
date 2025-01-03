<?php

namespace App\Models;
use CodeIgniter\Model;

class TagModel extends Model {

    protected $table = 'tags';
    protected $primaryKey = 'tagID';
    protected $DBGroup = 'default';
    protected $allowedFields = ['tagID', 'questionID', 'assignmentID', 'userID', 'tagName', 'created_at'];

    

    public function tagConfiguration($loadQuestions) 
    {
        // array of tags belong to the assignment index by questionID
        $tagss = [];
        foreach ($loadQuestions as $loadQuestion) {
            $tagss[$loadQuestion['questionID']] = $this->where('questionID', $loadQuestion['questionID'])
                                                        ->orderBy('created_at', 'asc')->findAll();
        }
 
        // the array required to load the tags
        $loadTagss = [];
        foreach ($tagss as $tags) {
            // $tags is an array of tags belong to the question with questionID
            foreach ($tags as $tag) {
                // if there is tag with corresponding questionID
                if (!empty($tag['questionID'])) {
                    // $loadTagss is actually similar to $tagss, but each {tag} is [tagID, tagName]
                    $loadTagss[$tag['questionID']][] = [
                        'tagID' => $tag['tagID'],
                        'tagName' => $tag['tagName'],
                    ];
                }
            }
        }

        return $loadTagss;
    }

    // configure the tags in the sidebar with the assignmentIDs
    public function sidebarConfiguration($assignmentIDs, $userType, $userID)
    {
        // if teacher
        if ($userType == 'teacher') {
            // array of tags belong to the assignment index by assignmentID
            $tagss = [];
            foreach ($assignmentIDs as $assignmentID) {
                $tagss[$assignmentID] = $this->where('assignmentID', $assignmentID)
                                                // tags created by students will not be shown
                                                ->where('userID', $userID) 
                                                ->orderBy('created_at', 'asc')->findAll();
            }
        }
        else {
            // array of tags belong to the assignment index by assignmentID
            $tagss = [];
            foreach ($assignmentIDs as $assignmentID) {
                $tagss[$assignmentID] = $this->where('assignmentID', $assignmentID)
                                                ->orderBy('created_at', 'asc')->findAll();
            }
        }

        // array of tag names in tagss
        $tagNames = [];
        foreach ($tagss as $tags) {
            // $tags is an array of tags belong to the assignment with assignmentID
            foreach ($tags as $tag) {
                // if there is tag with corresponding assignmentID
                if (!empty($tag['assignmentID'])) {
                    $tagNames[] = $tag['tagName'];
                }
            }
        }

        // array of tag names in tagss without duplicates
        $tagNames = array_unique($tagNames);

        // return the array of tag names
        return $tagNames;
    }

    // find tagIDs by tagNames
    public function findTagIDs($tagNames)
    {
        $tagIDs = [];
        foreach ($tagNames as $tagName) {
            $results = $this->where('tagName', $tagName)->findAll();
            foreach ($results as $result) {
                $tagIDs[] = $result['tagID'];
            }
        }
        return $tagIDs;
    }

    // find questionIDs by tagIDs
    public function findQuestionIDs($tagIDs)
    {
        foreach ($tagIDs as $tagID) {
            $questionIDs[] = $this->where('tagID', $tagID)->first()['questionID'];
        }
        return $questionIDs;
    }

    // find assignmentIDs by tagIDs
    public function findAssignmentIDs($tagIDs)
    {
        foreach ($tagIDs as $tagID) {
            $assignmentIDs[] = $this->where('tagID', $tagID)->first()['assignmentID'];
        }
        return $assignmentIDs;
    }

    public function addTag($questionID, $assignmentID, $tagName, $userID)
    {
        return $this->insert([
            'questionID' => $questionID,
            'assignmentID' => $assignmentID,
            'userID' => $userID,
            'tagName' => $tagName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    // check can the user delete the tag
    public function checkDeleteTag($tagID, $userID)
    {
        $tag = $this->find($tagID);
        if ($tag['userID'] == $userID) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteTag($tagID)
    {
        return $this->delete($tagID);
    }
}