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
        <div class="page-inner py-5 panel-header bg-primary-gradient">
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
                                {{-- <div class="col-md-3">

                                    <label for="email2">Students</label>
                                    <select class="form-control select2 select2-hidden-accessible student"
                                        style="width:100%;" name="student" id="student1">
                                        <option value="">Select Student</option>
                                        @foreach ($student as $item)
                                            <option value="{{ $item->id }}"
                                                {{ Request::get('student') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('student')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror


                                </div>
                                <div class="col-md-3">

                                    <label for="email2">Id Student</label>
                                    <input type="number" name="student" id="student2" class="form-control student"
                                        value="{{ Request::get('student') }}">
                                    @error('student')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror


                                </div>
                                <div class="col-md-3" style="margin-top:20px">
                                    <button type="submit" class="btn btn-primary" onclick="filter()"><i
                                            class="fas fa-filter"></i>
                                        Filter</button>
                                </div> --}}
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="">
                                        {{-- @if (Request::get('student')) --}}
                                        <table
                                            class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center">Program</th>
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
                                                        <td>{{ $item[0]->program }}</td>
                                                        {{-- <td>{{ $item->program }}</td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                        <div class="pull-right">
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
                                        </div>
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
    <script>
        function filter() {
            var student1 = $('#student1').val();
            var student2 = $('#student2').val();
            var student = '';
            if (student1 != '' && student2 == '') {
                student = student1
            } else if (student1 != '' && student2 != '') {
                student = student2
            } else {
                student = student2
            }
            window.location = " {{ url('mutasi?student=') }}" + student;
        }
    </script>
@endsection
