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
                    <h2 class="text-white pb-2 fw-bold">{{ $title }}</h2>
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
                            <a href="#" class="text-white">Attendance</a>
                        </li>
                        <li class="separator text-white">
                            <i class="flaticon-right-arrow text-white"></i>
                        </li>
                        <li class="nav-item text-white">
                            <a href="#" class="text-white">{{ $title }}</a>
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
                    <form
                        action="{{ $data->type == 'create' ? url('attendance/store') : url('attendance/update', $data->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ $data->type == 'create' ? 'Absence' : 'Edit Absence' }}</h4>
                            </div>
                            <input type="hidden" name="day1" value="{{ request()->get('day1') }}">
                            <input type="hidden" name="day2" value="{{ request()->get('day2') }}">
                            <input type="hidden" name="time" value="{{ request()->get('time') }}">
                            {{-- <div class="card-body">
                                <form action="" method="get">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="">Day</label>
                                            <select name="day" id="day" class="form-control select2">
                                                <option value="">---Select Day---</option>
                                                @foreach ($day as $itema)
                                                    <option
                                                        value="{{ $itema->id }}"{{ Request::get('day') == $itema->id ? 'selected' : '' }}>
                                                        {{ $itema->day }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">Time</label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input id="time" type="time" class="form-control" name="time"
                                                    value="{{ Request::get('time') ? Request::get('time') : '' }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <a class="btn btn-primary" href="javascript:void(0)" onclick="filter()"><i
                                                    class="fas fa-filter"></i>
                                                Filter</a>
                                        </div>
                                    </div>
                                </form>
                            </div> --}}
                            {{-- @if (Request::get('day')) --}}
                            <div class="card-body">
                                <input type="hidden" readonly name="priceId" value="{{ $data->id }}">
                                <input type="hidden" readonly name="attendanceId" value="{{ $data->attendanceId }}">

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="">
                                            <table
                                                class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">No</th>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center" scope="col" class="w-5"
                                                            style="min-width:3px;">Absence</th>
                                                        <th class="text-center">In Point</th>
                                                        <th class="text-center">Category</th>
                                                        <th class="text-center">Total</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($student as $it)
                                                        <tr style="height: 40px!important">
                                                            <td class="text-center" style="">{{ $no }}
                                                            </td>
                                                            <td style="">{{ $it->name }}</td>
                                                            <input type="hidden" readonly name="studentId[]"
                                                                value="{{ $it->id }}">
                                                            <td class=" text-center" scope="col"
                                                                style="width:3px!important;">
                                                                <input type="hidden"
                                                                    name="isAbsent[{{ $no }}][]" value="0">
                                                                @php
                                                                    $isChecked = false;
                                                                    if ($data->type == 'create') {
                                                                        $isChecked = false;
                                                                    } else {
                                                                        if ($data->students[$no - 1]->student_id == $it->id && $data->students[$no - 1]->is_absent == '1') {
                                                                            $isChecked = true;
                                                                        }
                                                                    }
                                                                    
                                                                @endphp
                                                                <input type="checkbox" class="form-check-input cekBox"
                                                                    id="cbAbsent{{ $no }}" value="1"
                                                                    {{ $isChecked ? 'checked' : '' }}
                                                                    aria-label="Checkbox for following text input"
                                                                    name="isAbsent[{{ $no }}][]">
                                                            </td>
                                                            <td class="text-center" style="">
                                                                @php
                                                                    $isAbsent = false;
                                                                    if ($data->type == 'create') {
                                                                        $isAbsent = false;
                                                                    } else {
                                                                        if ($data->students[$no - 1]->student_id == $it->id && $data->students[$no - 1]->is_absent) {
                                                                            $isAbsent = true;
                                                                        }
                                                                    }
                                                                @endphp
                                                                <h5 id="inPointAbsent{{ $no }}">
                                                                    {{ $isAbsent ? '10' : '0' }}</h5>
                                                            </td>
                                                            <td style="">
                                                                <select
                                                                    class="form-control select2 select2-hidden-accessible"
                                                                    style="width:100%;"
                                                                    name="categories[{{ $no }}][]"
                                                                    placeholder="Select Category"
                                                                    id="categories{{ $no }}" multiple="multiple">

                                                                    @foreach ($pointCategories as $st)
                                                                        <option value="{{ $st->id }}"
                                                                            {{ $data->type == 'update' && in_array(intval($st->id), $data->students[$no - 1]->category) ? 'selected' : '' }}>
                                                                            {{ $st->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="text-center" style="">
                                                                @php
                                                                    $totalPoint = 0;
                                                                    if ($data->type == 'create') {
                                                                        $totalPoint = 0;
                                                                    } else {
                                                                        if ($data->students[$no - 1]->student_id == $it->id) {
                                                                            $totalPoint = $data->students[$no - 1]->total_point;
                                                                        }
                                                                    }
                                                                @endphp
                                                                <input type="hidden" name="totalPoint[]"
                                                                    id="inpTotalPoint{{ $no }}"
                                                                    value="{{ $totalPoint }}" readonly>
                                                                <h5 id="totalPoint{{ $no }}">
                                                                    {{ $totalPoint }}</h5>
                                                            </td>

                                                        </tr>
                                                        @php
                                                            $no++;
                                                        @endphp
                                                    @endforeach

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <h2 class="mt-3">Agenda</h2>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Topic</label>
                                            <textarea name="comment" class="form-control" id="" cols="30" rows="3">{{ $data->type == 'update' ? $data->comment : '' }}</textarea>
                                        </div>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Text Book</label>
                                            <input type="text" class="form-control"
                                                value="{{ $data->type == 'update' ? $data->textBook : '' }}"
                                                name="textBook">
                                        </div>

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Excercise Book</label>
                                            <input type="text" class="form-control"
                                                value="{{ $data->type == 'update' ? $data->excerciseBook : '' }}"
                                                name="excerciseBook">

                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div class="card-action mt-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <button type="button" data-toggle="modal" data-target="#mdlCancel"
                                    class="btn btn-danger">Cancel</button>
                            </div>
                            {{-- @endif --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal" id="mdlCancel" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin membatalkan proses?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url('/advertise') }}"><button type="button" class="btn btn-success">Ya</button></a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#categories' + i).val(0);
            var dataCtgr = JSON.parse('{!! $pointCategories !!}');

            var len = $('.cekBox').length;

            for (let i = 1; i <= len; i++) {
                var totalPoint = parseInt($("#totalPoint" + i).text());
                $('#cbAbsent' + i).click('change', function() {
                    if ($(this).is(':checked')) {
                        $("#inPointAbsent" + i).text(parseInt(10));
                        $("#totalPoint" + i).text(parseInt($("#totalPoint" + i).text()) + 10);
                        $("#inpTotalPoint" + i).val(parseInt($("#inpTotalPoint" + i).val() != '' ? $(
                            "#inpTotalPoint" + i).val() : 0) + 10);
                    } else {
                        $("#totalPoint" + i).text(parseInt($("#totalPoint" + i).text()) - 10);
                        $("#inpTotalPoint" + i).val(parseInt($("#inpTotalPoint" + i).val() != '' ? $(
                            "#inpTotalPoint" + i).val() : 0) - 10);
                        $("#inPointAbsent" + i).text(0);
                    }
                });

                $('#categories' + i).change(function() {
                    var tmpTotalPoint = 0;
                    var getVal = $('#categories' + i).val();
                    dataCtgr.forEach(element => {
                        getVal.forEach(x => {
                            if (element.id.toString() == x.toString()) {
                                tmpTotalPoint += element.point;
                            }
                        })
                    });

                    $("#totalPoint" + i).text(tmpTotalPoint + parseInt($("#inPointAbsent" + i).text()));
                    $("#inpTotalPoint" + i).val(tmpTotalPoint + parseInt($("#inPointAbsent" + i).text()));

                });
            }

        });

        function filter() {
            var day = $('#day').val();
            var time = $('#time').val();
            // var ampm = $('#ampm').val();
            if (day == '') {
                alert("Day is required")
            } else if (time == '') {
                alert("Time is required")
            }
            // else if (ampm == '') {
            //     alert("AM/PM is required")
            // }
            else {
                window.location.href = "{{ url('/attendance/form/' . $data->id) }}?day=" + day + "&time=" + time;
            }
        }
    </script>

    <script></script>
@endsection
