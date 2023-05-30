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
                                    <div class="col-md-4">
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
                                            <th>Point</th>
                                            <th>Keterangan</th>
                                            <th>Type</th>
                                            <th>Saldo Point</th>
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
                                                {{-- <td>{{ ucwords($item->student->name) }}</td> --}}
                                                @if (Request::get('student'))
                                                    <td>{{ $openingBalance != null ? $openingBalance->total_point : 0 }}
                                                    </td>
                                                @endif
                                                <td>{{ $item->date }}</td>
                                                <td>{{ $item->total_point }}</td>
                                                <td>{{ $item->keterangan }}</td>
                                                <td>{{ $item->type == 'in' ? 'In' : 'Out' }}</td>
                                                <td>{{ $item->balance_in_advanced }}</td>
                                        @endforeach
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
