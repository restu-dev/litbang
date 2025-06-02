  <div class="modal fade" id="modal-default">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Default Modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              <div class="modal-body">
                  {{-- nama asset --}}
                  <div class="form-group">
                      <label>Nama Asset</label>
                      <select id="nama_asset" class="form-control select2" style="width: 100%;">
                      </select>
                  </div>

                  {{-- keterangan --}}
                  <div class="form-group">
                      <label>Keterangan</label>
                      <textarea class="form-control" id="keterangan" placeholder="Keterangan"></textarea>
                  </div>

              </div>

              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button id="save_form" type="button" class="btn btn-primary">Save changes</button>
              </div>
          </div>
      </div>
  </div>
