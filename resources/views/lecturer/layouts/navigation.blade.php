<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <a class="navbar-brand" href="{{ route('lecturer.dashboard') }}">
            <i class="fas fa-graduation-cap me-2"></i>
            LSUC MIS - Lecturer
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}" 
                       href="{{ route('lecturer.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('lecturer.courses.*') ? 'active' : '' }}" 
                       href="{{ route('lecturer.courses.index') }}">
                        <i class="fas fa-book me-1"></i> My Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('lecturer.assessments.*') ? 'active' : '' }}" 
                       href="{{ route('lecturer.assessments.index') }}">
                        <i class="fas fa-tasks me-1"></i> Assessments
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('lecturer.attendance.*') ? 'active' : '' }}" 
                       href="{{ route('lecturer.attendance.index') }}">
                        <i class="fas fa-calendar-check me-1"></i> Attendance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('lecturer.results.*') ? 'active' : '' }}" 
                       href="{{ route('lecturer.results.index') }}">
                        <i class="fas fa-chart-bar me-1"></i> Results
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('lecturer.students.*') ? 'active' : '' }}" 
                       href="{{ route('lecturer.students.index') }}">
                        <i class="fas fa-users me-1"></i> Students
                    </a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-bs-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge bg-danger badge-number">3</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg dropdown-menu-right">
                        <li class="dropdown-header">You have 3 notifications</li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-envelope me-2"></i> 2 new assignments submitted
                                <span class="float-end text-muted text-sm">3 mins</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-users me-2"></i> 3 new students enrolled
                                <span class="float-end text-muted text-sm">12 hours</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-file me-2"></i> New report available
                                <span class="float-end text-muted text-sm">1 day</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="dropdown-footer">
                            <a href="#">View all notifications</a>
                        </li>
                    </ul>
                </li>

                <!-- User Account Menu -->
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                             class="user-image img-circle elevation-2" alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            <img src="{{ Auth::user()->photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                                 class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ Auth::user()->name }}
                                <small>Lecturer</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-4 text-center">
                                    <a href="#">Courses</a>
                                </div>
                                <div class="col-4 text-center">
                                    <a href="#">Students</a>
                                </div>
                                <div class="col-4 text-center">
                                    <a href="#">Results</a>
                                </div>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <a href="{{ route('lecturer.profile.edit') }}" class="btn btn-default btn-flat">
                                <i class="fas fa-user me-1"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-default btn-flat float-end">
                                    <i class="fas fa-sign-out-alt me-1"></i> Sign out
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<!-- Add padding to the body to account for the fixed navbar -->
<style>
    body {
        padding-top: 70px;
    }
    @media (max-width: 991.98px) {
        body {
            padding-top: 60px;
        }
    }
</style>
