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
                <input type="hidden" class="form-control" id="id_menu_header">

                <div class="form-group">
                    <label for="nama_menu_header">Nama</label>
                    <input type="text" class="form-control" id="nama_menu_header" placeholder="Nama">
                </div>

                <div class="form-group">
                    <label for="url_menu_header">URL</label>
                    <input type="text" class="form-control" id="url_menu_header" placeholder="URL">
                </div>

                <div class="form-group">
                    <label>Punya Sub</label>
                    <select id="punya_sub" class="form-control">
                        <option value="Y">Ya</option>
                        <option value="T">Tidak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="icon_menu_header">Icon</label>
                    <input type="text" class="form-control" id="icon_menu_header" placeholder="Icon">
                </div>

                <div class="form-group">
                    <label for="urut_menu_header">Urut</label>
                    <input type="text" class="form-control" id="urut_menu_header" placeholder="Urut">
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="save_menu_header" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
