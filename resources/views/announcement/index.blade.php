@extends('template.app')

@section('content')
<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Announcement</h2>
                    {{-- <h5 class="text-white op-7 mb-2">Free Bootstrap 4 Admin Dashboard</h5> --}}
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="{{ url('/announces/create') }}" class="btn btn-secondary btn-round">Tambah Data</a>
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
                        <h4 class="card-title">Data Announcement</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                    <tr>

                                        <td>{{ $item->description }}</td>
                                        <td class=" d-flex">
                                            <form action="{{ route('announces.destroy',$item->id) }}" method="POST"
                                                class="form-inline">
                                                @method('delete')
                                                @csrf
                                                <a href="{{ url('/announces/'.$item->id.'/edit')}}"
                                                    class="btn btn-xs btn-info mr-2 "><i class="fas fa-edit"></i></a>
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                                <button type="submit" onclick="return confirm('apakah anda yakin ingin menghapus data ??')" class="btn btn-xs btn-primary"><i
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
@endsection
