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



@section('js')
    <script>
        $(document).ready(function(e) {
            const chatboxIcon = $("#chatbox-icon");
            const chatboxMessages = $("#chatbox-messages");
            const chatboxInput = $("#chatbox-input");
            const chatboxSend = $("#chatbox-send");

            chatboxIcon.on("click", function(e) {
                console.log(e.target);
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
    </script>
@endsection

{{-- <button onclick="window.open('https://console.dialogflow.com/api-client/demo/embedded/d091fe6d-c3c9-487c-9c4b-855241a4956d', '_blank', 'width=400,height=500')">
    Mở Chatbot
</button> --}}
