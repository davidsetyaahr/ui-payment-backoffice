@extends('template.app')

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                        <h5 class="text-white op-7 mb-2">Dashboard Sitani Web</h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            @if (session('message'))
                <script>
                    swal("Login Berhasil", "{{ session('message') }}!", {
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
                            <h4 class="card-title">Schedule Class</h4>

                        </div>
                        <div class="card-body">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="">Class</label>
                                        <select name="class" id="" class="form-control select2" required>
                                            <option value="">---Select Class---</option>
                                            @foreach ($class as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ Request::get('class') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->program }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Day</label>
                                        <select name="day" id="" class="form-control select2" required>
                                            <option value="">---Select Day---</option>
                                            @foreach ($day as $itema)
                                                <option
                                                    value="{{ $itema->id }}"{{ Request::get('day') == $itema->id ? 'selected' : '' }}>
                                                    {{ $itema->day }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Time</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="time"
                                                    value="{{ Request::get('time') ? Request::get('time') : '' }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="ampm" id="" class="form-control select2" required>
                                                    <option value="AM"
                                                        {{ Request::get('ampm') == 'AM' ? 'selected' : '' }}>AM</option>
                                                    <option value="PM"
                                                        {{ Request::get('ampm') == 'PM' ? 'selected' : '' }}>PM</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i>
                                            Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr>
                        @if (Request::get('class') && Request::get('day') && Request::get('time') && Request::get('ampm'))
                            <form action="{{ url('/schedule-class') }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="">
                                                <table
                                                    class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="10px"><input type="checkbox"
                                                                    id="checkAll">
                                                            </th>
                                                            <th class="text-center">Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $key = 0;
                                                        @endphp
                                                        @foreach ($data as $item)
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" name="upcls[]"
                                                                        class="updateCheck" id="upCls{{ $key++ }}"
                                                                        value="{{ $item->id }}"
                                                                        {{ Request::get('day') == $item->day1 ? 'checked' : '' }}>
                                                                    <input type="hidden" name="day"
                                                                        value="{{ Request::get('day') }}">
                                                                </td>
                                                                <td>
                                                                    {{ $item->name }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action mt-3">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>



        </div>
    </div>
    <script>
        $('#checkAll').change(function() {
            if ($(this).is(':checked')) {
                $('.updateCheck').prop('checked', true);
            } else {
                $('.updateCheck').prop('checked', false);
            }
        });
    </script>
@endsection
