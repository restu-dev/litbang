  <div class="modal fade" id="modal-default-edit">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Default Modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              <div class="modal-body">

                <input type="hidden" name="edit_id" class="form-control" id="edit_id">

                  {{-- nama --}}
                  <div class="form-group">
                      <label for="edit_nama">Nama</label>
                      <input disabled type="text" name="edit_nama" class="form-control" id="edit_nama"
                          placeholder="User">
                  </div>

                  {{-- keterangan --}}
                  <div class="form-group">
                      <label>Keterangan</label>
                      <textarea class="form-control" id="edit_keterangan" placeholder="keterangan"></textarea>
                  </div>

              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button id="save_edit_form" type="button" class="btn btn-primary">Save changes</button>
              </div>
          </div>
      </div>
  </div>
