@extends('template.app')

@section('content')
<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                    <h5 class="text-white op-7 mb-2">Dashboard Sitani Web</h5>
                </div>

            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        @if (session('message'))
        <script>
            swal("Login Berhasil", "{{ session('message') }}!", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    });

        </script>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Reguler Class</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($general as $item)
                            <div class="col-sm-6 col-md-4 ">
                                <div class="card">
                                    <div class="card-body">
                                        <span style="font-size: 16px"> <i class="fa fas fa-angle-right"></i>
                                            {{$item->program}}</span>

                                        <div class="d-flex justify-content-between">
                                            <div></div>
                                            <a href="{{ url('attendance/form/'.$item->id)}}"
                                                class="btn btn-xs btn-primary">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Private Class</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($private as $item)
                            <div class="col-sm-6 col-md-4 ">
                                <div class="card">
                                    <div class="card-body">
                                        <span style="font-size: 16px"> <i class="fa fas fa-angle-right"></i>
                                            {{$item->program}}</span>

                                        <div class="d-flex justify-content-between">
                                            <div></div>
                                            <a href="{{ url('attendance/form/'.$item->id)}}"
                                                class="btn btn-xs btn-primary">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>
@endsection