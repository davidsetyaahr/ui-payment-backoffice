@extends('template.app')

@section('content')
    <div class="content">
        <div class="panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Order Review & Test Paper</h2>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="card-title">Data Order Review & Test Paper</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Teacher</th>
                                            <th>Class</th>
                                            <th>Review / Test</th>
                                            <th>Due Date</th>
                                            <th>QTY</th>
                                            <th>Confirm</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->teacher->name }}</td>
                                                <td>{{ $item->class }}</td>
                                                <td>{{ $item->review_test }}</td>
                                                <td>{{ $item->due_date }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>
                                                    @if ($item->is_done == 0)
                                                        <a href="javascript:void(0)"
                                                            onclick="confirm({{ $item->id_attendance }})"
                                                            class="btn btn-sm btn-success">Done</a>
                                                    @endif
                                                </td>
                                            </tr>
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
    <script>
        function confirm(id) {
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
                    window.location = "{{ url('review-done') . '/' }}" + id
                }
            });
        }
    </script>
@endsection
