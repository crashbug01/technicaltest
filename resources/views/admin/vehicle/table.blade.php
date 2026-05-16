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
                    <div class="card card-outline card-primary mb-4">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h3 class="card-title">Daftar Kendaraan</h3>

                            <!-- Bagian Kanan: Fitur Pencarian & Tombol Tambah Data -->
                            <div class="d-flex align-items-center gap-2 ms-auto">
                                <!-- Form Pencarian -->
                                <form action="{{ url()->current() }}" method="GET" class="d-flex m-0">
                                    <!-- Mempertahankan parameter sorting yang sedang aktif saat mencari -->
                                    <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'id' }}">
                                    <input type="hidden" name="sort_order" value="{{ $sortOrder ?? 'desc' }}">

                                    <div class="input-group input-group-sm" style="width: 260px;">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama atau nomor plat..." value="{{ $search ?? '' }}">
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        @if(!empty($search))
                                            <a href="{{ url()->current() }}"
                                                class="btn btn-outline-danger btn-sm d-flex align-items-center">Reset</a>
                                        @endif
                                    </div>
                                </form>

                                <!-- Tombol Tambah Data -->
                                <a href="{{ route('vehicle.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Kendaraan
                                </a>
                            </div>
                        </div> <!-- /.card-header -->

                        <div class="card-body p-0 table-responsive">
                            <table class="table table-bordered table-striped m-0">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">#</th>

                                        <!-- Fitur Sort: Nama Kendaraan -->
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => (($sortBy ?? '') == 'name' && ($sortOrder ?? '') == 'asc') ? 'desc' : 'asc']) }}"
                                                class="text-decoration-none text-dark d-block w-100">
                                                Nama Kendaraan
                                                @if(($sortBy ?? '') == 'name')
                                                    <i
                                                        class="fas fa-sort-alpha-{{ ($sortOrder ?? '') == 'asc' ? 'down' : 'up' }} ms-1 text-primary"></i>
                                                @else
                                                    <i class="fas fa-sort text-muted ms-1"></i>
                                                @endif
                                            </a>
                                        </th>

                                        <!-- Fitur Sort: Nomor Plat -->
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'plate_number', 'sort_order' => (($sortBy ?? '') == 'plate_number' && ($sortOrder ?? '') == 'asc') ? 'desc' : 'asc']) }}"
                                                class="text-decoration-none text-dark d-block w-100">
                                                Nomor Plat
                                                @if(($sortBy ?? '') == 'plate_number')
                                                    <i
                                                        class="fas fa-sort-alpha-{{ ($sortOrder ?? '') == 'asc' ? 'down' : 'up' }} ms-1 text-primary"></i>
                                                @else
                                                    <i class="fas fa-sort text-muted ms-1"></i>
                                                @endif
                                            </a>
                                        </th>

                                        <th>Jenis</th>
                                        <th>Kepemilikan</th>

                                        <!-- Fitur Sort: Konsumsi BBM -->
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'fuel_consumption', 'sort_order' => (($sortBy ?? '') == 'fuel_consumption' && ($sortOrder ?? '') == 'asc') ? 'desc' : 'asc']) }}"
                                                class="text-decoration-none text-dark d-block w-100">
                                                Konsumsi BBM
                                                @if(($sortBy ?? '') == 'fuel_consumption')
                                                    <i
                                                        class="fas fa-sort-numeric-{{ ($sortOrder ?? '') == 'asc' ? 'down' : 'up' }} ms-1 text-primary"></i>
                                                @else
                                                    <i class="fas fa-sort text-muted ms-1"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th style="width: 160px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vehicles as $index => $vehicle)
                                        <tr class="align-middle">
                                            <!-- Nomor urut otomatis yang sinkron dengan halaman paginasi -->
                                            <td>{{ $vehicles->firstItem() + $index }}.</td>
                                            <td><strong>{{ $vehicle->name }}</strong></td>
                                            <td><span class="badge text-bg-secondary">{{ $vehicle->plate_number }}</span>
                                            </td>
                                            <td>
                                                @if($vehicle->type === 'person')
                                                    <span class="badge text-bg-info">Angkutan Orang</span>
                                                @else
                                                    <span class="badge text-bg-warning">Angkutan Barang</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($vehicle->ownership === 'owned')
                                                    <span class="badge text-bg-success">Milik Perusahaan</span>
                                                @else
                                                    <span class="badge text-bg-dark">Sewa / Rental</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="fas fa-gas-pump text-muted me-1"></i>
                                                {{ $vehicle->fuel_consumption }} Km/Liter
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('vehicle.edit', $vehicle->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>

                                                    <form action="{{ route('vehicle.destroy', $vehicle->id) }}"
                                                        method="POST" class="m-0"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-car-crash fa-2x mb-2 d-block"></i>
                                                Data kendaraan tidak ditemukan atau belum terisi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> <!-- /.card-body -->

                        <!-- Bagian Footer: Navigasi Halaman (Pagination) Dinamis Kustom -->
                        <div class="card-footer clearfix">
                            @if ($vehicles->hasPages())
                                <ul class="pagination pagination-sm m-0 float-end">
                                    {{-- Tombol Halaman Sebelumnya --}}
                                    @if ($vehicles->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $vehicles->appends(request()->all())->previousPageUrl() }}"
                                                rel="prev">&laquo;</a></li>
                                    @endif

                                    {{-- Nomor Urutan Angka Halaman --}}
                                    @foreach ($vehicles->getUrlRange(1, $vehicles->lastPage()) as $page => $url)
                                        @if ($page == $vehicles->currentPage())
                                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $vehicles->appends(request()->all())->getUrlRange($page, $page)[$page] }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Tombol Halaman Selanjutnya --}}
                                    @if ($vehicles->hasMorePages())
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $vehicles->appends(request()->all())->nextPageUrl() }}"
                                                rel="next">&raquo;</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                                    @endif
                                </ul>
                            @endif
                        </div>
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