<?php

namespace App\Controllers;

class Teacher extends BaseController
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


        // output the array required to load all the assignments
        $model = new \App\Models\AssignmentModel();
        $results = [];
        foreach ($classIDs as $classID) {
            $result = $model->assignmentConfiguration($id, $data['userType'], $classID);
            $results[$classID] = [
                'topics' => $result[0],
                'loadAssignments' => $result[1],
                'studentAssignments' => $result[2]
            ];
        }
        // Assign the final results back to the variables
        $topics = [];
        $loadAssignments = [];
        $studentAssignments = [];
        
        foreach ($results as $classID => $result) {
            $topics[$classID] = $result['topics'];
            $loadAssignments[] = $result['loadAssignments'];
            $studentAssignments[$classID] = $result['studentAssignments'];
        }


        // load data for the to-do list and calculate overall teacherProgress
        // also load data for the Task Review in sidebar
        $data['toDoList'] = [];
        foreach ($studentAssignments as $classID => $classAssignments) {
            foreach ($classAssignments as $Topic_Name => $assignments) {
                $overallTeacherProgress = 0;
                $count = 0;
                foreach ($assignments as $assignment) {
                    if ($assignment['teacherProgress'] != 100) {
                        $data['toDoList'][$classID][$Topic_Name] = [
                            'assignmentID' => $assignment['assignmentID'],
                            'topic' => $assignment['topic'],
                            'assignmentName' => $assignment['assignmentName'],
                            'dueDate' => $assignment['dueDate'],
                        ];
                        $overallTeacherProgress += $assignment['teacherProgress'];
                        $count++;
                    }
                }
                if ($count > 0) {
                    $averageTeacherProgress = round($overallTeacherProgress / $count);
                    $data['toDoList'][$classID][$Topic_Name]['overallTeacherProgress'] = $averageTeacherProgress;
                }
            }
        }

        

        // load data for the line chart
        $dataset_line = [];
        $performanceScores = [];
        foreach ($studentAssignments as $classID => $topicName) {
            foreach ($topicName as $assignments) {
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


        // load data for the bar chart
        $dataset_bar = [];
        $performanceScoreSum = [];
        $performanceScoreCount = [];
        foreach ($studentAssignments as $classID => $topicName) {
            $performanceScoreSum[$classID] = 0;
            $performanceScoreCount[$classID] = 0;
            foreach ($topicName as $assignments) {
                foreach ($assignments as $assignment) {
                    // if max mark not zero
                    if ($assignment['maxMark'] != 0) {
                        $performanceScoreSum[$classID] += $assignment['totalMark'] / $assignment['maxMark'] * 100;
                        $performanceScoreCount[$classID]++;
                    }
                }
            }
        }
        foreach ($classIDs as $classID) {
            // if $performanceScoreCount[$classID] is zero, then skip
            if ($performanceScoreCount[$classID] == 0) {
                continue;
            }
            $dataset_bar[] = $performanceScoreSum[$classID] / $performanceScoreCount[$classID];
        }
        // turn dataset into json format
        $data['dataset_bar'] = json_encode($dataset_bar);


        // output the array of classIDs in the charts
        $data['classIDs'] = json_encode(array_keys($dataset_line));
        // output the array of classNames in the charts
        $model = new \App\Models\ClassModel();
        $data['classNames'] = json_encode($model->getClassNames(array_keys($dataset_line)));



        // $data['classDetails'] is an array that takes classID as key and others (className and teacherID) as values
        $model = new \App\Models\ClassModel();
        $data['classDetails'] = $model->getClassDetails($classIDs);
        // add teachers' name based on teacherID
        $model = new \App\Models\UserModel();
        foreach ($data['classDetails'] as $classID => $classDetail) {
            $data['classDetails'][$classID]['teacherName'] = $model->findUsername([$classDetail['teacherID']])[0];
        }

        // load data for the bottom right comparison, compare overall perforamce of each class with other classes
        $model = new \App\Models\AssignmentModel();
        foreach ($classIDs as $classID) {
            $classMeanPerformanceScore = $model->findClassMeanPerformanceScore($classID);
            // if $classMeanPerformanceScore is zero, then skip {it was set zero because it was used as denominator in student's dashboard}
            if ($classMeanPerformanceScore == 0) {
                continue;
            }
            $allClassesMeanPerforamceScore = $model->findAllClassesMeanPerformaceScore($classIDs);
            $data['comparisonData'][$classID] = round(($classMeanPerformanceScore - $allClassesMeanPerforamceScore) / $allClassesMeanPerforamceScore * 100, 2);
        }
        

        return view('Teacher/dashboard', $data);
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


        // output the array required to load all the assignments
        $model = new \App\Models\AssignmentModel();
        $results = [];
        foreach ($classIDs as $classID) {
            $result = $model->assignmentConfiguration($id, $data['userType'], $classID);
            $results[$classID] = [
                'topics' => $result[0],
                'loadAssignments' => $result[1],
                'studentAssignments' => $result[2]
            ];
        }
        // Assign the final results back to the variables
        $topics = [];
        $loadAssignments = [];
        $studentAssignments = [];
        
        foreach ($results as $classID => $result) {
            $topics[$classID] = $result['topics'];
            $loadAssignments[] = $result['loadAssignments'];
            $studentAssignments[$classID] = $result['studentAssignments'];
        }


        $data['toDoList'] = [];
        // load data for the to-do list and calculate overall teacherProgress
        // also load data for the Task Review in sidebar
        foreach ($studentAssignments as $classID => $classAssignments) {
            foreach ($classAssignments as $Topic_Name => $assignments) {
                $overallTeacherProgress = 0;
                $count = 0;
                foreach ($assignments as $assignment) {
                    if ($assignment['teacherProgress'] != 100) {
                        $data['toDoList'][$classID][$Topic_Name] = [
                            'assignmentID' => $assignment['assignmentID'],
                            'topic' => $assignment['topic'],
                            'assignmentName' => $assignment['assignmentName'],
                            'dueDate' => $assignment['dueDate'],
                        ];
                        $overallTeacherProgress += $assignment['teacherProgress'];
                        $count++;
                    }
                }
                if ($count > 0) {
                    $averageTeacherProgress = round($overallTeacherProgress / $count);
                    $data['toDoList'][$classID][$Topic_Name]['overallTeacherProgress'] = $averageTeacherProgress;
                }
            }
        }

        // $data['classDetails'] is an array that takes classID as key and others (className and teacherID) as values
        $model = new \App\Models\ClassModel();
        $data['classDetails'] = $model->getClassDetails($classIDs);
        // add teachers' name based on teacherID
        $model = new \App\Models\UserModel();
        foreach ($data['classDetails'] as $classID => $classDetail) {
            $data['classDetails'][$classID]['teacherName'] = $model->findUsername([$classDetail['teacherID']])[0];
        }


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
        // Feature: after filtering by tags, tags that are 'mutually exclusive' will disappear in the sidebar; 
        // due to the fact that the sidebar is loaded with the assignmentIDs after filtering
        $model = new \App\Models\TagModel();
        $data['tagNames'] = $model->sidebarConfiguration($assignmentIDs, $data['userType'], $id);

        return view('Teacher/classroom', $data);
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


        // output the array required to load all the assignments
        $model = new \App\Models\AssignmentModel();
        $results = [];
        foreach ($classIDs as $classID) {
            $result = $model->assignmentConfiguration($id, $data['userType'], $classID);
            $results[$classID] = [
                'topics' => $result[0],
                'loadAssignments' => $result[1],
                'studentAssignments' => $result[2]
            ];
        }
        // Assign the final results back to the variables
        $topics = [];
        $loadAssignments = [];
        $studentAssignments = [];
        
        foreach ($results as $classID => $result) {
            $topics[$classID] = $result['topics'];
            $loadAssignments[] = $result['loadAssignments'];
            $studentAssignments[$classID] = $result['studentAssignments'];
        }


        $data['toDoList'] = [];
        // load data for the to-do list and calculate overall teacherProgress
        // also load data for the Task Review in sidebar
        foreach ($studentAssignments as $classID => $classAssignments) {
            foreach ($classAssignments as $Topic_Name => $assignments) {
                $overallTeacherProgress = 0;
                $count = 0;
                foreach ($assignments as $assignment) {
                    if ($assignment['teacherProgress'] != 100) {
                        $data['toDoList'][$classID][$Topic_Name] = [
                            'assignmentID' => $assignment['assignmentID'],
                            'topic' => $assignment['topic'],
                            'assignmentName' => $assignment['assignmentName'],
                            'dueDate' => $assignment['dueDate'],
                        ];
                        $overallTeacherProgress += $assignment['teacherProgress'];
                        $count++;
                    }
                }
                if ($count > 0) {
                    $averageTeacherProgress = round($overallTeacherProgress / $count);
                    $data['toDoList'][$classID][$Topic_Name]['overallTeacherProgress'] = $averageTeacherProgress;
                }
            }
        }

        // $data['classDetails'] is an array that takes classID as key and others (className and teacherID) as values
        $model = new \App\Models\ClassModel();
        $data['classDetails'] = $model->getClassDetails($classIDs);
        // add teachers' name based on teacherID
        $model = new \App\Models\UserModel();
        foreach ($data['classDetails'] as $classID => $classDetail) {
            $data['classDetails'][$classID]['teacherName'] = $model->findUsername([$classDetail['teacherID']])[0];
        }


        // find the classID that the assignment is in
        $model = new \App\Models\AssignmentModel();
        $data['classID'] = $model->findClassID($assignmentID);


        // output the array required to load students' assignments (to be marked)
        $model = new \App\Models\AssignmentModel();
        $data['loadStudentAssignments'] = $model->studentAssignmentConfiguration($assignmentID, $id);

        // output the name list of students
        $model = new \App\Models\UserModel();
        $data['studentNames'] = $model->findUsernames_attachedToIds(array_keys($data['loadStudentAssignments']));


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


        // output the array required to load the tags
        $model = new \App\Models\TagModel();
        $data['loadTagss'] = $model->tagConfiguration($data['loadQuestions']);


        // output the array required to load the tags in the sidebar
        $model = new \App\Models\TagModel();
        $data['tagNames'] = $model->sidebarConfiguration([$assignmentID], $data['userType'], $id);

        return view('Teacher/assignment', $data);
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


        // output the array required to load all the assignments
        $model = new \App\Models\AssignmentModel();
        $results = [];
        foreach ($classIDs as $classID) {
            $result = $model->assignmentConfiguration($id, $data['userType'], $classID);
            $results[$classID] = [
                'topics' => $result[0],
                'loadAssignments' => $result[1],
                'studentAssignments' => $result[2]
            ];
        }
        // Assign the final results back to the variables
        $topics = [];
        $loadAssignments = [];
        $studentAssignments = [];
        
        foreach ($results as $classID => $result) {
            $topics[$classID] = $result['topics'];
            $loadAssignments[] = $result['loadAssignments'];
            $studentAssignments[$classID] = $result['studentAssignments'];
        }


        $data['toDoList'] = [];
        // load data for the to-do list and calculate overall teacherProgress
        // also load data for the Task Review in sidebar
        foreach ($studentAssignments as $classID => $classAssignments) {
            foreach ($classAssignments as $Topic_Name => $assignments) {
                $overallTeacherProgress = 0;
                $count = 0;
                foreach ($assignments as $assignment) {
                    if ($assignment['teacherProgress'] != 100) {
                        $data['toDoList'][$classID][$Topic_Name] = [
                            'assignmentID' => $assignment['assignmentID'],
                            'topic' => $assignment['topic'],
                            'assignmentName' => $assignment['assignmentName'],
                            'dueDate' => $assignment['dueDate'],
                        ];
                        $overallTeacherProgress += $assignment['teacherProgress'];
                        $count++;
                    }
                }
                if ($count > 0) {
                    $averageTeacherProgress = round($overallTeacherProgress / $count);
                    $data['toDoList'][$classID][$Topic_Name]['overallTeacherProgress'] = $averageTeacherProgress;
                }
            }
        }

        // $data['classDetails'] is an array that takes classID as key and others (className and teacherID) as values
        $model = new \App\Models\ClassModel();
        $data['classDetails'] = $model->getClassDetails($classIDs);
        // add teachers' name based on teacherID
        $model = new \App\Models\UserModel();
        foreach ($data['classDetails'] as $classID => $classDetail) {
            $data['classDetails'][$classID]['teacherName'] = $model->findUsername([$classDetail['teacherID']])[0];
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
            foreach ($studentAssignments as &$classAssignments) {
                foreach ($classAssignments as &$assignments) {
                    foreach ($key = array_keys($assignments) as $assignmentKey) {
                        if (!in_array($assignments[$assignmentKey]['assignmentID'], $assignmentIDs)) {
                            unset($assignments[$assignmentKey]);
                        }
                    }
                }
            }
        }  


        // array of all assignmentIDs
        $assignmentIDs = [];
        foreach ($studentAssignments as $classAssignments) {
            foreach ($classAssignments as $assignments) {
                foreach ($assignments as $assignment) {
                    $assignmentIDs[] = $assignment['assignmentID'];
                }
            } 
        }

        // output the array required to load the tags in the sidebar
        $model = new \App\Models\TagModel();
        $data['tagNames'] = $model->sidebarConfiguration($assignmentIDs, $data['userType'], $id);



        // load data for the bubble chart
        $dataset_bubble = [];
        foreach ($studentAssignments as $classID => $classAssignments) {
            foreach ($classAssignments as $assignments) {
                foreach ($assignments as $assignment) {
                    if ($assignment['classID'] == $classID) {
                        // if max mark not zero
                        if ($assignment['maxMark'] != 0) {
                            $dataset_bubble[$classID][] = [
                                'x' => $assignment['topic'],
                                'y' => $assignment['totalMark'] / $assignment['maxMark'] * 100,
                            ];
                        }
                    }
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

        // load data for the area chart
        $dataset_area = [];
        // array performance scores of each assignment per class
        $performanceScores = [];
        foreach ($studentAssignments as $classID => $classAssignments) {
            foreach ($classAssignments as $assignments) {
                foreach ($assignments as $assignment) {
                    if ($assignment['classID'] == $classID) {
                        // if max mark not zero
                        if ($assignment['maxMark'] != 0) {
                            $performanceScores[$classID][] = strval($assignment['totalMark'] / $assignment['maxMark'] * 100);
                        }
                    }
                }
                
            }
            if (!empty($performanceScores[$classID])) {
                // sort the performance scores in ascending order
                sort($performanceScores[$classID]);
            }
        }
        $assignments = [];
        $performance = [];
        $cumulativePercentage = [];
        foreach ($classIDs as $classID) {
            // if $performanceScores[$classID] is empty, then skip
            if (empty($performanceScores[$classID])) {
                continue;
            }
            // Count the frequency of each performance score
            $scoreFrequencies = array_count_values($performanceScores[$classID]);
            // Iterate over the score frequencies to populate $assignments and $performance arrays
            foreach ($scoreFrequencies as $score => $frequency) {
                $assignments[$classID][] = $frequency; // Set the number of assignments based on the frequency
                $performance[$classID][] = $score; // Add the performance score to the $performance array
            }
            // Calculate the total count of assignments
            $totalCount = array_sum($assignments[$classID]);
            // Calculate cumulative count and cumulative percentage
            $runningCount = 0;
            foreach ($assignments[$classID] as $index => $count) {
                $runningCount += $count;
                $cumulativePercentage[$classID] = 100 - (($runningCount / $totalCount) * 100);
                $dataset_area[$classID][] = array('x' => $cumulativePercentage[$classID], 'y' => $performance[$classID][$index]);
            }
        }
        // turn dataset into json format
        $data['dataset_area'] = json_encode($dataset_area);

        // output the array of classIDs in the charts
        $data['classIDs'] = json_encode(array_keys($dataset_bubble));
        // output the array of classNames in the charts
        $model = new \App\Models\ClassModel();
        $data['classNames'] = json_encode($model->getClassNames(array_keys($dataset_bubble)));


        return view('Teacher/analysis', $data);
    }






// ------------------------------------------------------------ Ajax Request ------------------------------------------------------------





public function assignmentAdd()
{
    // retrieve input values from the request
    $userID = $this->request->getPost('userID');
    $classID = $this->request->getPost('classID');
    $topic = $this->request->getPost('topic');

    // insert record to database
    $model = new \App\Models\AssignmentModel();
    $model->addAssignment($userID, $classID, $topic);

    // Return a response indicating success
    $response = array('success' => true);
    return $this->response->setJSON($response);
}



public function assignmentDelete()
{
    // retrieve input values from the request
    $assignmentID = $this->request->getPost('assignmentID');

    // update the database
    $model = new \App\Models\AssignmentModel();
    $model->deleteAssignment($assignmentID);

    // Return a response indicating success
    $response = array('success' => true);
    return $this->response->setJSON($response);
}



public function assignmentEdit()
{
    // retrieve input values from the request
    $assignmentID = $this->request->getPost('assignmentID');
    $assignmentName = trim($this->request->getPost('assignmentName'));
    $dueDate = trim($this->request->getPost('dueDate'));

    // update the database
    $model = new \App\Models\AssignmentModel();
    $model->updateNameDate($assignmentID, $assignmentName, $dueDate);

    // Return a response indicating success
    $response = array('success' => true);
    return $this->response->setJSON($response);
}



public function assignmentTopicUpdate()
{
    // retrieve input values from the request
    $classID = $this->request->getPost('classID');
    $oldTopic = $this->request->getPost('oldTopic');
    $newTopic = $this->request->getPost('newTopic');

    // update the assignments with old topic to new topic
    if ($newTopic != $oldTopic) {
        $model = new \App\Models\AssignmentModel();
        $model->updateTopic($classID, $oldTopic, $newTopic);
    }

    // Return a response indicating success
    $response = array('success' => true);
    return $this->response->setJSON($response);
}



public function questionAdd()
{
    // retrieve input values from the request
    $assignmentID = $this->request->getPost('assignmentID');

    // update the database
    $model = new \App\Models\QuestionModel();
    $model->addQuestion($assignmentID);

    // Return a response indicating success
    $response = array('success' => true);
    return $this->response->setJSON($response);
}



public function questionDelete()
{
    $questionID = $this->request->getPost('questionID');

    // update the database
    $model = new \App\Models\QuestionModel();
    $model->deleteQuestion($questionID);

    // Return a response indicating success
    $response = array('success' => true);
    return $this->response->setJSON($response);
}



public function questionUpdate()
{
    // retrieve input values from the request
    $questionID = $this->request->getPost('questionID');
    $assignmentID = $this->request->getPost('assignmentID');
    $question = $this->request->getPost('question');
    $answer = $this->request->getPost('answer');
    $markingScheme = $this->request->getPost('markingScheme');
    $comment = $this->request->getPost('comment');
    $totalMark = $this->request->getPost('totalMark');
    $maxMark = $this->request->getPost('maxMark');

    // update the question database (update the mark of the question)
    $model = new \App\Models\QuestionModel();
    $model->updateQuestion($questionID, $question, $answer, $markingScheme, $comment, $totalMark, $maxMark);

    // get $studentProgress and $teacherProgress
    $studentProgress = $model->findAssignmentStudentProgress($assignmentID);
    $teaacherProgress = $model->findAssignmentTeacherProgress($assignmentID);

    // find new assignmentTotalMark and new assignmentMaxMark by counting the totalMark and maxMark of all questions with the same assignmentID
    $model = new \App\Models\QuestionModel();
    $assignmentTotalMark = $model->findAssignmentTotalMark($assignmentID);
    $assignmentMaxMark = $model->findAssignmentMaxMark($assignmentID);

    // update the assignment database (update the marks and pregresses of the assignment)
    $model = new \App\Models\AssignmentModel();
    $model->updateAssignmentMark($assignmentID, $assignmentTotalMark, $assignmentMaxMark);
    $model->updateAssignmentProgress($assignmentID, $studentProgress, $teaacherProgress);

    // Return a response indicating success
    return $this->response->setJSON($response);
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

    // update the database
    $model = new \App\Models\TagModel();
    $model->deleteTag($tagID, $userID);

    // Return a response indicating success
    $response = array('success' => true);
    return $this->response->setJSON($response);
}





}