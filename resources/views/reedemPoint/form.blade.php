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
                        <a href="#" class="text-white">{{$title}}</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="page-inner mt--5">
        @if (session('status'))
        <script>
            swal("Success!", "{{ session('status') }}!", {
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
            swal("Gagal!", "{{ session('error') }}!", {
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
                <form action="{{ url('/reedemPoint') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Reedem Point</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email2">Students</label>
                                        <select class="form-control select2 select2-hidden-accessible"
                                            style="width:100%;" name="student" id="student">
                                            <option value="">Select Student
                                            </option>
                                            @foreach ($students as $st)

                                            <option value="{{$st->id}}">{{$st->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('student')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4" id="point_show" style="display: none;">
                                    <div class="card">
                                        <div class="card-body mx-auto">
                                            <h6 class="card-title" id="student_name"></h6>
                                            <h1 class="text-center" id="student_point"></h1>
                                            <input type="hidden" name="point" id="point_total" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                            <div id="target" style="display: none;">
                                <div class="row ">
                                    <div class="col-md-4" style="padding-right: 0px!important">
                                        <div class="form-group">

                                            <select name="item[]" class="form-control text-black">
                                                <option>Select Item</option>
                                                @foreach ($item as $mt)
                                                <option value="{{ $mt->id }}">{{ $mt->item }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-md-3" style="padding-left: 0px!important">
                                        <div class="form-group">
                                            <input type="number" class="form-control" placeholder="Quantity reedem"
                                                required name="qty[]">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2 mb-3 button-add" style="display: none;">
                                <button id="add-target" type="button" class="btn btn-sm btn-success ml-4 mr-2"><span
                                        class="fa far fa-plus"></span> Add Item</button>
                                <button id="remove-target" type="button" class="btn btn-sm btn-danger"><span
                                        class="fa far fa-minus"></span> Remove Item</button>
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-3">

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
    var dataStudent = {!! json_encode($students)!!};
    
    
    $(document).ready(function () {
        $('#student').on('change', function () {
            var idStudent = $('select[name=student] option').filter(':selected').val();
            jQuery.each(dataStudent, function(index, item) {
                if (item.id == idStudent) {
                    $("#point_show").css("display", "block");
                    $("#target").css("display", "block");
                    $(".button-add").css("display", "block");
                    $('#student_name').text(item.name);
                    $('#student_point').text(item.total_point);
                    $('#point_total').val(item.total_point);

                }
            });
            
        });

        var length = $("#target").length;
            if (length == 1) {
                $("#remove-target").hide();
            }
            $("#add-target").click(function() {
                console.log("tambah");
                var clone = $("#target:first").clone();
                clone.find("input").val("");
                $(".button-add").before(clone);
                length += 1;
                if (length == 1) {
                    $("#remove-target").hide();
                } else {
                    $("#remove-target").show();
                }
            });
            $("#remove-target").click(function() {
                $("#target:last").remove();
                length -= 1;
                if (length == 1) {
                    $("#remove-target").hide();
                } else {
                    $("#remove-target").show();
                }
            });
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