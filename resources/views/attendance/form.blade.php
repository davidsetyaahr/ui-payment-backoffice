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
                    action="{{ $data->type == 'create' ? url('attendance/store') : route('announces.update', $data->id) }}"
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
                                                        <input type="hidden" name="isAbsent[{{$no}}][]" value="0">
                                                        <input type="checkbox" class="form-check-input cekBox"
                                                            id="cbAbsent{{$no}}" value="1"
                                                            aria-label="Checkbox for following text input"
                                                            name="isAbsent[{{$no}}][]">
                                                    </td>
                                                    <td class="text-center" style="">
                                                        <h5 id="inPointAbsent{{$no}}">0</h5>
                                                    </td>
                                                    <td style="">
                                                        <select class="form-control select2 select2-hidden-accessible"
                                                            style="width:100%;" name="categories[{{$no}}][]"
                                                            id="categories{{$no}}" multiple="multiple">

                                                            @foreach ($pointCategories as $st)

                                                            <option value="{{$st->id}}">{{$st->name}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-center" style="">
                                                        <input type="hidden" name="totalPoint[]"
                                                            id="inpTotalPoint{{$no}}" value="0" readonly>
                                                        <h5 id="totalPoint{{$no}}">0</h5>
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
        var dataCtgr = JSON.parse('{!! $pointCategories!!}');
        
        var len = $('.cekBox').length;
        
        for (let i = 1; i <= len; i++) {
            var totalPoint = parseInt($("#totalPoint"+i).text());
            $('#cbAbsent'+i).click('change', function(){
                if($(this).is(':checked')){
                    $("#inPointAbsent"+i).text(parseInt(10));
                    $("#totalPoint"+i).text(parseInt($("#totalPoint"+i).text()) + 10);
                    $("#inpTotalPoint"+i).val(parseInt($("#inpTotalPoint"+i).val() != '' ? $("#inpTotalPoint"+i).val():0) + 10);
                } else {
                    $("#totalPoint"+i).text(parseInt($("#totalPoint"+i).text()) - 10);
                    $("#inpTotalPoint"+i).val(parseInt($("#inpTotalPoint"+i).val() != '' ? $("#inpTotalPoint"+i).val():0) - 10);
                    $("#inPointAbsent"+i).text(0);
                }
            });
            
            $('#categories'+i).change(function() {
                var tmpTotalPoint = 0;
                var getVal =$('#categories'+i).val();
                dataCtgr.forEach(element => {
                    getVal.forEach(x => {
                        if (element.id.toString() == x.toString()) {
                            tmpTotalPoint += element.point;
                        }
                    })
                });
                
                $("#totalPoint"+i).text(tmpTotalPoint + parseInt($("#inPointAbsent"+i).text()));
                
            });
        }
       
    });
   

</script>

<script>


</script>
@endsection