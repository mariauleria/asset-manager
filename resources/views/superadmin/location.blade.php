@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
@endsection

@section('js')
    <script defer src="{{ asset('js/datatable.js')}}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script defer>
        $(document).ready(function (){
            $('.deleteLocationBtn').click(function (e){
                e.preventDefault();
                var location_id = $(this).val();
                $('#location_id').val(location_id);
                $('#deleteModal').modal('show');
            });
        });
    </script>
@endsection

@section('content')
    {{--    deletelocation--}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ url('delete-location') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Lokasi</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="location_id" id="location_id">
                        <h5>Apakah anda yakin ingin menghapus lokasi ini?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{--    tambah divisi baru--}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('store-location') }}">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Location Baru</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="location-name" class="col-form-label">{{ __('Nama Lokasi') }}</label>
                            <input type="text" class="form-control" id="location-name" name="location-name" autocomplete="location-name" autofocus>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambahkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <button type="button" class="btn btn-small btn-success mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <span class="material-symbols-outlined">add</span>Tambah Lokasi Baru
                </button>

                <div class="card">
                    <div class="card-header">{{ __('Dashboard Super Admin') }}</div>

                    <div class="card-body">



                        @if(session('message'))
                            <div class="alert alert-success">{{ session('message') }}</div>
                        @endif

                        <table id="myTable" class="display table">
                            <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Lokasi</th>
                                <th scope="col">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $index => $item)
                                <tr>
                                    <th scope="row">{{$index+1}}</th>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger deleteLocationBtn" value="{{ $item->id }}"><span class="material-symbols-outlined">delete</span></button>
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
@endsection
