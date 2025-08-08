

<div class="card">

    <div class="card-header">
        <h3 class="card-title">{{ $name_level }}</h3>
    </div>

    <div class="card-body table-responsive">
        <form id="form_akses">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="idlevel" id="idlevel" value="{{ $id_level ?? '' }}">
            <table class="table table-bordered table-hover table-striped align-middle" id="tabelakses">
                <thead class="bg-gradient-primary text-white">
                    <tr>
                        <th class="text-center" style="width: 40px;">No</th>
                        <th style="width: 400px;">Menu</th>
                        <th class="text-center" style="width: 60px;">Tambah</th>
                        <th class="text-center" style="width: 60px;">Edit</th>
                        <th class="text-center" style="width: 60px;">Hapus</th>
                        <th class="text-center" style="width: 60px;">Cetak</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lev1 = DB::select("SELECT * FROM menu WHERE yt_header='Y' ORDER BY urut_header + 1 ASC");
                        $no = 0;
                        $idlevel = $id_level ?? '';

                        foreach ($lev1 as $l) {
                            $no++;
                            $menu = $l->nama;
                            $idmenu = $l->id;
                            $d = infomenu($idmenu, $idlevel);

                            echo "<tr style='background-color:#f8f9fa; font-weight:bold;'>";
                            echo "<td class='text-center'>$no</td>";
                            echo checkitbox($menu, 'akses_' . $idmenu, $d['akses'], 'main');
                            echo checkitboxlone('add_' . $idmenu, $d['add']);
                            echo checkitboxlone('edit_' . $idmenu, $d['edit']);
                            echo checkitboxlone('delete_' . $idmenu, $d['delete']);
                            echo checkitboxlone('print_' . $idmenu, $d['print']);
                            echo '</tr>';

                            $lev2 = DB::select("SELECT * FROM menu WHERE yt_parent='Y' AND id_header='$idmenu' ORDER BY urut_parent +1 ASC");

                            foreach ($lev2 as $le) {
                                $menu2 = $le->nama;
                                $idmenu2 = $le->id;
                                $de = infomenu($idmenu2, $idlevel);

                                echo '<tr class="sub-menu">';
                                echo '<td></td>';
                                echo checkitbox($menu2, 'akses_' . $idmenu2, $de['akses'], 'sub');
                                echo checkitboxlone('add_' . $idmenu2, $de['add']);
                                echo checkitboxlone('edit_' . $idmenu2, $de['edit']);
                                echo checkitboxlone('delete_' . $idmenu2, $de['delete']);
                                echo checkitboxlone('print_' . $idmenu2, $de['print']);
                                echo '</tr>';
                            }
                        }
                    @endphp
                </tbody>
            </table>
            <style>
                #tabelakses th, #tabelakses td {
                    vertical-align: middle !important;
                }
                #tabelakses tr.sub-menu td {
                    background-color: #e9ecef;
                }
                #tabelakses th {
                    background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
                    color: #fff;
                    font-size: 1rem;
                    letter-spacing: 1px;
                }
                #tabelakses tr:hover {
                    background-color: #f1f3f4 !important;
                    transition: background 0.2s;
                }
                #tabelakses td {
                    font-size: 0.95rem;
                }
            </style>
            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                <button id="simpanaksesmenu" type="button" class="btn btn-gradient-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                {{-- <button class="btn btn-default float-right">Batal</button> --}}
            </div>
        </form>
    </div>
</div>
