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

        <tr style="">
            <td colspan="3">
                <table style="width:100%; margin: 2px 4px 2px 4px; border-collapse: collapse">
                    <tr style="border-collapse: collapse">
                        <td
                            style="width:20%; padding: 1px 2px 1px 2px; text-align: left; border: 0px solid black; border-collapse: collapse; font-size:10;">
                            <img style="width:110px;height:80px;" class="card-img-top"
                                src="https://fpb-it.pesantrenalirsyad.org/images/logo_pia.jpeg" alt="Dist Photo 1">
                        </td>

                        <td
                            style="padding: 1px 2px 1px 2px; text-align: center; border: 0px solid black; border-collapse: collapse; font-size:10;">
                            <p style="text-align: center; font-size:12; font-weight:bold;  padding:0px; margin:0px">
                                PESANTREN ISLAM ALIRSYAD TENGARAN</p>
                            <p style="text-align: center; font-size:12; font-weight:bold;  padding:0px; margin:0px">
                                BIDANG TEKNOLOGI INFORMASI</p>
                            <p style="text-align: center; font-size:10; padding:0px; margin:0px">Jln
                                Jl Raya Solo - Semarang Km. 45 Dusun Gintungan,</p>
                            <p style="text-align: center; font-size:10; padding:0px; margin:0px">
                                Desa Butuh, Kec. Tengaran, Kab. Semarang 5077</p>
                            <p style="text-align: center; font-size:10; padding:0px; margin:0px">
                                Telp (0298) 312456 ext 102</p>
                        </td>

                        <td
                            style="width:20%; padding: 1px 2px 1px 2px; text-align: right; border: 0px solid black; border-collapse: collapse; font-size:10;">
                            <img style="width:60px;height:80px;" class="card-img-top"
                                src="https://fpb-it.pesantrenalirsyad.org/images/logo_isct.jpeg" alt="Dist Photo 1">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 15px" colspan="3">
                <hr style="color: black">
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 15px" colspan="3">
                <p style="font-size:12; font-weight:bold; text-align: center; padding:0px; margin:0px">
                    Form Permintaan Sambungan Internet Untuk</p>
                <p style="font-size:12; font-weight:bold; text-align: center; padding:0px; margin:0px">
                    Komputer/Laptop Pribadi</p>
                    <br>
                <p style="font-size:12; font-weight:bold; text-align: center; padding:0px; margin:0px">
                    {{ $data_user['no_surat'] }}</p>
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 23px" colspan="3">
            </td>
        </tr>

        <tr style="border-collapse: collapse">
            <td style="height: 15px" colspan="3">
                <p style="font-weight:bold; font-size:10; text-align: left; padding:0px; margin:0px">
                    Yang bertanda tangan dibawah ini :</p>
            </td>
        </tr>

        <tr style="">
            <td style="width:130px; height: 20px">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">Nama Asatidzah </p>
            </td>
            <td style="height: 20px;">
                <p style="font-size:10; text-align: left; padding:0px; margin:0px;">: {{ $data_user['nama'] }}</p>
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

                    <tr style="border-collapse: collapse">
                        <td style="height: 23px" colspan="2">
                            <p style="font-weight:bold; font-size:10; text-align: center; padding:0px; margin:0px">
                                Megetahui : </p>
                        </td>
                    </tr>

                    <tr style="border: 1px solid black; border-collapse: collapse">
                        <td
                            style="width:50%; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">Kabag IT</p>
                        </td>
                        <td
                            style="width:50%; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">
                            <p style="font-size:10; text-align: center; padding:0px; margin:0px">Mudir
                                Tarbiyah/Keuangan/RT</p>
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
                                (____________________________)</p>
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
