@extends('template.app')

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">{{ $title }} </h2>
                        {{-- <h5 class="text-white op-7 mb-2">Free Bootstrap 4 Admin Dashboard</h5> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            @if (session('status'))
                <script>
                    swal("Berhasil", "{{ session('status') }}!", {
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
                    swal("Gagal", "{{ session('status') }}!", {
                        icon: "danger",
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
                            <h4 class="card-title">{{ $title }}</h4>
                            <br>
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-3">
                                        <input type="date" class="form-control" name="from"
                                            value="{{ Request::get('from') }}" required>
                                    </div>
                                    <div class="col-3">
                                        <input type="date" class="form-control" name="to"
                                            value="{{ Request::get('to') }}" required>
                                    </div>
                                    <div class="col-3">
                                        <select name="student" id="" class="form-control select2">
                                            <option value="">---Choose Student---</option>
                                            @foreach ($student as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ Request::get('student') == $item->id ? 'selected' : '' }}>
                                                    {{ ucwords($item->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter"></i>
                                            Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            @if (Request::get('student'))
                                                <th>Opening Balance</th>
                                            @endif
                                            <th>Date</th>
                                            <th>Date Time</th>
                                            <th>Point History</th>
                                            <th>Detail</th>
                                            <th>Type</th>
                                            <th>Last Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp

                                        @foreach ($data as $item)
                                            @php
                                                if (Request::get('student')) {
                                                    $openingBalance = DB::table('point_histories')
                                                        ->where('student_id', $item->student->id)
                                                        ->where('keterangan', 'Opening Balance')
                                                        ->first();
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $item->student ? ucwords($item->student->name) : '-' }}</td>
                                                @if (Request::get('student'))
                                                    <td>{{ $openingBalance != null ? $openingBalance->total_point : 0 }}
                                                    </td>
                                                @endif
                                                <td> {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                                <td>{{ $item->created_at == null ? '-' : $item->created_at }}</td>
                                                <td>{{ $item->total_point }} </td>
                                                <td>{{ $item->keterangan }}</td>
                                                <td>{{ $item->type == 'in' ? 'In' : 'Out' }}</td>
                                                <td>{{ $item->balance_in_advanced }}</td>
                                        @endforeach


                                        {{-- @php
                                            $lastDate = null;
                                        @endphp
                                        @foreach ($data as $item)
                                            @if ($lastDate != $item->date)
                                                @php
                                                    if (Request::get('student')) {
                                                        $openingBalance = DB::table('point_histories')
                                                            ->where('student_id', $item->student->id)
                                                            ->where('keterangan', 'Opening Balance')
                                                            ->first();
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $item->student ? ucwords($item->student->name) : '-' }}</td>
                                                    @if (Request::get('student'))
                                                        <td>{{ $openingBalance != null ? $openingBalance->total_point : 0 }}
                                                        </td>
                                                    @endif
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->created_at == null ? '-' : $item->created_at }}</td>
                                                    <td>{{ $item->total_point }} </td>
                                                    <td>{{ $item->keterangan }}</td>
                                                    <td>{{ $item->type == 'in' ? 'In' : 'Out' }}</td>
                                                    <td>{{ $item->balance_in_advanced }}</td>
                                                </tr>
                                                @php
                                                    $lastDate = $item->date;
                                                @endphp
                                            @endif
                                        @endforeach --}}

                                        {{-- @php
                                            $lastDate = null;
                                            $totalPoint = 0;
                                        @endphp
                                        @foreach ($data as $item)
                                            @if ($lastDate != $item->date)
                                                @php
                                                    if (Request::get('student')) {
                                                        $openingBalance = DB::table('point_histories')
                                                            ->where('student_id', $item->student->id)
                                                            ->where('keterangan', 'Opening Balance')
                                                            ->first();
                                                    }
                                                @endphp
                                                @if ($lastDate != null)
                                                    <tr>
                                                        <td colspan="5">Total Point: {{ $totalPoint }}</td>
                                                    </tr>
                                                    @php
                                                        $totalPoint = 0;
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $item->student ? ucwords($item->student->name) : '-' }}</td>
                                                    @if (Request::get('student'))
                                                        <td>{{ $openingBalance != null ? $openingBalance->total_point : 0 }}
                                                        </td>
                                                    @endif
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->created_at == null ? '-' : $item->created_at }}</td>
                                                    <td>{{ $item->total_point }} </td>
                                                    <td>{{ $item->keterangan }}</td>
                                                    <td>{{ $item->type == 'in' ? 'In' : 'Out' }}</td>
                                                    <td>{{ $item->balance_in_advanced }}</td>
                                                </tr>
                                                @php
                                                    $lastDate = $item->date;
                                                    $totalPoint += $item->total_point;
                                                @endphp
                                            @else
                                                @php
                                                    $totalPoint += $item->total_point;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if ($lastDate != null)
                                            <tr>
                                                <td colspan="5">Total Point: {{ $totalPoint }}</td>
                                            </tr>
                                        @endif --}}
                                        {{--
                                        @php
                                            $lastDate = null;
                                            $lastTotalPoint = 0;
                                        @endphp
                                        @foreach ($data as $item)
                                            @if ($lastDate != $item->date)
                                                @php
                                                    if (Request::get('student')) {
                                                        $openingBalance = DB::table('point_histories')
                                                            ->where('student_id', $item->student->id)
                                                            ->where('keterangan', 'Opening Balance')
                                                            ->first();
                                                    }
                                                @endphp
                                                @if ($lastDate != null)
                                                    <tr>
                                                        <td colspan="5">Total Point: {{ $lastTotalPoint }}</td>
                                                        <td colspan="4">Balance In Advance:
                                                            {{ $item->balance_in_advanced + $lastTotalPoint }}</td>
                                                    </tr>
                                                    @php
                                                        $lastTotalPoint = 0;
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $item->student ? ucwords($item->student->name) : '-' }}</td>
                                                    @if (Request::get('student'))
                                                        <td>{{ $openingBalance != null ? $openingBalance->total_point : 0 }}
                                                        </td>
                                                    @endif
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->created_at == null ? '-' : $item->created_at }}</td>
                                                    <td>{{ $item->total_point }} </td>
                                                    <td>{{ $item->keterangan }}</td>
                                                    <td>{{ $item->type == 'in' ? 'In' : 'Out' }}</td>
                                                    <td>{{ $item->balance_in_advanced }}</td>
                                                </tr>
                                                @php
                                                    $lastDate = $item->date;
                                                    $lastTotalPoint += $item->total_point;
                                                @endphp
                                            @else
                                                @php
                                                    $lastTotalPoint += $item->total_point;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if ($lastDate != null)
                                            <tr>
                                                <td colspan="5">Total Point: {{ $lastTotalPoint }}</td>
                                                <td colspan="4">Balance In Advance:
                                                    {{ $item->balance_in_advanced + $lastTotalPoint }}</td>
                                            </tr>
                                        @endif --}}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>




            </div>


        </div>
    </div>
@endsection
