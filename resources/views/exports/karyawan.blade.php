<table style="width: 100%">
    <thead>
        <tr style="background-color:blueviolet;font-size:large">
            <th scope="col">No</th>
            <th scope="col">Employee Name</th>
            <th scope="col">Employment Identity Number</th>
            <th scope="col">Employment Status</th>
            <th scope="col">Division</th>
            <th scope="col">PT</th>
            <th scope="col">Entry Date</th>
            <th scope="col">Start of Contract</th>
            <th scope="col">End of Contract</th>
            <th scope="col">KK Number</th>
            <th scope="col">Resident Number (NIK)</th>
            <th scope="col">Grade</th>
            <th scope="col">Position</th>
            <th scope="col">HP Number</th>
            <th scope="col">WA Number</th>
            <th scope="col">BPJS of Health</th>
            <th scope="col">BPJS of Employment</th>
            <th scope="col">Blood Group</th>
            <th scope="col">Email</th>
            <th scope="col">Place of Birth</th>
            <th scope="col">Date of Birth</th>
            <th scope="col">Age</th>
            <th scope="col">KTP Address</th>
            <th scope="col">Residence Address</th>
            <th scope="col">Gender</th>
            <th scope="col">Married Status</th>
            <th scope="col">Education</th>
            <th scope="col">School Name</th>
            <th scope="col">Assignment District</th>
            <th scope="col">Rekening Bank</th>
            <th scope="col">Clothes Size</th>
            <th scope="col">Contact Number Family</th>
            <th scope="col">Family Relationship</th>

        </tr>
    </thead>
    <tbody>
        @php $i=1 @endphp
        @foreach($karyawan as $k)
        
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $k->nama_karyawan }}</td>
            <td>{{ $k->nik_karyawan }}</td>
            @if ($k->status_kerja == 0)
            <td>Contract</td>
            @else
            <td>Fixed</td>
            @endif
            <td>{{ $k->divisi->nama_divisi }}</td>
            <td>{{ $k->perusahaan->nama_pt }}</td>
            <td>{{ $k->tanggal_masuk }}</td>
            <td>{{ $k->tanggal_kontrak }}</td>
            <td>{{ $k->akhir_kontrak }}</td>
            <td>{{ $k->no_kk }}</td>
            <td>{{ $k->nik_penduduk }}</td>
            <td>{{ $k->grade }}</td>
            <td>{{ $k->jabatan->nama_jabatan }}</td>
            <td>{{ $k->no_hp }}</td>
            <td>{{ $k->no_wa }}</td>
            <td>{{ $k->no_bpjs_kesehatan }}</td>
            <td>{{ $k->no_bpjs_ketenagakerjaan }}</td>
            @if ($k->gol_darah == 0)
            <td>A</td>
            @elseif ($k->gol_darah == 1)
            <td>B</td>
            @elseif ($k->gol_darah == 2)
            <td>O</td>
            @else
            <td>AB</td>
            @endif
            <td>{{ $k->email }}</td>
            <td>{{ $k->tempat_lahir }}</td>
            <td>{{ $k->tanggal_lahir }}</td>
            <td>{{ $k->umur }} years old</td>
            <td>{{ $k->alamat_ktp }}</td>
            <td>{{ $k->alamat_domisili }}</td>
            @if ($k->jenis_kelamin == 0)
            <td>Male</td>
            @else
            <td>Female</td>
            @endif
            @if ($k->status_pernikahan == 0)
            <td>Single</td>
            @else
            <td>Married</td>
            @endif
            @if ($k->pendidikan == 0)
            <td>SD</td>
            @elseif ($k->pendidikan == 1)
            <td>SMP</td>
            @elseif ($k->pendidikan == 2)
            <td>SMA</td>
            @elseif ($k->pendidikan == 3)
            <td>S1</td>
            @elseif ($k->pendidikan == 4)
            <td>S2</td>
            @else
            <td>S3</td>
            @endif
            <td>{{ $k->nama_sekolah }}</td>
            <td>{{ $k->kab_penugasan }}</td>
            <td>{{ $k->rekening }}</td>
            @if ($k->ukuran_baju == 0)
            <td>S</td>
            @elseif ($k->ukuran_baju == 1)
            <td>M</td>
            @elseif ($k->ukuran_baju == 2)
            <td>L</td>
            @elseif ($k->ukuran_baju == 3)
            <td>XL</td>
            @elseif ($k->ukuran_baju == 4)
            <td>XXL</td>
            @else
            <td>Jumbo</td>
            @endif
            <td>{{ $k->no_sdr }}</td>
            <td>{{ $k->hubungan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
