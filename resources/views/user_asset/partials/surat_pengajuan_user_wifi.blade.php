<html>

<head>
    <style>
        @page {
            size: A4 portrait;
            margin: 40px 20px 10px 20px;
            !important
        }

        body {
            margin: 0;
        }

        /* Style the header */
        .header {
            background-color: #f1f1f1;
            padding: 20px;
            text-align: center;
        }

        .column-3 {
            float: left;
            width: 33.33%;
            padding: 2px;
            margin: 2px;
            border: 1px solid black;
        }

        .column-1 {
            float: left;
            width: 100%;
            padding: 2px;
            margin: 2px;
            border: 1px solid black;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
            /* border: 1px solid black; */

        }
    </style>
</head>

<body>
    <table style="width:100%; border-collapse: collapse">

        <tr style="border-collapse: collapse">
            <td colspan="3">
                <table style="border: 2px solid black; width:100%; margin: 2px 4px 2px 4px; border-collapse: collapse">
                    <tr style="border: 1px solid black; border-collapse: collapse">

                        <td width="85px"
                            style="background-color: rgb(80, 255, 89); text-align: center; border: 2px solid black; border-collapse: collapse;">
                            <table style="text-align: center; border-collapse: collapse;">
                                <tr>
                                    <td style="width:3px; text-align: center; border: 0px; border-collapse: collapse;">
                                        <img style="width:80px;height:80px; display: block; margin-right: auto; margin-right: auto;" class="card-img-top"
                                            src="https://jaringan.pesantrenalirsyad.org/img/logo-pia.png"
                                            alt="Dist Photo 1">
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td width="434px" style="border: 2px solid black; border-collapse: collapse;">
                            <table width="434px" style="border: 0px; text-align: center; border-collapse: collapse;">
                                <tr style="background-color: rgb(255, 80, 138);">
                                    <td
                                        style="color:#0c0c0c; height: 25px; font-style: italic; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:12;">
                                        Records</td>
                                </tr>
                                <tr style="background-color: rgb(36, 173, 42);">
                                    <td
                                        style="color:#ffffff; height: 65px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:15;">
                                        PERMINTAAN SAMBUNGAN INTERNET <br>

                                        UNTUK PRIBADI DAN PERANGKAT
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <td style="background-color: rgb(244, 255, 87); border: 0px solid black; border-collapse: collapse;">
                            <table width="220px" style="border-collapse: collapse; padding:0px; margin:0px">
                                <tr style="border-collapse: collapse;">
                                    <td
                                        style="width:40px; height: 19px; padding: 2px; margin:0px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">
                                        Doc No</td>
                                    <td
                                        style="width:100px; height: 19px; padding: 2px; margin:0px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">
                                        RCD-13-02</td>
                                </tr>2

                                <tr style="border-collapse: collapse;">
                                    <td
                                        style="width:40px; height: 19px; padding: 2px; margin:0px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">
                                        Rev No</td>
                                    <td
                                        style="width:100px; height: 19px; padding: 2px; margin:0px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">
                                        00</td>
                                </tr>

                                <tr style="border-collapse: collapse;">
                                    <td
                                        style="width:40px; height: 19px; padding: 2px; margin:0px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">
                                        Date</td>
                                    <td
                                        style="width:100px; height: 19px; padding: 2px; margin:0px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">
                                        02 Sep 2024</td>
                                </tr>

                                <tr style="border-collapse: collapse;">
                                    <td
                                        style="width:40px; height: 19px; padding: 2px; margin:0px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">
                                        Page No</td>
                                    <td
                                        style="width:100px; height: 19px; padding: 2px; margin:0px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">
                                        1/1</td>
                                </tr>

                            </table>
                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 23px" colspan="3">
            </td>
        </tr>

        <tr style="padding-left:4px">
            <td style="width:130px; height: 20px">
                <p style="font-weight:bold; font-size:10; text-align: left; padding:0px; margin:0px;">Tanggal </p>
            </td>
            <td style="height: 20px;">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">: {{ $data_user['tgl'] }}</p>
            </td>
        </tr>

        <tr style="padding-left:4px">
            <td style="width:130px; height: 20px">
                <p style="font-weight:bold; font-size:10; text-align: left; padding:0px; margin:0px;"> No Surat </p>
            </td>
            <td style="height: 20px;">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">: {{ $no_surat }}</p></p>
            </td>
        </tr>

         <tr style="border-collapse: collapse">
            <td style="height: 23px" colspan="3">
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td width="300px" style="height: 15px" colspan="3">
                <p style="font-weight:bold; font-size:10; text-align: left; padding:0px; margin:0px">
                    Yang bertanda tangan dibawah ini :</p>
            </td>
        </tr>

        <tr style="padding-left:4px">
            <td style="width:130px; height: 20px">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">NIP </p>
            </td>

            <td style="height: 20px;">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">: {{ $data_user['nip'] }}</p>
            </td>
        </tr>

        <tr style="padding-left:4px">
            <td style="width:130px; height: 20px">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">Nama Asatidzah </p>
            </td>
            <td style="height: 20px;">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">: {{ $data_user['nama'] }}</p>
            </td>
        </tr>

        <tr style="padding-left:4px">
            <td style="width:130px; height: 20px">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">HP </p>
            </td>
            <td style="height: 20px;">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">: {{ $data_user['hp'] }}</p>
            </td>
        </tr>

        <tr style="">
            <td style="width:130px; height: 20px">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">Jabatan</p>
            </td>
            <td style="height: 20px;">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">: {{ $data_user['jabatan'] }}</p>
            </td>
        </tr>

        <tr style="">
            <td style="width:130px; height: 20px">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">Bidang</p>
            </td>

            <td style="height: 20px;">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">: {{ $data_user['bidang'] }}</p>
            </td>
        </tr>

        <tr style="">
            <td style="height: 20px" colspan="3">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px">
                    Dibawah Mudir (Tarbiyah, Keunagan, Rumah Tangga) Corek yang tidak perlu</p>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 23px" colspan="3">
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 15px" colspan="3">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px">
                    Memohon komputer/laptop pribadi saya agar bisa disambungkan ke internet pesantren.</p>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 15px" colspan="3">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px">Pertimbangan sebagai berikut</p>
            </td>
        </tr>

        <tr style="">
            <td style="width:130px; height: 20px">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">Keperluan </p>
            </td>
            <td style="height: 20px;">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">: Keperluan</p>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 23px" colspan="3">
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 20px" colspan="3">
                <p style="font-size:10; text-align: right; padding:0px; margin:0px">Tengaran, {{ $data_user['tgl'] }}
                </p>
            </td>
        </tr>

        <tr style="">
            <td colspan="3">
                <table style="border: 1px solid black; width:100%; margin: 2px 4px 2px 4px; border-collapse: collapse">

                    <tr style="border: 1px solid black; border-collapse: collapse">
                        <td
                            style="width:50%; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">Meyetujui :</p>
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">Kabid / Kasek</p>
                        </td>
                        <td
                            style="width:50%; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">Hormat Kami :</p>
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">Pemohon</p>
                        </td>
                    </tr>

                    <tr style="border: 1px solid black; border-collapse: collapse">
                        <td
                            style="height:50px; vertical-align: bottom; width:50%; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">
                                (____________________________)</p>
                        </td>

                        <td
                            style="height:50px; vertical-align: bottom; width:50%; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">
                                ({{ $data_user['nama'] }})</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 23px" colspan="3">
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 15px" colspan="3">
                <p
                    style="font-weight:bold; text-decoration: underline; font-size:11; text-align: left; padding:0px; margin:0px">
                    Diisi Oleh Staf IT</p>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td colspan="3">
                <table style="width:100%; margin: 2px 4px 2px 4px; border-collapse: collapse">
                    <tr style="border-collapse: collapse">
                        <td style="height: 20px">
                            <p style="font-size:10; text-align: left; padding:0px; margin:0px">Nama : Arief</p>
                        </td>
                        <td style="height: 20px">
                            <p style="font-size:10; text-align: right; padding:0px; margin:0px">Tengaran,
                                __/__/{{ date('Y') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td colspan="3">
                <table style="border: 1px solid black; width:100%; margin: 2px 4px 2px 4px; border-collapse: collapse">
                    <tr style="border: 1px solid black; border-collapse: collapse">
                        <td style="border: 1px solid black; height: 20px">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">Merk & Seri</p>
                        </td>
                        <td style="border: 1px solid black; height: 20px">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">Mac Add Wf & Ip Add
                            </p>
                        </td>
                        <td style="border: 1px solid black; height: 20px">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">Mac Add Lan Card & Ip
                                Add
                            </p>
                        </td>
                    </tr>

                    <tr style="border: 1px solid black; border-collapse: collapse">
                        <td style="width:35%; border: 1px solid black; height: 20px">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px"></p>
                        </td>
                        <td style="width:35%; border: 1px solid black; height: 20px">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">
                            </p>
                        </td>
                        <td style="width:35%; border: 1px solid black; height: 20px">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">
                            </p>
                        </td>
                    </tr>

                    <tr style="border: 1px solid black; border-collapse: collapse">
                        <td style="width:35%; border: 1px solid black; height: 20px">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px"></p>
                        </td>
                        <td style="width:35%; border: 1px solid black; height: 20px">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">
                            </p>
                        </td>
                        <td style="width:35%; border: 1px solid black; height: 20px">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 23px" colspan="3">
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 15px" colspan="3">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px">
                    Dengan mengisi Form Permintaan Sambungan Internet diatas berarti saya sepakat dengan ketentuan
                    dibawah : </p>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 15px" colspan="3">
                @foreach ($data_keterangan as $ket)
                    <p style="font-size:10; text-align: left; padding:0px; margin:0px">
                        {{ $loop->iteration }}. {{ $ket->keterangan }}
                    </p>
                @endforeach

            </td>
        </tr>


    </table>
</body>

</html>
