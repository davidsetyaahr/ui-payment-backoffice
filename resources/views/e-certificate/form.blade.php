@extends('template.app')

@section('content')
    <div class="content">
        <div class="page-inner py-5 panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div class="">
                    <h2 class="text-white pb-2 fw-bold">Last Score Student</h2>
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
                            <a href="#" class="text-white">Last Score Student</a>
                        </li>
                        <li class="separator text-white">
                            <i class="flaticon-right-arrow text-white"></i>
                        </li>
                        <li class="nav-item text-white">
                            <a href="#" class="text-white">{{ $class->program }}</a>
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
            <form action="{{ route('e-certificate.store') }}" method="POST" id="formSubmit">
                @csrf
                @foreach ($students as $student)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">{{ $student->name }} Score</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Skill</th>
                                                        @if ($class->id >= 43 && $class->id <= 45)
                                                            <th>
                                                                Test 1
                                                            </th>
                                                        @elseif ($class->id >= 22 && $class->id <= 37)
                                                            <th>
                                                                Test 1
                                                            </th>
                                                            <th>
                                                                Test 2
                                                            </th>
                                                        @else
                                                            <th>
                                                                Test 1
                                                            </th>
                                                            <th>
                                                                Test 2
                                                            </th>
                                                            <th>
                                                                Test 3
                                                            </th>
                                                        @endif
                                                        <th>Average</th>
                                                        <th>Grade</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $total_test1 = 0;
                                                        $total_test2 = 0;
                                                        $total_test3 = 0;
                                                        $total_test = 0;
                                                    @endphp
                                                    @foreach ($testItem as $item)
                                                        @php
                                                            $score1 = DB::table('student_scores')
                                                                ->join('student_score_details', 'student_score_details.student_score_id', 'student_scores.id')
                                                                ->select('student_scores.*', 'student_score_details.score as score_test', 'student_score_details.test_item_id')
                                                                ->where('student_id', $student->id)
                                                                ->where('price_id', $class->id)
                                                                ->where('test_id', 1)
                                                                ->where('student_score_details.test_item_id', $item->id)
                                                                ->first();
                                                            $score2 = DB::table('student_scores')
                                                                ->join('student_score_details', 'student_score_details.student_score_id', 'student_scores.id')
                                                                ->select('student_scores.*', 'student_score_details.score as score_test', 'student_score_details.test_item_id')
                                                                ->where('student_id', $student->id)
                                                                ->where('price_id', $class->id)
                                                                ->where('test_id', 2)
                                                                ->where('student_score_details.test_item_id', $item->id)
                                                                ->first();
                                                            $score3 = DB::table('student_scores')
                                                                ->join('student_score_details', 'student_score_details.student_score_id', 'student_scores.id')
                                                                ->select('student_scores.*', 'student_score_details.score as score_test', 'student_score_details.test_item_id')
                                                                ->where('student_id', $student->id)
                                                                ->where('price_id', $class->id)
                                                                ->where('test_id', 3)
                                                                ->where('student_score_details.test_item_id', $item->id)
                                                                ->first();
                                                            $score_test1 = $score1 ? $score1->score_test : 0;
                                                            $score_test2 = $score2 ? $score2->score_test : 0;
                                                            $score_test3 = $score3 ? $score3->score_test : 0;
                                                            $score_test = round(($score_test1 + $score_test2 + $score_test3) / 3);
                                                            $total_test1 += $score_test1;
                                                            $total_test2 += $score_test2;
                                                            $total_test3 += $score_test3;
                                                            $total_test += $score_test;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item->name }}</td>
                                                            @if ($class->id >= 43 && $class->id <= 45)
                                                                <td>{{ $score_test1 }}</td>
                                                            @elseif($class->id >= 22 && $class->id <= 37)
                                                                <td>{{ $score_test1 }}</td>
                                                                <td>{{ $score_test2 }}</td>
                                                            @else
                                                                <td>{{ $score_test1 }}</td>
                                                                <td>{{ $score_test2 }}</td>
                                                                <td>{{ $score_test3 }}</td>
                                                            @endif
                                                            <td>{{ $score_test }}</td>
                                                            <td>{{ Helper::getGrade($score_test) }}</td>
                                                            @if ($loop->iteration == 1)
                                                                <td rowspan="{{ count($testItem) + 1 }}">
                                                                    {{-- <form
                                                                    action="{{ url('e-certificate') . '/' . $student->id . '?type=done' }}"
                                                                    method="POST" id="submitForm{{ $student->id }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                </form>
                                                                <a href="javascript:void(0)" class="btn btn-success btn-sm"
                                                                    onclick="done({{ $student->id }})">Done</a> --}}
                                                                    @if ($student->is_certificate == false)
                                                                        <input type="checkbox" name="student[]"
                                                                            value="{{ $student->id }}">
                                                                    @endif
                                                                    <a class="btn btn-sm btn-warning dropdown-toggle"
                                                                        type="button" id="dropdownMenuButton"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false">
                                                                        Edit
                                                                    </a>
                                                                    <div class="dropdown-menu"
                                                                        aria-labelledby="dropdownMenuButton">
                                                                        @if ($class->id >= 43 && $class->id <= 45)
                                                                            @if ($score1)
                                                                                <a href="{{ url('score/create?type=edit&class=') . $student->priceid . '&student=' . $student->id . '&test=1&id_test=' . $score1->id . '&date=' . $score1->date . '&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="dropdown-item">Test 1</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('score/create?type=create&class=') . $student->priceid . '&student=' . $student->id . '&test=1&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="btn btn-sm btn-primary">Test
                                                                                    1</a>
                                                                            @endif
                                                                        @elseif($class->id >= 22 && $class->id <= 37)
                                                                            @if ($score1)
                                                                                <a href="{{ url('score/create?type=edit&class=') . $student->priceid . '&student=' . $student->id . '&test=1&id_test=' . $score1->id . '&date=' . $score1->date . '&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="dropdown-item">Test 1</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('score/create?type=create&class=') . $student->priceid . '&student=' . $student->id . '&test=1&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="btn btn-sm btn-primary">Test
                                                                                    1</a>
                                                                            @endif
                                                                            @if ($score2)
                                                                                <a href="{{ url('score/create?type=edit&class=') . $student->priceid . '&student=' . $student->id . '&test=2&id_test=' . $score2->id . '&date=' . $score2->date . '&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="dropdown-item">Test 2</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('score/create?type=create&class=') . $student->priceid . '&student=' . $student->id . '&test=2&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="btn btn-sm btn-primary">Test
                                                                                    2</a>
                                                                            @endif
                                                                        @else
                                                                            @if ($score1)
                                                                                <a href="{{ url('score/create?type=edit&class=') . $student->priceid . '&student=' . $student->id . '&test=1&id_test=' . $score1->id . '&date=' . $score1->date . '&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="dropdown-item">Test 1</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('score/create?type=create&class=') . $student->priceid . '&student=' . $student->id . '&test=1&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="btn btn-sm btn-primary">Test
                                                                                    1</a>
                                                                            @endif
                                                                            @if ($score2)
                                                                                <a href="{{ url('score/create?type=edit&class=') . $student->priceid . '&student=' . $student->id . '&test=2&id_test=' . $score2->id . '&date=' . $score2->date . '&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="dropdown-item">Test 2</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('score/create?type=create&class=') . $student->priceid . '&student=' . $student->id . '&test=2&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="btn btn-sm btn-primary">Test
                                                                                    2</a>
                                                                            @endif
                                                                            @if ($score3)
                                                                                <a href="{{ url('score/create?type=edit&class=') . $student->priceid . '&student=' . $student->id . '&test=3&id_test=' . $score3->id . '&date=' . $score3->date . '&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="dropdown-item">Test 3</a>
                                                                            @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('score/create?type=create&class=') . $student->priceid . '&student=' . $student->id . '&test=3&day1=' . $student->day1 . '&day2=' . $student->day2 . '&teacher=' . $student->id_teacher . '&time=' . $student->course_time }}"
                                                                                    class="btn btn-sm btn-primary">Test
                                                                                    3</a>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        @if (Request::get('class') >= 43 && Request::get('class') <= 45)
                                                            <td>Total</td>
                                                            <td>{{ round($total_test1 / count($testItem)) }}</td>
                                                            <td>{{ round($total_test / count($testItem)) }}</td>
                                                            <td>{{ Helper::getGrade(round($total_test / count($testItem))) }}
                                                            </td>
                                                        @elseif(Request::get('class') >= 22 && Request::get('class') <= 37)
                                                            <td colspan="2">Total</td>
                                                            <td>{{ round($total_test1 / count($testItem)) }}</td>
                                                            <td>{{ round($total_test2 / count($testItem)) }}</td>
                                                            <td>{{ round($total_test / count($testItem)) }}</td>
                                                            <td>
                                                                {{ Helper::getGrade(round($total_test / count($testItem))) }}
                                                            </td>
                                                        @else
                                                            <td colspan="2">Total</td>
                                                            <td>{{ round($total_test1 / count($testItem)) }}</td>
                                                            <td>{{ round($total_test2 / count($testItem)) }}</td>
                                                            <td>{{ round($total_test3 / count($testItem)) }}</td>
                                                            <td>{{ round($total_test / count($testItem)) }}</td>
                                                            <td>
                                                                {{ Helper::getGrade(round($total_test / count($testItem))) }}
                                                            </td>
                                                        @endif
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success btn-sm" type="button" onclick="confirm()">Done</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function done(id) {
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
                    $('#submitForm' + id).submit();
                }
            });
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
                    $('#formSubmit').submit();
                }
            });
        }
    </script>
@endsection
