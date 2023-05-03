@extends('template.app')

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Opening Balance</h2>
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
                            {{-- <form action="" method="get">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Teacher</label>
                                        <select name="teacher" id="teacher" class="form-control select2">
                                            <option value="">---Choose Teacher---</option>
                                            @foreach ($teachers as $t)
                                                <option value="{{ $t->id }}"
                                                    {{ Request::get('teacher') == $t->id ? 'selected' : '' }}>
                                                    {{ $t->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2" style="margin-top:20px;">
                                        <button class="btn btn-primary text-white"><i class="fas fa-filter"></i>
                                            Filter</button>
                                    </div>
                                </div>
                            </form>
                            <hr> --}}
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
                                                    </div>
                                                    <br>
                                                    <b>{{ $item->day_one }} {{ $item->day1 != $item->day2 ? '&' : '' }}
                                                        {{ $item->day1 != $item->day2 ? $item->day_two : '' }}</b>
                                                    <br>
                                                    <b>{{ $item->course_time }}</b>
                                                </span>

                                                <div class="d-flex justify-content-between mt-4">
                                                    <div class="fw-bold">{{ $item->teacher_name }}</div>
                                                    <a href="{{ url('saldo-awal/form/' . $item->priceid . '?day1=' . $item->day1 . '&day2=' . $item->day2 . '&time=' . $item->course_time) . '&teacher=' . $item->id_teacher }}"
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
                                                    </div> <i class="fa fas fa-angle-right"></i>
                                                    <b>{{ $item->day_one }} {{ $item->day1 != $item->day2 ? '&' : '' }}
                                                        {{ $item->day1 != $item->day2 ? $item->day_two : '' }}</b>
                                                    <br>
                                                    <b>{{ $item->course_time }}</b>
                                                </span>

                                                <div class="d-flex justify-content-between mt-4">
                                                    <div class="fw-bold">{{ $item->teacher_name }}</div>
                                                    <a href="{{ url('saldo-awal/form/' . $item->priceid . '?day1=' . $item->day1 . '&day2=' . $item->day2 . '&time=' . $item->course_time) }}"
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

        </div>
    </div>
@endsection