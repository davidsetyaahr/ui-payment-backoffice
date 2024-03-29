@extends('template.app')

@section('content')
    <div class="content">
        <div class="page-inner py-5 panel-header bg-primary-gradient" style="background:#01c293 !important">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div class="">
                    <h2 class="text-white pb-2 fw-bold">Parents Detail</h2>
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
                            <a href="{{ url('/parents') }}" class="text-white">Data Parents Detail</a>
                        </li>
                        <li class="separator text-white">
                            <i class="flaticon-right-arrow text-white"></i>
                        </li>
                        <li class="nav-item text-white">
                            <a href="#" class="text-white">Detail Data</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="page-inner mt--5">
            @if (session('status'))
                <script>
                    swal("Berhasil!", "{{ session('status') }}!", {
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
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title">Detail Data</h4>
                                <div class="ml-md-auto ">
                                    <a href="{{ url('parents') }}" class="btn btn-sm btn-info">Kembali</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email2">Name</label>
                                        <p>{{ $data->name }}</p>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Phone Number</label>
                                        <p>{{ $data->no_hp }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">

                                <h4 class="card-title">Students</h4>
                                <div class="ml-md-auto ">
                                    <button type="button" data-toggle="modal" data-target="#mdlAdd"
                                        class="btn btn-sm btn-info">Tambah Siswa</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Grade</th>
                                            <th>Birthday</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $item)
                                            <tr>

                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->grade == null ? '-' : $item->grade }}</td>
                                                <td>{{ $item->birthday }}</td>
                                                <td class=" d-flex">
                                                    <form action="{{ url('/parentStudent/' . $item->parent_student_id) }}"
                                                        method="POST" class="form-inline">
                                                        @method('delete')
                                                        @csrf
                                                        {{-- <a href="{{ url('/parents/' . $item->id . '/edit') }}"
                                                            class="btn btn-xs btn-info mr-2 "><i
                                                                class="fas fa-edit"></i></a> --}}
                                                        {{-- <a href="{{ url('/parents/' . $item->id) }}"
                                                            class="btn btn-xs btn-warning mr-2 "><i
                                                                class="fas fa-eye"></i></a>
                                                        <input type="hidden" name="id" value="{{ $item->id }}"> --}}
                                                        <button type="submit"
                                                            onclick="return confirm('apakah anda yakin ingin menghapus data ??')"
                                                            class="btn btn-xs btn-primary"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
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

    <div class="modal" id="mdlAdd" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('parentStudent') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Students</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email2">Students</label>
                                    <input type="hidden" name="parent_id" value="{{ $data->id }}" readonly />
                                    <select class="form-control select2 select2-hidden-accessible" style="width:100%;"
                                        name="student[]" multiple="">

                                        @foreach ($dataStudent as $st)
                                            <option value="{{ $st->id }}">{{ $st->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student')
                                        <label class="mt-1" style="color: red!important">{{ $message }}</label>
                                    @enderror
                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
@endsection
