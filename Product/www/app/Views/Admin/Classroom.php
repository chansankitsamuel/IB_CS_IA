<?= $this->extend("Layouts/admin_default") ?>
<?= $this->section("content") ?>

<title>Classroom</title>
    <!--begin::Header-->
    <div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
        <!--begin::Container-->
        <div class="container-xxl d-flex align-items-center justify-content-between" id="kt_header_container">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-5 pb-lg-0" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                <!--begin::Heading-->
                <h1 class="text-dark fw-bold my-0 fs-2">Classroom</h1>
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
        // savedID is a global variable used to save the id of the user to be edited or deleted
        var savedID;

        // global variables used to save the classID, className of the current classroom
        var currentClassroomName = "Not chosen";
        var currentClassroomID;
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
                                <!--begin::changeShowClassroomModal button -->
                                <div class="offset--1">
                                    <div>
                                        <!-- show currentClassroomName -->
                                        <div class="mt-8 float-left me-5 card-label fw-bolder jumbotron">
                                            <h3 class="text-muted">
                                                Current Classroom: <span id="currentClassroomName"></span>
                                                <script> document.getElementById('currentClassroomName').textContent = currentClassroomName; </script>
                                            </h3>
                                        </div>
                                        <div>
                                            <button data-bs-toggle="modal" data-bs-target="#changeShowClassroomModal" class="btn btn-info float-right me-5">Change Calssroom</button>
                                        </div>
                                    </div>
                                </div>
                                <!--begin::Header-->
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder fs-3 mb-1">Users Table</span>
                                    </h3>
                                    <h3 class="card-title align-items-end flex-column">
                                        <span class="card-label fw-bold fs-6 mb-1"></span>
                                    </h3>
                                </div>
                                <div class="mb-1 offset--1">
                                    <div>
                                        <div>
                                            <button id="addButton" class="smallButton btn-success float-right me-5">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-3">
                                    
                                <?php if ($users): ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col" class="text-center">Username</th>
                                            <th scope="col" class="text-center">Last Active</th>
                                            <th scope="col" class="text-center">Created At</th>
                                            <th scope="col" class="text-center">Updated At</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--Load jQuery for ajax request-->
                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        
                                        <script>
                                            // ajax request to load users
                                            function loadUsers(currentClassroomID) {
                                                $.ajax({
                                                    url: "/admin/classroom/loadUsers",
                                                    type: "GET",
                                                    data: {
                                                        classID: currentClassroomID
                                                    },
                                                    success: function(response) {
                                                        $.each(response.teachers, function(key, value) {
                                                            var row = '<tr>';
                                                            row += '<th scope="row" class="text-center">' + value.id + '</th>';
                                                            row += '<td class="text-center">' + value.username + '</td>';
                                                            row += '<td class="text-center">' + value.last_active + '</td>';
                                                            row += '<td class="text-center">' + value.created_at + '</td>';
                                                            row += '<td class="text-center">' + value.updated_at + '</td>';
                                                            row += '<td class="text-center"> <div class="d-flex justify-content-center">';
                                                            row += '<button data-bs-toggle="modal" data-bs-target="#editUserModal" class="saveIdRequired verySmallButton m-1 btn-primary">Edit</button>';
                                                            row += '<button data-bs-toggle="modal" data-bs-target="#deleteUserModal" class="saveIdRequired verySmallButton m-1 btn-danger">Delete</button>';
                                                            row += '</td>';
                                                            row += '</tr>';
                                                            $('tbody').append(row);
                                                        });
                                                        $.each(response.students, function(key, value) {
                                                            var row = '<tr>';
                                                            row += '<th scope="row" class="text-center">' + value.id + '</th>';
                                                            row += '<td class="text-center">' + value.username + '</td>';
                                                            row += '<td class="text-center">' + value.last_active + '</td>';
                                                            row += '<td class="text-center">' + value.created_at + '</td>';
                                                            row += '<td class="text-center">' + value.updated_at + '</td>';
                                                            row += '<td class="text-center"> <div class="d-flex justify-content-center">';
                                                            row += '<button data-bs-toggle="modal" data-bs-target="#editUserModal" class="saveIdRequired verySmallButton m-1 btn-primary">Edit</button>';
                                                            row += '<button data-bs-toggle="modal" data-bs-target="#deleteUserModal" class="saveIdRequired verySmallButton m-1 btn-danger">Delete</button>';
                                                            row += '</td>';
                                                            row += '</tr>';
                                                            $('tbody').append(row);
                                                        });
                                                    }
                                                });
                                            }

                                            // load users when the page is ready
                                            $(document).ready(function() {
                                                loadUsers();
                                            });

                                            // save id of the user to savedID by looking for the closest <tr> tag
                                            $(document).on('click', '.saveIdRequired', function() {
                                                savedID = $(this).closest('tr').find('th').text();
                                            });

                                            // send ajax request to add user
                                            $(document).on('click', '#addButton', function() {
                                                $.ajax({
                                                    url: "/admin/classroom/addUser",
                                                    type: "POST",
                                                    data: {
                                                        classID: currentClassroomID
                                                    },
                                                    success: function(response) {
                                                        $('tbody').empty();
                                                        loadUsers(currentClassroomID);
                                                    }
                                                });
                                            });

                                            // send ajax request to delete user
                                            $(document).on('click', '#deleteButton', function() {

                                                // get savedID
                                                id = savedID;

                                                $.ajax({
                                                    url: "/admin/classroom/deleteUser",
                                                    type: "POST",
                                                    data: {
                                                        id: id, 
                                                        classID: currentClassroomID
                                                    },
                                                    success: function(response) {
                                                        $('tbody').empty();
                                                        loadUsers(currentClassroomID);
                                                    }
                                                });
                                            });

                                            // send ajax request to edit user
                                            $(document).on('click', '#editButton', function() {

                                                // get savedID
                                                id = savedID;

                                                $.ajax({
                                                    url: "/admin/users/editUser",
                                                    type: "POST",
                                                    data: {
                                                        id: id,
                                                        username: $('#username').val(),
                                                        first_name: $('#first_name').val(),
                                                        last_name: $('#last_name').val(),
                                                        classID_1: $('#classID_1').val(),
                                                        classID_2: $('#classID_2').val(),
                                                        classID_3: $('#classID_3').val(),
                                                        classID_4: $('#classID_4').val(),
                                                        classID_5: $('#classID_5').val(),
                                                        classID_6: $('#classID_6').val()
                                                    },
                                                    success: function(response) {
                                                        $('tbody').empty();
                                                        loadUsers(currentClassroomID);
                                                    }
                                                });
                                            });

                                            // send AJAX request to change currentClassroom
                                            $(document).on('click', '#ChangeButton', function() {
                                                var classID = $('#classID').val();
                                                var className = $('#className').val();

                                                if (classID !== '') {
                                                    currentClassroomID = classID;

                                                    // AJAX request to get className
                                                    $.ajax({
                                                        url: "/admin/classroom/getClassName",
                                                        type: "GET",
                                                        data: {
                                                            classID: classID
                                                        },
                                                        success: function(response) {
                                                            currentClassroomName = response.className;
                                                            $('#currentClassroomName').text(currentClassroomName);
                                                            $('tbody').empty();
                                                            loadUsers(currentClassroomID);
                                                        },
                                                        error: function(error) {
                                                            alert("No class can be found with this classID");
                                                        }
                                                    });
                                                }
                                                else if (className !== '') {
                                                    currentClassroomName = className;

                                                    // AJAX request to get classID
                                                    $.ajax({
                                                        url: "/admin/classroom/getClassID",
                                                        type: "GET",
                                                        data: {
                                                            className: className
                                                        },
                                                        success: function(response) {
                                                            currentClassroomID = response.classID;
                                                            $('#currentClassroomName').text(currentClassroomName);
                                                            $('tbody').empty();
                                                            loadUsers(currentClassroomID);
                                                        },
                                                        error: function(error) {
                                                            alert("No class can be found with this className");
                                                        }
                                                    });
                                                }
                                                else {
                                                    currentClassroomName = "Not chosen";
                                                    currentClassroomID = null;
                                                    $('#currentClassroomName').text(currentClassroomName);
                                                }

                                                $('#currentClassroomName').text(currentClassroomName);
                                            });
                                           
                                        </script>

                                    </tbody>
                                </table>

                                <?= $pager->links() ?>

                            <?php else: ?>
                                <p>No users found</p>
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

    <!--begin::changeShowClassroomModal-->
    <div class="modal fade" id="changeShowClassroomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Change Classroom</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="classID" id="classID" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="className" id="className" row="1"></textarea> <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="ChangeButton" type="button" class="btn btn-danger" data-bs-dismiss="modal">Change</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::changeShowClassroomModal-->

    <!--begin::deleteUserModal-->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Delete User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Confirm to delete this user?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="deleteButton" type="button" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::deleteUserModal-->

    <!--begin::editUserModal-->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="username" id="username" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="first_name" id="first_name" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="last_name" id="last_name" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="classID_1" id="classID_1" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="classID_2" id="classID_2" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="classID_3" id="classID_3" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="classID_4" id="classID_4" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="classID_5" id="classID_5" row="1"></textarea> <br>
                    <textarea type="text" class="form-control form-control-solid ps-15" placeholder="classID_6" id="classID_6" row="1"></textarea> <br>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="editButton" type="button" class="btn btn-danger" data-bs-dismiss="modal">Edit</button>
                </div>
            </div>
        </div>
    </div>
    <!--end::editUserModal-->



    <!--end::Modal-->


    
<?= $this->endSection() ?>

