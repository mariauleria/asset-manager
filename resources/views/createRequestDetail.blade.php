@extends('layouts.app')

@section('js')
    <script defer src="{{ asset('js/datatable.js')}}"></script>
    <script defer src="{{ asset('js/newassetcategory.js')}}"></script>
@endsection

@section('css')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">{{ __('Pinjam Aset') }}</div>

                    <div class="card-body">

                        <form method="POST" action="{{ route('confirmRequest') }}" id="checkGroup">
                            @csrf

                            <div class="mb-3">
                                <label for="purpose" class="col-form-label text-md-end">{{ __('Tujuan Peminjaman') }}</label>

                                <div>
                                    <textarea class="form-control" id="purpose" name="purpose" required autofocus></textarea>
                                </div>
                            </div>

{{--                            TODO: tambahin 1 lagi input lokasi peminjaman--}}
                            <div class="mb-3">
                                <label class="col-form-label text-md-end">{{ __('Lokasi Peminjaman') }}</label>

                                <div>
                                    <input class="form-check-input mt-1" type="radio" id="hide" name="lokasi" value="{{ "keluar kampus BINUS" }}" checked />
                                    <label for="hide">keluar kampus BINUS</label>

                                    <div class="mt-2">
                                        <input class="form-check-input mt-1" type="radio" id="show" name="lokasi" value="" />
                                        <label for="show">dalam lingkungan kampus BINUS</label>
                                    </div>
                                    <div id="box" class="col-sm-5 col-md-6" style="display: none;">
                                        <select class="form-select" name="new-lokasi" id="new-lokasi">
                                            @foreach($data as $index => $item)
                                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-0">
                                    <input type="hidden" name="return_date" value="{{$return_date }}">
                                    <input type="hidden" name="book_date" value="{{$book_date }}">
                                    <input type="hidden" name="assets" value="{{serialize($assets)}}">
                                    <input type="hidden" name="division_id" value="{{$division_id}}">

                                    <button type="submit" name="submit" class="btn btn-primary">
                                        {{ __('Next') }}
                                    </button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
