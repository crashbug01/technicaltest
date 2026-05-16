<!doctype html>
<html lang="en">
<!--begin::Head-->
@include('admin.includes.header')
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        @include('admin.includes.topbar')
        <!--end::Header-->
        <!--begin::Sidebar-->
        @include('admin.includes.sidebar')
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                    </div>
                    <!--end::Row-->
                    <div class="card mb-4">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Log Status Persetujuan Kendaraan</h3>
                                <a href="{{ route('admin.booking.index') }}"
                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Lihat
                                    Semua Data</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <p class="d-flex flex-column">
                                    <!-- Menampilkan total akumulasi booking -->
                                    <span class="fw-bold fs-5">{{ $totalPending + $totalApproved }} Pesanan</span>
                                    <span>Total Distribusi Operasional</span>
                                </p>
                                <p class="ms-auto d-flex flex-column text-end">
                                    <span class="text-warning">
                                        <i class="bi bi-clock-history"></i> {{ $totalPending }} Pending
                                    </span>
                                    <span class="text-secondary">Butuh Tindakan Segera</span>
                                </p>
                            </div> <!-- /.d-flex -->

                            <!-- Container Tempat Grafik Digambar -->
                            <div class="position-relative mb-4">
                                <canvas id="approval-status-chart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>

                            <!-- Legenda Custom Indikator Status -->
                            <div class="d-flex flex-row justify-content-center gap-3">
                                <span> <i class="bi bi-square-fill text-warning"></i> Pending </span>
                                <span> <i class="bi bi-square-fill text-info"></i> Approved Lvl 1 </span>
                                <span> <i class="bi bi-square-fill text-success"></i> Approved Final </span>
                                <span> <i class="bi bi-square-fill text-danger"></i> Rejected </span>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex gap-2">
                        <!-- Tombol Export Biasa -->
                        <a href="{{ route('admin.booking.export') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-file-excel"></i> Export Semua Data
                        </a>

                        <!-- Tombol Export Berkala Bulanan Baru -->
                        <a href="{{ route('admin.booking.export_periodic') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-calendar-alt"></i> Export Laporan Periodik (Bulanan)
                        </a>
                    </div>
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        @include('admin.includes.footer')
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js"
        integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src={{ asset('assets/adminlte/js/adminlte.js') }}></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!-- OPTIONAL SCRIPTS -->
    <!-- sortablejs -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
        integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script>
    <!-- sortablejs -->
    <script>
        const connectedSortables =
            document.querySelectorAll(".connectedSortable");
        connectedSortables.forEach((connectedSortable) => {
            let sortable = new Sortable(connectedSortable, {
                group: "shared",
                handle: ".card-header",
            });
        });

        const cardHeaders = document.querySelectorAll(
            ".connectedSortable .card-header",
        );
        cardHeaders.forEach((cardHeader) => {
            cardHeader.style.cursor = "move";
        });
    </script>
    <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
    <!-- ChartJS -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Mengambil data murni array angka dari controller [pending, approved_lvl_1, approved_final, rejected]
            const approvalData = {!! $statusCounts ?? '[0, 0, 0, 0]' !!};

            const ctxApproval = document.getElementById('approval-status-chart').getContext('2d');
            new Chart(ctxApproval, {
                type: 'bar', // Menggunakan grafik batang vertikal seperti template asli
                data: {
                    labels: ['Pending', 'Approved Lvl 1', 'Approved Final', 'Rejected'],
                    datasets: [{
                        label: 'Jumlah Request',
                        data: approvalData,
                        backgroundColor: [
                            '#ffc107', // Kuning untuk Pending
                            '#0dcaf0', // Biru Muda/Info untuk Approved Lvl 1
                            '#198754', // Hijau untuk Approved Final
                            '#dc3545'  // Merah untuk Rejected
                        ],
                        borderRadius: 4,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Dimatikan karena kita sudah membuat komponen legenda custom HTML di bawah canvas
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1, // Memaksa grafik menggunakan angka bulat (bukan desimal)
                                precision: 0
                            }
                        },
                        x: {
                            grid: {
                                display: false // Menghilangkan garis vertikal di latar belakang agar lebih clean
                            }
                        }
                    }
                }
            });
        });
    </script>
    <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
        integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
        integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>
    <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // 1. Render Grafik Tren Bulanan
        const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'line', // Grafik garis cocok untuk melihat tren dari bulan ke bulan
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Jumlah Pesanan Masuk',
                    data: {!! json_encode($values) !!},
                    borderColor: '#4e73df',
                    tension: 0.3
                }]
            }
        });

        // 2. Render Grafik Pemakaian Kendaraan (Bar Chart)
        const ctxVehicle = document.getElementById('vehicleUsageChart').getContext('2d');
        new Chart(ctxVehicle, {
            type: 'bar', // Grafik Batang
            data: {
                labels: {!! $vehicleLabels !!}, // Membaca data JSON langsung
                datasets: [{
                    label: 'Frekuensi Digunakan (Approved)',
                    data: {!! $vehicleValues !!},
                    backgroundColor: '#1cc88a'
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>