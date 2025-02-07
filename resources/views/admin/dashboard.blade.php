@extends('admin.master')

@section('title', 'Admin')

@section('content')
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
