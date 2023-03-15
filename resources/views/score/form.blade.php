@extends('template.app')

@section('content')
<style>
    .form-table {
        font-size: 14px;
        border-color: #ebedf2;
        padding: 0.6rem 1rem;
        height: 35px !important;
        display: block;
        width: 100%;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
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
                        <a href="#" class="text-white">Score</a>
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
                <form action="{{ $data->type == 'create' ? url('score/store') : route('score.update', $data->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($data->type != 'create')
                    @method('PUT')
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$data->type == 'create' ? 'Tambah Data'  : 'Edit Data'}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">

                                    <label for="email2">Class</label>
                                    <select class="form-control select2 select2-hidden-accessible" style="width:100%;"
                                        name="class">
                                        <option value="">Select Class
                                        </option>
                                        {{-- @foreach ($class as $st)

                                            <option value="{{$st->id}}">{{$st->name}}
                                        </option>
                                        @endforeach --}}
                                    </select>
                                    @error('class')
                                    <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror


                                </div>
                                <div class="col-md-3">

                                    <label for="email2">Students</label>
                                    <select class="form-control select2 select2-hidden-accessible" style="width:100%;"
                                        name="student">
                                        <option value="">Select Student
                                        </option>
                                        @foreach ($students as $st)

                                        <option value="{{$st->id}}">{{$st->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('student')
                                    <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror


                                </div>
                                <div class="col-md-3">

                                    <label for="email2">Test</label>
                                    <select class="form-control select2 select2-hidden-accessible" style="width:100%;"
                                        name="test">
                                        <option value="">Select Test
                                        </option>
                                        @foreach ($test as $st)

                                        <option value="{{$st->id}}">{{$st->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('test')
                                    <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror


                                </div>
                                <div class="col-md-2">

                                    <label for="email2">Date</label>
                                    <input type="date" class="form-control" name="date" placeholder="Date" />
                                    @error('date')
                                    <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror

                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-sm btn-primary mt-4">Filter</button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="display table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Item</th>
                                                    <th>Score</th>
                                                    <th >Grade</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                $no = 1;
                                                @endphp
                                                @foreach ($item as $it)
                                                <tr style="height: 40px!important">
                                                    <td style="height: 40px!important; padding: 8px 16px!important;">{{ $no }}</td>
                                                    <td style="height: 40px!important; padding: 8px 16px!important;">{{ $it->name}}</td>
                                                    <td style="height: 40px!important; padding: 8px 16px!important;"><input type="number" name="score[]" class="form-table" id="">
                                                    </td>
                                                    <td style="height: 40px!important; padding: 8px 16px!important; text-align:center;"  >
                                                        <h6>A</h6>
                                                    </td>
                                                </tr>
                                                @php
                                                $no++;
                                                @endphp
                                                @endforeach
                                                <tr>
                                                    <td colspan="2">Average</td>
                                                    <td  style="height: 40px!important; padding: 8px 16px!important;"><input type="number" name="total" class="form-table" readonly id="">
                                                    </td>
                                                    <td style="height: 40px!important; padding: 8px 16px!important; text-align:center;"><h6>A</h6></td>
                                                </tr>
                                            </tbody>
                                           
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Comment For Student</label>
                                        <textarea name="" class="form-control" id="" cols="30" rows="3"></textarea>
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
