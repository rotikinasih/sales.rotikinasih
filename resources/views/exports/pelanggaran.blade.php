<table style="width: 100%">
    <thead>
        <tr>
            <th><b>Daftar Pelanggaran Karyawan</b></th>
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
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Tingkat Pelangaran</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Catatan</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Tanggal</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Status Tindakan</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $i=1; 
            // dd($pelanggaran);
        @endphp
        @foreach($pelanggaran as $val)
        <tr>
            <td style="text-align: center; border: 1px solid black;">{{ $i++ }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->karyawan->nama_lengkap }}</td>
            <td scope="col" style="border: 1px solid black;">
                @if ($val->tingkatan == null)
                    
                @elseif ($val->tingkatan == 1)
                    Ringan
                @elseif ($val->tingkatan == 2)
                    Sedang
                @elseif ($val->tingkatan == 3)
                    Serius
                @elseif ($val->tingkatan == 4)
                    Berat
                @endif
            </td>
            <td scope="col" style="border: 1px solid black;">{{ $val->catatan}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->tanggal}}</td>
            <td scope="col" style="border: 1px solid black;">
                @if ($val->status == null)
                    
                @elseif ($val->status == 1)
                    Teguran Lisan
                @elseif ($val->status == 2)
                    SP 1
                @elseif ($val->status == 3)
                    SP 2
                @elseif ($val->status == 4)
                    SP 3
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
