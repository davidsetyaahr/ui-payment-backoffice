@extends('template.app')

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Students Follow Up</h2>
                        {{-- <h5 class="text-white op-7 mb-2">Free Bootstrap 4 Admin Dashboard</h5> --}}
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            @if (session('status'))
                <script>
                    swal("Success", "{{ session('status') }}!", {
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
                    swal("Failed", "{{ session('error') }}!", {
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
                            <h4 class="card-title">Data Students</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Student Name</th>
                                            <th>Old Class</th>
                                            <th>Old Time Course</th>
                                            <th>Old Teacher</th>
                                            <th>Bulk</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $item)
                                            @php
                                                $score1 = DB::table('student_scores')
                                                    ->join('student_score_details', 'student_score_details.student_score_id', 'student_scores.id')
                                                    ->select('student_scores.*', 'student_score_details.score as score_test', 'student_score_details.test_item_id')
                                                    ->where('student_id', $item->student_id)
                                                    ->where('price_id', $item->old_price_id)
                                                    ->where('test_id', 1)
                                                    ->first();
                                                $score2 = DB::table('student_scores')
                                                    ->join('student_score_details', 'student_score_details.student_score_id', 'student_scores.id')
                                                    ->select('student_scores.*', 'student_score_details.score as score_test', 'student_score_details.test_item_id')
                                                    ->where('student_id', $item->student_id)
                                                    ->where('price_id', $item->old_price_id)
                                                    ->where('test_id', 2)
                                                    ->first();
                                                $score3 = DB::table('student_scores')
                                                    ->join('student_score_details', 'student_score_details.student_score_id', 'student_scores.id')
                                                    ->select('student_scores.*', 'student_score_details.score as score_test', 'student_score_details.test_item_id')
                                                    ->where('student_id', $item->student_id)
                                                    ->where('price_id', $item->old_price_id)
                                                    ->where('test_id', 3)
                                                    ->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $item->student_id }}</td>
                                                <td>{{ ucwords($item->student->name) }}</td>
                                                <td>{{ $item->class->program }}</td>
                                                <td>{{ $item->day1 . '-' . $item->day2 . '/' . $item->course_time }}
                                                </td>
                                                <td>{{ $item->teacher->name }}</td>
                                                <td><input type="checkbox" name="student_id[]"
                                                        id="studentId{{ $item->id }}"
                                                        onclick="onClickFollowUp({{ $item->id }})"
                                                        value="{{ $item->id }}"></td>
                                                <td class=" d-flex">
                                                    <form action="{{ route('follow-up.destroy', $item->id) }}"
                                                        method="POST" class="form-inline" id="formDelete">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="button" onclick="confirm()"
                                                            class="btn btn-xs btn-danger"><i
                                                                class="fas fa-trash"></i></button>
                                                        <a class="btn btn-xs btn-warning dropdown-toggle" type="button"
                                                            id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            Edit
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            @if ($item->old_class_id >= 43 && $item->old_class_id <= 45)
                                                                @if ($score1)
                                                                    <a href="{{ url('score/create?type=edit&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=1&id_test=' . $score1->id . '&date=' . $score1->date . '&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="dropdown-item">Test 1</a>
                                                                @else
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('score/create?type=create&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=1&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="btn btn-sm btn-primary">Test
                                                                        1</a>
                                                                @endif
                                                            @elseif($item->old_class_id >= 22 && $item->old_class_id <= 37)
                                                                @if ($score1)
                                                                    <a href="{{ url('score/create?type=edit&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=1&id_test=' . $score1->id . '&date=' . $score1->date . '&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="dropdown-item">Test 1</a>
                                                                @else
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('score/create?type=create&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=1&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="btn btn-sm btn-primary">Test
                                                                        1</a>
                                                                @endif
                                                                @if ($score2)
                                                                    <a href="{{ url('score/create?type=edit&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=2&id_test=' . $score2->id . '&date=' . $score2->date . '&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="dropdown-item">Test 2</a>
                                                                @else
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('score/create?type=create&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=2&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="btn btn-sm btn-primary">Test
                                                                        2</a>
                                                                @endif
                                                            @else
                                                                @if ($score1)
                                                                    <a href="{{ url('score/create?type=edit&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=1&id_test=' . $score1->id . '&date=' . $score1->date . '&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="dropdown-item">Test 1</a>
                                                                @else
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('score/create?type=create&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=1&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="btn btn-sm btn-primary">Test
                                                                        1</a>
                                                                @endif
                                                                @if ($score2)
                                                                    <a href="{{ url('score/create?type=edit&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=2&id_test=' . $score2->id . '&date=' . $score2->date . '&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="dropdown-item">Test 2</a>
                                                                @else
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('score/create?type=create&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=2&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="btn btn-sm btn-primary">Test
                                                                        2</a>
                                                                @endif
                                                                @if ($score3)
                                                                    <a href="{{ url('score/create?type=edit&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=3&id_test=' . $score3->id . '&date=' . $score3->date . '&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="dropdown-item">Test 3</a>
                                                                @else
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('score/create?type=create&class=') . $item->old_price_id . '&student=' . $item->student_id . '&test=3&day1=' . $item->old_day_1 . '&day2=' . $item->old_day_2 . '&teacher=' . $item->old_teacher_id . '&time=' . $item->course_time }}"
                                                                        class="btn btn-sm btn-primary">Test
                                                                        3</a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4" style="text-align: end;">
                                <button type="button" onclick="submitBulkDelete()" id="buttonBulkDelete"
                                    class="btn btt-lg btn-danger" disabled><i class="fas fa-trash"></i> Bulk Delete</button>
                                <form action="{{ route('bulk.follow-up') }}" method="POST" class="form-inline"
                                    id="formBulkDelete">
                                    @method('delete')
                                    @csrf
                                    <div id="idSiswaFollowUp"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
                    $('#formDelete').submit();
                }
            });
        }

        const dataStudent = {
            id: [],
        };

        function onClickFollowUp(id) {
            const checkbox = document.getElementById('studentId' + id);
            if (checkbox.checked) {
                if (dataStudent.id.indexOf(id) === -1) {
                    dataStudent.id.push(id);
                    var newElement = $(`<input type="hidden" id="idSiswa${id}" name="student_id[]" value="${id}">`);
                    $("#idSiswaFollowUp").append(newElement);
                    if (dataStudent.id.length != 0) {
                        $('#buttonBulkDelete').prop('disabled', false);
                    } else {
                        $('#buttonBulkDelete').prop('disabled', true);
                    }
                }
            } else {
                const index = dataStudent.id.indexOf(id);
                $("#idSiswa" + id).remove();
                if (index !== -1) {
                    dataStudent.id.splice(index, 1);
                    if (dataStudent.id.length != 0) {
                        $('#buttonBulkDelete').prop('disabled', false);
                    } else {
                        $('#buttonBulkDelete').prop('disabled', true);
                    }
                }
            }
        }

        function submitBulkDelete() {
            swal("Are you sure ?", "Data will be bulk updated", {
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
                    $('#formBulkDelete').submit();
                }
            });
        }
    </script>
@endsection
