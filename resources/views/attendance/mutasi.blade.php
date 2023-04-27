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
                    <h2 class="text-white pb-2 fw-bold">Mutasi</h2>
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
                            <a href="#" class="text-white">Mutasi</a>
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
                            @php
                                $getStudent = DB::table('student')
                                    ->where('id', Request::get('student'))
                                    ->first();
                            @endphp
                            <h4 class="card-title">Mutasi Siswa {{ Request::get('student') ? $getStudent->name : '' }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">

                                    <label for="email2">Class</label>
                                    <select class="form-control select2 select2-hidden-accessible" style="width:100%;"
                                        name="price" id="price">
                                        <option value="">Select class</option>
                                        @foreach ($price as $st)
                                            <option value="{{ $st->id }}"
                                                {{ Request::get('class') == $st->id ? 'selected' : '' }}>{{ $st->program }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror

                                    @error('class')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror


                                </div>
                                <div class="col-md-3">

                                    <label for="email2">Students</label>
                                    <select class="form-control select2 select2-hidden-accessible" style="width:100%;"
                                        name="student1" id="student1">
                                        <option value="">Select Student</option>

                                    </select>
                                    @error('student')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror


                                </div>
                                <div class="col-md-3">

                                    <label for="email2">Id Student</label>
                                    <input type="number" name="student2" id="student2" class="form-control"
                                        value="{{ Request::get('student') }}">
                                    @error('student')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror


                                </div>
                                <div class="col-md-3" style="margin-top:20px">
                                    <button type="submit" class="btn btn-primary" onclick="filter()" id="filter"><i
                                            class="fas fa-filter"></i>
                                        Filter</button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="">
                                        @if (Request::get('student'))
                                            <table
                                                class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">No</th>
                                                        <th class="text-center">Kelas</th>
                                                        <th class="text-center">Average Score</th>
                                                        <th class="text-center">Average Grade</th>
                                                        {{-- <th class="text-center">Action</th> --}}
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php
                                                        $no = 1;
                                                        $score = '';
                                                    @endphp
                                                    @foreach ($data as $item)
                                                        @php
                                                            $score = DB::table('student_scores')
                                                                ->where('student_id', $item->student_id)
                                                                ->where('price_id', $item->price_id)
                                                                ->first();
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $item->program }}</td>
                                                            <td>{{ $score != null ? $score->average_score : '-' }}
                                                            </td>
                                                            <td>{{ $score != null ? Helper::getGrade($score->average_score) : '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            <div class="pull-right">
                                                {{ $data->appends(Request::all())->links() }}
                                            </div>
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
    <script>
        $(document).ready(function() {
            function getStudent() {
                var typeClass = $('#price').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ url('') }}/score/students/filter?class=' + typeClass,
                    dataType: 'JSON',
                    success: function(data) {

                        var $student = $('#student1');
                        $student.empty();
                        $student.append('<option value="">Select Student</option>');
                        for (var i = 0; i < data.length; i++) {
                            $student.append(
                                `<option id='${data[i].id}' value='${data[i].id}' ${data[i].id == "{{ Request::get('student') }}" ? 'selected' : ''}>${data[i].name}</option>`
                            );
                        }
                        $student.change();

                    }
                });
            }
            getStudent();
            $('#price').on('change', function() {
                getStudent()
            });
            $('#student1').on('change', function() {
                $('#student2').val('');
            });
        });
    </script>
    <script>
        function filter() {
            var student1 = $('#student1').val();
            var student2 = $('#student2').val();
            var price = $('#price').val();
            var student = '';
            if (student1 != '' && student2 == '') {
                student = student1
            } else if (student1 != '' && student2 != '') {
                student = student2
                // alert('Pilih salah satu dari student atau id student')
            } else {
                student = student2
            }
            window.location = " {{ url('mutasi?student=') }}" + student + '&class=' + price;
        }
    </script>
@endsection