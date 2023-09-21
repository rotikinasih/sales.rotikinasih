<table style="width: 100%">
    <thead>
        <tr>
            <th><b>Daftar Pelatihan Karyawan</b></th>
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
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Nama Pelatihan</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Kategori Pelatihan</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Tanggal</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Durasi (Jam)</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $i=1; 
            // dd($pelatihan);
        @endphp
        @foreach($pelatihan as $val)
        <tr>
            <td style="text-align: center; border: 1px solid black;">{{ $i++ }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->karyawan->nama_lengkap }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->nama_pelatihan}}</td>
            <td scope="col" style="border: 1px solid black;">
                @if ($val->kategori_pelatihan == null)
                    
                @elseif ($val->kategori_pelatihan == 1)
                    Internal Perusahaan
                @elseif ($val->kategori_pelatihan == 2)
                    Personal (Individu)
                @elseif ($val->kategori_pelatihan == 3)
                    Pemerintah
                @endif
            </td>
            <td scope="col" style="border: 1px solid black;">{{ $val->tanggal_pelatihan}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->durasi_pelatihan}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
