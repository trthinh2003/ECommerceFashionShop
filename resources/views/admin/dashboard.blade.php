@extends('admin.master')

@section('title', 'Admin')

@section('content')
    @if (Session::has('success'))
        <div class="shadow-lg p-2 move-from-top js-div-dissappear" style="width: 18rem; display:flex; text-align:center">
            <i class="fas fa-check p-2 bg-success text-white rounded-circle pe-2 mx-2"></i>{{ Session::get('success') }}
        </div>
    @endif
    @can('managers')
        <p>Bạn đang là quản lý</p>
    @elsecan('salers')
        <p>Bạn đang là nhân viên bán hàng</p>
    @elsecan('warehouse workers')
        <p>Bạn đang là nhân viên kho</p>
    @else
        <p>Bạn không có quyền truy cập</p>
    @endcan
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/message.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('assets/js/message.js') }}"></script>
@endsection
