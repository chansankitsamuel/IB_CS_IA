<?= $this->extend("layouts/default") ?>
<?= $this->section("content") ?>

<title>Dashboard</title>
    <!--begin::Header-->
    <div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
        <!--begin::Container-->
        <div class="container-xxl d-flex align-items-center justify-content-between" id="kt_header_container">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-5 pb-lg-0" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                <!--begin::Heading-->
                <h1 class="text-dark fw-bold my-0 fs-2">Dashboard</h1>
                <!--end::Heading-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-line text-muted fw-bold fs-base my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="<?= base_url() ?>" class="text-muted">Home</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Dashboard</li>
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





    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Row-->
            <div class="row gy-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-8">
                    <!--begin::Line Chart-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <canvas id="lineChart"></canvas>
                    </div>
                    <!--end::Line Chart-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-4">
                    <!--begin::Bar Chart-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <canvas id="barChart" style="width:100%;height:100%;"></canvas>
                    </div>
                    <!--end::Bar Chart-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row gy-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-8">
                    <!--begin::Tables Widget 3-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder fs-3 mb-1">To-do List</span>
                                <span class="text-muted mt-1 fw-bold fs-7">Tasks in progress</span>
                            </h3>
                        </div>
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
                                            <th class="min-w-150px">Assignment</th>
                                            <th class="min-w-140px">Class</th>
                                            <th class="min-w-120px">Progress</th>
                                            <th class="min-w-130px">Due Date</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                        <?php foreach ($toDoList as $classID => $toDoItems): ?>
                                            <?php foreach ($toDoItems as $toDoItem): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <a href="<?= base_url('/student/assignment/'.$toDoItem['assignmentID']) ?>" class="text-dark fw-bolder text-hover-primary fs-6"><?= $toDoItem['assignmentName'] ?></a>
                                                                <span class="text-muted fw-bold text-muted d-block fs-7"><?= $toDoItem['topic'] ?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('/student/classroom/'.$classID) ?>" class="text-dark fw-bolder text-hover-primary d-block fs-6"><?= $classDetails[$classID]['className'] ?></a>
                                                        <span class="text-muted fw-bold text-muted d-block fs-7"><?= $classDetails[$classID]['teacherName'] ?></span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex flex-column w-80 me-2">
                                                            <div class="d-flex flex-stack mb-2">
                                                                <span class="text-muted me-2 fs-7 fw-bold"><?= $toDoItem['studentProgress'] ?>%</span>
                                                            </div>
                                                            <div class="progress h-6px w-50">
                                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $toDoItem['studentProgress'] ?>%" aria-valuenow="<?= $toDoItem['studentProgress'] ?>" aria-valuemin="0" aria-valuemax="100"></div>l
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="text-dark fw-bolder text-hover-primary d-block fs-6"><?= $toDoItem['dueDate'] ?></a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
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
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-4">
                    <!--begin::List Widget 1-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bolder text-dark">Class Analysis</span>
                                <span class="text-muted mt-1 fw-bold fs-7">Your current position</span>
                            </h3>
                            <div class="card-toolbar">
                                <!--begin::Menu-->
                                <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
                                                <rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
                                                <rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
                                                <rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
                                            </g>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                <!-- </button> -->
                                <!--begin::Menu 3-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
                                    <!--begin::Heading-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Sort</div>
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Ascending Order</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Decending Order</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu 3-->
                                <!--end::Menu-->
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-5">
                            <?php foreach($comparisonData as $classID => $data): ?>
                                <!--begin::Item-->
                                <div class="d-flex align-items-sm-center mb-7">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                        <div class="flex-grow-1 me-2">
                                            <a href="<?= base_url("/student/classroom/".$classID) ?>" class="text-gray-800 text-hover-primary fs-6 fw-bolder"><?= $classDetails[$classID]['className'] ?></a>
                                            <span class="text-muted fw-bold d-block fs-7"><?= $classDetails[$classID]['teacherName'] ?></span>
                                        </div>
                                        <span class="badge badge-light fw-bolder my-2">Compare to classmates: <?= $data ?> %</span>
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Item-->
                            <?php endforeach; ?>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::List Widget 1-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->




    <!--begin::ChartJS-->
    <script>

        const lineChart = document.getElementById('lineChart');

        const data_area = {
            datasets: [{
                label: 'Average Performance of Students (%)',
                data: <?= $dataset_line ?>,
                borderColor: 'rgb(255, 99, 132)',
                fill: false,
                tension: 0.1
            }],
        }; 

        new Chart(lineChart, {
            type: 'line',
            data: data_area,
            options: {
                scales: {
                    x: {
                        position: 'bottom',
                        title: {
                            display: true,
                            text: 'Time',
                        }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Average Performance of Students (%)',
                        }
                    },
                }, 
                plugins: {
                    title: {
                        display: true,
                        text: 'Overall Perforamce Over Time',
                    }, 
                    legend: {
                        display: false,
                    },
                }
            }
        });



        const barChart = document.getElementById('barChart');

        const data_bar = {
            labels: <?= $classNames ?>,
            datasets: [{
                label: 'Average Performance (%)',
                data: <?= $dataset_bar ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)', 
                    'rgba(255, 206, 86, 0.2)', 
                    'rgba(75, 192, 192, 0.2)', 
                    'rgba(153, 102, 255, 0.2)', 
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)', 
                    'rgb(255, 206, 86)', 
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)'
                ],
            }]
        };

        new Chart(barChart, {
            type: 'bar',
            data: data_bar,
            options: {
                scales: {
                    x: {
                        position: 'bottom',
                        title: {
                            display: true,
                            text: 'Class',
                        }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Average Performance (%)',
                        }
                    },
                }, 
                plugins: {
                    title: {
                        display: true,
                        text: 'Overall Perforamce of Classes',
                    }, 
                    legend: {
                        display: false,
                    },
                }
            }
        });

    </script>
    <!--end::ChartJS-->


<?= $this->endSection() ?>