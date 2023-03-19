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
    <div class="page-inner py-5 panel-header bg-primary-gradient">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div class="">
                <h2 class="text-white pb-2 fw-bold">{{$title}}</h2>
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
                        <a href="#" class="text-white">Attendance</a>
                    </li>
                    <li class="separator text-white">
                        <i class="flaticon-right-arrow text-white"></i>
                    </li>
                    <li class="nav-item text-white">
                        <a href="#" class="text-white">{{$title}}</a>
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
                <form
                    action="{{ $data->type == 'create' ? route('announces.store') : route('announces.update', $data->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($data->type != 'create')
                    @method('PUT')
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$data->type == 'create' ? 'Tambah Data' : 'Edit Data'}}</h4>
                        </div>
                        <div class="card-body">


                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="">
                                        <table
                                            class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Name</th>
                                                    <th class="text-center" scope="col" class="w-5"
                                                        style="min-width:3px;">Absent</th>
                                                    <th class="text-center">In Point</th>
                                                    <th class="text-center">Category</th>
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                $no = 1;
                                                @endphp
                                                @foreach ($student as $it)
                                                <tr style="height: 40px!important">
                                                    <td class="text-center" style="">{{
                                                        $no }}</td>
                                                    <td style="">{{
                                                        $it->name}}</td>
                                                    <td class=" text-center" scope="col" style="width:3px!important;">
                                                        <input type="checkbox"
                                                            aria-label="Checkbox for following text input">
                                                    </td>
                                                    <td class="text-center" style="">
                                                        <h5>0</h5>
                                                    </td>
                                                    <td style="">
                                                        <select class="form-control select2 select2-hidden-accessible"
                                                            style="width:100%;" name="student[]" multiple="">

                                                            @foreach ($pointCategories as $st)

                                                            <option value="{{$st->id}}">{{$st->name}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="">
                                                        <h5>0</h5>
                                                    </td>

                                                </tr>
                                                @php
                                                $no++;
                                                @endphp
                                                @endforeach

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <h2 class="mt-3">Agenda</h2>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Comment For Student</label>
                                        <textarea name="comment" class="form-control" id="" cols="30"
                                            rows="3"></textarea>
                                    </div>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Text Book</label>
                                        <input type="text" class="form-control" name="textBook">
                                    </div>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Excercise Book</label>
                                        <input type="text" class="form-control" name="excerciseBook">
                                    </div>

                                </div>
                            </div>


                        </div>
                        <div class="card-action mt-3">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="button" data-toggle="modal" data-target="#mdlCancel"
                                class="btn btn-danger">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="mdlCancel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah anda yakin ingin membatalkan proses?</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ url('/advertise') }}"><button type="button" class="btn btn-success">Ya</button></a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#summernote').summernote();
    });
    $(document).ready(function () {
        $('#kandungan').summernote();
    });
    $(document).ready(function () {
        $('#aturan').summernote();
    });

</script>

<script>
    $(document).ready(function () {
        // Basic
        $('.dropify').dropify();


        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function (event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function (event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function (event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function (e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });

</script>
@endsection