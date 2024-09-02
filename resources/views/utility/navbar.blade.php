
<nav class="navbar navbar-light navbar-expand-lg mb-5" style="background-color: #e3f2fd;">
    <div class="container">
        <a class="navbar-brand mr-auto" href="{{ route('dashboard') }}">FYP AGILE </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
                
            {{-- <ul class="navbar-nav">
                @guest

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('registration') }}">Register</a>
                </li>

                @else

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                </li>

                @endguest
            </ul> --}}
            <ul class="navbar-nav ml-auto">
                @if (Session::get('role_id')==0)
                        <li class="nav-item ">
                            <a class="nav-link" href="{{ route('dashboard') }}">Admin</a>
                        </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Students</a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('importstudents') }}">Import PSM1 Students</a></li>
                        <li><a class="dropdown-item" href="{{ route('importstudents') }}">Import PSM2 Students</a></li>
                        <li><a class="dropdown-item" href="{{ route('get.supervisors')}}">Assign Student</a></li>
                        </ul>
                    </li> -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">User</a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('importusers') }}">Import Users</a></li>
                        <li><a class="dropdown-item" href="{{ route('registration') }}">Register User</a></li>
                        <li><a class="dropdown-item" href="{{ route('listusers') }}">List User</a></li>
                        
                        </ul>
                    </li>
                    @elseif(Session::get('role_id')==1) 
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('change-password') }}">Coordinator</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">PSM 1</a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('importpsm1') }}">Import PSM1 Students</a></li>
                        <li><a class="dropdown-item" href="{{ route('listPSM1') }}">List Students</a></li>
                        <li><a class="dropdown-item" href="{{ route('get.supervisors') }}">Assign Supervisor</a></li>
                        <li><a class="dropdown-item" href="{{ route('proposal.get.panel')}}">Assign Proposal Panel</a></li>
                        <li><a class="dropdown-item" href="{{ route('get.panel') }}">Assign Panel</a></li>
                        <li><a class="dropdown-item" href="{{ route('listcgrade') }}">Grade Students</a></li>
                        <li><a class="dropdown-item" href="{{ route('listresultPSM1') }}">View Result</a></li>
                        <li><a class="dropdown-item" href="{{ route('rubricPSM1') }}">Evaluation Rubric</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">PSM 2</a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('importpsm2') }}">Import PSM2 Students</a></li>
                        <li><a class="dropdown-item" href="{{ route('listPSM2') }}">List Students</a></li>
                        <li><a class="dropdown-item" href="{{ route('psm2.get.panel') }}">Assign Panel</a></li>
                        <li><a class="dropdown-item" href="{{ route('listcgrade2') }}">Grade Students</a></li>
                        <li><a class="dropdown-item" href="{{ route('listresultPSM2') }}">View Result</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Supervisor</a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('listsvpelajar') }}">PSM 1</a></li>
                        <li><a class="dropdown-item" href="{{ route('listsvpelajar2') }}">PSM 2</a></li>
                       
                        </ul>
                    </li>
                    
                    @elseif(Session::get('role_id')==2) 
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Supervisor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('listsvpelajar') }}">PSM1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('listsvpelajar2') }}">PSM2</a>
                    </li>
                    @endif
                    
                    @if(Session::get('panel')==1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Panel</a>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('studentproposal') }}">Grade Proposal</a></li>
                        <li><a class="dropdown-item" href="{{ route('listpanelpelajar') }}">Grade PSM1</a></li>
                        <li><a class="dropdown-item" href="{{ route('listpanelpelajar2') }}">Grade PSM2</a></li>
                        </ul>
                        
                    </li>
                    @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                </li>
            </ul>
            
        </div>
    </div>
</nav>