@extends('template.app')

@section('content')
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
                        <a href="#" class="text-white">Advertise</a>
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
                    action="{{ $data->type == 'create' ? route('advertise.store') : route('advertise.update', $data->id) }}"
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email2">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            value="{{ $data->title }}" name="title" placeholder="Title">
                                        @error('title')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email2">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                            value="{{ old('description') }}" name="description"
                                            placeholder="Description">{{ $data->description}}</textarea>

                                        @error('description')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email2">Banner</label>
                                        <input type="file" name="banner" id="input-file-now-custom-1"
                                            class="dropify @error('banner') is-invalid @enderror"
                                            data-default-file="{{url('/storage/'.$data->banner)}}" />
                                        @error('banner')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                        @enderror
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
