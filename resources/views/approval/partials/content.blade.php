  {{-- filter --}}
  <div class="row">
      <div class="col-lg-12">
          <div class="card">
              <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

              <div class="card-body card-body">
                  <div class="row">
                      {{-- filter tahun --}}
                      {{-- <div class="col-3">
                                     <select id="filter_tahun_pelajaran" class="form-control select2" style="width: 100%;">
                                     </select>
                                 </div> --}}

                      {{-- filter approvement --}}
                      <div class="col-6 col-sm-3">
                          <select id="filter_status_capaian" class="form-control select2" style="width: 100%;">
                          </select>
                      </div>

                      {{-- filter approvement --}}
                      <div class="col-6 col-sm-3">
                          <select id="filter_approvement" class="form-control select2" style="width: 100%;">
                              <option value="">-Approvement-</option>
                              <option value="Belum">Belum</option>
                              <option value="Ya">Ya</option>
                              <option value="Tidak">Tidak</option>
                          </select>
                      </div>

                  </div>
              </div>
          </div>
      </div>
  </div>

  <div class="row">
      <div class="col-lg-12">
          <div class="card">
              <div class="overlay loader"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>

              <div class="card-header">
                  <h3 class="card-title">Tabel {{ $title }}</h3>
              </div>

              <div class="card-body">
                  <table id="tabel_master" class="table table-bordered table-striped table-sm">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Aksi</th>
                              <th>Approvement</th>
                              <th>Program Kerja</th>
                              <th>Tahun</th>
                              <th>Penanggung Jawab</th>
                              <th>Target Frekuensi Tahunan</th>
                              <th>Indikator Kinerja</th>
                              <th>Capaian Aktual</th>
                              <th>Pro Capaian</th>
                              <th>Status Capaian</th>
                              <th>Keterangan</th>
                          </tr>
                      </thead>
                  </table>
              </div>

          </div>
      </div>
  </div>

  {{-- modal add edit --}}
  <div class="modal fade" id="modal_add_edit">
      <div class="modal-dialog">
          <div class="modal-content">

              <div class="modal-header">
                  <h4 class="modal-title">Default Modal</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              <div class="modal-body">
                  <input type="hidden" name="id" class="form-control" id="id">

                  {{-- Tahun Pelajaran --}}
                  <div class="form-group">
                      <label for="tahun_pelajaran">Tahun Pelajaran</label>

                      <select id="tahun_pelajaran" class="form-control select2" style="width: 100%;">
                      </select>
                  </div>

                  {{-- Program Kerja --}}
                  <div class="form-group">
                      <label for="penanggung_jawab">Penanggung Jawab</label>
                      <input disabled type="text" name="penanggung_jawab" class="form-control" id="penanggung_jawab"
                          placeholder="Input Program Kerja">
                  </div>

                  {{-- Program Kerja --}}
                  <div class="form-group">
                      <label for="program_kerja">Program Kerja</label>
                      <input disabled type="text" name="program_kerja" class="form-control" id="program_kerja"
                          placeholder="Input Program Kerja">
                  </div>

                  {{-- Target Frekuensi Tahunan --}}
                  <div class="form-group">
                      <label for="target_frekuensi_tahunan">Target Frekuensi Tahunan</label>
                      <input disabled type="number" name="target_frekuensi_tahunan" class="form-control"
                          id="target_frekuensi_tahunan" placeholder="Input Target Frekuensi Tahunan (Angka)">
                  </div>

                  {{-- Indikator Kinerja --}}
                  <div class="form-group">
                      <label for="indikator_kinerja">Indikator Kinerja</label>
                      <textarea disabled name="indikator_kinerja" id="indikator_kinerja" class="form-control"
                          placeholder="Input Indikator Kinerja"></textarea>
                  </div>

                  {{-- Status Capaian --}}
                  <div class="form-group">
                      <label for="status_capaian">Status Capaian</label>

                      <select disabled id="status_capaian" class="form-control select2" style="width: 100%;">
                      </select>
                  </div>

                  {{-- Keterangan --}}
                  <div class="form-group">
                      <label for="keterangan">Keterangan</label>
                      <textarea disabled name="keterangan" id="keterangan" class="form-control" placeholder="Input Keterangan"></textarea>
                  </div>

                  {{-- approvement --}}
                  <div class="form-group">
                      <label for="keterangan">Approvement</label>

                      <select id="approvement" class="form-control select2" style="width: 100%;">
                          <option value="">-Approvement-</option>
                          <option value="Belum">Belum</option>
                          <option value="Ya">Ya</option>
                          <option value="Tidak">Tidak</option>
                      </select>
                  </div>

              </div>

              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" id="save_form" class="btn btn-primary">Simpan</button>
              </div>
          </div>
      </div>
  </div>
