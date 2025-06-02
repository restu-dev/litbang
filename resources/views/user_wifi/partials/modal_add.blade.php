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
                  {{-- nama pegawai --}}
                  <div class="form-group">
                      <label>Nama Pegawai</label>
                      <select id="nama_pegawai" class="form-control select2" style="width: 100%;">
                      </select>
                  </div>

                  {{-- nip --}}
                  <div class="form-group">
                      <label for="nip">NIP</label>
                      <input disabled type="text" name="nip" class="form-control" id="nip"
                          placeholder="NIP">
                  </div>

                  {{-- nama_pegawai --}}
                  <div class="form-group">
                      <label for="nama">Nama Pegawai</label>
                      <input disabled type="text" name="nama" class="form-control" id="nama"
                          placeholder="Nama Pegawai">
                  </div>

                  {{-- user --}}
                  <div class="form-group">
                      <label for="user_wifi">User Wifi</label>
                      <input type="text" name="user_wifi" class="form-control" id="user_wifi"
                          placeholder="User">
                  </div>

                  {{-- HP --}}
                  <div class="form-group">
                      <label for="no_hp">No HP</label>
                      <input disabled type="text" name="no_hp" class="form-control" id="no_hp"
                          placeholder="User">
                  </div>

                  {{-- nama divisi --}}
                  <div class="form-group">
                      <label>Bidang</label>
                      <select id="nama_bidang" class="form-control">
                          <option value="">All</option>
                          @foreach ($bidang as $d)
                              <option value="{{ $d->id }}">{{ $d->nama }}</option>
                          @endforeach
                      </select>
                  </div>

                  {{-- password --}}
                  <div class="form-group">
                      <label for="password">Password</label>
                      <input type="text" name="password" class="form-control" id="password" placeholder="User">
                  </div>

                  <div class="form-group">
                      <label>Keperluan</label>
                      <textarea class="form-control" id="keperluan" placeholder="Keperluan"></textarea>
                  </div>

              </div>

              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button id="save_form" type="button" class="btn btn-primary">Save changes</button>
              </div>
          </div>
      </div>
  </div>
