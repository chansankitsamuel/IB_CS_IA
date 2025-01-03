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
                                        <span class="card-label fw-bolder fs-3 mb-1">Users Table</span>
                                    </h3>
                                    <h3 class="card-title align-items-end flex-column">
                                        <span class="card-label fw-bold fs-6 mb-1"></span>
                                    </h3>
                                </div>
                                <div>
                                    <a href="/admin/users/showAddUser" class="smallButton btn-success float-right me-5">Add</a>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-3">
                                    
                                <?php if ($teachers != null || $students != null): ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Last Active</th>
                                            <th scope="col">Created At</th>
                                            <th scope="col">Updated At</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php if ($teachers != null): ?>
                                        <?php foreach ($teachers as $teacher): ?>
                                            <tr>
                                                <th scope="row"><?= $teacher['id'] ?? '' ?></th>
                                                <td><?= $teacher['username'] ?? '' ?></td>
                                                <td><?= $teacher['last_active'] ?? '' ?></td>
                                                <td><?= $teacher['created_at'] ?? '' ?></td>
                                                <td><?= $teacher['updated_at'] ?? '' ?></td>
                                                <td>
                                                    <a href="/admin/users/showEditUser/<?= $teacher['id'] ?>" class="verySmallButton btn-primary">Edit</a>
                                                    <a href="/admin/users/deleteUser/<?= $teacher['id'] ?>" onclick="confirmDelete(<?= $teacher['id']?>, 'user', '/admin/users/delete/')" class="verySmallButton btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <?php if ($students != null): ?>
                                        <?php foreach ($students as $student): ?>
                                            <tr>
                                                <th scope="row"><?= $student['id'] ?? '' ?></th>
                                                <td><?= $student['username'] ?? '' ?></td>
                                                <td><?= $student['last_active'] ?? '' ?></td>
                                                <td><?= $student['created_at'] ?? '' ?></td>
                                                <td><?= $student['updated_at'] ?? '' ?></td>
                                                <td>
                                                    <a href="/admin/users/showEditUser/<?= $student['id'] ?>" class="verySmallButton btn-primary">Edit</a>
                                                    <a href="/admin/users/deleteUser/<?= $student['id'] ?>" onclick="confirmDelete(<?= $student['id']?>, 'user', '/admin/users/delete/')" class="verySmallButton btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    </tbody>
                                </table>


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


    
<?= $this->endSection() ?>