<table style="width: 100%">
    <thead>
        <tr>
            <th><b>Daftar Karyawan Resign</b></th>
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
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">NIK (Karyawan)</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Alasan Resign</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $i=1; 
            // dd($organisasi);
        @endphp
        @foreach($resign as $val)
        <tr>
            <td style="text-align: center; border: 1px solid black;">{{ $i++ }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->karyawan->nama_lengkap }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->karyawan->nik_karyawan}}</td>
            <td scope="col" style="border: 1px solid black;">
                @if ($val->alasan_resign == null)

                @elseif ($val->alasan_resign == 1)
                    Tidak Memenuhi Target
                @elseif ($val->alasan_resign == 2)
                    Mendapat Pekerjaan Lain
                @elseif ($val->alasan_resign == 3)
                    Melanjutkan Pendidikan
                @elseif ($val->alasan_resign == 4)
                    Faktor Keluarga
                @elseif ($val->alasan_resign == 5)
                    Pekerjaan dan Passion Tidak Sejalan
                @endif
            </td>
            <td scope="col" style="border: 1px solid black;">{{ $val->tanggal_resign}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
