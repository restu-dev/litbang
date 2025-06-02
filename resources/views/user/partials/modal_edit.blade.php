<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" class="form-control" id="id_pegawai_edit_level">
                <input readonly type="text" class="form-control" id="nama_level">
                <br>
                <div class="form-group">
                    <label>Level</label>

                    <select id="level_pilih" class="form-control">
                        <option value="">-Pilih Level-</option>

                        @foreach ($data_level as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="save_edit_level" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
