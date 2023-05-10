@extends('template.app')

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                        <h5 class="text-white op-7 mb-2">Dashboard </h5>
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
                            <h4 class="card-title">Student's Score</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="get">
                                <div class="row">
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
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter"></i>
                                            Filter</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <div class="row">
                                @foreach ($class as $key => $item)
                                    <div class="col-sm-6 col-md-4 ">
                                        <div class="card">
                                            <div class="card-body">
                                                <span style="font-size: 16px">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <i class="fa fas fa-angle-right"></i>
                                                            @if ($item->program == 'Private' || $item->program == 'Semi Private')
                                                                @php
                                                                    $studentName = DB::table('student')
                                                                        ->where('priceid', $item->priceid)
                                                                        ->where('day1', $item->day1)
                                                                        ->where('day2', $item->day2)
                                                                        ->where('id_teacher', $item->id_teacher)
                                                                        ->where('course_time', $item->course_time)
                                                                        ->first();
                                                                @endphp
                                                                <b>{{ $studentName->name }}</b>
                                                            @else
                                                                <b> {{ $item->program }}</b>
                                                            @endif
                                                        </div>
                                                        <div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <b>{{ $item->day_one }} & {{ $item->day_two }}</b>
                                                    <br>
                                                    <b>{{ $item->course_time }}</b>
                                                </span>

                                                <div class="d-flex justify-content-between mt-4">
                                                    <div class="fw-bold">{{ $item->teacher_name }}</div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-xs btn-primary dropdown-toggle"
                                                            type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false">
                                                            View
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            @foreach ($test as $itemt)
                                                                <a class="dropdown-item"
                                                                    href="{{ url('score/form-create?test=') . $itemt->id . '&class=' . $item->priceid . '&day1=' . $item->d1 . '&day2=' . $item->d2 . '&teacher=' . $item->teacher_id . '&time=' . $item->course_time }}">{{ $itemt->name }}</a>
                                                            @endforeach
                                                        </div>
                                                    </div>
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
