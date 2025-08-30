<table style="width: 100%">
    <thead>
        <tr>
            <th><b>Daftar Penilaian Karyawan</b></th>
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
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Tanggal Penilaian</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Nilai Kedisiplinan</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Nilai Skill</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Nilai Knowledge</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Nilai Attitude</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Penilaian Akhir</th>
            <th scope="col" style="background-color: yellow; text-align: center; width: 4cm; border: 1px solid black;">Hasil DISC</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $i=1; 
            // dd($penilaian);
        @endphp
        @foreach($penilaian as $val)
        <tr>
            <td style="text-align: center; border: 1px solid black;">{{ $i++ }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->karyawan->nama_lengkap }}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->tanggal_penilaian}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->nilai_kedisiplinan}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->nilai_skill}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->nilai_knowledge}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->nilai_attitude}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->penilaian_akhir}}</td>
            <td scope="col" style="border: 1px solid black;">{{ $val->hasil_disc}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
