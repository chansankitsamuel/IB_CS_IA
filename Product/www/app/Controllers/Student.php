<?php

namespace App\Controllers;

class Student extends BaseController
{
    public function index()
    {
        $id = auth()->user()->id;

        $model = new \App\Models\UserModel();
        [$data['userType'], $classIDs] = $model->userConfiguration($id);

        $model = new \App\Models\ClassModel();
        [$classes, $classNames] = $model->classroomConfiguration_1($classIDs);

        // find teacherIDs according to classIDs
        $model = new \App\Models\UserModel();
        $teacherIDs = [];
        foreach ($classes as $class) {
            $teacherIDs[] = $class['teacherID'];
        }

        // find teacherNames with teacherIDs
        $teacherNames = $model->findUsername($teacherIDs);

        // output the array required to load the classrooms in the sidebar
        $model = new \App\Models\ClassModel();
        $data['loadClassrooms'] = $model->classroomConfiguration_2($classes, $classNames, $teacherNames);


        // output the array required to load all the assignments of user
        $model = new \App\Models\AssignmentModel();
        $results = [];
        foreach ($classIDs as $classID) {
            $result = $model->assignmentConfiguration($id, $data['userType'], $classID);
            $results[$classID] = [
                'topics' => $result[0],
                'loadAssignments' => $result[1],
            ];
        }
        // Assign the final results back to the variables
        $topics = [];
        $loadAssignments = [];
        foreach ($results as $classID => $result) {
            $topics[$classID] = $result['topics'];
            $loadAssignments[$classID] = $result['loadAssignments'];
        }

        // load data for the to-do list and calculate overall studentProgress
        // also load data for Task Review in sidbar
        $data['toDoList'] = [];
        foreach ($loadAssignments as $classID => $assignments) {
            foreach ($assignments as $assignment) {
                if ($assignment['studentProgress'] != 100) {
                    $data['toDoList'][$classID][] = [
                        'assignmentID' => $assignment['assignmentID'],
                        'assignmentName' => $assignment['assignmentName'], 
                        'topic' => $assignment['topic'],
                        'dueDate' => $assignment['dueDate'],
                        'studentProgress' => $assignment['studentProgress'],
                    ];
                }
            }
        }



        // load data for the line chart
        $dataset_line = [];
        $performanceScores = [];
        foreach ($loadAssignments as $assignments) {
            foreach ($assignments as $assignment) {
                // if max mark not zero
                if ($assignment['maxMark'] != 0) {
                    $performanceScores[] = [
                        'performance' => $assignment['totalMark'] / $assignment['maxMark'] * 100, 
                        'date' => $assignment['dueDate'],
                    ];
                } 
                
            }
        }
        $monthlyPerformance = [];
        $monthlyAveragePerformance = [];
        // Iterate over the assignments
        foreach ($performanceScores as $assignment) {
            $performance = $assignment['performance'];
            $date = date('Y-m', strtotime($assignment['date'])); // Extract the year and month
            // If the month is already present in the array, add the performance score
            if (isset($monthlyPerformance[$date])) {
                $monthlyPerformance[$date][] = $performance;
            } else {
                // If the month is not present, create a new array with the performance score
                $monthlyPerformance[$date] = [$performance];
            }
        }
        // Calculate the average performance for each month
        foreach ($monthlyPerformance as $month => $performanceArray) {
            $averagePerformance = array_sum($performanceArray) / count($performanceArray);
            $monthlyAveragePerformance[$month] = $averagePerformance;
        }
        // Sort the performance data by month in ascending order
        ksort($monthlyAveragePerformance);
        // Prepare the data for the line chart
        $dataset_line = [];
        foreach ($monthlyAveragePerformance as $month => $averagePerformance) {
            $dataset_line[] = ['x' => $month, 'y' => $averagePerformance];
        }
        // turn dataset into json format
        $data['dataset_line'] = json_encode($dataset_line);


        // load data for the bar chart
        $dataset_bar = [];
        $performanceScoreSum = [];
        $performanceScoreCount = [];
        foreach ($loadAssignments as $classID => $assignments) {
            $performanceScoreSum[$classID] = 0;
            $performanceScoreCount[$classID] = 0;
            foreach ($assignments as $assignment) {
                // if max mark not zero
                if ($assignment['maxMark'] != 0) {
                    $performanceScoreSum[$classID] += $assignment['totalMark'] / $assignment['maxMark'] * 100;
                    $performanceScoreCount[$classID]++;
                }

            }
        }
        foreach ($classIDs as $classID) {
            // if $performanceScoreCount[$classID] is zero, then skip
            if ($performanceScoreCount[$classID] == 0) {
                continue;
            }
            $dataset_bar[$classID] = $performanceScoreSum[$classID] / $performanceScoreCount[$classID];
        }
        // turn dataset into json format
        $data['dataset_bar'] = json_encode(array_values($dataset_bar));

        // output the array of classIDs in the bar chart
        $data['classIDs'] = json_encode(array_keys($dataset_bar));
        // output the array of classNames in the bar chart
        $model = new \App\Models\ClassModel();
        $data['classNames'] = json_encode($model->getClassNames(array_keys($dataset_bar)));

        
        // array of class details, with classID as key
        $model = new \App\Models\ClassModel();
        $data['classDetails'] = $model->getClassDetails($classIDs);
        // attach the teacher name of each class in the class details, to show on the widget
        $model = new \App\Models\UserModel();
        foreach ($data['classDetails'] as $classID => $classDetail) {
            $data['classDetails'][$classID]['teacherName'] = $model->findUsername([$classDetail['teacherID']])[0];
        }
        
        // load data for the bottom right comparison, compare user's performance in each class with other students in the same class
        // can use the dataset in the bar chart as the user's performance score
        $model = new \App\Models\AssignmentModel();
        foreach ($dataset_bar as $classID => $performanceScore) {
            $classMeanPerforamceScore = $model->findClassMeanPerformanceScore($classID);
            $data['comparisonData'][$classID] = round(($performanceScore - $classMeanPerforamceScore) / $classMeanPerforamceScore * 100, 2);
        }


        return view('Student/dashboard', $data);
    }



    public function classroom($currentClassID)
    {
        $id = auth()->user()->id;

        $model = new \App\Models\UserModel();
        [$data['userType'], $classIDs] = $model->userConfiguration($id);

        $model = new \App\Models\ClassModel();
        [$classes, $classNames] = $model->classroomConfiguration_1($classIDs);

        // find teacherIDs according to classIDs
        $model = new \App\Models\UserModel();
        $teacherIDs = [];
        foreach ($classes as $class) {
            $teacherIDs[] = $class['teacherID'];
        }

        // find teacherNames with teacherIDs
        $teacherNames = $model->findUsername($teacherIDs);

        // output the array required to load the classrooms in the sidebar
        $model = new \App\Models\ClassModel();
        $data['loadClassrooms'] = $model->classroomConfiguration_2($classes, $classNames, $teacherNames);
        

        // output the array required to load all the assignments of user
        $model = new \App\Models\AssignmentModel();
        $results = [];
        foreach ($classIDs as $classID) {
            $result = $model->assignmentConfiguration($id, $data['userType'], $classID);
            $results[$classID] = [
                'topics' => $result[0],
                'loadAssignments' => $result[1],
            ];
        }
        // Assign the final results back to the variables
        $topics = [];
        $loadAssignments = [];
        foreach ($results as $classID => $result) {
            $topics[$classID] = $result['topics'];
            $loadAssignments[$classID] = $result['loadAssignments'];
        }

        // load data for the to-do list and calculate overall studentProgress
        // also load data for Task Review in sidbar
        $data['toDoList'] = [];
        foreach ($loadAssignments as $classID => $assignments) {
            foreach ($assignments as $assignment) {
                if ($assignment['studentProgress'] != 100) {
                    $data['toDoList'][$classID][] = [
                        'assignmentID' => $assignment['assignmentID'],
                        'assignmentName' => $assignment['assignmentName'], 
                        'topic' => $assignment['topic'],
                        'dueDate' => $assignment['dueDate'],
                        'studentProgress' => $assignment['studentProgress'],
                    ];
                }
            }
        }

        // array of class details, with classID as key
        $model = new \App\Models\ClassModel();
        $data['classDetails'] = $model->getClassDetails($classIDs);


        
        // output the array required to load the assignments
        [$data['topics'], $data['loadAssignments']] = [$topics[$currentClassID], $loadAssignments[$currentClassID]];
        $data['currentClassID'] = $currentClassID;

        // retrieve the tagNames of the selected tags from the url
        $selectedTagNames = $this->request->getGet('tags');

        // if there are selected tags
        if (!empty($selectedTagNames)) {
            $selectedTagNames = explode(',', $selectedTagNames);

            // array of tagIDs of the selected tags
            $model = new \App\Models\TagModel();
            $selectedTagIDs = $model->findTagIDs($selectedTagNames);

            // array of assignmentIDs that have the selected tags
            $assignmentIDs = [];
            $model = new \App\Models\TagModel();
            $assignmentIDs[] = $model->findAssignmentIDs($selectedTagIDs);

            // delete assignments in the $data['loadAssignments'] that do not have the selected tags
            foreach ($data['loadAssignments'] as $key => $assignment) {
                if (!in_array($assignment['assignmentID'], $assignmentIDs[0])) {
                    unset($data['loadAssignments'][$key]);
                }
            }
        }  


        // array of all assignmentIDs
        $assignmentIDs = [];
        foreach ($data['loadAssignments'] as $assignment) {
            $assignmentIDs[] = $assignment['assignmentID'];
        }

        // output the array required to load the tags in the sidebar
        $model = new \App\Models\TagModel();
        $data['tagNames'] = $model->sidebarConfiguration($assignmentIDs, $data['userType'], $id);

        return view('Student/classroom', $data);
    }



    public function assignment($assignmentID)
    {
        $id = auth()->user()->id;

        $model = new \App\Models\UserModel();
        [$data['userType'], $classIDs] = $model->userConfiguration($id);

        $model = new \App\Models\ClassModel();
        [$classes, $classNames] = $model->classroomConfiguration_1($classIDs);

        // find teacherIDs according to classIDs
        $model = new \App\Models\UserModel();
        $teacherIDs = [];
        foreach ($classes as $class) {
            $teacherIDs[] = $class['teacherID'];
        }

        // find teacherNames with teacherIDs
        $teacherNames = $model->findUsername($teacherIDs);

        // output the array required to load the classrooms in the sidebar
        $model = new \App\Models\ClassModel();
        $data['loadClassrooms'] = $model->classroomConfiguration_2($classes, $classNames, $teacherNames);


        // output the array required to load all the assignments of user
        $model = new \App\Models\AssignmentModel();
        $results = [];
        foreach ($classIDs as $classID) {
            $result = $model->assignmentConfiguration($id, $data['userType'], $classID);
            $results[$classID] = [
                'topics' => $result[0],
                'loadAssignments' => $result[1],
            ];
        }
        // Assign the final results back to the variables
        $topics = [];
        $loadAssignments = [];
        foreach ($results as $classID => $result) {
            $topics[$classID] = $result['topics'];
            $loadAssignments[$classID] = $result['loadAssignments'];
        }

        // load data for the to-do list and calculate overall studentProgress
        // also load data for Task Review in sidbar
        $data['toDoList'] = [];
        foreach ($loadAssignments as $classID => $assignments) {
            foreach ($assignments as $assignment) {
                if ($assignment['studentProgress'] != 100) {
                    $data['toDoList'][$classID][] = [
                        'assignmentID' => $assignment['assignmentID'],
                        'assignmentName' => $assignment['assignmentName'], 
                        'topic' => $assignment['topic'],
                        'dueDate' => $assignment['dueDate'],
                        'studentProgress' => $assignment['studentProgress'],
                    ];
                }
            }
        }

        // array of class details, with classID as key
        $model = new \App\Models\ClassModel();
        $data['classDetails'] = $model->getClassDetails($classIDs);


        // find the classID that the assignment is in
        $model = new \App\Models\AssignmentModel();
        $data['classID'] = $model->findClassID($assignmentID);
        

        // output the array required to load the questions
        $model = new \App\Models\QuestionModel();
        $data['loadQuestions'] = $model->questionConfiguration($assignmentID);
        $data['assignmentID'] = $assignmentID;

        // retrieve the tagNames of the selected tags from the url
        $selectedTagNames = $this->request->getGet('tags');

        // if there are selected tags
        if (!empty($selectedTagNames)) {
            $selectedTagNames = explode(',', $selectedTagNames);

            // array of tagIDs of the selected tags
            $model = new \App\Models\TagModel();
            $selectedTagIDs = $model->findTagIDs($selectedTagNames);

            // array of questionIDs that have the selected tags
            $questionIDs = [];
            $model = new \App\Models\TagModel();
            $questionIDs[] = $model->findQuestionIDs($selectedTagIDs);

            // delete questions in the $data['loadQuestions'] that do not have the selected tags
            foreach ($data['loadQuestions'] as $key => $question) {
                if (!in_array($question['questionID'], $questionIDs[0])) {
                    unset($data['loadQuestions'][$key]);
                }
            }
        }  

        // output the array required to load the tags on the questions
        $model = new \App\Models\TagModel();
        $data['loadTagss'] = $model->tagConfiguration($data['loadQuestions']);


        // output the array required to load the tags in the sidebar
        $model = new \App\Models\TagModel();
        $data['tagNames'] = $model->sidebarConfiguration([$assignmentID], $data['userType'], $id);

        return view('Student/assignment', $data);
    }



    public function analysis()
    {
        $id = auth()->user()->id;

        $model = new \App\Models\UserModel();
        [$data['userType'], $classIDs] = $model->userConfiguration($id);

        $model = new \App\Models\ClassModel();
        [$classes, $classNames] = $model->classroomConfiguration_1($classIDs);

        // find teacherIDs according to classIDs
        $model = new \App\Models\UserModel();
        $teacherIDs = [];
        foreach ($classes as $class) {
            $teacherIDs[] = $class['teacherID'];
        }

        // find teacherNames with teacherIDs
        $teacherNames = $model->findUsername($teacherIDs);

        // output the array required to load the classrooms in the sidebar
        $model = new \App\Models\ClassModel();
        $data['loadClassrooms'] = $model->classroomConfiguration_2($classes, $classNames, $teacherNames);


        // output the array required to load all the assignments of user
        $model = new \App\Models\AssignmentModel();
        $results = [];
        foreach ($classIDs as $classID) {
            $result = $model->assignmentConfiguration($id, $data['userType'], $classID);
            $results[$classID] = [
                'topics' => $result[0],
                'loadAssignments' => $result[1],
            ];
        }
        // Assign the final results back to the variables
        $topics = [];
        $loadAssignments = [];
        foreach ($results as $classID => $result) {
            $topics[$classID] = $result['topics'];
            $loadAssignments[$classID] = $result['loadAssignments'];
        }

        // load data for the to-do list and calculate overall studentProgress
        // also load data for Task Review in sidbar
        $data['toDoList'] = [];
        foreach ($loadAssignments as $classID => $assignments) {
            foreach ($assignments as $assignment) {
                if ($assignment['studentProgress'] != 100) {
                    $data['toDoList'][$classID][] = [
                        'assignmentID' => $assignment['assignmentID'],
                        'assignmentName' => $assignment['assignmentName'], 
                        'topic' => $assignment['topic'],
                        'dueDate' => $assignment['dueDate'],
                        'studentProgress' => $assignment['studentProgress'],
                    ];
                }
            }
        }

        // array of class details, with classID as key
        $model = new \App\Models\ClassModel();
        $data['classDetails'] = $model->getClassDetails($classIDs);

        // output the array required to load all the assignments
        $model = new \App\Models\AssignmentModel();
        $results = [];
        foreach ($classIDs as $classID) {
            $result = $model->assignmentConfiguration($id, $data['userType'], $classID);
            $results[$classID] = [
                'topics' => $result[0],
                'loadAssignments' => $result[1],
            ];
        }


        // retrieve the tagNames of the selected tags from the url
        $selectedTagNames = $this->request->getGet('tags');

        // if there are selected tags
        if (!empty($selectedTagNames)) {
            $selectedTagNames = explode(',', $selectedTagNames);

            // array of tagIDs of the selected tags
            $model = new \App\Models\TagModel();
            $selectedTagIDs = $model->findTagIDs($selectedTagNames);

            // array of assignmentIDs that have the selected tags
            $assignmentIDs = [];
            $model = new \App\Models\TagModel();
            $assignmentIDs = $model->findAssignmentIDs($selectedTagIDs);

            // delete assignments in the $studentAssignments that do not have the selected tags
            foreach ($loadAssignments as $classID => $assignments) {
                foreach ($assignments as $key => $assignment) {
                    if (!in_array($assignment['assignmentID'], $assignmentIDs)) {
                        unset($loadAssignments[$classID][$key]);
                    }
                }
            }
        }  

        // array of all assignmentIDs
        $assignmentIDs = [];
        foreach ($loadAssignments as $classID => $assignments) {
            foreach ($assignments as $assignment) {
                $assignmentIDs[] = $assignment['assignmentID'];
            }
        }

        // output the array required to load the tags in the sidebar
        // Feature: after filtering by tags, tags that are 'mutually exclusive' will disappear in the sidebar; due to the fact that the sidebar is loaded with the assignmentIDs after filtering
        $model = new \App\Models\TagModel();
        $data['tagNames'] = $model->sidebarConfiguration($assignmentIDs, $data['userType'], $id);



        // load data for the bubble chart
        $dataset_bubble = [];
        foreach ($loadAssignments as $classID => $assignments) {
            foreach ($assignments as $assignment) {
                // if max mark not zero
                if ($assignment['maxMark'] != 0) {
                    $dataset_bubble[$classID][] = [
                        'x' => $assignment['topic'],
                        'y' => $assignment['totalMark'] / $assignment['maxMark'] * 100,
                    ];
                }
            }
        }
        // add r to the dataset --> number of students with the same x & y values
        $counts = [];
        foreach ($dataset_bubble as &$dataset) {
            foreach ($dataset as &$item) {
                $key = $item['x'] . '_' . $item['y'];
                $item['r'] = isset($counts[$key]) ? ++$counts[$key] : ($counts[$key] = 1);
            }
        }
        // foreach ($dataset_bubble as &$dataset) {
        //     foreach ($dataset as &$item) {
        //         $key = $item['x'] . '_' . $item['y'];
        //         $item['r'] = isset($counts[$key]) ? ($counts[$key] += 10) : ($counts[$key] = 10);
        //     }
        // }
        // turn it into json format
        $data['dataset_bubble'] = json_encode($dataset_bubble);



        // load data for the line chart
        $dataset_line = [];
        $performanceScores = [];
        foreach ($loadAssignments as $classID => $assignments) {
            foreach ($assignments as $assignment) {
                // if max mark not zero
                if ($assignment['maxMark'] != 0) {
                    $performanceScores[$classID][] = [
                        'performance' => $assignment['totalMark'] / $assignment['maxMark'] * 100, 
                        'date' => $assignment['dueDate'],
                    ];
                }
            }
        }
        $monthlyPerformance = [];
        $monthlyAveragePerformance = [];
        // Iterate over the assignments
        foreach ($classIDs as $classID) {
            // if $performanceScores do not have the index classID, then skip
            if (!isset($performanceScores[$classID])) {
                continue;
            }
            foreach ($performanceScores[$classID] as $assignment) {
                $performance = $assignment['performance'];
                $date = date('Y-m', strtotime($assignment['date'])); // Extract the year and month
                // If the month is already present in the array, add the performance score
                if (isset($monthlyPerformance[$classID][$date])) {
                    $monthlyPerformance[$classID][$date][] = $performance;
                } else {
                    // If the month is not present, create a new array with the performance score
                    $monthlyPerformance[$classID][$date] = [$performance];
                }
            }
            // Calculate the average performance for each month
            foreach ($monthlyPerformance[$classID] as $month => $performanceArray) {
                $averagePerformance = array_sum($performanceArray) / count($performanceArray);
                $monthlyAveragePerformance[$classID][$month] = $averagePerformance;
            }
            // Sort the performance data by month in ascending order
            ksort($monthlyAveragePerformance[$classID]);
            // Prepare the data for the line chart
            $dataset_line[$classID] = [];
            foreach ($monthlyAveragePerformance[$classID] as $month => $averagePerformance) {
                $dataset_line[$classID][] = ['x' => $month, 'y' => $averagePerformance];
            }
        }
        // turn dataset into json format
        $data['dataset_line'] = json_encode($dataset_line);


        // output the array of classIDs in the bubble chart
        $data['classIDs'] = json_encode(array_keys($dataset_bubble));
        // output the array of classNames in the bubble chart
        $model = new \App\Models\ClassModel();
        $data['classNames'] = json_encode($model->getClassNames(array_keys($dataset_bubble)));

        
        return view('Student/analysis', $data);
    }








// ------------------------------------------------------------ Ajax Request ------------------------------------------------------------




    public function questionUpdate()
    {
        // retrieve input values from the request
        $userID = $this->request->getPost('userID');
        $assignmentID = $this->request->getPost('assignmentID');
        $questionID = $this->request->getPost('questionID');
        $question = $this->request->getPost('question');
        $answer = $this->request->getPost('answer');
        $markingScheme = $this->request->getPost('markingScheme');
        $comment = $this->request->getPost('comment');
        $totalMark = $this->request->getPost('totalMark');
        $maxMark = $this->request->getPost('maxMark');

        // check if the assignment is owned by the user -> if not, create a copy of the assignment and update the copy
        $model = new \App\Models\AssignmentModel();
        $newFlag = $model->checkNewAssignment($assignmentID, $userID);
        if ($newFlag) {
            // create a copy of the assignment
            $newAssignmentID = $model->copyAssignment($assignmentID, $userID);

            // copy all questions to the new assignment
            $model = new \App\Models\QuestionModel();
            $newQuestionID = $model->copyQuestion($questionID, $assignmentID, $newAssignmentID);
            $model->copyAllOtherQuestions($assignmentID, $newAssignmentID, $questionID);

            // update the assignmentID
            $assignmentID = $newAssignmentID;

            // update the questionID
            $questionID = $newQuestionID;
        }

        // update the database
        $model = new \App\Models\QuestionModel();
        $model->updateQuestion($questionID, $question, $answer, $markingScheme, $comment, $totalMark, $maxMark);

        // get $studentProgress and $teacherProgress
        $studentProgress = $model->findAssignmentStudentProgress($assignmentID);
        $teacherProgress = $model->findAssignmentTeacherProgress($assignmentID);

        // update the assignment database (update the pregresses of the assignment)
        $model = new \App\Models\AssignmentModel();
        $model->updateAssignmentProgress($assignmentID, $studentProgress, $teacherProgress);

        // redirect to the assignment page using AJAX response
        return $this->response->setJSON(['route' => 'student/assignment/' . $assignmentID]);
    }


        // // fetch all questions in an assignment for ajax request
        // public function loadQuestion($assignmentID)
        // {
        //     // output the array required to load the questions
        //     $model = new \App\Models\QuestionModel();
        //     $data['assignmentID'] = $assignmentID;
        //     $data['loadQuestions'] = $model->questionConfiguration($assignmentID);
        //     return $this->response->setJSON($data);
        // }



    public function tagAdd()
    {
        // retrieve input values from the request
        $questionID = $this->request->getPost('questionID');
        $assignmentID = $this->request->getPost('assignmentID');
        $userID = $this->request->getPost('userID');
        $tagName = $this->request->getPost('tagName');

        // update the database
        $model = new \App\Models\TagModel();
        $model->addTag($questionID, $assignmentID, $tagName, $userID);

        // Return a response indicating success
        $response = array('success' => true);
        return $this->response->setJSON($response);
    }



    public function tagDelete()
    {
        // retrieve input values from the request
        $tagID = $this->request->getPost('tagID');
        $userID = $this->request->getPost('userID');

        
        $model = new \App\Models\TagModel();
        // check if the user can delete the tag (if the tag belongs to the user)
        if ($model->checkDeleteTag($tagID, $userID))
        {
            // update the database
            $model->deleteTag($tagID);
        }

        // Return a response indicating success
        $response = array('success' => true);
        return $this->response->setJSON($response);
    }





}
