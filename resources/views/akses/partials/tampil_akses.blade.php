

<div class="card">

    <div class="card-header">
        <h3 class="card-title">{{ $name_level }}</h3>
    </div>

    <div class="card-body">

        <form id='form_akses'>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="idlevel" id="idlevel" value="<?php echo $id_level == '' ? '' : $id_level; ?>">
            <table class="table table-striped m-b-0" id="tabelakses">
                <thead>
                    <tr>
                        <th style="width: 5px;">No </th>
                        <th style="width: 400px;">Menu</th>
                        <th style="width: 20px;">Tambah</th>
                        <th style="width: 20px;">Edit</th>
                        <th style="width: 20px;">Hapus</th>
                        <th>Cetak</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lev1 = DB::select("SELECT * 
                                            FROM menu
                                            WHERE yt_header='Y'
                                            ORDER BY urut_header + 1 ASC");
                        $no = 0;
                        $idlevel = $id_level == '' ? '' : $id_level;

                        foreach ($lev1 as $l) {
                            $no++;
                            $menu = $l->nama;
                            $idmenu = $l->id;

                            $d = infomenu($idmenu, $idlevel);

                            $show = "<tr style='padding:0px'>";
                            $show .= "<td style='text-align:center'>$no</td>";
                            $show .= checkitbox($menu, 'akses_' . $idmenu, $d['akses'], 'main');
                            $show .= checkitboxlone('add_' . $idmenu, $d['add']);
                            $show .= checkitboxlone('edit_' . $idmenu, $d['edit']);
                            $show .= checkitboxlone('delete_' . $idmenu, $d['delete']);
                            $show .= checkitboxlone('print_' . $idmenu, $d['print']);
                            $show .= '</tr>';

                            echo $show;

                            $lev2 = DB::select("SELECT * 
                                                FROM menu
                                                WHERE yt_parent='Y'
                                                AND id_header='$idmenu'
                                                ORDER BY urut_parent +1 ASC");

                            foreach ($lev2 as $le) {
                                $menu2 = $le->nama;
                                $idmenu2 = $le->id;

                                $de = infomenu($idmenu2, $idlevel);
                                $show2 = '<tr>';
                                $show2 .= '<td></td>';
                                $show2 .= checkitbox($menu2, 'akses_' . $idmenu2, $de['akses'], 'sub');
                                $show2 .= checkitboxlone('add_' . $idmenu2, $de['add']);
                                $show2 .= checkitboxlone('edit_' . $idmenu2, $de['edit']);
                                $show2 .= checkitboxlone('delete_' . $idmenu2, $de['delete']);
                                $show2 .= checkitboxlone('print_' . $idmenu2, $de['print']);
                                $show2 .= '</tr>';

                                echo $show2;
                            }
                        }
                    @endphp

                </tbody>
            </table>
            <div class="card-footer">
                <button id='simpanaksesmenu' type='button' class="btn btn-info">Simpan</button>
                {{-- <button class="btn btn-default float-right">Batal</button> --}}
            </div>
            {{-- <button class='pull-right btn btn-primary btn-md' id='simpanaksesmenu'  type='submit'><i class='ion-arrow-right-b'></i>[F2] Simpan</button>
            <button style='margin-left:20px;margin-right:20px' class='pull-right btn btn-danger btn-md' data-dismiss="modal"  type='button'><i class='ion-arrow-right-b'></i>[Esc] Batal</button> --}}
        </form>
    </div>
</div>
