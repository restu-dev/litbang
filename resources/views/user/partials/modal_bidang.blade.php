<div class="modal fade" id="modal-bidang">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" class="form-control" id="id_pegawai_bidang">
                <input readonly type="text" class="form-control" id="nama_bidang">
                <br>
                <div class="form-group">
                    <label>Bidang</label>

                    <select id="bidang_pilih" class="form-control select2">
                        <option value="">-Pilih Bidang-</option>

                        @foreach ($data_bidang as $data)
                            <option value="{{ $data->id }}">[{{ $data->kode }}] {{ $data->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="save_edit_bidang" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
