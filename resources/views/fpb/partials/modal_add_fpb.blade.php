<div class="modal fade" id="modal-add-fpb" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 id="modal-title-add-fpb" class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="add_id_master_fpb">
                <input type="hidden" id="add_no_surat">

                <div class="row">
                    <div class="col-3">
                        <div class="border border-secondary">
                            <div class="card-body">
                                <input type="hidden" name="id_unit" class="form-control" id="id_unit" value="">

                                <div class="form-group mb-1">
                                    <label for="fpb_barang" style="font-size:12px;" >Nama Barang</label>

                                     <select id="fpb_barang" class="form-control form-control-sm select2" style="width: 100%;">
                                     </select>
                                </div>

                                <div class="form-group mb-1">
                                    <label for="fpb_jumlah" style="font-size:12px;">Jumlah</label>

                                    <input type="number" name="fpb_jumlah" class="form-control form-control-sm" id="nama_unit"
                                        placeholder="Jumlah">
                                </div>

                                <div class="form-group mb-1">
                                    <label for="fpb_anggaran" style="font-size:12px;" >Anggaran</label>

                                    <select id="fpb_anggaran" class="form-control form-control-sm select2" style="width: 100%;">
                                        <option value="">-Anggaran-</option>
                                        <option value="Y">Y</option>
                                        <option value="N">N</option>
                                    </select>
                                </div>

                                <div class="form-group mb-1">
                                    <label for="fpb_nominal" style="font-size:12px;">Nominal Peritem</label>

                                    <input type="text" name="fpb_nominal" class="form-control form-control-sm" id="fpb_nominal"
                                        placeholder="Nominal">
                                </div>

                                <div class="form-group mb-1">
                                    <label for="fpb_pengguna" style="font-size:12px;">Pengguna</label>

                                    <select id="fpb_pengguna" class="form-control form-control-sm select2" style="width: 100%;">
                                     </select>
                                </div>

                                <div class="form-group mb-1">
                                    <label for="nama_unit" style="font-size:12px;">Deskripsi</label>

                                    <input type="text" name="nama_unit" class="form-control form-control-sm" id="nama_unit"
                                        placeholder="Kode Unit">
                                </div>

                                <div class="form-group mb-1">
                                    <label for="nama_unit" style="font-size:12px;">File</label>

                                    <input type="text" name="nama_unit" class="form-control form-control-sm" id="nama_unit"
                                        placeholder="Kode Unit">
                                </div>


                            </div>

                            <div class="card-footer">
                                <button id="add_reset_form" class="btn btn-warning btn-sm"><i
                                        class="fas fa-refresh"></i>Reset</button>
                                <button id="add_save_form" class="btn btn-success btn-sm"><i
                                        class="fas fa-save"></i>Save</button>
                            </div>
                        </div>
                    </div>

                    <div class="col">

                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" id="save_edit_bidang" class="btn btn-primary">Ajukan</button>
            </div>
        </div>
    </div>
</div>
