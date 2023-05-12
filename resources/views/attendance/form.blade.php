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
                        method="POST" enctype="multipart/form-data" id="form-submit">
                        @csrf

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ $data->type == 'create' ? 'Presence' : 'Edit Presence' }}</h4>
                            </div>
                            <input type="hidden" name="day1" value="{{ request()->get('day1') }}">
                            <input type="hidden" name="day2" value="{{ request()->get('day2') }}">
                            <input type="hidden" name="time" value="{{ request()->get('time') }}">
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
                                                            style="min-width:3px;">Presence</th>
                                                        <th class="text-center">In-Point</th>
                                                        <th class="text-center">Category</th>
                                                        {{-- <th class="text-center">In Point Category</th> --}}
                                                        <th class="text-center">
                                                            Total
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($student as $it)
                                                        @php
                                                            $birthDayPoint = 0;
                                                            if ($it->birthday == date('M d')) {
                                                                $birthDayPoint = 30;
                                                            }
                                                        @endphp
                                                        <tr style="height: 40px!important">
                                                            <td class="text-center" style="">{{ $no }}
                                                            </td>
                                                            <td style="">{{ $it->name }}
                                                            </td>
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
                                                                    name="isAbsent[{{ $no }}][]"
                                                                    data-hour="{{ $it->course_hour }}"
                                                                    data-class="{{ $it->priceid }}">
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
                                                                    @if ($isAbsent)
                                                                        @php
                                                                            $pointDay = 0;
                                                                            $pointHour = 0;
                                                                            if (Request::get('day1') == 5 || Request::get('day1') == 6 || Request::get('day2') == 5 || Request::get('day2') == 6 || Request::get('day1') == Request::get('day2')) {
                                                                                $pointDay = 20;
                                                                            } else {
                                                                                $pointDay = 10;
                                                                            }
                                                                            
                                                                            if ($it->course_hour != null || $it->priceid == 42 || $it->priceid == 39) {
                                                                                $totalPoint = $it->course_hour . '0';
                                                                            } else {
                                                                                $totalPoint = $pointDay;
                                                                            }
                                                                        @endphp
                                                                        {{ $totalPoint }}
                                                                    @else
                                                                        0
                                                                    @endif
                                                                    {{-- {{ $isAbsent ? '10' : '0' }}</h5> --}}
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
                                                                {{-- @if ($birthDayPoint != 0)
                                                                    <span class="center"
                                                                        style="color: red
                                                                ">+
                                                                        Extra Birthday</span>
                                                                @endif --}}
                                                                {{-- <input type="text" class="form-control"
                                                                    placeholder="Enter Category"
                                                                    name="category[{{ $no }}][]"
                                                                    value="{{ $data->type == 'update' ? $data->students[$no - 1]->category : '' }}"> --}}
                                                            </td>
                                                            {{-- <td>
                                                                <input type="number" class="form-control"
                                                                    placeholder="Enter In Point Category"
                                                                    name="point_category[{{ $no }}][]"
                                                                    id="point_category{{ $no }}"
                                                                    value="{{ $data->type == 'update' ? $data->students[$no - 1]->categoryPoint : '' }}">
                                                            </td> --}}
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
                                                                <input type="hidden"
                                                                    name="birthdaypoint[{{ $no }}][]"
                                                                    value="{{ $birthDayPoint }}">
                                                                {{-- <input type="text" name="totalPointCategory[]"
                                                                    id="inpTotalPointCategory{{ $no }}"
                                                                    value="{{ $totalPoint }}" readonly> --}}
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
                                @if (Auth::guard('teacher')->check() == true)
                                    <h2 class="mt-3">Last Agenda</h2>
                                    <div class="row-mt-3">
                                        <div class="col-md-12">
                                            @php
                                                $agenda = \App\Models\AttendanceDetail::join('attendances', 'attendances.id', 'attendance_details.attendance_id')
                                                    ->select('attendances.activity', 'attendances.date', 'attendances.text_book', 'attendances.excercise_book')
                                                    ->where('attendances.price_id', $priceId)
                                                    ->where('attendances.day1', Request::get('day1'))
                                                    ->where('attendances.day2', Request::get('day2'))
                                                    ->where('attendances.course_time', Request::get('time'))
                                                    ->where('attendances.teacher_id', Request::get('teacher'))
                                                    ->orderBy('attendance_details.id', 'DESC')
                                                    ->take(5)
                                                    ->get();
                                                $noA = 1;
                                            @endphp
                                            <table
                                                class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Topic</th>
                                                        <th>Date</th>
                                                        <th>Text Book</th>
                                                        <th>Excercise Book</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($agenda) != 0)
                                                        @foreach ($agenda as $itema)
                                                            <tr>
                                                                <td>{{ $noA++ }}</td>
                                                                <td>{{ $itema->activity }}</td>
                                                                <td>{{ $itema->date }}</td>
                                                                <td>{{ $itema->text_book }}</td>
                                                                <td>{{ $itema->excercise_book }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5">Data not found</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
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
                                <a href="javascript:void(0)" onclick="confirm()" class="btn btn-success">Submit</a>
                                <button type="button" data-toggle="modal" data-target="#mdlCancel"
                                    class="btn btn-danger">Cancel</button>
                            </div>
                            {{-- @endif --}}
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Last Agenda</h4>
                            </div>
                            <div class="card-body">
                                @foreach ($agenda as $item)
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <p>{{ $item->date }}
                                                    <br>{{ $item->activity }}
                                                    <br>Text Book : {{ $item->text_book }}
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
                var pointDay = {{ Request::get('day1') }} == 5 || {{ Request::get('day1') }} == 6 ||
                    {{ Request::get('day2') }} == 5 || {{ Request::get('day2') }} == 6 ||
                    {{ Request::get('day1') }} == {{ Request::get('day2') }} ? 20 : 10;
                var birthDayPoint = 0;
                var totalPoint = parseInt($("#totalPoint" + i).text());
                $('#cbAbsent' + i).click('change', function() {
                    var dataHour = $(this).data('hour');
                    var dataClass = $(this).data('class');
                    var conditionPoint = $(this).data('hour') != '' || dataClass == 39 || dataClass == 42 ?
                        parseInt(dataHour + '0') : pointDay
                    if ($(this).is(':checked')) {
                        $("#inPointAbsent" + i).text(parseInt(conditionPoint));
                        $("#totalPoint" + i).text(parseInt($("#totalPoint" + i).text()) + conditionPoint);
                        $("#inpTotalPoint" + i).val(parseInt($("#inpTotalPoint" + i).val() != '' ? $(
                            "#inpTotalPoint" + i).val() : 0) + conditionPoint);
                    } else {
                        $("#totalPoint" + i).text(parseInt($("#totalPoint" + i).text()) - conditionPoint);
                        $("#inpTotalPoint" + i).val(parseInt($("#inpTotalPoint" + i).val() != '' ? $(
                            "#inpTotalPoint" + i).val() : 0) - conditionPoint);
                        $("#inPointAbsent" + i).text(0);
                    }
                });

                $('#categories' + i).change(function() {
                    var tmpTotalPoint = 0;
                    console.log(tmpTotalPoint);
                    var getVal = $('#categories' + i).val();
                    dataCtgr.forEach(element => {
                        getVal.forEach(x => {
                            if (element.id.toString() == x.toString()) {
                                tmpTotalPoint += element.point;
                            }
                        })
                    });

                    $("#totalPoint" + i).text(tmpTotalPoint +
                        birthDayPoint + parseInt($("#inPointAbsent" + i).text()));
                    $("#inpTotalPoint" + i).val(tmpTotalPoint +
                        birthDayPoint + parseInt($("#inPointAbsent" + i).text()));

                });
                // $('#point_category' + i).keyup(function() {
                //     var tmpTotalPoint = 0;
                //     var point = isNaN(parseInt($('#point_category' + i).val())) == true ? 0 : parseInt($(
                //         '#point_category' + i).val());
                //     $("#totalPoint" + i).text(point + parseInt($("#inPointAbsent" + i).text()));
                //     $("#inpTotalPoint" + i).val(point + parseInt($("#inPointAbsent" + i).text()));

                // });
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

        function confirm() {
            swal("Are you sure ?", "Data will be updated", {
                icon: "info",
                buttons: {
                    confirm: {
                        className: 'btn btn-success',
                        text: 'Ok'
                    },
                    dismiss: {
                        className: 'btn btn-secondary',
                        text: 'Cancel'
                    },
                },
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result == true) {
                    $('#form-submit').submit();
                }
            });
        }
    </script>

    <script></script>
@endsection
