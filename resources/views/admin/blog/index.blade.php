@can('managers')
    @extends('admin.master')
    @section('title', 'Thông tin bài viết')
@section('content')
    @if (Session::has('success'))
        <div class="shadow-lg p-2 move-from-top js-div-dissappear" style="width: 25rem; display:flex; text-align:center">
            <i class="fas fa-check p-2 bg-success text-white rounded-circle pe-2 mx-2"></i>{{ Session::get('success') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="card-sub">
                <form method="GET" class="form-inline row" action="{{ route('blog.search') }}">
                    @csrf
                    <div
                        class="col-9 navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-search pe-1">
                                    <i class="fa fa-search search-icon"></i>
                                </button>
                            </div>
                            <input name="query" type="text" placeholder="Nhập vào tags hoặc tên bài viết..."
                                class="form-control" />
                        </div>
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#addBlogModal">
                            <i class="fa fa-plus"></i> Thêm mới
                        </button>
                    </div>
                </form>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tiêu đề</th>
                        <th scope="col">Nội dung</th>
                        <th scope="col">Ảnh</th>
                        <th scope="col">Tags</th>
                        <th scope="col">ID nhân viên</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->title }}</td>
                            <td>{{ $model->content }}</td>
                            <td><img src="uploads/{{ $model->image }}" width="50" alt=""></td>
                            <td>{{ $model->tags }}</td>
                            <td>{{ $model->staff_id }}</td>
                            <td>{{ $model->status }}</td>
                            <td class="text-center">
                                <form method="post" action="{{ route('blog.destroy', $model->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-secondary btn-detail" data-bs-toggle="modal"
                                        data-bs-target="#detailBlogModal" data-id="{{ $model->id }}">
                                        <i class="fa fa-plus"></i> Chi tiết
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary btn-update" data-bs-toggle="modal"
                                        data-bs-target="#updateBlogModal" data-id="{{ $model->id }}">
                                        <i class="fa fa-plus"></i> Sửa
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Bạn có chắc muốn xóa không?')">
                                        <i class="fa fa-trash pe-2"></i>
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $data->links() }}

    <!-- Modal blogAdd-->
    <div class="modal fade" id="addBlogModal" tabindex="-1" aria-labelledby="addBlogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBlogModalLabel">Thêm Bài Viết Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="image" class="form-label">Ảnh</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <img src="" alt="" width="100"
                                    class="img-preview preview-img-item d-none">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" data-role="tagsinput" name="blog_tag" class="form-control"
                                value="" required>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" class="form-control" value="{{ auth()->user()->id - 1 }}"
                                id="staff_id" name="staff_id">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <input type="submit" class="btn btn-primary" value="Lưu thông tin">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal blogUpdate-->
    <div class="modal fade" id="updateBlogModal" tabindex="-1" aria-labelledby="updateBlogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateBlogModalLabel">Cập Nhật Bài Viết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('blog.update', '') }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="blog-title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea class="form-control" id="blog-content" name="content" rows="3" required></textarea>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="image" class="form-label">Ảnh</label>
                                <input type="file" class="form-control" id="blog-image" name="image" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <img src="" alt="" width="100"
                                    class="img-preview preview-img-item d-none">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" data-role="tagsinput" id="blog-tag" name="blog_tag" class="form-control"
                                value="" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-control" id="blog-status" name="status">
                                <option value="1">Hiển thị</option>
                                <option value="0">Ẩn</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <input type="submit" class="btn btn-primary" value="Lưu thông tin">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal blogDetail -->
    <div class="modal fade" id="detailBlogModal" tabindex="-1" aria-labelledby="detailBlogModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailBlogModalLabel">Chi Tiết Bài Viết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">ID</label>
                        <input type="text" class="form-control" id="blog-id" name="id" required>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" id="blog-title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Nội dung</label>
                        <textarea class="form-control" id="blog-content" name="content" rows="3" required></textarea>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="image" class="form-label">Ảnh</label>
                            <image  id="blog-image" width="100" name="image">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" id="blog-tag" name="blog_tag" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="staff_name"  class="form-label">Nhân viên</label>
                        <input type="text" class="form-control" id="staff-name" name="staff_name" required>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" class="form-control" value="{{ auth()->user()->id - 1 }}" id="staff_id"
                            name="staff_id">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <input type="text" class="form-control" id="blog-status" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Ngày thêm</label>
                        <input type="text" class="form-control" id="blog-createDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Ngày cập nhật</label>
                        <input type="text" class="form-control" id="blog-updateDate" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Lưu thông tin">
                </div>
            </div>
        </div>
    </div>
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/message.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-tagsinput.css') }}" />
@endsection

@section('js')

    <script src="{{ asset('assets/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

    <script>
        document.querySelector('input[name="image"]').addEventListener('change', function(e) {
            const [file] = e.target.files
            if (file) {
                document.querySelector('.preview-img-item').classList.remove('d-none')
                document.querySelector('.img-preview').src = URL.createObjectURL(file)
            }
        })
    </script>
    @if (Session::has('success'))
        <script src="{{ asset('assets/js/message.js') }}"></script>
    @endif


    <script>
        $(document).ready(function() {
            $(".btn-detail").click(function(event) {
                event.preventDefault();
                let blogId = $(this).data("id");
                $.ajax({
                    url: `http://127.0.0.1:8000/api/blog_detail/${blogId}`, //url, type, datatype, success,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.status_code === 200) {
                            let blogInfo = response.data;
                            $("#blog-id").val(blogInfo.id);
                            $("#blog-title").val(blogInfo.title);
                            $("#blog-content").val(blogInfo.content);
                            $("#blog-image").attr("src", `uploads/${blogInfo.image}`);
                            $("#blog-tag").val(blogInfo.tags);
                            $("#staff-name").val(blogInfo.staff.name);
                            $("#blog-status").val(blogInfo.status) === 0 ? "Hiển thị" : "Ẩn";
                            $("#blog-createDate").val(new Date(blogInfo.created_at).toLocaleString(
                                'vi-VN'));
                            $("#blog-updateDate").val(new Date(blogInfo.updated_at).toLocaleString('vi-VN'));
                        } else {
                            alert("Không thể lấy dữ liệu chi tiết!");
                        }
                    },
                    error: function() {
                        alert("Đã có lỗi xảy ra, vui lòng thử lại!");
                    }
                });
            });
        });

        $(document).ready(function() {
            $(".btn-update").click(function(event) {
                event.preventDefault();
                let blogId = $(this).data("id");
                $.ajax({
                    url: `http://127.0.0.1:8000/api/blog_detail/${blogId}`, //url, type, datatype, success,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        if (response.status_code === 200) {
                            let blogInfo = response.data;
                            $("#blog-title").val(blogInfo.title);
                            $("#blog-content").val(blogInfo.content);
                            $("#blog-image").attr("src", `uploads/${blogInfo.image}`);
                            $("#blog-tag").val(blogInfo.tags);
                            $("#staff-name").val(blogInfo.staff.name);
                            $("#blog-status").val(blogInfo.status) === 0 ? "Hiển thị" : "Ẩn";
                        } else {
                            alert("Không thể lấy dữ liệu chi tiết!");
                        }
                    },
                    error: function() {
                        alert("Đã có lỗi xảy ra, vui lòng thử lại!");
                    }
                });
            });
        });
    </script>

    {{-- cách 2 Test thử  thấy cũng oke --}}
    {{-- <script>
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('staffDetail').classList.add("open");
            });
        @endif
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let btnDetails = document.querySelectorAll(".btn-detail");
            btnDetails.forEach(button => {
                button.addEventListener("click", async (event) => {
                    event.preventDefault();
                    let row = button.closest("tr");
                    let staffId = row.querySelector("td:first-child").textContent.trim();
                    try {
                        let response = await fetch(`http://127.0.0.1:8000/api/staff/${staffId}`);
                        let result = await response.json();

                        if (result.status_code === 200) {
                            let staff = result.data;
                            document.getElementById("staff-id").textContent = staff.id;
                            document.getElementById("staff-name").textContent = staff.name;
                            document.getElementById("staff-sex").textContent = staff.sex === 1 ?"Nam" : "Nữ";
                            document.getElementById("staff-address").textContent = staff.address;
                            document.getElementById("staff-phone").textContent = staff.phone;
                            document.getElementById("staff-email").textContent = staff.email;
                            document.getElementById("staff-position").textContent = staff.position;
                            document.getElementById("staff-status").textContent = staff.status;
                            document.getElementById("staff-createDate").textContent = new Date(staff.created_at).toLocaleString('vi-VN');
                            document.getElementById("staff-updateDate").textContent = new Date(staff.updated_at).toLocaleString('vi-VN');
                            let modal = new bootstrap.Modal(document.getElementById("staffDetail"));
                            modal.show();
                        } else {
                            alert("Không thể lấy dữ liệu chi tiết!");
                        }
                    } catch (error) {
                        alert("Đã có lỗi xảy ra, vui lòng thử lại!");
                    }
                });
            });
        });
    </script> --}}
@endsection
@else
{{ abort(403, 'Bạn không có quyền truy cập trang này!') }}
@endcan
