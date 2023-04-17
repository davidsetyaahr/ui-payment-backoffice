<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="../assets/img/profile.png" alt="..." class="avatar-img rounded-circle">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            <!-- {{ session('nama') }} -->
                            Admin UI Payment
                            <span class="user-level">{{ Auth::guard('teacher')->user()->name }}</span>

                        </span>
                    </a>

                </div>
            </div>
            <ul class="nav nav-primary">
                <li class="nav-item {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ url('/dashboard') }}" class="collapsed">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>

                </li>
                <li
                    class="nav-section {{ Request::segment(1) == 'admin' || Request::segment(1) == 'petani' ? 'active' : '' }}">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>


                <li class="nav-item {{ Request::segment(1) == 'user' ? 'active' : '' }}">
                    <a href="{{ url('/attendance/class') }}" class="collapsed">
                        <i class="fas fa-book"></i>
                        <p>Attendance</p>
                    </a>

                </li>
                <li class="nav-item {{ Request::segment(1) == 'score' ? 'active' : '' }}">
                    <a href="{{ url('/score/form') }}" class="collapsed">
                        <i class="fas fa-user-alt"></i>
                        <p>Student Score</p>
                    </a>

                </li>

                <li
                    class="nav-item {{ Request::segment(1) == 'advertise' || Request::segment(1) == 'announces' || Request::segment(1) == 'pointCategories' || Request::segment(1) == 'reedemItems' || Request::segment(1) == 'testMaster' || Request::segment(1) == 'tests' || Request::segment(1) == 'parents' ? 'active' : '' }} submenu">
                    <a data-toggle="collapse" href="#tables">
                        <i class="fas fa-th-large"></i>
                        <p>Master Data</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::segment(1) == 'advertise' || Request::segment(1) == 'announces' || Request::segment(1) == 'pointCategories' || Request::segment(1) == 'reedemItems' || Request::segment(1) == 'testMaster' || Request::segment(1) == 'tests' || Request::segment(1) == 'parents' ? 'show' : '' }}"
                        id="tables">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::segment(1) == 'parents' ? 'active' : '' }}">
                                <a href="{{ url('/parents') }}">
                                    <span class="sub-item ">Parents</span>
                                </a>
                            </li>
                            <li class="{{ Request::segment(1) == 'tests' ? 'active' : '' }}">
                                <a href="{{ url('/tests') }}">
                                    <span class="sub-item ">Test</span>
                                </a>
                            </li>
                            <li class="{{ Request::segment(1) == 'pointCategories' ? 'active' : '' }}">
                                <a href="{{ url('/pointCategories') }}">
                                    <span class="sub-item ">Point Categories</span>
                                </a>
                            </li>
                            <li class="{{ Request::segment(1) == 'reedemItems' ? 'active' : '' }}">
                                <a href="{{ url('/reedemItems') }}">
                                    <span class="sub-item ">Reedem Item</span>
                                </a>
                            </li>
                            <li class="{{ Request::segment(1) == 'announces' ? 'active' : '' }}">
                                <a href="{{ url('/announces') }}">
                                    <span class="sub-item ">Announcement</span>
                                </a>
                            </li>
                            <li class="{{ Request::segment(1) == 'advertise' ? 'active' : '' }}">
                                <a href="{{ url('/advertise') }}">
                                    <span class="sub-item ">Advertise</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item {{ Request::segment(1) == 'reedemPoint' ? 'active' : '' }}">
                    <a href="{{ url('/reedemPoint') }}" class="collapsed">
                        <i class="fas fa-download"></i>
                        <p>Reedem Point</p>
                    </a>

                </li>
                <li class="nav-item {{ Request::segment(1) == 'schedule-class' ? 'active' : '' }}">
                    <a href="{{ url('/schedule-class') }}" class="collapsed">
                        <i class="fas fa-calendar"></i>
                        <p>Schedule Class</p>
                    </a>

                </li>
                <li class="nav-item {{ Request::segment(2) == 'reminder' ? 'active' : '' }}">
                    <a href="{{ url('/attendance/reminder') }}" class="collapsed">
                        <i class="fas fa-bell"></i>
                        <p>Reminder</p>
                    </a>

                </li>
                <li class="nav-item {{ Request::segment(1) == 'mutasi' ? 'active' : '' }}">
                    <a href="{{ url('/mutasi') }}" class="collapsed">
                        <i class="fas fa-users"></i>
                        <p>Mutasi</p>
                    </a>

                </li>
                <li class="nav-item {{ Request::segment(1) == 'history-test' ? 'active' : '' }}">
                    <a href="{{ url('/history-test') }}" class="collapsed">
                        <i class="fas fa-book"></i>
                        <p>History Test</p>
                    </a>

                </li>
            </ul>
        </div>
    </div>
</div>
