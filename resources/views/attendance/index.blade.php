@extends('template.app')

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                        <h5 class="text-white op-7 mb-2">
                            {{ Auth::guard('teacher')->check() == true ? Auth::guard('teacher')->user()->name : Auth::guard('staff')->user()->name }}
                        </h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            @if (session('message'))
                <script>
                    swal("Successful", "{{ session('message') }}!", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    });
                </script>
            @elseif(session('error'))
                <script>
                    swal("Successful", "{{ session('error') }}!", {
                        icon: "error",
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
                            <h4 class="card-title">Regular</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="get">
                                <div class="row">
                                    @if (Auth::guard('staff')->check() == true)
                                        <div class="col-md-3">
                                            <select name="teacher" id="" class="form-control select2">
                                                <option value="">---Choose Teacher---</option>
                                                @foreach ($teachers as $t)
                                                    <option value="{{ $t->id }}"
                                                        {{ Request::get('teacher') == $t->id ? 'selected' : '' }}>
                                                        {{ $t->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <select name="level" id="" class="form-control select2">
                                            <option value="">---Choose Class---</option>
                                            @foreach ($level as $c)
                                                <option value="{{ $c->id }}"
                                                    {{ Request::get('level') == $c->id ? 'selected' : '' }}>
                                                    {{ $c->program }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if (Auth::guard('teacher')->check() == true)
                                        <div class="col-md-3">
                                            <select name="day" id="" class="form-control select2">
                                                <option value="">---Choose Day---</option>
                                                @foreach ($day as $d)
                                                    <option value="{{ $d->id }}"
                                                        {{ Request::get('day') == $d->id ? 'selected' : '' }}>
                                                        {{ $d->day }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter"></i>
                                            Filter</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <div class="row">
                                @foreach ($general as $key => $item)
                                    @if (
                                        $item->priceid != 17 &&
                                            $item->id_teacher != 4 &&
                                            $item->day1 != 5 &&
                                            $item->day2 != 5 &&
                                            $item->course_time == '17:00' &&
                                            $item->is_class_new != 1)
                                        <div class="col-sm-6 col-md-4 ">
                                            <div class="card">
                                                <div class="card-body">
                                                    <span style="font-size: 16px">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <i class="fa fas fa-angle-right"></i>
                                                                <b>
                                                                    {{ $item->program }}</b>
                                                                @if ($item->is_class_new == true)
                                                                    {{-- @foreach ($already_absent as $ab)
                                                                    $new_label = '';
                                                                    @if ($item->price_id != $ab->price_id && $item->day1 == $ab->day1 && $item->day2 == $ab->day2 && $item->course_time == $ab->course_time && $item->id_teacher == $ab->teacher_id)
                                                                        $new_label = '<span style="color: red">(New!)</span>';
                                                                    @endif
                                                                @endforeach --}}
                                                                    @php
                                                                        $already_absent = DB::table('attendances')
                                                                            ->where([
                                                                                ['price_id', $item->priceid],
                                                                                ['day1', $item->day1],
                                                                                ['day2', $item->day2],
                                                                                ['course_time', $item->course_time],
                                                                                ['teacher_id', $item->id_teacher],
                                                                                ['is_presence', 1],
                                                                            ])
                                                                            ->first();

                                                                        if ($already_absent != null) {
                                                                            $new_label = 'hidden';
                                                                        } else {
                                                                            $new_label = '';
                                                                        }
                                                                    @endphp

                                                                    <span style="color: red"
                                                                        {{ $new_label }}>(New!)</span>
                                                                @endif
                                                            </div>
                                                            @if (Auth::guard('staff')->check() == true)
                                                                <div>
                                                                    <form action="{{ url('schedule-class/delete') }}"
                                                                        method="POST" class="form-inline">
                                                                        @method('delete')
                                                                        @csrf
                                                                        <input type="hidden" name="priceid"
                                                                            value="{{ $item->priceid }}">
                                                                        <input type="hidden" name="day1"
                                                                            value="{{ $item->day1 }}">
                                                                        <input type="hidden" name="day2"
                                                                            value="{{ $item->day2 }}">
                                                                        <input type="hidden" name="course_time"
                                                                            value="{{ $item->course_time }}">
                                                                        <input type="hidden" name="id_teacher"
                                                                            value="{{ $item->id_teacher }}">
                                                                        <button type="submit"
                                                                            onclick="return confirm('apakah anda yakin ingin menghapus data ??')"
                                                                            class="btn btn-xs btn-danger"
                                                                            style="margin-right: 10px !important;"><i
                                                                                class="fas fa-trash"></i></button>
                                                                        <a href="javascript:void(0)"
                                                                            class="btn btn-xs btn-success"
                                                                            data-toggle="modal"
                                                                            data-target="#editJadwalModal"
                                                                            onclick="updateModalReg({{ $key }})"><i
                                                                                class="fas fa-pencil-alt"></i></a>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <br>
                                                        <b>{{ $item->day_one }}
                                                            {{ $item->day1 != $item->day2 ? '&' : '' }}
                                                            {{ $item->day1 != $item->day2 ? $item->day_two : '' }}</b>
                                                        <br>
                                                        <b>{{ $item->course_time }}</b>

                                                        <input type="hidden" id="regprogramModal{{ $key }}"
                                                            value="{{ $item->program }}">
                                                        <input type="hidden" id="regcourseTimeModal{{ $key }}"
                                                            value="{{ $item->course_time }}">
                                                        <input type="hidden" id="regday1Modal{{ $key }}"
                                                            value="{{ $item->day1 }}">
                                                        <input type="hidden" id="regday2Modal{{ $key }}"
                                                            value="{{ $item->day2 }}">
                                                        <input type="hidden" id="regteacherModal{{ $key }}"
                                                            value="{{ $item->teacher_name }}">
                                                        <input type="hidden" id="regteacherOldModal{{ $key }}"
                                                            value="{{ $item->id_teacher }}">
                                                        <input type="hidden" id="regclassModal{{ $key }}"
                                                            value="{{ $item->priceid }}">
                                                        <input type="hidden" id="regdayOneModal{{ $key }}"
                                                            value="{{ $item->day_one }}">
                                                        <input type="hidden" id="regdayTwoModal{{ $key }}"
                                                            value="{{ $item->day_two }}">
                                                        <input type="hidden" id="regidteacherModal{{ $key }}"
                                                            value="{{ $item->id_teacher }}">
                                                    </span>

                                                    <div class="d-flex justify-content-between mt-4">
                                                        <div class="fw-bold">{{ $item->teacher_name }}</div>
                                                        <a href="{{ url('attendance/form/' . $item->priceid . '?day1=' . $item->day1 . '&day2=' . $item->day2 . '&time=' . $item->course_time) . '&teacher=' . $item->id_teacher . '&new=' . $item->is_class_new }}"
                                                            class="btn btn-xs btn-primary">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Semi Private</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($semiPrivate as $keySemiPrivate => $itemSemiPrivate)
                                    <div class="col-sm-6 col-md-4 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <span style="font-size: 16px">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <i class="fa fas fa-angle-right"></i>
                                                            <b>
                                                                {{ $itemSemiPrivate->program }}</b>
                                                            @if ($itemSemiPrivate->is_class_new == true)
                                                                @php
                                                                    $already_absent = DB::table('attendances')
                                                                        ->where([
                                                                            ['price_id', $item->priceid],
                                                                            ['day1', $item->day1],
                                                                            ['day2', $item->day2],
                                                                            ['course_time', $item->course_time],
                                                                            ['teacher_id', $item->id_teacher],
                                                                            ['is_presence', 1],
                                                                        ])
                                                                        ->first();

                                                                    if ($already_absent != null) {
                                                                        $new_label = 'hidden';
                                                                    } else {
                                                                        $new_label = '';
                                                                    }
                                                                @endphp
                                                                <span style="color: red" {{ $new_label }}>(New!)</span>
                                                            @endif
                                                        </div>
                                                        @if (Auth::guard('staff')->check() == true)
                                                            <div>
                                                                <form action="{{ url('schedule-class/delete') }}"
                                                                    method="POST" class="form-inline">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <input type="hidden" name="priceid"
                                                                        value="{{ $itemSemiPrivate->priceid }}">
                                                                    <input type="hidden" name="day1"
                                                                        value="{{ $itemSemiPrivate->day1 }}">
                                                                    <input type="hidden" name="day2"
                                                                        value="{{ $itemSemiPrivate->day2 }}">
                                                                    <input type="hidden" name="course_time"
                                                                        value="{{ $itemSemiPrivate->course_time }}">
                                                                    <input type="hidden" name="id_teacher"
                                                                        value="{{ $itemSemiPrivate->id_teacher }}">
                                                                    <button type="submit"
                                                                        onclick="return confirm('apakah anda yakin ingin menghapus data ??')"
                                                                        class="btn btn-xs btn-danger"
                                                                        style="margin-right: 10px !important;"><i
                                                                            class="fas fa-trash"></i></button>
                                                                    <a href="javascript:void(0)"
                                                                        class="btn btn-xs btn-success" data-toggle="modal"
                                                                        data-target="#editJadwalModal"
                                                                        onclick="updateModalReg({{ $keySemiPrivate }})"><i
                                                                            class="fas fa-pencil-alt"></i></a>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <br>
                                                    <b>{{ $itemSemiPrivate->day_one }}
                                                        {{ $itemSemiPrivate->day1 != $itemSemiPrivate->day2 ? '&' : '' }}
                                                        {{ $itemSemiPrivate->day1 != $itemSemiPrivate->day2 ? $itemSemiPrivate->day_two : '' }}</b>
                                                    <br>
                                                    <b>{{ $itemSemiPrivate->course_time }}</b>

                                                    <input type="hidden" id="regprogramModal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->program }}">
                                                    <input type="hidden" id="regcourseTimeModal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->course_time }}">
                                                    <input type="hidden" id="regday1Modal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->day1 }}">
                                                    <input type="hidden" id="regday2Modal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->day2 }}">
                                                    <input type="hidden" id="regteacherModal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->teacher_name }}">
                                                    <input type="hidden" id="regteacherOldModal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->id_teacher }}">
                                                    <input type="hidden" id="regclassModal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->priceid }}">
                                                    <input type="hidden" id="regdayOneModal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->day_one }}">
                                                    <input type="hidden" id="regdayTwoModal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->day_two }}">
                                                    <input type="hidden" id="regidteacherModal{{ $keySemiPrivate }}"
                                                        value="{{ $itemSemiPrivate->id_teacher }}">
                                                </span>

                                                <div class="d-flex justify-content-between mt-4">
                                                    <div class="fw-bold">{{ $itemSemiPrivate->teacher_name }}</div>
                                                    <a href="{{ url('attendance/form/' . $itemSemiPrivate->priceid . '?day1=' . $itemSemiPrivate->day1 . '&day2=' . $itemSemiPrivate->day2 . '&time=' . $itemSemiPrivate->course_time) . '&teacher=' . $itemSemiPrivate->id_teacher . '&new=' . $itemSemiPrivate->is_class_new }}"
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
                            <h4 class="card-title">Private</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($private as $key => $item)
                                    @php
                                        $studentName = DB::table('student')
                                            ->where('priceid', $item->priceid)
                                            ->where('day1', $item->day1)
                                            ->where('day2', $item->day2)
                                            ->where('id_teacher', $item->id_teacher)
                                            ->where('course_time', $item->course_time)
                                            ->groupBy('id')
                                            ->get();
                                    @endphp
                                    @foreach ($studentName as $keyStudentName => $itemStudentName)
                                        <div class="col-sm-6 col-md-4 ">
                                            <div class="card">
                                                <div class="card-body">
                                                    <span style="font-size: 16px">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <b> {{ ucwords($itemStudentName->name) }}</b>
                                                                <br>
                                                            </div>
                                                            @if (Auth::guard('staff')->check() == true)
                                                                <div>
                                                                    <form action="{{ url('schedule-class/delete') }}"
                                                                        method="POST" class="form-inline">
                                                                        @method('delete')
                                                                        @csrf
                                                                        <input type="hidden" name="priceid"
                                                                            value="{{ $item->priceid }}">
                                                                        <input type="hidden" name="day1"
                                                                            value="{{ $item->day1 }}">
                                                                        <input type="hidden" name="day2"
                                                                            value="{{ $item->day2 }}">
                                                                        <input type="hidden" name="course_time"
                                                                            value="{{ $item->course_time }}">
                                                                        <input type="hidden" name="id_teacher"
                                                                            value="{{ $item->id_teacher }}">
                                                                        <button type="submit"
                                                                            onclick="return confirm('apakah anda yakin ingin menghapus data ??')"
                                                                            class="btn btn-xs btn-danger"
                                                                            style="margin-right: 10px !important;"><i
                                                                                class="fas fa-trash"></i></button>
                                                                        <a href="javascript:void(0)"
                                                                            class="btn btn-xs btn-success"
                                                                            data-toggle="modal"
                                                                            data-target="#editJadwalModal"
                                                                            onclick="updateModalPrv({{ $key }})"><i
                                                                                class="fas fa-pencil-alt"></i></a>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        </div> <i class="fa fas fa-angle-right"></i>
                                                        <b>{{ $item->day_one }}
                                                            {{ $item->day1 != $item->day2 ? '&' : '' }}
                                                            {{ $item->day1 != $item->day2 ? $item->day_two : '' }}</b>
                                                        <br>
                                                        <b>{{ $item->course_time }}</b>

                                                        <input type="hidden" id="prvprogramModal{{ $key }}"
                                                            value="{{ $item->program }}">
                                                        <input type="hidden" id="prvcourseTimeModal{{ $key }}"
                                                            value="{{ $item->course_time }}">
                                                        <input type="hidden" id="prvday1Modal{{ $key }}"
                                                            value="{{ $item->day1 }}">
                                                        <input type="hidden" id="prvday2Modal{{ $key }}"
                                                            value="{{ $item->day2 }}">
                                                        <input type="hidden" id="prvteacherModal{{ $key }}"
                                                            value="{{ $item->teacher_name }}">
                                                        <input type="hidden" id="prvclassModal{{ $key }}"
                                                            value="{{ $item->priceid }}">
                                                        <input type="hidden" id="prvdayOneModal{{ $key }}"
                                                            value="{{ $item->day_one }}">
                                                        <input type="hidden" id="prvdayTwoModal{{ $key }}"
                                                            value="{{ $item->day_two }}">
                                                        <input type="hidden" id="prvidteacherModal{{ $key }}"
                                                            value="{{ $item->id_teacher }}">
                                                    </span>

                                                    <div class="d-flex justify-content-between mt-4">
                                                        <div class="fw-bold">{{ $item->teacher_name }}</div>
                                                        <a href="{{ url('attendance/form/' . $item->priceid . '?day1=' . $item->day1 . '&day2=' . $item->day2 . '&time=' . $item->course_time . '&teacher=' . $item->id_teacher . '&new=' . $item->is_class_new . '&student=' . $itemStudentName->id) }}"
                                                            class="btn btn-xs btn-primary">View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editJadwalModal" tabindex="-1" aria-labelledby="editJadwalModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editJadwalModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="POST" id="updateClassModal">
                            @csrf
                            <input type="hidden" id="update_day1" name="update_day1">
                            <input type="hidden" id="update_day2" name="update_day2">
                            <input type="hidden" id="update_class" name="update_class">
                            <input type="hidden" id="update_time" name="update_time">
                            <input type="hidden" id="old_teacher" name="old_teacher">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="">Course Time</label>
                                        <input type="time" class="form-control" name="update_course_time">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Day 1</label>
                                        <select name="update_day_one" id="update_day_one"
                                            class="form-control select2 select2-hidden-accessible" style="width:100%;">
                                            <option value="">---Choose Day 1---</option>
                                            @foreach ($day as $item1)
                                                <option value="{{ $item1->id }}">{{ $item1->day }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Day 2</label>
                                        <select name="update_day_two" id=""
                                            class="form-control select2 select2-hidden-accessible" style="width:100%;">
                                            <option value="">---Choose Day 2---</option>
                                            @foreach ($day as $item2)
                                                <option value="{{ $item2->id }}">{{ $item2->day }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Class</label>
                                        <select name="update_level" id="update_level"
                                            class="form-control select2 select2-hidden-accessible" style="width:100%;">
                                            <option value="">---Choose Class---</option>
                                            @foreach ($level as $item4)
                                                <option value="{{ $item4->id }}">{{ $item4->program }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Teacher</label>
                                        <select name="update_teacher" id=""
                                            class="form-control select2 select2-hidden-accessible" style="width:100%;">
                                            <option value="">---Choose Teacher---</option>
                                            @foreach ($teachers as $item3)
                                                <option value="{{ $item3->id }}">{{ $item3->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-group">
                                            <label for="">Type</label>
                                            <select name="type" id=""
                                                class="form-control select2 select2-hidden-accessible" style="width:100%;"
                                                required>
                                                <option value="">---Choose Type---</option>
                                                <option value="edit">Edit</option>
                                                <option value="promoted">Promoted</option>
                                            </select>
                                        </div>
                                        <div class="rowStudent">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        function updateModalReg(id) {
            var program = $('#regprogramModal' + id).val();
            var dayOne = $('#regdayOneModal' + id).val();
            var dayTwo = $('#regdayTwoModal' + id).val();
            var courseTime = $('#regcourseTimeModal' + id).val();
            var teacher = $('#regteacherModal' + id).val();
            var teacherOld = $('#regteacherOldModal' + id).val();
            var day1 = $('#regday1Modal' + id).val();
            var day2 = $('#regday2Modal' + id).val();
            var course = $('#regclassModal' + id).val();
            var idTeacher = $('#regidteacherModal' + id).val();
            var day = day1 != day2 ? dayOne + ' & ' + dayTwo : dayOne
            $('#editJadwalModalLabel').html('Update Class ' + program + ' (' + day + ' ' + courseTime +
                ') ' +
                ' ' + teacher);
            $('#updateClassModal').attr('action', '{{ url('attendance/update-class') }}');
            $('#update_day1').val(day1);
            $('#update_day2').val(day2);
            $('#update_class').val(course);
            $('#update_time').val(courseTime);
            $('#old_teacher').val(teacherOld);
            $('input[name="update_course_time"]').val(courseTime);
            $("#update_day_one").select2("val", day1);
            $('select[name="update_day_two"]').select2("val", day2);
            $('#update_level').select2("val", course);
            $('select[name="update_teacher"]').select2("val", idTeacher);
            // $('.rowStudent').empty();
            // $.ajax({
            //     type: "get",
            //     url: "{{ url('attendance/get-student/') }}?class=" + course + "&day1=" + day1 + "&day2=" + day2 +
            //         "&time=" + courseTime + "&teacher=" + idTeacher,
            //     dataType: "json",
            //     success: function(response) {
            //         $(response).each(function(i, v) {
            //             $('.rowStudent').append(`
        //                             <div class="form-group">
        //                                 <label for="">${v.name}</label>
        //                                 <input type="checkbox" name="studentId[]" value="${v.id}">
        //                             </div>
        //                         `);
            //         });
            //     }
            // });
        }

        function updateModalPrv(id) {
            var program = $('#prvprogramModal' + id).val();
            var dayOne = $('#prvdayOneModal' + id).val();
            var dayTwo = $('#prvdayTwoModal' + id).val();
            var courseTime = $('#prvcourseTimeModal' + id).val();
            var teacher = $('#prvteacherModal' + id).val();
            var day1 = $('#prvday1Modal' + id).val();
            var day2 = $('#prvday2Modal' + id).val();
            var course = $('#prvclassModal' + id).val();
            var idTeacher = $('#prvidteacherModal' + id).val();
            var teacherOld = $('#regteacherOldModal' + id).val();
            var day = day1 != day2 ? dayOne + ' & ' + dayTwo : dayOne
            $('#editJadwalModalLabel').html('Update Class ' + program + ' (' + day + ' ' + courseTime +
                ') ' +
                ' ' + teacher);
            $('#updateClassModal').attr('action', '{{ url('attendance/update-class') }}');
            $('#update_day1').val(day1);
            $('#update_day2').val(day2);
            $('#update_class').val(course);
            $('#update_time').val(courseTime);
            $('#old_teacher').val(teacherOld);
            $('input[name="update_course_time"]').val(courseTime);
            $("#update_day_one").select2("val", day1);
            $('select[name="update_day_two"]').select2("val", day2);
            $('select[name="update_level"]').select2("val", 32);
            $('select[name="update_teacher"]').select2("val", idTeacher);
        }
    </script>
@endsection
