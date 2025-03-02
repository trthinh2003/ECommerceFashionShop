<div class="bg-light d-flex align-items-center justify-content-center">
    <!-- Chatbox Icon -->
    <div class="position-fixed bottom-0 end-0 m-3" id="chatbox-icon">
        <button class="btn btn-primary rounded-circle p-3">
            <i class="fas fa-comments fa-2x"></i>
        </button>
    </div>

    <!-- Chatbox Modal -->
    <div class="modal fade" id="chatbox-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-end">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Trò chuyện với chúng tôi</h5>
                    <button type="button" class="btn-close border-0 btn-close-chatbot" style="background: none;"
                        data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>

                <div class="modal-body">
                    <div class="d-flex flex-column gap-3" id="chatbox-messages">
                        <!-- Tin nhắn sẽ hiển thị ở đây -->
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex w-100 gap-2">
                        <input id="chatbox-input" class="form-control" placeholder="Nhập tin nhắn..." type="text" />
                        <button id="chatbox-send" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Start Search Modal -->
<form id="modal-search" class="modal-search js-modal" method="post" action="{{ route('sites.shopSearch') }}">
    @csrf
    {{-- <input type="hidden" name="_method" value="POST"> --}}
    <div class="modal-container-search js-modal-container p-3">
        <div class="modal-close js-modal-close">
            <i class="fas fa-times"></i>
        </div>
        <div class="modal-body">

            <div class="form-group">
                <input type="text" name="q" id="search-box" class="form-control"
                    placeholder="Nhập từ khóa bạn muốn tìm...">
                <span class="btn btn-success search-modal-btn" onclick="submitSearch()">
                    <i class="fa fa-fw fa-search text-white"></i>
                </span>
            </div>
            <ul id="search-results" class="list-unstyled"></ul>
            <h3>Lịch sử tìm kiếm</h3>
            <ul id="search-history"></ul>
            <button id="clear-history">Xóa lịch sử tìm kiếm</button>

            <h3>Có thể bạn sẽ thích</h3>
            <ul id="suggestion-list"></ul>
        </div>
    </div>
</form>
<!-- End Modal Search -->


@section('js')
    <script>
        $(document).ready(function() {
            const chatboxIcon = $("#chatbox-icon");
            const chatboxMessages = $("#chatbox-messages");
            const chatboxInput = $("#chatbox-input");
            const chatboxSend = $("#chatbox-send");

            chatboxIcon.on("click", function(e) {
                $("#chatbox-modal").modal("show");
            });

            chatboxSend.on("click", function() {
                sendMessage();
            });

            chatboxInput.on("keypress", function(event) {
                if (event.which === 13) {
                    sendMessage();
                }
            });

            function scrollToBottom() {
                const chatboxMessages = $("#chatbox-messages");
                setTimeout(() => {
                    chatboxMessages.scrollTop(chatboxMessages[0].scrollHeight);
                }, 100); // Chờ 100ms để tin nhắn render xong
            }

            function sendMessage() {
                let message = chatboxInput.val().trim();
                if (message === "") return;

                chatboxMessages.append(`<div class="d-flex justify-content-end align-items-end gap-2 ms-auto">
                                            <div class="bg-primary text-white p-2 rounded">
                                                <p class="mb-0">${message}</p>
                                            </div>
                                        </div>`);

                chatboxInput.val("");
                scrollToBottom(); // Cuộn xuống sau khi gửi tin nhắn

                // Gửi tin nhắn đến API Laravel
                $.post("/chatbot", {
                    message: message,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    chatboxMessages.append(`
                                            <div class="d-flex align-items-start gap-2 mb-3">
                                                <img class="rounded-circle mt-2" src="{{ asset('client/img/chatbot/bot.avif') }}" width="30">
                                                <div class="bg-light p-2 rounded">
                                                    <p class="mb-0">${response.message}</p>
                                                </div>
                                            </div>
                                        `);
                    scrollToBottom(); // Cuộn xuống sau khi bot phản hồi
                });
            }

            $('.btn-close-chatbot').on("click", function() {
                $("#chatbox-modal").modal("hide");
            });

        });

        $('.search-btn').click(function() {
            $('.js-modal').addClass("open");
        });

        $('.js-modal-close').click(function() {
            $('.js-modal').removeClass("open");
        });

        function submitSearch() {
            $('#modal-search').submit();
        }

        // Tìm kiếm sản phẩm bằng AJAX
        $("#search-box").on("input", function(e) {
            let query = $("#search-box").val();
            console.log(query);
            if (query.length > 1) {
                $.ajax({
                    url: "http://127.0.0.1:8000/api/search",
                    type: "GET",
                    data: {
                        q: query
                    },
                    success: function(data) {
                        let results = $("#search-results");
                        // console.log(results);
                        results.empty();

                        if (data.results.length > 0) {
                            data.results.forEach(function(item) {
                                console.log(item);
                                let price = Intl.NumberFormat('vi-VN').format(item.price);
                                results.append(`
                                        <li class="p-2 search-item" 
                                                style="cursor: pointer;" 
                                                onmouseover="this.style.backgroundColor='#ccc'; this.style.textDecoration='underline';" 
                                                onmouseout="this.style.backgroundColor='#fff'; this.style.textDecoration='none';">
                                            <a class="text-dark" href="{{ url('product') }}/${item.slug}">
                                            <img src="{{ asset('uploads/${item.image}') }}" width="30" height="30" alt="">
                                            ${item.product_name} | <p class="d-inline">Giá:</p> ${price} đ
                                            </a>
                                        </li>
                                    
                                `);
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

        $(".dropdown-btn").click(function(event) {
            event.stopPropagation(); // Ngăn chặn sự kiện lan ra ngoài
            $(".dropdown-content").toggle();
        });

        $(document).click(function() {
            $(".dropdown-content").hide();
        });
    </script>
    <script src="{{ asset('client/js/cart-add.js') }}"></script>

    {{-- danh sách sản phẩm liên quan --}}
    <script>
        $(document).ready(function() {
            $("#suggestion-list-product").empty(); // Xóa dữ liệu cũ trước khi cập nhật mới
    
            $.ajax({
                url: "http://127.0.0.1:8000/api/suggest-content-based", // API lấy danh sách sản phẩm
                method: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.length > 0) {
                        
                        data.forEach(function(item) {
                            let productHTML = `
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="product__item" id="product-list-shop">
                                        <div class="product__item__pic">
                                            <img class="product__item__pic set-bg" width="280" height="250"
                                                src="{{ asset('uploads/${item.image}')}}"
                                                alt="${item.product_name}">
                                            <ul class="product__hover">
                                                <li><a href="{{ url('add-to-wishlist') }}/${item.id}"><img src="{{ asset('client/img/icon/heart.png') }}"
                                                            alt=""></a></li>
                                                <li><a href="#"><img src="{{ asset('client/img/icon/compare.png') }}"
                                                            alt=""><span>Compare</span></a></li>
                                                <li><a href="{{ url('product') }}/${item.slug}"><img
                                                            src="{{ asset('client/img/icon/search.png') }}"
                                                            alt=""></a></li>
                                            </ul>
                                        </div>
    
                                        <div class="product__item__text">
                                            <h6>${item.product_name}</h6>
                                            <a href="javascript:void(0);" class="add-cart" data-id="${item.id}">+
                                                Add To Cart</a>
                                            <div class="rating">
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>
                                            <h5>{{ number_format(100000) }} VND</h5>
                                            <div class="product__color__select">
                                                <label for="pc-4">
                                                    <input type="radio" id="pc-4">
                                                </label>
                                                <label class="active black" for="pc-5">
                                                    <input type="radio" id="pc-5">
                                                </label>
                                                <label class="grey" for="pc-6">
                                                    <input type="radio" id="pc-6">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $("#suggestion-list-product").append(productHTML);
                        });
                    } else {
                        $("#suggestion-list-product").html("<p>Không có sản phẩm nào.</p>");
                    }
                },
                error: function() {
                    console.error("Lỗi API khi tải danh sách sản phẩm.");
                    $("#suggestion-list-product").html("<p>Lỗi khi tải sản phẩm.</p>");
                }
            });
        });
    </script>
@endsection

{{-- <button onclick="window.open('https://console.dialogflow.com/api-client/demo/embedded/d091fe6d-c3c9-487c-9c4b-855241a4956d', '_blank', 'width=400,height=500')">
    Mở Chatbot
</button> --}}
