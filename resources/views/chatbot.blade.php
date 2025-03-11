<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chatbot</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        #chat-container {
            width: 350px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        #chatbox {
            height: 400px;
            padding: 10px;
            overflow-y: auto;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        #messageInput {
            width: 80%;
            padding: 10px;
            border: none;
            outline: none;
            border-top: 1px solid #ddd;
        }

        #sendBtn {
            width: 20%;
            padding: 10px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        #sendBtn:hover {
            background-color: #45a049;
        }

        .message {
            margin: 5px 0;
            padding: 8px 12px;
            border-radius: 15px;
            max-width: 70%;
            word-wrap: break-word;
        }

        .user-message {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
            margin-left: auto;
        }

        .bot-message {
            background-color: #e0e0e0;
            color: #333;
            align-self: flex-start;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <div id="chat-container">
        <div id="chatbox"></div>
        <div>
            <input type="text" id="messageInput" placeholder="Nhập tin nhắn..." autofocus>
            <button id="sendBtn">Gửi</button>
        </div>
    </div>

    <script>
        function appendMessage(content, type) {
            $('#chatbox').append('<div class="message ' + type + '">' + content + '</div>');
            $('#chatbox').scrollTop($('#chatbox')[0].scrollHeight);
        }

        $('#sendBtn').click(function () {
            var message = $('#messageInput').val().trim();
            if (message === '') return;

            appendMessage(message, 'user-message');
            $('#messageInput').val('');

            $.ajax({
                url: '/chatbot',
                method: 'POST',
                data: {
                    message: message,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    appendMessage(response.response, 'bot-message');
                },
                error: function () {
                    appendMessage('Đã xảy ra lỗi!', 'bot-message');
                }
            });
        });

        $('#messageInput').keypress(function (e) {
            if (e.which === 13) {
                $('#sendBtn').click();
            }
        });
    </script>
</body>

</html>
