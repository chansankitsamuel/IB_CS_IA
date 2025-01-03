<?= $this->extend("Layouts/admin_default") ?>
<?= $this->section("content") ?>

<title>Classes</title>
    <!--begin::Header-->
    <div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
        <!--begin::Container-->
        <div class="container-xxl d-flex align-items-center justify-content-between" id="kt_header_container">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-5 pb-lg-0" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                <!--begin::Heading-->
                <h1 class="text-dark fw-bold my-0 fs-2">Classes</h1>
                <!--end::Heading-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-line text-muted fw-bold fs-base my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="<?= base_url() ?>" class="text-muted">Home</a>
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
                <a href="../dist/student_index.html" class="d-flex align-items-center">
                    <img alt="Logo" src="asset/Main_Page/dist/assets/media/logos/logo-default.svg" class="h-40px" />
                </a>
                <!--end::Logo-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Header-->



    <script>
        // showClassForm is used to filter classes
        var showClassForm = "";
        function changeShowClassForm(classForm) {
            showClassForm = classForm;
        }

        // savedClassID is a global variable used to save the id of the class to be edited or deleted
        var savedClassID;
    </script>



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
                            <div class="card card-xll-stretch mb-5 mb-xl-8">
                                <!--begin::Header-->
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder fs-3 mb-1">Classes Table</span>
                                    </h3>
                                    <h3 class="card-title align-items-end flex-column">
                                        <span class="card-label fw-bold fs-6 mb-1"></span>
                                    </h3>
                                </div>
                                <div class="mb-1 offset--1">
                                    <div>
                                        <div class="tagcloud">
                                            <button onclick="changeShowClassForm('')" class="tag-cloud-link changeShowClassForm">All</button>
                                            <button onclick="changeShowClassForm('1')" class="tag-cloud-link changeShowClassForm">Form 1</button>
                                            <button onclick="changeShowClassForm('2')" class="tag-cloud-link changeShowClassForm">Form 2</button>
                                            <button onclick="changeShowClassForm('3')" class="tag-cloud-link changeShowClassForm">Form 3</button>
                                            <button onclick="changeShowClassForm('4')" class="tag-cloud-link changeShowClassForm">Form 4</button>
                                            <button onclick="changeShowClassForm('5')" class="tag-cloud-link changeShowClassForm">Form 5</button>
                                            <button onclick="changeShowClassForm('6')" class="tag-cloud-link changeShowClassForm">Form 6</button>
                                        </div>
                                        <div>
                                            <button id="addButton" class="smallButton btn-success float-right me-5">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-3">
                                    
                                <?php if ($classes): ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col" class="text-center">form</th>
                                            <th scope="col" class="text-center">className</th>
                                            <th scope="col" class="text-center">studentNumber</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--Load jQuery for ajax request-->
                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        
                                        <script>
                                            // ajax request to load classes
                                            function loadClasses() {
                                                $.ajax({
                                                    url: "/admin/classes/loadClasses",
                                                    type: "GET",
                                                    data: {
                                                        showClassForm: showClassForm
                                                    },
                                                    success: function(response) {
                                                        $.each(response.classes, function(key, value) {
                                                            var row = '<tr>';
                                                            row += '<th scope="row" class="text-center">' + value.classID + '</th>';
                                                            row += '<td class="text-center">' + value.form + '</td>';
                                                            row += '<td class="text-center">' + value.className + '</td>';
                                                            row += '<td class="text-center">' + value.studentNumber + '</td>';
                                                            row += '<td class="text-center"> <div class="d-flex justify-content-center">';
                                                            row += '<button data-bs-toggle="modal" data-bs-target="#editClassModal" class="saveClassIdRequired verySmallButton m-1 btn-primary">Edit</button>';
                                                            row += '<button data-bs-toggle="modal" data-bs-target="#deleteClassModal" class="saveClassIdRequired verySmallButton m-1 btn-danger">Delete</button>';
                                                            row += '</td>';
                                                            row += '</tr>';
                                                            $('tbody').append(row);
                                                        });
                                                    }
                                                });
                                            }

                                            // load classes when the page is ready
                                            $(document).ready(function() {
                                                loadClasses();
                                            });

                                            // load classes when the showClassForm is changed
                                            $('.changeShowClassForm').click(function() {
                                                $('tbody').empty();
                                                loadClasses();
                                            });


                                            // send ajax request to add class
                                            $(document).on('click', '#addButton', function() {
                                                $.ajax({
                                                    url: "/admin/classes/addClass",
                                                    type: "POST",
                                                    data: {
                                                        classForm: showClassForm
                                                    },
                                                    success: function(response) {
                                                        $('tbody').empty();
                                                        loadClasses();
                                                    }
                                                });
                                            });

                                            // save classID of the class to savedClassID by looking for the closest <tr> tag
                                            $(document).on('click', '.saveClassIdRequired', function() {
                                                savedClassID = $(this).closest('tr').find('th').text();
                                            });

                                            // send ajax request to delete class
                                            $(document).on('click', '#deleteButton', function() {

                                                // get savedClassID
                                                classID = savedClassID;

                                                $.ajax({
                                                    url: "/admin/classes/deleteClass",
                                                    type: "POST",
                                                    data: {
                                                        classID: classID
                                                    },
                                                    success: function(response) {
                                                        $('tbody').empty();
                                                        loadClasses();
                                                    }
                                                });
                                            });

                                            // send ajax request to edit class
                                            $(document).on('click', '#editButton', function() {

                                                // get savedClassID
                                                classID = savedClassID;

                                                $.ajax({
                                                    url: "/admin/classes/editClass",
                                                    type: "POST",
                                                    data: {
                                                        classID: classID,
                                                        form: $('#form').val(),
                                                        className: $('#className').val(),
                                                        studentNumber: $('#studentNumber').val(),
                                                    },
                                                    success: function(response) {
                                                        $('tbody').empty();
                                                        loadClasses();
                                                    }
                                                });
                                            });
                                        </script>

                                    </tbody>
                                </table>

                                <?= $pager->links() ?>

                            <?php else: ?>
                                <p>No classes found</p>
                            <?php endif; ?>


                                </div>
                                <!--begin::Body-->
                            </div>
                            <!--end::Questions Widget 1-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Content-->
        </div>
    </div>




    <!--begin::Modal-->

    <!--begin::deleteClassModal-->
    <div class="modal fade" id="deleteClassModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Delete Class</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Confirm to delete this class?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="deleteButton" type="button" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::deleteClassModal-->

    <!--begin::editClassModal-->
    <div class="modal fade" id="editClassModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Edit Class</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="form" id="form" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="className" id="className" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="studentNumber" id="studentNumber" row="1"></textarea> <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="editButton" type="button" class="btn btn-danger" data-bs-dismiss="modal">Edit</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::editClassModal-->

    <!--end::Modal-->


    
<?= $this->endSection() ?>

