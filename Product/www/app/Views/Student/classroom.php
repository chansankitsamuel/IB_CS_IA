<?= $this->extend("layouts/default") ?>
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
                    </li>
                    <li class="breadcrumb-item text-dark">Classroom</li>
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





    <div class="container d-md-flex align-items-stretch">
        <div id="content" class="p-4 p-md-5 pt-5">
            <!--begin::Content-->
            <div class="content d-flex flex-column flex-column-fluid maxWidth" id="kt_content">
                <!--begin::Container-->
                <div class="container-xxl" id="kt_content_container">
                <?php foreach (array_unique($topics) as $topic) : ?>
                        <?php if ($topic != null) : ?>
                            <!--begin::Tables Widget 3-->
                            <div class="card card-xl-stretch mb-5 mb-xl-8">
                                <!--begin::Header-->
                                <form action="<?= base_url('/student/assignmentTopicUpdate/'.$currentClassID) ?>" method="post">
                                    <input type="hidden" name="currentClassID" value="<?= $currentClassID ?>">
                                    <input type="hidden" name="oldTopic" value="<?= $topic ?>">
                                    <div class="card-header border-0 pt-5">
                                        <div class="d-flex align-items-center">
                                            <h3 class="card-title">
                                                <span class="card-label fw-bolder fs-3 mb-1">Topic:</span>
                                            </h3>
                                            <div class="flex-fill">
                                                <textarea class="form-control centered-textarea transparent-textarea" id="newTopic" name="newTopic" rows="1"><?= trim($topic) ?></textarea>
                                            </div>
                                        </div>
                                        <!-- <div class="card-toolbar" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="Click to update the topic"> -->
                                            <!-- <button class="btn btn-sm btn-light-primary" type="submit"> -->
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                                            <!-- <span class="svg-icon svg-icon-3"></span> -->
                                            <!--end::Svg Icon-->
                                            <!-- Edit</button> -->
                                        <!-- </div> -->
                                    </div>
                                </form>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-3">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                            <!--begin::Table head-->
                                            <thead>
                                                <tr class="fw-bolder text-muted">
                                                    <th class="w-25px">
                                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check" />
                                                        </div>
                                                    </th>
                                                    <th class="min-w-150px">Assignments</th>
                                                    <th class="min-w-140px">Due Date</th>
                                                    <th class="min-w-120px">Progress</th>
                                                    <th class="min-w-100px">Mark</th>
                                                    <!-- <th class="min-w-100px text-end">Actions</th> -->
                                                </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody>
                                                <?php foreach ($loadAssignments as $loadAssignment) : ?>
                                                    <?php if ($loadAssignment['topic'] == $topic) : ?>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input widget-9-check" type="checkbox" value="1" />
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="d-flex justify-content-start flex-column">
                                                                        <a href="<?= base_url('student/assignment/'.$loadAssignment['assignmentID']); ?>" class="text-dark fw-bolder text-hover-primary fs-6"><?= $loadAssignment['assignmentName'] ?></a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6"><?= $loadAssignment['dueDate'] ?></a>
                                                                <!-- <span class="text-muted fw-bold text-muted d-block fs-7">Web, UI/UX Design</span> -->
                                                            </td>
                                                            <td class="text-end">
                                                                <div class="d-flex flex-column w-100 me-2">
                                                                    <div class="d-flex flex-stack mb-2">
                                                                        <span class="text-muted me-2 fs-7 fw-bold"><?= $loadAssignment['studentProgress'] ?>%</span>
                                                                    </div>
                                                                    <div class="progress h-6px w-100">
                                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $loadAssignment['studentProgress'] ?>%" aria-valuenow="<?= $loadAssignment['studentProgress'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6"><?= $loadAssignment['totalMark'] ?>/<?= $loadAssignment['maxMark'] ?></a>
                                                                <!-- <span class="text-muted fw-bold text-muted d-block fs-7">Web, UI/UX Design</span> -->
                                                            </td>
                                                            <!-- <td> -->
                                                                <!-- <div class="d-flex justify-content-end flex-shrink-0"> -->
                                                                    <!-- <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"> -->
                                                                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                                                                        <!-- <span class="svg-icon svg-icon-3">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black" />
                                                                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black" />
                                                                            </svg>
                                                                        </span> -->
                                                                        <!--end::Svg Icon-->
                                                                    <!-- </a> -->
                                                                    <!-- <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"> -->
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                                                        <!-- <span class="svg-icon svg-icon-3">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
                                                                                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
                                                                                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
                                                                            </svg>
                                                                        </span> -->
                                                                        <!--end::Svg Icon-->
                                                                    <!-- </a> -->
                                                                <!-- </div> -->
                                                            <!-- </td> -->
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table container-->
                                </div>
                                <!--begin::Body-->
                            </div>
                            <!--end::Tables Widget 3-->
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <!--end::Container-->
            </div>
            <!--end::Content-->
        </div>
        <?= $this->include('Layouts/sidebar') ?>
    </div>



    
<?= $this->endSection() ?>