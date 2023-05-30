@extends('template.app')

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                        <h5 class="text-white op-7 mb-2">Dashboard
                        </h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            @if (session('message'))
                <script>
                    swal("Need to follow up", "", {
                        icon: "info",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            },
                            dismiss: {
                                className: 'btn btn-secondary'
                            },
                        },
                    }).then((result) => {
                        console.log(result);
                        /* Read more about isConfirmed, isDenied below */
                        if (result == true) {
                            window.location = "{{ url('/attendance/reminder') }}"
                        }
                    });
                </script>
            @endif
            @if (Auth::guard('teacher')->check() == false)
                <div class="row mt--2">
                    <div class="col-sm-6 col-md-4">
                        <div class="card card-stats card-warning card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="flaticon-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Student</p>
                                            <h4 class="card-title">{{ $data->student }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="card card-stats card-info card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Parent</p>
                                            <h4 class="card-title">{{ $data->parent }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="card card-stats card-success card-round">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Teacher </p>
                                            <h4 class="card-title">{{ $data->teacher }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Announcement</h4>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img style="width: 100%; height: 300px;"
                                                src="{{ url('/storage') . '/' . $data->announces->banner }}" alt="">
                                        </div>
                                        <div class="col-md-12">
                                            <p>{{ $data->announces->description }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
