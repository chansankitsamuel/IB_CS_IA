<?= $this->extend("layouts/default") ?>
<?= $this->section("content") ?>

<title>Analysis</title>
    <!--begin::Header-->
    <div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
        <!--begin::Container-->
        <div class="container-xxl d-flex align-items-center justify-content-between" id="kt_header_container">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap me-lg-2 pb-5 pb-lg-0" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
                <!--begin::Heading-->
                <h1 class="text-dark fw-bold my-0 fs-2">Analysis</h1>
                <!--end::Heading-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-line text-muted fw-bold fs-base my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="<?= base_url() ?>" class="text-muted">Home</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Analysis</li>
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
                                        <span class="card-label fw-bolder fs-3 mb-1">Bubble Chart</span>
                                    </h3>
                                    <h3 class="card-title align-items-end flex-column">
                                        <span class="card-label fw-bold fs-6 mb-1"></span>
                                    </h3>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-3">
                                <!--begin::Chart-->
                                    <div>
                                        <canvas id="bubbleChart"></canvas>
                                    </div>
                                <!--end::Chart-->
                                </div>
                                <!--begin::Body-->
                            </div>
                            <!--end::Questions Widget 1-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row gy-5 g-xl-11">
                            <!--begin::Questions Widget 1-->
                            <div class="card card-xll-stretch mb-5 mb-xl-8">
                                <!--begin::Header-->
                                <div class="card-header border-0 pt-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder fs-3 mb-1">Area Chart</span>
                                    </h3>
                                    <h3 class="card-title align-items-end flex-column">
                                        <span class="card-label fw-bold fs-6 mb-1"></span>
                                    </h3>
                                </div>
                                <!--end::Header-->
                                <!--begin::Body-->
                                <div class="card-body py-3">
                                    <!--begin::Chart-->
                                    <div>
                                            <canvas id="areaChart"></canvas>
                                        </div>
                                    <!--end::Chart-->
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
        <?= $this->include('Layouts/sidebar') ?>
    </div>



<!--begin::ChartJS-->
<script>
    const bubbleChart = document.getElementById('bubbleChart');

    const data_bubble = {
        datasets: [{
            label: <?= $classNames ?>[0],
            data: <?= $dataset_bubble ?>[<?= $classIDs ?>[0]],
            backgroundColor: 'rgb(255, 99, 132)', 
            borderColor: 'rgb(255, 99, 132)', 
            borderWidth: 3, 
        }
        ,{
            label:  <?= $classNames ?>[1],
            data: <?= $dataset_bubble ?>[<?= $classIDs ?>[1]],
            backgroundColor: 'rgb(54, 162, 235)', 
            borderColor: 'rgb(54, 162, 235)', 
            borderWidth: 3, 
        }
        ,{
            label:  <?= $classNames ?>[2],
            data: <?= $dataset_bubble ?>[<?= $classIDs ?>[2]],
            backgroundColor: 'rgb(255, 206, 86)', 
            borderColor: 'rgb(255, 206, 86)', 
            borderWidth: 3, 
        }
        ,{
            label:  <?= $classNames ?>[3],
            data: <?= $dataset_bubble ?>[<?= $classIDs ?>[3]],
            backgroundColor: 'rgb(75, 192, 192)', 
            borderColor: 'rgb(75, 192, 192)', 
            borderWidth: 3, 
        }
        ,{
            label:  <?= $classNames ?>[4],
            data: <?= $dataset_bubble ?>[<?= $classIDs ?>[4]],
            backgroundColor: 'rgb(153, 102, 255)', 
            borderColor: 'rgb(153, 102, 255)', 
            borderWidth: 3, 
        }
        ,{
            label:  <?= $classNames ?>[5],
            data: <?= $dataset_bubble ?>[<?= $classIDs ?>[5]],
            backgroundColor: 'rgb(255, 159, 64)', 
            borderColor: 'rgb(255, 159, 64)', 
            borderWidth: 3, 
        }]
    }; 

    new Chart(bubbleChart, {
        type: 'bubble',
        data: data_bubble,
        options: {
            scales: {
                x: {
                    type: 'category',
                    position: 'bottom',
                    title: {
                        display: true,
                        text: 'Topics',
                    }
                },
                y: {
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Performance (%)',
                    }
                },
            }, 
            plugins: {
                title: {
                    display: true,
                    text: 'Performance over Topics',
                }
            }, 
        }
    });



    const areaChart = document.getElementById('areaChart');

    const data_area = {
        datasets: [{
            label: <?= $classNames ?>[0],
            data: <?= $dataset_area ?>[<?= $classIDs ?>[0]],
            backgroundColor: 'rgb(255, 99, 132, 0.3)',
            fill: true,
            tension: 0.1
        }
        ,{
            label:  <?= $classNames ?>[1],
            data: <?= $dataset_area ?>[<?= $classIDs ?>[1]],
            backgroundColor: 'rgb(54, 162, 235, 0.3)',
            fill: true,
            tension: 0.1
        }
        ,{
            label:  <?= $classNames ?>[2],
            data: <?= $dataset_area ?>[<?= $classIDs ?>[2]],
            backgroundColor: 'rgb(255, 206, 86, 0.3)',
            fill: true,
            tension: 0.1
        }
        ,{
            label:  <?= $classNames ?>[3],
            data: <?= $dataset_area ?>[<?= $classIDs ?>[3]],
            backgroundColor: 'rgb(75, 192, 192, 0.3)',
            fill: true,
            tension: 0.1
        }
        ,{
            label:  <?= $classNames ?>[4],
            data: <?= $dataset_area ?>[<?= $classIDs ?>[4]],
            backgroundColor: 'rgb(153, 102, 255, 0.3)',
            fill: true,
            tension: 0.1
        }
        ,{
            label:  <?= $classNames ?>[5],
            data: <?= $dataset_area ?>[<?= $classIDs ?>[5]],
            backgroundColor: 'rgb(255, 159, 64, 0.3)',
            fill: true,
            tension: 0.1
        }]
    }; 

    new Chart(areaChart, {
        type: 'line',
        data: data_area,
        options: {
            scales: {
                x: {
                    type: 'linear',
                    position: 'bottom',
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Cumulative Percentage of Assignments',
                    }
                },
                y: {
                    min: 0,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Cumulative Percentage of Performance',
                    }
                },
            }, 
            plugins: {
                title: {
                    display: true,
                    text: 'Performance in Assignments',
                }
            }
        }
    });
</script>

<!--end::ChartJS-->

    
<?= $this->endSection() ?>