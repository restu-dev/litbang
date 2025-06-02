<?php

use App\Models\UserLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

function getMenu()
{
    $nip = session('nip');
    $level = UserLevel::where(['nip'=>$nip])->first()->id_level;


    $url_aktive = session('url_aktif');

    $query1 = DB::select("SELECT m.id AS id_main,a.id_level,a.id_menu,a.yt_tampil,a.yt_add,a.yt_edit,
                            a.yt_del,a.yt_print,m.id,m.url,m.nama AS nama_menu,m.punya_sub,m.icon,l.name AS nama_level 
                        FROM wifi.akses a
                        LEFT OUTER JOIN wifi.menu m ON m.id=a.id_menu 
                        LEFT OUTER JOIN wifi.level l ON l.id=a.id_level
                        WHERE m.yt_header='Y' AND a.yt_tampil='Y' AND a.id_level='$level' 
                        ORDER BY m.urut_header +1 ASC;");

    $showmenu = "";
    foreach ($query1 as $level1) {
        $id_main = $level1->id_main;
        $id_level = $level1->id_level;
        $nama_level = $level1->nama_level;
        $id_menu = $level1->id_menu;
        $nama_menu = $level1->nama_menu;
        $punya_sub = $level1->punya_sub;
        $url = $level1->url;
        // $akses = $level1->yt_tampil;
        $add = $level1->yt_add;
        $edit = $level1->yt_edit;
        $delete = $level1->yt_del;
        $print = $level1->yt_print;
        $icon = $level1->icon;

        if ($punya_sub == "T") {
            $yt_aktif = ($url_aktive == $url) ? "active" : "";
            $showmenu .= "<li class='nav-item'>
                                <a href='/$url' class='$yt_aktif nav-link' data-add='$add' data-edit='$edit' data-delete='$delete' data-print='$print' class='nav-link yt_akses'>
                                <i class='nav-icon fas $icon'></i>
                                <p>
                                    $nama_menu
                                </p>
                                </a>
                            </li>";
        } else {
            $yt_aktif = ($url_aktive == $url) ? "active" : "";
            $showmenu .= "<li class='nav-item menu-is-opening menu-open'>
                                <a href='#' class='nav-link $yt_aktif'>
                                <i class='nav-icon fas $icon'></i>
                                <p>
                                    $nama_menu
                                    <i class='right fas fa-angle-left'></i>
                                </p>
                                </a>
                                <ul class='nav nav-treeview' style='display: block;'>";
                                    
            $query2 = DB::select("SELECT m.id AS id_main,a.id_level,a.id_menu,a.yt_add,a.yt_edit,
                                        a.yt_del,a.yt_print,m.id,m.url,m.nama AS nama_menu,m.punya_sub,m.icon,l.name AS nama_level 
                                    FROM wifi.akses a
                                    LEFT OUTER JOIN wifi.menu m ON m.id=a.id_menu 
                                    LEFT OUTER JOIN wifi.level l ON l.id=a.id_level
                                    WHERE m.yt_parent='Y' AND a.yt_tampil='Y' AND a.id_level='$level' AND id_header='$id_main'
                                    ORDER BY m.urut_parent +1 ASC");

            foreach ($query2 as $level2) {
                $id_level2 = $level2->id_level;
                $nama_level2 = $level2->nama_level;
                $id_menu2 = $level2->id_menu;
                $nama_menu2 = $level2->nama_menu;
                $punya_sub2 = $level2->punya_sub;
                $url2 = $level2->url;
                // $akses2 = $level2->yt_tampil;
                $add2 = $level2->yt_add;
                $edit2 = $level2->yt_edit;
                $delete2 = $level2->yt_del;
                $print2 = $level2->yt_print;

                $yt_aktif2 = ($url_aktive == $url2) ? "active" : "";
                $showmenu .= "<li class='nav-item'>
                                <a href='/$url2' class='nav-link $yt_aktif2 data-edit='$edit2' data-delete='$delete2' data-print='$print2' class='nav-link yt_akses'>
                                <i class='far fa-circle nav-icon'></i>
                                <p>$nama_menu2</p>
                                </a>
                            </li>";
      
            }
            $showmenu .= "</ul>
                        </li>";
        }
    }
    return $showmenu;
}

function checkitbox($label, $id, $yt, $sub)
{
    $checked = $yt == "Y" ? "checked=''" : "";
    $spasi = $sub == "sub" ? "style='margin-left:30px'" : "";
    $data = "<td class='with-checkbox'>
				<div class='icheck-primary d-inline' $spasi>
				<input type='checkbox' value='' id='$id' name='$id' $checked>
				<label for='$id'>$label</label>
				</div>
			</td>";
    return $data;
}

function checkitboxlone($id, $yt)
{
    $checked = $yt == "Y" ? "checked=''" : "";
    $data = "<td class='with-checkbox' >
				<div class='icheck-primary d-inline'>
				<input type='checkbox' value='' id='$id' name='$id' $checked style='margin: 0 auto'>
				<label for='$id'>&nbsp;</label>
				</div>
			</td>";
    return $data;
}

function infomenu($idmenu, $idlevel)
{
    $dt = DB::select("SELECT COUNT(id) AS ada FROM wifi.akses WHERE id_menu='$idmenu' AND id_level='$idlevel'");
    foreach ($dt as $d) {
        $ada = $d->ada;
    }

    if ($ada == 0) {
        $hasil = array("akses" => "T", "add" => "T", "edit" => "T", "delete" => "T", "print" => "T");
    } else {
        $result = DB::select("SELECT * 
                             FROM wifi.akses
                             WHERE id_menu='$idmenu' 
                             AND id_level='$idlevel'");
        foreach ($result as $d) {
            $akses = $d->yt_tampil;
            $add = $d->yt_add;
            $edit = $d->yt_edit;
            $delete = $d->yt_del;
            $print = $d->yt_print;
        }
        $hasil = array("akses" => $akses, "add" => $add, "edit" => $edit, "delete" => $delete, "print" => $print);
    }

    return $hasil;
}
