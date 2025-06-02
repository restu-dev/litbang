<html><head><style>
	@page {
		size: A4 portrait; 
		margin: 40px 15px 10px 15px;
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
	</head><body>
		<table style="border: 1px solid black; width:100%; border-collapse: collapse">
			<tr style="border: 1px solid black;">
				
				{{-- <td style="border-bottom: 1px solid black; text-align:left; ">
					<img style="width:110px;height:80px;" class="card-img-top" src="https://fpb-it.pesantrenalirsyad.org/images/logo_pia.jpeg" alt="Dist Photo 1">
				</td>

				<td style="border-bottom: 1px solid black;">
					<p style="text-align: center; font-size:12; font-weight:bold;  padding:0px; margin:0px">PESANTREN ISLAM ALIRSYAD TENGARAN</p>
					<p style="text-align: center; font-size:8; font-style: italic; padding:0px; margin:0px">Jl. Solo - Semarang km 45 Ds.  Butuh, Kec. Tengaran, Kab. Semarang</p>
				</td>

				<td style="border-bottom: 1px solid black; text-align:right">
					<img style="width:60px;height:80px;" class="card-img-top" src="https://fpb-it.pesantrenalirsyad.org/images/logo_isct.jpeg" alt="Dist Photo 1">
					<img style="width:60px;height:80px;" class="card-img-top" src="https://fpb-it.pesantrenalirsyad.org/images/logo_isct.jpeg" alt="Dist Photo 1">
				</td> --}}
				<td colspan="3">
					<table style="border: 0px solid black; width:99%; margin: 2px 4px 2px 4px; border-collapse: collapse">
						<tr style="border: 1px solid black; border-collapse: collapse">
							<td style="width:20%; padding: 1px 2px 1px 2px; text-align: left; border: 0px solid black; border-collapse: collapse; font-size:10;">
								<img style="width:110px;height:80px;" class="card-img-top" src="https://fpb-it.pesantrenalirsyad.org/images/logo_pia.jpeg" alt="Dist Photo 1">
							</td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 0px solid black; border-collapse: collapse; font-size:10;">
								<p style="text-align: center; font-size:12; font-weight:bold;  padding:0px; margin:0px">PESANTREN ISLAM ALIRSYAD TENGARAN</p>
								<p style="text-align: center; font-size:12; font-weight:bold;  padding:0px; margin:0px">BIDANG {{ strtoupper($bidang['bidang']) }}</p>
								<p style="text-align: center; font-size:10; font-style: italic; padding:0px; margin:0px">Jln Solo-Semarang KM.45, Dsn. Gintungan, Ds. Butuh, </p>
								<p style="text-align: center; font-size:10; font-style: italic; padding:0px; margin:0px">Kec. Tengaran, Kab. Semarang 50775 Telp (0298)321658</p>
							</td>
							<td style="width:20%; padding: 1px 2px 1px 2px; text-align: right; border: 0px solid black; border-collapse: collapse; font-size:10;">
								<img style="width:60px;height:80px;" class="card-img-top" src="https://fpb-it.pesantrenalirsyad.org/images/logo_isct.jpeg" alt="Dist Photo 1">
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr style="border: 1px solid black; border-collapse: collapse">
				<td style="height: 15px" colspan="3">
					<hr>
				</td>
			</tr>


			<tr style="border: 1px solid black; border-collapse: collapse">
				<td style="height: 15px" colspan="3">
					<p style="font-size:12; font-weight:bold; text-align: center; padding:0px; margin:0px">FORMULIR  PENGAJUAN PERANGKAT IT</p>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td style="height: 20px">
					<p style="font-style: italic; font-weight:bold; font-size:10;  text-align: center; padding:0px; margin:0px;">Tanggal : {{ $header['tgl'] }}</p>
				</td>
				<td colspan="2" style="height: 20px;">
					<p style="font-style: italic; font-weight:bold; font-size:10;  text-align: center; padding:0px; margin:0px;">No Surat : {{ $header['no_surat'] }}</p>
				</td>
			</tr>

			<tr style="border: 1px solid black; border-collapse: collapse">
				<td style="background-color: black;" colspan="3">
					<p style="font-weight:bold; color: white; font-size:10;  text-align: left; padding:0px; margin:0px">A. DATA PEMOHON</p>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td style="height: 30px">
					<p style="font-style: italic; font-size:9;  text-align: center; padding:0px; margin:0px">Nama : {{ $header['nama_pegawai'] }}</p>
				</td>
				<td>
					<p style="font-style: italic; font-size:9;  text-align: center; padding:0px; margin:0px">Jabatan : {{ $header['jabatan'] }}</p>
				</td>
				<td>
					<p style="font-style: italic; font-size:9;  text-align: center; padding:0px; margin:0px">Jenjang/ Divisi : {{ $header['nama_divisi'] }}</p>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td style="background-color: black;" colspan="3">
					<p style="font-weight:bold; color: white; font-size:10;  text-align: left; padding:0px; margin:0px">B. DATA PENGAJUAN</p>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td colspan="3">
					<table style="border: 1px solid black; width:99%; margin: 2px 2px 2px 4px; border-collapse: collapse">
						
						<tr style="border: 1px solid black; border-collapse: collapse">
							<td style="padding: 1px 1px 1px 1px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:9;">No</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">Jenis Perangkat</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">Spesifikasi</td>
							<td style="padding: 1px 1px 1px 1px; text-align: right; border: 1px solid black; border-collapse: collapse; font-size:9;">Jumlah</td>
							<td style="padding: 1px 1px 1px 1px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:9;">Anggaran</td>
							<td style="padding: 1px 1px 1px 1px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:9;">Nama Anggaran</td>
							<td style="padding: 1px 1px 1px 1px; text-align: right; border: 1px solid black; border-collapse: collapse; font-size:9;">Harga Peritem</td>
							<td style="padding: 1px 1px 1px 1px; text-align: right; border: 1px solid black; border-collapse: collapse; font-size:9;">Nominal Anggaran</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">Uraian Kebutuhan</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">Jenis Pengajuan</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">Penguna</td>
						</tr>

						<?php 
							$sum_jml=0;
							$sum_ang=0;
						?>

						@foreach($detail as $index => $d)
						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="padding: 1px 1px 1px 1px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $index+1 }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $d->nama_perangkat }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $d->spesifikasi }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: right; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $d->jumlah }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $d->yt_anggaran }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:8;">{{ $d->nama_anggaran }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: right; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $d->harga_peritem==''?'':number_format($d->harga_peritem,0,',','.') }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: right; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $d->anggaran==''?'':number_format($d->anggaran,0,',','.') }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $d->uraian_kebutuhan }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $d->jenis_pengajuan }}</td>
							<td style="padding: 1px 1px 1px 1px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:9;">{{ $d->nama_pegawai }}</td>
							<?php 
								  $sum_jml+=$d->jumlah;
								  $sum_ang+=$d->anggaran;
							?>
						</tr>
						@endforeach 
						
						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td colspan="3" style="text-align: center; font-weight:bold; border: 1px solid black; border-collapse: collapse; font-size:10;">Total</td>
							<td style="border: 1px solid black; text-align: right; font-weight:bold; border-collapse: collapse; font-size:10;">{{ $sum_jml }}</td>
							<td></td>
							<td></td>
							<td style="border: 1px solid black; text-align: right; font-weight:bold; border-collapse: collapse; font-size:10;">{{ number_format($sum_ang,0,',','.') }}</td>
							<td colspan="4" style="border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>
					</table>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td colspan="3">
					<table style="border: 1px solid black; width:99%; margin: 2px 4px 2px 4px; border-collapse: collapse">
						<tr style="border: 1px solid black; border-collapse: collapse">
							<td style="padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:10;">
								<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Dengan mengisi form Pengajuan ini berarti saya bersedia untuk :</p>
								<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">1. Tidak menyalahgunakan amanat</p>
								<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">2. Menggunakan fungsi perangkat / aset sebagaimana mestinya </p>
								<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">3. Menjaga dan memelihara aset/perangkat</p>
							</td>
							<td rowspan="2" style="padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px; text-align: left; border: 1px solid black; font-size:10;">
								<table style="border: 0px solid black; width:100%; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;">
									<tr style="padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px; border: 0px solid black;">
										<td style="vertical-align: top; padding: 0px 0px 0px 0px; text-align: center; border: 0px solid black; font-size:10;">
											<p style="padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">Menyetujui,</p>
											<p style="padding: 20px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">(_____________)</p>
											<p style="padding: 1px 0px 0px 0px; margin: 1px 0px 0px 0px; font-size:9px; font-style: italic">(diisi oleh kabid / kamad ybs)</p>
										</td>
										<td style="vertical-align: top; padding: 0px 0px 0px 0px; text-align: center; border: 0px solid black; font-size:10;">
											<p style="padding: 10px 0px 0px 0px; margin: 40px 0px 0px 0px; font-size:10px; font-weight: bold">Mengetahui,</p>
											<p style="padding: 20px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">(_____________)</p>
											<p style="padding: 1px 0px 0px 0px; margin: 1px 0px 0px 0px; font-size:9px; font-style: italic">(diisi oleh mudir ybs apabila pembelian >= Rp 5.000.000)</p>
										</td>
										<td style="vertical-align: top; padding: 0px 0px 0px 0px; text-align: center; border: 0px solid black; font-size:10;">
											<p style="padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">Pemohon,</p>
											<p style="padding: 20px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">( {{ $header['nama_pegawai'] }} )</p>
											<p style="padding: 1px 0px 0px 0px; margin: 1px 0px 0px 0px; font-size:9px; font-style: italic">(diisi oleh pemohon)</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse">
							<td style="padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:10;">
								<p style="font-weight:bold; font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Perhatian:</p>
								<p style="font-weight:bold; font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Pengajuan perangkat umum (mengacu pada kebijakan no ), harus 3 hari kerja sebelum penggunaan, dan pengajuan perangkat khusus (mengacu pada kebijakan no ), harus 6 hari kerja sebelum penggunaan.
								</p>
							</td>
						</tr>
						
					</table>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td style="background-color: black;" colspan="3">
					<p style="font-weight:bold; color: white; font-size:10;  text-align: left; padding:0px; margin:0px">C. DATA ANALISA KEBUTUHAN</p>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td colspan="3">
					<table style="border: 1px solid black; width:99%; margin: 2px 4px 2px 4px; border-collapse: collapse">
						<tr style="border: 1px solid black; border-collapse: collapse">
							<td style="height: 10px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:10;">
								<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Tanggal Terima :</p>
							</td>
							<td rowspan="2" style="vertical-align: top;  text-align: left; height: 20px; padding: 5px 0px 0px 4px; margin: 0px 0px 0px 4px; text-align: left; border: 1px solid black; font-size:10;">
								<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Uraian Analisa Kebutuhan :</p>
							</td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse">
							<td style="padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:10;">
								<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Nama Penerima :</p>
							</td>
						</tr>
						
					</table>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td colspan="3">
					<table style="border: 1px solid black; width:99%; margin: 2px 4px 2px 4px; border-collapse: collapse">
						<tr style="border: 1px solid black; border-collapse: collapse">
							
							<td style="height: 10px; width: 153px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:10;">
								<table style="width:100%; margin: 0px 0px 0px 0px; border-collapse: collapse">
									<tr>
										<td style="width: 60px">
											<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Keputusan :</p>
										</td>
										<td style="width: 10px;">										
											<div style="height: 10px; width: 10px; border: 1px solid blue;"></div>
										</td>
										<td style="width: 50px">
											<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Setuju</p>
										</td>
										<td style="width: 90px">
											<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Tanggal Keputusan :</p>
										</td>
										<td style="padding: 4px 2px 0px 4px; margin: 0px 0px 0px 4px;">
											<div style="height: 15px; width: 160px; border: 1px solid blue;"></div>
										</td>
									</tr>
									<tr>
										<td style="width: 60px"></td>
										<td style="width: 10px;">										
											<div style="height: 10px; width: 10px; border: 1px solid blue;"></div>
										</td>
										<td>
											<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Tidak</p>
										</td>
									</tr>
								</table>
							</td>
							

							<td rowspan="2" style="width: 120px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px; text-align: left; border: 1px solid black; border-collapse: collapse; font-size:10;">
								<table style="border: 0px solid black; width:100%; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px;">
									<tr style="padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px; border: 0px solid black;">
										<td style="vertical-align: top; padding: 0px 0px 0px 0px; text-align: center; border: 0px solid black; font-size:10;">
											<p style="padding: 0px 0px 0px 0px; margin: 20px 0px 0px 0px; font-size:10px; font-weight: bold">Kabag IT,</p>
											<p style="padding: 20px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">(_____________)</p>
										</td>
										<td style="vertical-align: top; padding: 0px 0px 0px 0px; text-align: center; border: 0px solid black; font-size:10;">
											<p style="padding: 0px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">Menyetujui : </p>
										</td>
										<td style="vertical-align: top; padding: 0px 0px 0px 0px; text-align: center; border: 0px solid black; font-size:10;">
											<p style="padding: 0px 0px 0px 0px; margin: 20px 0px 0px 0px; font-size:10px; font-weight: bold">Staf Pembelian IT</p>
											<p style="padding: 20px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">(_____________)</p>
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse">
							<td>
								<table style="width:99%; margin: 0px 0px 0px 0px; border-collapse: collapse">
									<tr>
										<td style="width: 40px">
											<p style="font-size:9px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Solusi :</p>
										</td>
										<td style="padding: 2px 2px 2px 4px; margin: 0px 0px 0px 4px;">
											<div style="height: 40px; width: 340px; border: 1px solid blue;"></div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
					</table>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td style="background-color: black;" colspan="3">
					<p style="font-weight:bold; color: white; font-size:10;  text-align: left; padding:0px; margin:0px">D. PENGAJUAN PO DAN INSTALASI</p>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td colspan="3">
					<table style="border: 1px solid black; width:99%; margin: 2px 4px 2px 4px; border-collapse: collapse">
						<tr style="border: 1px solid black; border-collapse: collapse">
							<td style="width:10px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">No</td>
							<td style="width:120px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Nomer PO</td>
							<td style="width:250px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Supplier</td>
							<td style="width:80px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Tgl Bayar</td>
							<td style="width:80px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Harga</td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Tgl Penerimaan</td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Tgl Instalasi</td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

					</table>
				</td>
			</tr>

			{{-- <tr style="border: 1px solid black;">
				<td style="background-color: black;" colspan="3">
					<p style="font-weight:bold; color: white; font-size:10;  text-align: left; padding:0px; margin:0px">E. BERITA ACARA SERAH TERIMA PERANGKAT / ASET</p>
				</td>
			</tr>

			<tr style="border: 1px solid black;">
				<td colspan="3">
					<table style="width:60%; margin: 0px 0px 0px 0px; border-collapse: collapse">
						<tr>
							<td style="width: 10px;">
								<p style="width: 40px; font-size:10px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Tanggal :</p>
							</td>

							<td style="width: 30px">
								<p style="font-size:10px; padding: 0px 0px 0px 4px; margin: 0px 0px 0px 4px;">Barang yang diserahkan :</p>
							</td>
						</tr>
					</table>

					<table style="border: 1px solid black; width:100%; margin: 2px 4px 2px 4px; border-collapse: collapse">
						<tr style="border: 1px solid black; border-collapse: collapse">
							<td style="width:10px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">No</td>
							<td style="width:120px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Jenis</td>
							<td style="width:250px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">No Aset</td>
							<td style="width:30px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Qty</td>
							<td style="width:80px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Kondisi</td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;">Keterangan</td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

						<tr style="border: 1px solid black; border-collapse: collapse;">
							<td style="height:15px; padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
							<td style="padding: 1px 2px 1px 2px; text-align: center; border: 1px solid black; border-collapse: collapse; font-size:10;"></td>
						</tr>

					</table>

					<table style="width:100%; margin: 0px 4px 2px 4px; border-collapse: collapse;">
						<tr>
							<td style="width:200px">
								<p style="text-align: center; padding: 0px 0px 0px 0px; margin: 4px 0px 0px 0px; font-size:10px; font-weight: bold">Yang Menyerahkan</p>
								<p style="text-align: center; padding: 40px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">IT</p>
							</td>

							<td style="width:200px">
								<p style="text-align: center; padding: 0px 0px 0px 0px; margin: 4px 0px 0px 0px; font-size:10px; font-weight: bold">Yang Menerima</p>
								<p style="text-align: center; padding: 40px 0px 0px 0px; margin: 0px 0px 0px 0px; font-size:10px; font-weight: bold">Pemohon</p>
							</td>

							<td style="vertical-align: top; width:80px">
								<p style="font-size:10px; padding: 2px 0px 0px 4px; margin: 0px 0px 0px 4px;">Hasil Konfirmas :</p>
							</td>

							<td style="vertical-align: top; padding: 2px 2px 2px 4px; margin: 0px 0px 0px 4px;">
								<div style="height: 65px; width: 250px; border: 1px solid blue;"></div>
							</td>
						</tr>
					</table>
				</td>
			</tr> --}}

		</table>

	</body></html>