@extends('admin.master')

@section('title', 'Admin')

@section('content')
    @if (Session::has('ok'))
        <div class="shadow-lg p-2 move-from-top js-div-dissappear" style="width: 17rem; display:none;">
            <i class="fa-solid fa-check p-2 bg-success text-white rounded-circle pe-2 mx-2"></i>{{ Session::get('ok') }}
        </div>
    @endif
@endsection

@section('successLogin')
@endsection
