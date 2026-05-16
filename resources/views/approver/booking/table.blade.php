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
                            <h3 class="mb-0">Kendaraan</h3>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Persetujuan Pemesanan Kendaraan</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Notifikasi Status -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Kendaraan</th>
                                        <th>Driver</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Peran Anda</th>
                                        <th>Status Saat Ini</th>
                                        <th style="width: 220px; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $index => $booking)
                                        @php
                                            // Cek posisi approver yang sedang login
                                            $isApprover1 = ($booking->approver_1_id == auth()->id());
                                            $isApprover2 = ($booking->approver_2_id == auth()->id());
                                        @endphp

                                        <tr class="align-middle">
                                            <td>{{ $index + 1 }}.</td>
                                            <td>
                                                <strong>{{ $booking->vehicle->name ?? 'N/A' }}</strong>
                                                <br><small
                                                    class="text-muted">{{ $booking->vehicle->plate_number ?? '' }}</small>
                                            </td>
                                            <td>{{ $booking->driver->name ?? 'N/A' }}</td>
                                            <td>
                                                <small>
                                                    <strong>Mulai:</strong>
                                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}<br>
                                                    <strong>Selesai:</strong>
                                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($isApprover1)
                                                    <span class="badge bg-info text-dark">Atasan 1</span>
                                                @endif
                                                @if($isApprover2)
                                                    <span class="badge bg-md bg-secondary">Atasan 2</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($booking->status == 'pending')
                                                    <span class="badge text-bg-warning">Menunggu Atasan 1</span>
                                                @elseif($booking->status == 'approved_lvl_1')
                                                    <span class="badge text-bg-primary">Disetujui Atasan 1</span>
                                                @elseif($booking->status == 'approved_final')
                                                    <span class="badge text-bg-success">Selesai (Approved)</span>
                                                @else
                                                    <span class="badge text-bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="text-center">

                                                {{-- LOGIKA AKSI UNTUK ATASAN 1 --}}
                                                @if($isApprover1)
                                                    @if($booking->status == 'pending')
                                                        <!-- Tombol Setuju / Tolak jika masih pending -->
                                                        <div class="btn-group" role="group">
                                                            <form action="{{ route('approver.booking.approve', $booking->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success"><i
                                                                        class="fas fa-check"></i> Setuju</button>
                                                            </form>
                                                            <form action="{{ route('approver.booking.reject', $booking->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('Tolak pesanan ini?')"><i
                                                                        class="fas fa-times"></i> Tolak</button>
                                                            </form>
                                                        </div>
                                                    @elseif($booking->status == 'approved_lvl_1')
                                                        <!-- BISA BATAL: Karena Atasan 2 BELUM melakukan approval (status masih approved_lvl_1) -->
                                                        <form action="{{ route('approver.booking.cancel', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning"
                                                                onclick="return confirm('Batalkan persetujuan Anda?')">
                                                                <i class="fas fa-undo"></i> Batal Setuju
                                                            </button>
                                                        </form>
                                                    @else
                                                        <!-- TIDAK BISA DIOTAK-ATIK: Jika sudah approved_final atau rejected -->
                                                        <span class="text-muted"><small>Keputusan Final</small></span>
                                                    @endif
                                                @endif

                                                {{-- LOGIKA AKSI UNTUK ATASAN 2 --}}
                                                @if($isApprover2)
                                                    @if($booking->status == 'approved_lvl_1')
                                                        <!-- Tombol Setuju / Tolak baru muncul setelah Atasan 1 menyetujui -->
                                                        <div class="btn-group" role="group">
                                                            <form action="{{ route('approver.booking.approve', $booking->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success"><i
                                                                        class="fas fa-check"></i> Setuju (Final)</button>
                                                            </form>
                                                            <form action="{{ route('approver.booking.reject', $booking->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('Tolak pesanan ini?')"><i
                                                                        class="fas fa-times"></i> Tolak</button>
                                                            </form>
                                                        </div>
                                                    @elseif($booking->status == 'pending')
                                                        <span class="text-muted"><small>Menunggu Atasan 1</small></span>
                                                    @elseif($booking->status == 'approved_final')
                                                        <!-- BISA BATAL: Atasan 2 bisa membatalkan miliknya sendiri karena statusnya baru saja dirubah ke approved_final olehnya (Atasan 1 sudah setuju, tapi Atasan 2 ingin menarik kembali) -->
                                                        <form action="{{ route('approver.booking.cancel', $booking->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning"
                                                                onclick="return confirm('Batalkan persetujuan Final Anda?')">
                                                                <i class="fas fa-undo"></i> Batal Setuju Final
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted"><small>Ditolak</small></span>
                                                    @endif
                                                @endif

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Tidak ada pengajuan pemesanan
                                                yang membutuhkan persetujuan Anda saat ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
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
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        const sales_chart_options = {
            series: [
                {
                    name: "Digital Goods",
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: "Electronics",
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 300,
                type: "area",
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ["#0d6efd", "#20c997"],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: "smooth",
            },
            xaxis: {
                type: "datetime",
                categories: [
                    "2023-01-01",
                    "2023-02-01",
                    "2023-03-01",
                    "2023-04-01",
                    "2023-05-01",
                    "2023-06-01",
                    "2023-07-01",
                ],
            },
            tooltip: {
                x: {
                    format: "MMMM yyyy",
                },
            },
        };

        const sales_chart = new ApexCharts(
            document.querySelector("#revenue-chart"),
            sales_chart_options,
        );
        sales_chart.render();
    </script>
    <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
        integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
        integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>
    <!-- jsvectormap -->
    <script>
        const visitorsData = {
            US: 398, // USA
            SA: 400, // Saudi Arabia
            CA: 1000, // Canada
            DE: 500, // Germany
            FR: 760, // France
            CN: 300, // China
            AU: 700, // Australia
            BR: 600, // Brazil
            IN: 800, // India
            GB: 320, // Great Britain
            RU: 3000, // Russia
        };

        // World map by jsVectorMap
        const map = new jsVectorMap({
            selector: "#world-map",
            map: "world",
        });

        // Sparkline charts
        const option_sparkline1 = {
            series: [
                {
                    data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
                },
            ],
            chart: {
                type: "area",
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ["#DCE6EC"],
        };

        const sparkline1 = new ApexCharts(
            document.querySelector("#sparkline-1"),
            option_sparkline1,
        );
        sparkline1.render();

        const option_sparkline2 = {
            series: [
                {
                    data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
                },
            ],
            chart: {
                type: "area",
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ["#DCE6EC"],
        };

        const sparkline2 = new ApexCharts(
            document.querySelector("#sparkline-2"),
            option_sparkline2,
        );
        sparkline2.render();

        const option_sparkline3 = {
            series: [
                {
                    data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
                },
            ],
            chart: {
                type: "area",
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: "straight",
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ["#DCE6EC"],
        };

        const sparkline3 = new ApexCharts(
            document.querySelector("#sparkline-3"),
            option_sparkline3,
        );
        sparkline3.render();
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>