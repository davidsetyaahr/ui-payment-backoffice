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
        @if (session('success'))
        <script>
            swal("Success!", "{{ session('success') }}!", {
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
                <form action="{{url('score/store')}}" id="formScore" {{-- <form
                    action="{{ $data->type == 'create' ? url('score/store') : route('score.update', $data->id) }}" --}}
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{$data->type == 'create' ? 'Tambah Data' : 'Edit Data'}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">

                                    <label for="email2">Class</label>
                                    <select class="form-control" style="width:100%;" name="class" id="class">
                                        <option value="">Select Class
                                        </option>
                                        <option value="Private">Private</option>
                                        <option value="General">General</option>
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
                                        name="student" id="student">
                                        <option value="">Select Student</option>

                                    </select>
                                    @error('student')
                                    <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror


                                </div>
                                <div class="col-md-3">

                                    <label for="email2">Test</label>
                                    <select class="form-control select2 select2-hidden-accessible" style="width:100%;"
                                        name="test" id="test">
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
                                    <input type="date" class="form-control" name="date" id="date" placeholder="Date" />
                                    @error('date')
                                    <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror

                                </div>
                                <div class="col-md-1">
                                    <button type="button" onclick="" id="filter"
                                        class="btn btn-sm btn-primary mt-4">Filter</button>
                                </div>
                            </div>

                            <div class="row mt-3" id="table-detail-score" style="display: none;">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="display table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Item</th>
                                                    <th>Score</th>
                                                    <th>Grade</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                $no = 1;
                                                @endphp
                                                @foreach ($item as $it)
                                                <tr style="height: 40px!important">
                                                    <td style="height: 40px!important; padding: 8px 16px!important;">{{
                                                        $no }}</td>
                                                    <td style="height: 40px!important; padding: 8px 16px!important;">{{
                                                        $it->name}}</td>
                                                    <td style="height: 40px!important; padding: 8px 16px!important;">
                                                        <input type="hidden" value="{{$it->id}}" name="items[]">
                                                        <input type="hidden" value="" name="idScore[]" id="idScore{{$no}}">
                                                        <input type="number" id="{{'score'.$no}}" name="score[]"
                                                            required class="form-table score">

                                                    </td>
                                                    <td
                                                        style="height: 40px!important; padding: 8px 16px!important; text-align:center;">
                                                        <h6 id="grade{{$no}}"></h6>
                                                    </td>
                                                </tr>
                                                @php
                                                $no++;
                                                @endphp
                                                @endforeach
                                                <tr>
                                                    <td colspan="2">Average</td>
                                                    <td style="height: 40px!important; padding: 8px 16px!important;">
                                                        <input type="number" name="total" class="form-table average"
                                                            readonly id="">
                                                    </td>
                                                    <td
                                                        style="height: 40px!important; padding: 8px 16px!important; text-align:center;">
                                                        <h6 id="gradeAvg"></h6>
                                                    </td>
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
                                        <textarea name="comment" class="form-control" id="comment" cols="30"
                                            rows="3"></textarea>
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
        $(".score").keyup(function(e) {
           
           var len = $('.score').length;
           var avg = 0;
           var total = 0;
            for (let index = 1; index <= len; index++) {
                var v = $("#score"+index).val();
                $("#grade"+index).text($('#score'+index).val() != ''?getGrade(v):'');
                var tmpScore = parseInt($('#score'+index).val() != ''? $('#score'+index).val():  0);
                total += tmpScore;
            }
            avg = total / len;
            var grade = getGrade(avg);
            $('.average').val(avg.toFixed(2));
            $('#gradeAvg').text(grade);
        });
    });
   function getGrade(score) {
    if (score < 50) {
        return 'E';
    } else if ( score >= 50 &&score <= 59) {
        return 'D';
    } else if ( score >= 60 &&score <= 69) {
        return 'C';
    } else if ( score >= 70 &&score <= 85) {
        return 'B';
    } else if ( score >= 86 &&score <= 100) {
        return 'A';
    }
   }
    

</script>

<script type="text/javascript">
    $(document).on("click", "#filter", function () {
        var student =  $('#student').val();
        var test =$('#test').val();
        var date =$('#date').val();
        $.ajax({
            type: "GET",
            // url: "/score/filterScore?student=000002&test=2&date=2023-03-16",
            url: "{{url('')}}/score/filterScore?student="+student+"&test="+test+"&date="+date,
            dataType: 'JSON',
            success: function(res) {
                console.log(res);
                    $('#table-detail-score').css('display', 'block');
                    if (res.detail != []) {
                            var len = $('.score').length;
                            
                            $('#comment').val(res.data.comment);
                            for (let index = 0; index < len; index++) {
                                var idScore = index+1;
                                $("#score"+idScore).val(res.detail[index].score);
                                $("#grade"+idScore).text(getGrade(res.detail[index].score));
                                $('.average').val(res.data.average_score);
                                $('#gradeAvg').text(getGrade(res.data.average_score));
                                
                                $('#idScore'+idScore).val(res.detail[index].id);
                                $("#myform").attr('action', 'page1.php');
                            }
                            $('form').get(0).setAttribute('action', "{{ url('score/update')}}/"+res.data.id);
                    } else {
                        $('form').get(0).setAttribute('action',  "{{  url('score/store')}}");
                    }
                }
            });
        });

    $(document).ready(function(){
    $('#class').on('change',function(){
        var typeClass = $(this).val();
            $.ajax({
                type:'GET',
                url:'{{url('')}}/score/students/filter?class='+typeClass,
                dataType: 'JSON',
                success:function(data){
                    var $student = $('#student');
                    $student.empty();
                    $student.append('<option value="">Select Student</option>');
                    for (var i = 0; i < data.length; i++) {
                        $student.append('<option id=' + data[i].id + ' value=' + data[i].id + '>' + data[i].name + '</option>');
                    }
                    $student.change();
                    
                }
            }); 
    });
    
    
});

</script>
@endsection