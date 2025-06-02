<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use KwikKoders\Zkteco\Http\Library\ZktecoLib;
// use KwikKoders\Zkteco\Http\Library\ZktecoLib;

class CobaController extends Controller
{
    public function index()
    {
        // $zk = new ZktecoLib(config('192.16.21.70'));
        // $koneksi = $zk->connect();

        // var_dump($koneksi);
        $jml = MasterGroup::count()+1;

        var_dump(sprintf("%02d", $jml));
        
    }

  

}
