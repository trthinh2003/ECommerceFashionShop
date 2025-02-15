  <div class="bg-light d-flex align-items-center justify-content-center">
      <!-- Chatbox Icon -->
      <div class="position-fixed bottom-0 end-0 m-3 chatbox-icon-fixed" id="chatbox-icon">
          <button class="btn btn-primary rounded-circle p-3">
              <i class="fas fa-comments fa-2x">
              </i>
          </button>
      </div>
      <!-- Chatbox Modal -->
      <div aria-hidden="true" aria-labelledby="chatboxModalLabel" class="modal fade" id="chatbox-modal" tabindex="-1">
          <div class="modal-dialog modal-dialog-end">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="chatboxModalLabel">
                          Chat with Us
                      </h5>
                      <button type="button" class="btn-close border-0" data-bs-dismiss="modal" aria-label="Close" style="background: none;">X</button>
                  </div>
                  <div class="modal-body">
                      <div class="d-flex flex-column gap-3">
                          <div class="d-flex align-items-start gap-2 mb-3">
                              <img alt="User avatar" class="rounded-circle" height="40"
                                  src="https://storage.googleapis.com/a1aa/image/AlXGIGOKjGG7NxQXmJ6n_TuTLawp8vvg3o3Fa5SRCIY.jpg"
                                  width="40" />
                              <div class="bg-light p-2 rounded">
                                  <p class="mb-0">
                                      Hello! How can I help you today?
                                  </p>
                              </div>
                          </div>
                          <div class="d-flex justify-content-end align-items-end gap-2 ms-auto">
                              <div class="bg-primary text-white p-2 rounded">
                                  <p class="mb-0">
                                      I have a question about your services.
                                  </p>
                              </div>
                              <img alt="User avatar" class="rounded-circle" height="40"
                                  src="https://storage.googleapis.com/a1aa/image/AlXGIGOKjGG7NxQXmJ6n_TuTLawp8vvg3o3Fa5SRCIY.jpg"
                                  width="40" />
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <input class="form-control" placeholder="Type your message..." type="text" />
                  </div>
              </div>
          </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <script>
          const chatboxIcon = document.getElementById('chatbox-icon');
          const chatboxModal = new bootstrap.Modal(document.getElementById('chatbox-modal'));

          chatboxIcon.addEventListener('click', () => {
              chatboxModal.show();
          });
      </script>
  </div>
