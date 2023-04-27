@extends('template.app')

@section('content')
    <style>
        .table th {
            font-size: 14px;

            padding: 0 25px !important;
            height: 35px;
            vertical-align: middle !important;
        }

        .table td {
            height: 35px !important;
            padding: 8px 16px !important;
        }
    </style>
    <div class="content">
        <div class="page-inner py-5 panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div class="">
                    <h2 class="text-white pb-2 fw-bold">Reminder</h2>
                    <ul class="breadcrumbs">
                        <li class="nav-home text-white">
                            <a href="#">
                                <i class="flaticon-home text-white"></i>
                            </a>
                        </li>
                        <li class="separator text-white">
                            <i class="flaticon-right-arrow text-white"></i>
                        </li>
                        {{-- <li class="nav-item text-white">
                            <a href="#" class="text-white">Attendance</a>
                        </li> --}}
                        <li class="separator text-white">
                            <i class="flaticon-right-arrow text-white"></i>
                        </li>
                        <li class="nav-item text-white">
                            <a href="#" class="text-white">Reminder</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="page-inner mt--5">
            @if (session('status'))
                <script>
                    swal("Gagal!", "{{ session('status') }}!", {
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
                            <h4 class="card-title">Reminder Student</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="email2">Level</label>
                                                <select class="form-control select2 select2-hidden-accessible"
                                                    style="width:100%;" name="level" id="level">
                                                    <option value="">Select Level</option>
                                                    @foreach ($class as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ Request::get('level') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->program }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="email2">Teacher</label>
                                                <select class="form-control select2 select2-hidden-accessible"
                                                    style="width:100%;" name="teacher" id="teacher">
                                                    <option value="">Select Teacher</option>
                                                    @foreach ($teacher as $item1)
                                                        <option value="{{ $item1->id }}"
                                                            {{ Request::get('teacher') == $item1->id ? 'selected' : '' }}>
                                                            {{ $item1->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2" style="margin-top:20px">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i>
                                                    Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="">
                                        {{-- @if (Request::get('student')) --}}
                                        <table id="basic-datatables"
                                            class="table table-sm display table table-striped table-hover table-head-bg-info">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Teacher</th>
                                                    <th class="text-center">Level</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($data as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $item[0]->name }}</td>
                                                        <td>{{ $item[0]->teacher != null ? $item[0]->teacher : '-' }}</td>
                                                        <td>{{ $item[0]->program }}</td>
                                                        {{-- <td>{{ $item->program }}</td> --}}
                                                        <td>
                                                            <a class="btn btn-sm btn-primary text-white">Done</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                        {{-- <div class="pull-right">
                                            @php
                                                $page = Request::get('page') != null ? Request::get('page') : 1;
                                                $next = $page + 1;
                                                $previous = $page - 1;
                                            @endphp
                                            @if (Request::get('page') != 1 && Request::get('page') != null)
                                                <a href="{{ url('attendance/reminder?page=') . $previous }}"
                                                    class="btn btn-sm btn-secondary">
                                                    < Previous</a>
                                            @endif
                                            @if (Request::get('page') < $totalPages || Request::get('page') == null || Request::get('page') == 1)
                                                <a href="{{ url('attendance/reminder?page=') . $next }}"
                                                    class="btn btn-sm btn-secondary">
                                                    Next ></a>
                                            @endif
                                        </div> --}}
                                        {{-- @endif --}}
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
