@extends('admin.master')
@section('title', 'Thêm Chương trình khuyến mãi')

@section('content')
    <form method="POST" action="{{ route('discount.store') }}">
        @csrf
        <div class="form-group">
            <label for="">Tên chương trình khuyến mãi:</label>
            <input type="text" name="name" id="" class="form-control" placeholder="">
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="">Phần trăm khuyến mãi:</label>
            <input type="text" name="percent_discount" id="" class="form-control" placeholder="">
            @error('percent_discount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="row">
            <div class="col-6 form-group">
                <label for="">Ngày bắt đầu:</label>
                <input type="datetime-local" name="start_date" id="" class="form-control" placeholder="">
                @error('start_date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-6 form-group">
                <label for="">Ngày kết thúc:</label>
                <input type="datetime-local" name="end_date" id="" class="form-control" placeholder="">
                @error('end_date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <input class="btn btn-primary" name="" type="submit" value="Lưu thông tin">
    </form>
@endsection
