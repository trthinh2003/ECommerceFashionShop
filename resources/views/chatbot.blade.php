<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Laravel</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Chat với Bot</h2>
    <div id="chat-box" style="border: 1px solid #ccc; padding: 10px; width: 300px; height: 400px; overflow-y: scroll;">
        <p><b>Bot:</b> Xin chào! Bạn cần giúp gì?</p>
    </div>
    <input type="text" id="message" placeholder="Nhập tin nhắn..." style="width: 80%;">
    <button id="send">Gửi</button>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            $("#send").click(function() {
                var message = $("#message").val().trim();
                if (message === "") return;

                $("#chat-box").append("<p><b>Bạn:</b> " + message + "</p>");
                $("#message").val(""); // Xóa nội dung ô nhập

                $.ajax({
                    url: "/chatbot",
                    type: "POST",
                    data: {
                        message: message,
                        _token: $('meta[name="csrf-token"]').attr("content") // Thêm CSRF Token vào dữ liệu gửi đi
                    },
                    success: function(response) {
                        if (response.message) {
                            $("#chat-box").append("<p><b>Bot:</b> " + response.message + "</p>");
                        } else {
                            $("#chat-box").append("<p><b>Bot:</b> Không có phản hồi.</p>");
                        }
                        $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight); // Cuộn xuống cuối
                    },
                    error: function(xhr, status, error) {
                        $("#chat-box").append("<p><b>Bot:</b> Lỗi khi gửi tin nhắn: " + error + "</p>");
                    }
                });
            });

            // Gửi tin nhắn khi nhấn Enter
            $("#message").keypress(function(event) {
                if (event.which == 13) {
                    $("#send").click();
                }
            });
        });
    </script>

{{-- <iframe height="430" width="350" src="https://bot.dialogflow.com/8d80dd3a-55d9-4a52-b07f-e0334896dc2f"></iframe> --}}
</body>
</html>
