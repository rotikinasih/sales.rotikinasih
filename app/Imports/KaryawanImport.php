<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\MasterDivisi;
use App\Models\MasterJabatan;
use App\Models\MasterPerusahaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KaryawanImport implements WithStartRow, ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $jenis_kelamin = ['Male' => 0, 'Female' => 1];
    private $gol_darah = ['A' => 0, 'B' => 1, 'O' => 2, 'AB' => 3];
    private $status_pernikahan = ['Single' => 0, 'Married' => 1];
    private $pendidikan = ['SD' => 0, 'SMP' => 1, 'SMA' => 2, 'S1' => 3, 'S2' => 4, 'S3' => 5];
    private $ukuran_baju = ['S' => 0, 'M' => 1, 'L' => 2, 'XL' => 3, 'XXL' => 4, 'Jumbo' => 5];

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        return new Karyawan([
            'nama_karyawan' => $row[1],
            'nik_karyawan' => $row[2],
            'status_kerja' => $row[3],
            'divisi_id' => MasterDivisi::where('nama_divisi', $row[4])->firstOrFail()->id,
            'pt_id' => MasterPerusahaan::where('nama_pt', $row[5])->firstOrFail()->id,
            'tanggal_masuk' => $row[6],
            'tanggal_kontrak' => $row[7],
            'akhir_kontrak' => $row[8],
            'no_kk' => $row[9],
            'nik_penduduk' => $row[10],
            'grade' => $row[11],
            'jabatan_id' => MasterJabatan::where('nama_jabatan', $row[12])->firstOrFail()->id,
            'no_hp' => $row[13],
            'no_wa' => $row[14],
            'no_bpjs_kesehatan' => $row[15],
            'no_bpjs_ketenagakerjaan'   => $row[16],
            'gol_darah' => $this->gol_darah[$row[17]] ?? null,
            'email' => $row[18],
            'tempat_lahir'  => $row[19],
            'tanggal_lahir' => $row[20],
            'umur'  => $row[21],
            'alamat_ktp'    => $row[22],
            'alamat_domisili'   => $row[23],
            'jenis_kelamin' => $this->jenis_kelamin[$row[24]] ?? null,
            'status_pernikahan' => $this->status_pernikahan[$row[25]] ?? null,
            'pendidikan'    => $this->pendidikan[$row[26]] ?? null,
            'nama_sekolah'  => $row[27],
            'kab_penugasan' => $row[28],
            'rekening'  => $row[29],
            'ukuran_baju'    => $this->ukuran_baju[$row[30]] ?? null,
            'no_sdr'    => $row[31],
            'hubungan'  => $row[32]
        ]);
    }
}
