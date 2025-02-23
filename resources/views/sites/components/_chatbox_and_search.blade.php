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
<form id="modal-search" class="modal-search js-modal" method="" action="">
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
                <span class="btn btn-success search-modal-btn">
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

        $(".dropdown-btn").click(function (event) {
            event.stopPropagation(); // Ngăn chặn sự kiện lan ra ngoài
            $(".dropdown-content").toggle();
        });

        $(document).click(function () {
            $(".dropdown-content").hide();
        });
    </script>
    <script src="{{ asset('client/js/cart-add.js') }}"></script>
@endsection

{{-- <button onclick="window.open('https://console.dialogflow.com/api-client/demo/embedded/d091fe6d-c3c9-487c-9c4b-855241a4956d', '_blank', 'width=400,height=500')">
    Mở Chatbot
</button> --}}
