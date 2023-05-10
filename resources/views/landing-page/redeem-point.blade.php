@include('template.head')

<body>
    <div class="wrapper">
        <div class="main-header">
            <!-- Logo Header -->
            <div class="logo-header justify-content-center" data-background-color="blue">

                <a href="{{ url('/dashboard') }}" class="logo">
                    <img src="{{ asset('assets/img/ui4.png') }}" width="100px" alt="navbar brand" class="navbar-brand">
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
                    data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <i class="icon-menu"></i>
                    </span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="icon-menu"></i>
                    </button>
                </div>
            </div>
            <!-- End Logo Header -->

            <!-- Navbar Header -->
            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

                <div class="container-fluid">

                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">



                    </ul>
                </div>


            </nav>

            <!-- End Navbar -->
        </div>


        <!-- Sidebar -->
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
                                    {{-- Admin UI Payment --}}
                                    <span class="user-level">Guest</span>

                                </span>
                            </a>

                        </div>
                    </div>
                    <ul class="nav nav-primary">
                        <li class="nav-item {{ Request::segment(2) == 'redeem-point' ? 'active' : '' }}">
                            <a href="#" class="collapsed">
                                <i class="fas fa-download"></i>
                                <p>Redeem Point</p>
                            </a>

                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="content">
                <div class="page-inner py-5 panel-header bg-primary-gradient" style="background:#01c293 !important">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div class="">
                            <h2 class="text-white pb-2 fw-bold">Reedem Point</h2>
                            <ul class="breadcrumbs">
                                <li class="nav-home text-white">
                                    <a href="#">
                                        <i class="flaticon-home text-white"></i>
                                    </a>
                                </li>
                                <li class="separator text-white">
                                    <i class="flaticon-right-arrow text-white"></i>
                                </li>
                                <li class="nav-item text-white">
                                    <a href="#" class="text-white">Reedem Point</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="page-inner mt--5">
                    @if (session('status'))
                        <script>
                            swal("Success!", "{{ session('status') }}!", {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-success'
                                    }
                                },
                            });
                        </script>
                    @endif
                    @if (session('error'))
                        <script>
                            swal("Gagal!", "{{ session('error') }}!", {
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-danger'
                                    }
                                },
                            });
                        </script>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Reedem Point</h4>
                                </div>
                                <div class="card-body">

                                    <form action="" method="GET">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="email2">Students</label>
                                                <select class="form-control select2 select2-hidden-accessible"
                                                    style="width:100%;" name="student" id="student">
                                                    <option value="">Select Student</option>
                                                    @foreach ($students as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->id == $reqStudent ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="email2">Id Student</label>
                                                <input type="number" name="id_student" id="id_student"
                                                    class="form-control" value="{{ $reqStudent }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="email2">Staff</label>
                                                <select class="form-control select2 select2-hidden-accessible"
                                                    style="width:100%;" name="staff" id="staff">
                                                    <option value="">Select Staff</option>
                                                    @foreach ($staffs as $s)
                                                        <option value="{{ $s->id }}"
                                                            {{ $s->id == $reqStaff ? 'selected' : '' }}>
                                                            {{ $s->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="margin-top:18px;">
                                                <button class="btn btn-primary"><i class="fas fa-filter"></i>
                                                    Cari</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                @if (Request::get('staff'))
                                    <form action="{{ url('landing-page/store-redeem-point') }}" method="POST">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4" id="point_show">
                                                    <div class="card">
                                                        <div class="card-body mx-auto">
                                                            <h6 class="card-title" id="student_name">
                                                                {{ $student != null ? $student->name : '' }}
                                                            </h6>
                                                            <h1 class="text-center" id="student_point">
                                                                {{ $student != null ? $student->total_point : 'Tidak Ada Data' }}
                                                            </h1>
                                                            <input type="hidden" name="point" id="point_total"
                                                                value="{{ $student != null ? $student->total_point : '' }}"
                                                                readonly>
                                                            <input type="hidden" name="student" id="student"
                                                                value="{{ $student != null ? $student->id : '' }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>
                                            <div id="target">
                                                <div class="row ">
                                                    <div class="col-md-3" style="padding-left: 0px!important">
                                                        <div class="form-group">
                                                            <input type="number" class="form-control"
                                                                placeholder="Reedem Point" required
                                                                name="total_point">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-action mt-3">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <script>
                    var dataStudent = {!! json_encode($students) !!};


                    $(document).ready(function() {
                        $('#student').on('change', function() {
                            $('#id_student').val('')
                            var idStudent = $('select[name=student] option').filter(':selected').val();
                            jQuery.each(dataStudent, function(index, item) {
                                if (item.id == idStudent) {

                                    $("#point_show").css("display", "block");
                                    $("#target").css("display", "block");
                                    $(".button-add").css("display", "block");
                                    $('#student_name').text(item.name);
                                    $('#student_point').text(item.total_point);
                                    $('#point_total').val(item.total_point);

                                }
                            });

                        });

                        $('#id_student').on('keyup', function() {
                            $('#student').val(0);
                            var idStudent = $('input[name=id_student]').val();
                            jQuery.each(dataStudent, function(index, item) {
                                if (item.id == idStudent) {

                                    $("#point_show").css("display", "block");
                                    $("#target").css("display", "block");
                                    $(".button-add").css("display", "block");
                                    $('#student_name').text(item.name);
                                    $('#student_point').text(item.total_point);
                                    $('#point_total').val(item.total_point);
                                }
                            });

                        });

                        var length = $("#target").length;
                        if (length == 1) {
                            $("#remove-target").hide();
                        }
                        $("#add-target").click(function() {
                            console.log("tambah");
                            var clone = $("#target:first").clone();
                            clone.find("input").val("");
                            $(".button-add").before(clone);
                            length += 1;
                            if (length == 1) {
                                $("#remove-target").hide();
                            } else {
                                $("#remove-target").show();
                            }
                        });
                        $("#remove-target").click(function() {
                            $("#target:last").remove();
                            length -= 1;
                            if (length == 1) {
                                $("#remove-target").hide();
                            } else {
                                $("#remove-target").show();
                            }
                        });
                    });
                </script>

                <script>
                    $(document).ready(function() {
                        $('#class').on('change', function() {
                            var typeClass = $(this).val();
                            $.ajax({
                                type: 'GET',
                                url: '{{ url('') }}/score/students/filter?class=' + typeClass,
                                dataType: 'JSON',
                                success: function(data) {

                                    var $student = $('#student');
                                    $student.empty();
                                    $student.append('<option value="">Select Student</option>');
                                    for (var i = 0; i < data.length; i++) {
                                        $student.append('<option id=' + data[i].id + ' value=' + data[i]
                                            .id + '>' + data[i].name + '</option>');
                                    }
                                    $student.change();

                                }
                            });
                        });
                    });
                </script> --}}


            <footer class="footer">
                <div class="container-fluid">

                    <div class="copyright ml-auto">
                        2023, made with <i class="fa fa-heart heart text-danger"></i> by <a
                            href="{{ url('/dashboard') }}">UI Payment</a>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Custom template | don't include it in your project! -->
        <div class="custom-template">
            <div class="title">Settings</div>
            <div class="custom-content">
                <div class="switcher">
                    <div class="switch-block">
                        <h4>Logo Header</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeLogoHeaderColor" data-color="dark"></button>
                            <button type="button" class="selected changeLogoHeaderColor" data-color="blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="orange"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="red"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="white"></button>
                            <br />
                            <button type="button" class="changeLogoHeaderColor" data-color="dark2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="blue2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="purple2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="light-blue2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="green2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="orange2"></button>
                            <button type="button" class="changeLogoHeaderColor" data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Navbar Header</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeTopBarColor" data-color="dark"></button>
                            <button type="button" class="changeTopBarColor" data-color="blue"></button>
                            <button type="button" class="changeTopBarColor" data-color="purple"></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue"></button>
                            <button type="button" class="changeTopBarColor" data-color="green"></button>
                            <button type="button" class="changeTopBarColor" data-color="orange"></button>
                            <button type="button" class="changeTopBarColor" data-color="red"></button>
                            <button type="button" class="changeTopBarColor" data-color="white"></button>
                            <br />
                            <button type="button" class="changeTopBarColor" data-color="dark2"></button>
                            <button type="button" class="selected changeTopBarColor" data-color="blue2"></button>
                            <button type="button" class="changeTopBarColor" data-color="purple2"></button>
                            <button type="button" class="changeTopBarColor" data-color="light-blue2"></button>
                            <button type="button" class="changeTopBarColor" data-color="green2"></button>
                            <button type="button" class="changeTopBarColor" data-color="orange2"></button>
                            <button type="button" class="changeTopBarColor" data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Sidebar</h4>
                        <div class="btnSwitch">
                            <button type="button" class="selected changeSideBarColor" data-color="white"></button>
                            <button type="button" class="changeSideBarColor" data-color="dark"></button>
                            <button type="button" class="changeSideBarColor" data-color="dark2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Background</h4>
                        <div class="btnSwitch">
                            <button type="button" class="changeBackgroundColor" data-color="bg2"></button>
                            <button type="button" class="changeBackgroundColor selected" data-color="bg1"></button>
                            <button type="button" class="changeBackgroundColor" data-color="bg3"></button>
                            <button type="button" class="changeBackgroundColor" data-color="dark"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="mdlLogout" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin keluar?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url('logout') }}"><button type="button"
                                class="btn btn-success">Iya</button></a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Custom template -->
    </div>
    @include('template.script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                closeOnSelect: true
            });
        });
    </script>
</body>

</html>
