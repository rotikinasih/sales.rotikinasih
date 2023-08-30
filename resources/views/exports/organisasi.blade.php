<table style="width: 100%">
    <thead>
        <tr>
            <th><b>Daftar Organisasi/Mutasi Karyawan</b></th>
        </tr>
        <tr>
            <th><b>Dicetak pada : {{$waktu_sekarang}}</b></th>
        </tr>
    </thead>
</table>
<table style="width: 100%">
    <thead>
        <tr style="background-color:yellow;font-size:large">
            <th scope="col" style="background-color: yellow; text-align: center; width: 1cm; border: 1px solid black;">No</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Nama Lengkap</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Entitas</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Divisi</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Jabatan</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Tanggal Masuk</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Tanggal Berakhir</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $i=1; 
            // dd($organisasi);
        @endphp
        @foreach($organisasi as $val)
        <tr>
            <td style="text-align: center; border: 1px solid black;">{{ $i++ }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->karyawan->nama_lengkap }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->perusahaan->nama_pt}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->divisi->nama_divisi}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->jabatan->nama_jabatan }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->tgl_masuk }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->tgl_berakhir }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
