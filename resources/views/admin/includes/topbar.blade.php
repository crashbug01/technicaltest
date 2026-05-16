<nav class="app-header navbar navbar-expand bg-body">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->

        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/adminlte/assets/img/avatar6.png') }}"
                        class="user-image rounded-circle shadow" alt="User Image">
                    <!-- Mengubah nama statis menjadi dinamis -->
                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> <!--begin::User Image-->
                    <li class="user-header text-bg-primary">
                        <img src="{{ asset('assets/adminlte/assets/img/avatar6.png') }}" class="rounded-circle shadow"
                            alt="User Image">
                        <p>
                            <!-- Mengubah nama dan role menjadi dinamis -->
                            {{ auth()->user()->name }} - {{ ucfirst(auth()->user()->role) }}
                        </p>
                    </li> <!--end::User Image-->

                    <!--begin::Menu Footer-->
                    <li class="user-footer">
                        <!-- Tombol Logout yang memicu Form di bawahnya -->
                        <a href="#" class="btn btn-default btn-flat float-end"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sign out
                        </a>

                        <!-- Form Hidden untuk Logout Laravel -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li> <!--end::User Menu Dropdown-->
            <!--end::Fullscreen Toggle-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>