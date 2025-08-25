<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'LSUC MIS')) - Lecturer</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    
    <!-- Custom styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-light">
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="border-right" id="sidebar-wrapper" style="background-color: #fff;">
            <div class="sidebar-heading text-center py-4">
                <img src="{{ asset('img/logo.png') }}" alt="LSUC Logo" class="img-fluid" style="max-height: 50px;">
                <h6 class="mt-2 text-muted">Lecturer Portal</h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('lecturer.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('lecturer.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="{{ route('lecturer.courses') }}" class="list-group-item list-group-item-action {{ request()->routeIs('lecturer.courses*') ? 'active' : '' }}">
                    <i class="fas fa-book mr-2"></i> My Courses
                </a>
                <a href="{{ route('lecturer.assessments') }}" class="list-group-item list-group-item-action {{ request()->routeIs('lecturer.assessments*') ? 'active' : '' }}">
                    <i class="fas fa-tasks mr-2"></i> Assessments
                </a>
                <a href="{{ route('lecturer.attendance') }}" class="list-group-item list-group-item-action {{ request()->routeIs('lecturer.attendance*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check mr-2"></i> Attendance
                </a>
                <a href="{{ route('lecturer.students') }}" class="list-group-item list-group-item-action {{ request()->routeIs('lecturer.students*') ? 'active' : '' }}">
                    <i class="fas fa-users mr-2"></i> Students
                </a>
                <a href="{{ route('lecturer.results') }}" class="list-group-item list-group-item-action {{ request()->routeIs('lecturer.results*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line mr-2"></i> Results
                </a>
                <a href="{{ route('lecturer.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('lecturer.profile*') ? 'active' : '' }}">
                    <i class="fas fa-user mr-2"></i> My Profile
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <button class="btn btn-link" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" 
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" 
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="notificationsDropdown">
                                <h6 class="dropdown-header">Notifications</h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2023</div>
                                        <span class="font-weight-bold">New assignment submitted!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Notifications</a>
                            </div>
                        </li>
                        
                        <div class="topbar-divider d-none d-sm-block"></div>
                        
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle" src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=fff&background=4e73df' }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('lecturer.profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main Content -->
            <div class="container-fluid px-4 py-4">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    
    <script>
        // Toggle the side navigation
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('menu-toggle').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('wrapper').classList.toggle('toggled');
            });
            
            // Enable tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
        
        // Auto-hide alerts after 5 seconds
        window.setTimeout(function() {
            $('.alert').fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 5000);
    </script>
</body>
</html>
