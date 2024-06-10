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

        <div class="container mb-5 mt-5">
            <div class="row">
                @foreach ($arr as $key => $item)
                    <div class="col-md-3 ">

                        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="height: 120px">
                            <strong>Remember to Input Score in </strong> <span style="font-size: 11px">
                                {{ $item->class . ' - ' . $item->review_test }}</span>
                            <p><i class="fas fa-info-circle"> {{ $item->name }}</i></p>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>



        <div class="page-inner mt--5">
            @if (session('message'))
                @if ($arr != null)
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
                                            {{-- <h4 class="card-title">{{ $data->parent }}</h4> --}}
                                            <h4 class="card-title">1005</h4>
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
                                            @if ($data->announces)
                                                <!--<img style="width: 100%"
                                                                                                                                                                                                                    src="{{ url('/storage') . '/' . $data->announces->banner }}" alt="">-->
                                                <img style="width: 100%" src="{{ url($data->announces->banner) }}"
                                                    alt="">
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            @if ($data->announces)
                                                <p>{{ $data->announces->description }}</p>
                                            @endif
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
