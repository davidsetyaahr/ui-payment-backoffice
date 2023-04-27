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
                    swal("Success", "{{ session('message') }}!", {
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
                            <h4 class="card-title">Regular</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($general as $key => $item)
                                    <div class="col-sm-6 col-md-4 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <span style="font-size: 16px">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <i class="fa fas fa-angle-right"></i>
                                                            <b> {{ $item->program }}</b>
                                                        </div>
                                                        <div>
                                                            <form action="{{ url('schedule-class/delete') }}" method="POST"
                                                                class="form-inline">
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
                                                                <a href="javascript:void(0)" class="btn btn-xs btn-success"
                                                                    data-toggle="modal" data-target="#editJadwalModal"
                                                                    onclick="updateModalReg({{ $key }})"><i
                                                                        class="fas fa-pencil-alt"></i></a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <b>{{ $item->day_one }} & {{ $item->day_two }}</b>
                                                    <br>
                                                    <b>{{ $item->course_time }}</b>

                                                    <input type="hidden" id="regprogramModal{{ $key }}"
                                                        value="{{ $item->program }}">
                                                    <input type="hidden" id="regcourseTimeModal{{ $key }}"
                                                        value="{{ $item->course_time }}">
                                                    <input type="hidden" id="regday1Modal{{ $key }}"
                                                        value="{{ $item->day_one }}">
                                                    <input type="hidden" id="regday2Modal{{ $key }}"
                                                        value="{{ $item->day_two }}">
                                                    <input type="hidden" id="regteacherModal{{ $key }}"
                                                        value="{{ $item->teacher_name }}">
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
                                                    <a href="{{ url('attendance/form/' . $item->priceid . '?day1=' . $item->day1 . '&day2=' . $item->day2 . '&time=' . $item->course_time) }}"
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
                                @foreach ($private as $key => $item)
                                    <div class="col-sm-6 col-md-4 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <span style="font-size: 16px">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <b> {{ $item->program }}</b>
                                                            <br>
                                                        </div>
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
                                                                    class="btn btn-xs btn-success" data-toggle="modal"
                                                                    data-target="#editJadwalModal"
                                                                    onclick="updateModalPrv({{ $key }})"><i
                                                                        class="fas fa-pencil-alt"></i></a>
                                                            </form>
                                                        </div>
                                                    </div> <i class="fa fas fa-angle-right"></i>
                                                    <b>{{ $item->day_one }} & {{ $item->day_two }}</b>
                                                    <br>
                                                    <b>{{ $item->course_time }}</b>

                                                    <input type="hidden" id="prvprogramModal{{ $key }}"
                                                        value="{{ $item->program }}">
                                                    <input type="hidden" id="prvcourseTimeModal{{ $key }}"
                                                        value="{{ $item->course_time }}">
                                                    <input type="hidden" id="prvday1Modal{{ $key }}"
                                                        value="{{ $item->day_one }}">
                                                    <input type="hidden" id="prvday2Modal{{ $key }}"
                                                        value="{{ $item->day_two }}">
                                                    <input type="hidden" id="prvteacherModal{{ $key }}"
                                                        value="{{ $item->teacher_name }}">
                                                    <input type="hidden" id="prvclassModal{{ $key }}"
                                                        value="{{ $item->priceid }}">
                                                    <input type="hidden" id="prvdayOneModal{{ $key }}"
                                                        value="{{ $item->day1 }}">
                                                    <input type="hidden" id="prvdayTwoModal{{ $key }}"
                                                        value="{{ $item->day2 }}">
                                                    <input type="hidden" id="prvidteacherModal{{ $key }}"
                                                        value="{{ $item->id_teacher }}">
                                                </span>

                                                <div class="d-flex justify-content-between mt-4">
                                                    <div class="fw-bold">{{ $item->teacher_name }}</div>
                                                    <a href="{{ url('attendance/form/' . $item->priceid . '?day1=' . $item->day1 . '&day2=' . $item->day2 . '&time=' . $item->course_time) }}"
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
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Class</label>
                                    <select name="update_class" id="update_class"
                                        class="form-control select2 select2-hidden-accessible" style="width:100%;">
                                        <option value="">---Choose Class---</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Course Time</label>
                                    <input type="time" class="form-control" name="update_course_time">
                                </div>
                                <div class="form-group">
                                    <label for="">Day 1</label>
                                    <select name="update_day_one" id=""
                                        class="form-control select2 select2-hidden-accessible" style="width:100%;">
                                        <option value="">---Choose Day 1---</option>
                                        @foreach ($day as $item1)
                                            <option value="{{ $item1->id }}">{{ $item1->day }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Day 2</label>
                                    <select name="update_day_one" id=""
                                        class="form-control select2 select2-hidden-accessible" style="width:100%;">
                                        <option value="">---Choose Day 2---</option>
                                        @foreach ($day as $item2)
                                            <option value="{{ $item2->id }}">{{ $item2->day }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            console.log(id);
            // var program = $('#regprogramModal' + id).val();
            // var day1 = $('#regday1Modal' + id).val();
            // var day2 = $('#regday2Modal' + id).val();
            // var courseTime = $('#regcourseTimeModal' + id).val();
            // var teacher = $('#regteacherModal' + id).val();
            // $('#editJadwalModalLabel').html('Update Class ' + program + ' (' + day1 + ' & ' + day2 + ') ' + courseTime +
            //     ' ' + teacher);
            // $.ajax({
            //     type: "get",
            //     url: "{{ url('attendance/get-class?level=') }}",
            //     dataType: "json",
            //     success: function(response) {
            //         $.each(response, function(i, v) {
            //             $('#update_class').append('<option value="' + v.id + '">' + v.program +
            //                 '</option>');
            //         });
            //     }
            // });
        }

        function updateModalPrv(id) {
            var program = $('#prvprogramModal' + id).val();
            var day1 = $('#prvday1Modal' + id).val();
            var day2 = $('#prvday2Modal' + id).val();
            var courseTime = $('#prvcourseTimeModal' + id).val();
            var teacher = $('#prvteacherModal' + id).val();
            var idTeacher = $('#prvidteacherModal' + id).val();
            var dayOne = $('#prvdayOneModal' + id).val();
            var dayTwo = $('#prvdayTwoModal' + id).val();
            var class = $('#prvclassModal' + id).val();

            $('#updateClassModal').attr('action', "asds" + class);
            $('#editJadwalModalLabel').html('Update Class ' + program + ' (' + day1 + ' & ' + day2 + ') ' + courseTime +
                ' ' + teacher);
            $.ajax({
                type: "get",
                url: "{{ url('attendance/get-class?level=priv') }}",
                dataType: "json",
                success: function(response) {
                    $.each(response, function(i, v) {
                        $('#update_class').append('<option value="' + v.id + '">' + v.program +
                            '</option>');
                    });
                }
            });
        }
    </script>
@endsection
