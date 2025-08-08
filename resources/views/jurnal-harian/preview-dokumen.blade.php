

@if ($data['jenis'] == 'dokumen')
    <h2>📑 {{ $data['jurnal_harian']['uraian_kegiatan'] }}</h2>

    <iframe width="100%" height="500px" src="upload/dokumen/{{ $data['jurnal_harian']['file_dokumen'] }}"></iframe>
@else
    <h2>📷 {{ $data['jurnal_harian']['uraian_kegiatan'] }}</h2>

    <iframe width="100%" height="500px" src="upload/foto/{{ $data['jurnal_harian']['file_foto'] }}"></iframe>
@endif
