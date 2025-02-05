@extends('admin.master')
@section('title', 'Thông tin Phiếu nhập hàng')
@section('content')
    @if (Session::has('createSuccess'))
        <div class="shadow-lg p-2 move-from-top js-div-dissappear" style="width: 26rem; display:flex; text-align:center">
            <i
                class="fas fa-check p-2 bg-success text-white rounded-circle pe-2 mx-2"></i>{{ Session::get('createSuccess') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="card-sub">
                <form method="GET" class="form-inline row" action="{{ route('inventory.search') }}">
                    @csrf
                    <div
                        class="col-9 navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-search pe-1">
                                    <i class="fa fa-search search-icon"></i>
                                </button>
                            </div>
                            <input name="query" type="text"
                                placeholder="Nhập vào tên nhân viên lập hay ID phiếu nhập..." class="form-control" />
                        </div>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('inventory.create') }}" type="submit" class="btn btn-success"><i
                                class="fa fa-plus"></i>Thêm mới</a>
                    </div>
                </form>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Hình ảnh</th>
                        <th scope="col">Nhà cung cấp</th>
                        <th scope="col">Nhân viên lập</th>
                        <th scope="col">Số lượng nhập</th>
                        <th scope="col">Giá nhập</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    {{-- {{ $data->links() }} --}}
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/message.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('assets/js/message.js') }}"></script>
    <script>
        $(document).ready(function() {
            fetchInventories();
        });

        function fetchInventories() {
            $.ajax({
                url: "http://127.0.0.1:8000/api/inventory",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status_code === 200) {
                        let data = response.data;
                        let tbody = $("table tbody");
                        tbody.empty();
                        console.log(data);
                        $.each(data, function(index, inventory) {
                            // console.log(inventory.detail[0].product.name);
                            $.each(inventory.detail, function(i, dl) {
                                let totalMoney = dl.price * dl.quantity;
                                // console.log(dl.product.name);
                                let row = `
                                    <tr>
                                        <td>${inventory.id}</td>
                                        <td>${dl.product.name}</td>
                                        <td><img src="uploads/${dl.product.image}" width="45"></td>
                                        <td>${inventory.provider.name}</td>
                                        <td>${inventory.staff.name}</td>
                                        <td>${dl.quantity}</td>
                                        <td>${parseFloat(dl.price).toLocaleString()} VNĐ</td>
                                        <td>${totalMoney.toLocaleString()} VNĐ</td>
                                        <td class="text-center">
                                            <button class="btn btn-secondary btn-sm">Chi tiết</button>
                                        </td>
                                    </tr>
                                `;
                                tbody.append(row);
                            });
                        });
                    } else {
                        console.error("Lỗi API:", response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi lấy dữ liệu:", xhr.responseText);
                }
            });
        }
    </script>
@endsection
