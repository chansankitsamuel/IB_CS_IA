<?= $this->extend("layouts/default") ?>
<?= $this->section("content") ?>

<title>Assignment</title>
    <!--begin::Header-->
    <div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
        <!--begin::Container-->
        <div class="container-xxl d-flex align-items-center justify-content-between" id="kt_header_container">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-5 pb-lg-0" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                <!--begin::Heading-->
                <h1 class="text-dark fw-bold my-0 fs-2">Assignment</h1>
                <!--end::Heading-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-line text-muted fw-bold fs-base my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="<?= base_url() ?>" class="text-muted">Home</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Assignment</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title=-->
            <!--begin::Wrapper-->
            <div class="d-flex d-lg-none align-items-center ms-n2 me-2">
                <!--begin::Aside mobile toggle-->
                <div class="btn btn-icon btn-active-icon-primary" id="kt_aside_toggle">
                    <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                    <span class="svg-icon svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="black" />
                            <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="black" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Aside mobile toggle-->
                <!--begin::Logo-->
                <a href="<?= base_url(); ?>" class="d-flex align-items-center">
                    <img alt="Logo" src="asset/Main_Page/dist/assets/media/logos/logo-default.svg" class="h-40px" />
                </a>
                <!--end::Logo-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Toolbar wrapper-->
            <div class="d-flex flex-shrink-0">
                <!--begin::Create app-->
                <div class="d-flex ms-3">
                    <a href="<?= site_url('student/analysis') ?>" class="btn btn-info">Analysis</a>
                </div>
                <!--end::Create app-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->



    <!-- begin::saveQuestionID() -->
    <script>
        // Save the actual questionID to be used in the ajax request
        // Since ajax request is performed after the page is loaded, the instances of QuestionID is not saved
        function saveQuestionID(SavedQuestionID) {
            window.SavedQuestionID = SavedQuestionID;
        }
    </script>
    <!-- end::saveQuestionID() -->
    
    <!-- begin::saveTagID() -->
    <script>
        // Save the actual questionID to be used in the ajax request
        // Since ajax request is performed after the page is loaded, the instances of QuestionID is not saved
        function saveTagID(SavedTagID) {
            window.SavedTagID = SavedTagID;
        }
    </script>
    <!-- end::saveTagID() -->



    <div class="container d-md-flex align-items-stretch">
        <div id="content" class="p-4 p-md-5 pt-5">
            <!--begin::Content-->
            <div class="content d-flex flex-column flex-column-fluid maxWidth" id="kt_content">
                <!--begin::Container-->
                <div class="container-xxl" id="kt_content_container">
                    <!--begin::Col-->
                    <div class="col-xxl-12">
                        <!--begin::Row-->
                        <div class="row gy-5 g-xl-11">
                            <!--begin::Questions Widget 1-->
                            <?php $questionCounter = 1; ?>
                            <?php foreach ($loadQuestions as $loadQuestion) : ?>
                                <div class="card card-xll-stretch mb-5 mb-xl-8">
                                        <!--begin::Header-->
                                        <div class="card-header border-0 pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bolder fs-3 mb-1">Question <?= $questionCounter ?></span>
                                            </h3>
                                            <h3 class="card-title align-items-end flex-column">
                                                <span class="card-label fw-bold fs-6 mb-1">
                                                    <div class="d-flex align-items-center">
                                                        <textarea disabled class="form-control centered-textarea transparent-textarea" id="totalMark_<?= $loadQuestion['questionID'] ?>" name="totalMark" rows="1" cols="2"><?= $loadQuestion['totalMark'] ?></textarea>
                                                        <span class="slash">/</span>
                                                        <textarea disabled class="form-control centered-textarea transparent-textarea" id="maxMark_<?= $loadQuestion['questionID'] ?>" name="maxMark" rows="1" cols="2"><?= $loadQuestion['maxMark'] ?></textarea>
                                                    </div>
                                                </span>
                                            </h3>
                                        </div>
                                        <div class="mb-1 offset--1">
                                            <div>
                                                <div class="tagcloud" data-kt-menu-trigger="click">
                                                    <?php foreach ($loadTagss as $questionID => $loadTags) : ?>
                                                        <?php if ($questionID == $loadQuestion['questionID']) : ?>
                                                            <?php foreach ($loadTags as $loadTag) : ?>
                                                                <a type="button" data-bs-toggle="modal" data-bs-target="#deleteTagModal" onclick="saveTagID(<?= $loadTag['tagID'] ?>)" class="tag-cloud-link">
                                                                    <?= $loadTag['tagName'] ?>
                                                                </a>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover">
                                                <!--begin::Add Tag Button-->
                                                <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#addTagModal" onclick="saveQuestionID(<?= $loadQuestion['questionID'] ?>)">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="black" />
                                                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="black" />
                                                        </svg>
                                                    </span>
                                                <!--end::Svg Icon-->Add Tags
                                                </button>
                                                <!--end::Add Tag Button-->
                                            </div>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Body-->
                                        <div class="card-body py-3">
                                            <!--begin::Form container-->
                                                <div class="form-label">
                                                    <label for="question">Question</label>
                                                    <textarea disabled class="form-control" id="question_<?= $loadQuestion['questionID'] ?>" name="question" rows="3"><?= $loadQuestion['question'] ?></textarea><br>

                                                    <label for="answer">Answer</label>
                                                    <textarea class="form-control" id="answer_<?= $loadQuestion['questionID'] ?>" name="answer" rows="3"><?= $loadQuestion['answer'] ?></textarea><br>

                                                    <label for="markingScheme">Marking Scheme</label>
                                                    <textarea disabled class="form-control" id="markingScheme_<?= $loadQuestion['questionID'] ?>" name="markingScheme" rows="3"><?= $loadQuestion['markingScheme'] ?></textarea><br>

                                                    <label for="comment">Teacher's Comment</label>
                                                    <textarea disabled class="form-control" id="comment_<?= $loadQuestion['questionID'] ?>" name="comment" rows="2"><?= $loadQuestion['comment'] ?></textarea><br>
                                                </div>    
                                                <!--begin::Submit button-->
                                                <div class="d-flex ms-auto flex-center">
                                                    <button id="submitUpdateQuestionForm" type="button" onclick="saveQuestionID(<?= $loadQuestion['questionID'] ?>)" class="btn btn-info">Update</button >
                                                </div>
                                                <!--end::Submit button-->
                                            <!--end::Form container-->
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                <?php $questionCounter++; ?>
                            <?php endforeach; ?>
                            <!--end::Questions Widget 1-->
                        </div>
                        <!--end::Row-->
                        <br/><br/><br/><br/><br/>
                        <!--begin::Back button-->
                        <form action="<?= base_url('/student/classroom/'.$classID) ?>" method="get">
                            <div class="d-flex ms-auto flex-right">
                                <button class="btn btn-success" type="submit">Back</button>
                            </div>
                        </form>
                        <!--end::Back button-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Content-->
        </div>
        <?= $this->include('Layouts/sidebar') ?>
    </div>



    <!--begin::Modal-->

        <!--begin::Modal - Add Tag-->
        <div class="modal fade" id="addTagModal" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header pb-0 border-0 justify-content-end">
                        <!--begin::Close-->
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--begin::Modal header-->
                    <!--begin::addTagForm-->
                    <div id="addTagForm">
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                            <!--begin::Heading-->
                            <div class="text-center mb-13">
                                <!--begin::Title-->
                                <h1 class="mb-3">Add Tag</h1>
                                <!--end::Title-->
                                <!--begin::Description-->
                                <div class="text-muted fw-bold fs-5">
                                    Add a tag to the question
                                </div>
                                <!--end::Description-->
                            </div>
                            <!--end::Heading-->
                            <div>
                                <!--begin::Input-->
                                <input id="tagName" type="text" class="form-control form-control-solid mb-8" rows="1" placeholder="Type the name of the tag here" >
                                <!--end::Input-->
                            </div><br/>
                            <!--end::addTagForm-->
                            <!--begin::Submit Button-->
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-lg btn-secondary me-3" data-bs-dismiss="modal">Cancel</button>
                                <button id="submitAddTagForm" type="button" class="btn btn-lg btn-primary">Add Tag</button>
                            </div>
                            <!--end::Submit Button-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - Add Tags-->

        <!--begin::Modal - Delete Tag-->
        <div class="modal fade" id="deleteTagModal" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header pb-0 border-0 justify-content-end">
                        <!--begin::Close-->
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--begin::Modal header-->
                    <!--begin::deleteTagForm-->
                    <div id="deleteTagForm">
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-18 pt-0 pb-15">
                            <!--begin::Heading-->
                            <div class="text-center mb-13">
                                <!--begin::Title-->
                                <h1 class="mb-3">Delete Tag</h1>
                                <!--end::Title-->
                                <!--begin::Description-->
                                <div class="text-muted fw-bold fs-5">
                                    Delete the tag selected
                                </div>
                                <!--end::Description-->
                            </div>
                            <!--end::Heading-->
                            <br/><br/>
                            <div>
                                <!--begin::Submit Button-->
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-lg btn-secondary me-3" data-bs-dismiss="modal">Cancel</button>
                                    <button id="submitDeleteTagForm" type="button" class="btn btn-lg btn-primary">Delete Tag</button>
                                </div>
                                <!--end::Submit Button-->
                            </div>
                            <!--end::Modal body-->
                        </div>
                        <!--end::Modal content-->
                    </div>
                    <!--end::deleteTagForm-->
                </div>
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - Delete Tags-->

        <!--end::Modal-->

    <!--begin:: Ajax request-->

    <!--Load jQuery-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!--begin::Load Question-->
    <script>
        // Declare global variables
        // var assignmentID;
        // var loadQuestions;

        // function loadQuestion(assignmentID) {
        //     $.ajax({
        //         url: '/student/loadQuestion/' + assignmentID,
        //         type: 'GET',
        //         dataType: 'json',
        //         data: {
        //             assignmentID: assignmentID,
        //         },
        //         success: function(response) {
        //             // Iterate over the response
        //             $.each(response, function(id, item) {
        //                 // Check if assignmentID is not set and the current item has the key "assignmentID"
        //                 if (!assignmentID && id === "assignmentID") {
        //                     assignmentID = item;
        //                 }
                        
        //                 // Check if loadQuestions is not set and the current item has the key "loadQuestions"
        //                 if (!loadQuestions && id === "loadQuestions") {
        //                     loadQuestions = item;
        //                 }
        //             });

        //             // Debugging
        //             console.log('assignmentID: ' + assignmentID);
        //             console.log('loadQuestions: ' + JSON.stringify(loadQuestions));
        //             alert("wait");
                    
        //             // Set the JavaScript variables to the global PHP variables
        //             <?php
        //             // Check if the JavaScript variables are set
        //             if (isset($assignmentID) && isset($loadQuestions)) {
        //                 // Assign the JavaScript variables to the global PHP variables
        //                 echo "var assignmentID = " . json_encode($assignmentID) . ";";
        //                 echo "var loadQuestions = " . json_encode($loadQuestions) . ";";
        //                 echo "alert('success');";
        //             }
        //             ?>
        //         },
        //         error: function(error) {
        //             alert("error");
        //         }
        //     });
        // }
    </script>
    <script>
        // // check if the document is ready
        // $(document).ready(function() {
        //     // when the Update button is clicked
        //     $(document).on('click', '#submitLoadQuestionForm', function(event) {
        //         loadQuestion(<?= $assignmentID; ?>);
        //     });
        // });
    </script>
    <!--end::Load Question-->
  
    <!--begin::Update Question-->
    <script>
        // Declare global variables of userID --> check if new assignment is required to created in the ajax request
        var userID = <?= auth()->user()->id; ?>;

        // check if the document is ready
        $(document).ready(function() {
            // when the add button is clicked
            $(document).on('click', '#submitUpdateQuestionForm', function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: '/student/questionUpdate', // Provide the full URL if this code is in a separate JavaScript file
                    type: 'POST',
                    data: {
                        userID: userID, 
                        assignmentID: <?= $assignmentID; ?>,
                        questionID: window.SavedQuestionID, 
                        question: $('#question_' + window.SavedQuestionID).val(),
                        answer: $('#answer_' + window.SavedQuestionID).val(),
                        markingScheme: $('#markingScheme_' + window.SavedQuestionID).val(),
                        comment: $('#comment_' + window.SavedQuestionID).val(),
                        totalMark: $('#totalMark_' + window.SavedQuestionID).val(),
                        maxMark: $('#maxMark_' + window.SavedQuestionID).val()
                    },

                    success: function(response) {
                        if (response) {
                            // Redirect to new assignment
                            var url = '<?= base_url(); ?>' + response.route;
                            window.location.href = url;
                        }
                    },
                    error: function(error) {
                        return false;
                    }
                });
            });

            // when the ajax request is completed
            // $(document).ajaxStop(function(){
            //     window.location.reload();
            // });
        });
    </script>
    <!--end::Update Question-->

    <!--begin::Add Tag-->
    <script>
        // Declare global variables of userID --> check if new assignment is required to created in the ajax request
        var userID = <?= auth()->user()->id; ?>;
        
        // check if the document is ready
        $(document).ready(function() {
            // when the submit button is clicked
            $(document).on('click', '#submitAddTagForm', function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: '/student/tagAdd', // Provide the full URL if this code is in a separate JavaScript file
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        questionID: window.SavedQuestionID,
                        assignmentID: <?= $assignmentID; ?>,
                        userID: userID, 
                        tagName: $('#tagName').val()
                    },
                    // reload the page if succeeded
                    success: function(response) {
                        if (response) {
                            window.location.reload();
                        }
                    },
                    error: function(error) {
                        return false;
                    }
                });
            });
        });
    </script>
    <!--end::Add Tag-->

    <!--begin::Delete Tag-->
    <script>
        // Declare global variables of userID --> check if new assignment is required to created in the ajax request
        var userID = <?= auth()->user()->id; ?>;

        // check if the document is ready
        $(document).ready(function() {
            // when the delete button is clicked
            $(document).on('click', '#submitDeleteTagForm', function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: '/student/tagDelete', // Provide the full URL if this code is in a separate JavaScript file
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        tagID: window.SavedTagID, 
                        userID: userID, 
                    },
                    // reload the page if succeeded
                    success: function(response) {
                        if (response) {
                            window.location.reload();
                        }
                    },
                    error: function(error) {
                        return false;
                    }
                });
            });
        });
    </script>
    <!--end::Delete Tag-->

    <!--end:: Ajax request-->

    
<?= $this->endSection() ?>