<!-- Modal -->
<div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-search" role="document">
        <div class="w-100 pt-1 mb-2 text-right">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                style="background: none; border:none;">X</button>
        </div>
        <form action="" method="" class="modal-content modal-body border-0 p-0">
            <div class="input-group mb-2">
                <input type="text" class="form-control" id="search-box" name="q"
                    placeholder="Search ...">
                <button type="submit" class="input-group-text bg-success text-light">
                    <i class="fa fa-fw fa-search text-white"></i>
                </button>
            </div>
        </form>
        <ul id="search-results" class="list-unstyled">
            {{-- <li>Hello</li>
            <li>Hi</li>
            <li>plsaojf</li> --}}
        </ul>
        <h3>Lịch sử tìm kiếm</h3>
        <ul id="search-history"></ul>
        <button id="clear-history">Xóa lịch sử tìm kiếm</button>

        <h3>Có thể bạn sẽ thích</h3>
        <ul id="suggestion-list"></ul>
    </div>
</div>

@section('css')
    <link rel="stylesheet" href="{{ asset('client/css/search.css') }}" type="text/css">
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            $(".search-btn").click(function() {
                $("#templatemo_search").modal("show");
            });
        })
    </script>
    <script>
        $(document).ready(function() {
            // Tìm kiếm sản phẩm bằng AJAX
            $("#search-box").on("input", function(e) {
                let query = $("#search-box").val();
                console.log(query);
                if (query.length > 1) {
                    $.ajax({
                        url: "http://127.0.0.1:8000/api/search",
                        type: "GET",
                        data: { q: query },
                        success: function(data) {
                            let results = $("#search-results");
                            // console.log(results);
                            results.empty();

                            if (data.results.length > 0) {
                                data.results.forEach(function(item) {
                                    console.log(item);
                                    results.append("<li>" + item.product_name + "</li>");
                                });
                            } else {
                                results.append("<li>Không tìm thấy kết quả</li>");
                            }

                            // Cập nhật lịch sử tìm kiếm
                            updateSearchHistory(data.history);
                        }
                    });
                }
            });

            // Lấy lịch sử tìm kiếm
            function updateSearchHistory(history) {
                let historyList = $("#search-history");
                historyList.empty();

                if (history.length > 0) {
                    history.forEach(function(item) {
                        historyList.append("<li>" + item + "</li>");
                    });
                } else {
                    historyList.append("<li>Chưa có lịch sử tìm kiếm</li>");
                }
            }

            $.get("http://127.0.0.1:8000/api/search-history", function(data) {
                updateSearchHistory(data);
            });

            // Lấy gợi ý sản phẩm
            $.get("http://127.0.0.1:8000/api/suggest-content-based", function(data) {
                let suggestions = $("#suggestion-list");
                suggestions.empty();

                if (data.length > 0) {
                    data.forEach(function(item) {
                        suggestions.append("<li>" + item.product_name + "</li>");
                    });
                } else {
                    suggestions.append("<li>Không có gợi ý nào</li>");
                }
            });

            // Xóa lịch sử tìm kiếm
            $("#clear-history").on("click", function() {
                $.ajax({
                    url: "http://127.0.0.1:8000/api/search-history",
                    type: "DELETE",
                    success: function(response) {
                        alert(response.message);
                        updateSearchHistory([]);
                    }
                });
            });
        });
    </script>
@endsection
