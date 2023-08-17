<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\MasterDivisi;
use App\Models\MasterJabatan;
use App\Models\MasterPerusahaan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KaryawanImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $errors = []; // array to accumulate errors
    private $status_kerja = ['Contract' => 0, 'Fixed' => 1, 'Training' => 2];
    private $jenis_kelamin = ['Male' => 0, 'Female' => 1];
    private $gol_darah = ['A' => 0, 'B' => 1, 'O' => 2, 'AB' => 3];
    private $status_pernikahan = ['Single' => 0, 'Married' => 1];
    private $pendidikan = ['SD' => 0, 'SMP' => 1, 'SMA' => 2, 'S1' => 3, 'S2' => 4, 'S3' => 5];
    private $ukuran_baju = ['S' => 0, 'M' => 1, 'L' => 2, 'XL' => 3, 'XXL' => 4, 'Jumbo' => 5];
    // private $setStartRow = 3;

    public function startRow(): int
    {
        return 4;
    }
    public function model(array $row)
    {
        // if (!isset($row[1])) {
        //     return null;
        // }

        return new Karyawan([
            'nama_karyawan' => $row[1] == null ? null : $row[1],
            'nik_karyawan' => $row[2],
            'status_kerja' => $row[3] ?? null == null ? null : $this->status_kerja[$row[3]] ?? null, 
            'divisi_id' => $row[6]  == null ? null : MasterDivisi::where('nama_divisi', $row[4])->firstOrFail()->id,
            'pt_id' => $row[6]  == null ? null : MasterPerusahaan::where('nama_pt', $row[5])->firstOrFail()->id,
            'tanggal_masuk' => $row[6]  == null ? null : $row[6],
            'tanggal_kontrak' => $row[7]  == null ? null : $row[7],
            'akhir_kontrak' => $row[8] == null ? null : $row[8],
            'no_kk' => $row[9] == null ? null : $row[9],
            'nik_penduduk' => $row[10] == null ? null : $row[10],
            'grade' => $row[11] == null ? null : $row[11],
            'jabatan_id' => $row[12] == null ? null : MasterJabatan::where('nama_jabatan', $row[12])->firstOrFail()->id,
            'no_hp' => $row[13] == null ? null : $row[13],
            'no_wa' => $row[14] == null ? null : $row[14],
            'no_bpjs_kesehatan' => $row[15] ?? null == null ? null : $row[15],
            'no_bpjs_ketenagakerjaan'   => $row[16] ?? null == null ? null : $row[16],
            'gol_darah' => $row[17] ?? null == null ? null : $this->gol_darah[$row[17]],
            'email' => $row[18],
            'tempat_lahir'  => $row[19] == null ? null : $row[19],
            'tanggal_lahir' => $row[20] == null ? null : $row[20],
            'umur'  => $row[21] == null ? null : $row[21],
            'alamat_ktp'    => $row[22] == null ? null : $row[22],
            'alamat_domisili'   => $row[23] == null ? null : $row[23],
            'jenis_kelamin' => $row[24] ?? null == null ? null : $this->jenis_kelamin[$row[24]],
            'status_pernikahan' => $row[25] ?? null == null ? null : $this->status_pernikahan[$row[25]],
            'pendidikan'    => $row[26] ?? null == null ? null : $this->pendidikan[$row[26]],
            'nama_sekolah'  => $row[27] == null ? null : $row[27],
            'kab_penugasan' => $row[28] == null ? null : $row[28],
            'rekening'  => $row[29] == null ? null : $row[29],
            'ukuran_baju' => $row[30] ?? null == null ? null : $this->ukuran_baju[$row[30]],
            'no_sdr'    => $row[31] == null ? null : $row[31],
            'hubungan'  => $row[32] == null ? null : $row[32],
        ]);
    }


    // this function returns all validation errors after import:
    // public function getErrors()
    // {
    //     return $this->errors;
    // }

    // public function rules(): array
    // {
    //     return [
    //         '*.employee_name' => 'required|max:255',
    //         '*.employment_identity_number' => 'required|max:255',
    //         '*.employment_status' => 'required|in:Contract,Fixed,Training',
    //         '*.division' => 'required|exists:master_divisi,nama_divisi',
    //         '*.pt' => 'required|exists:master_perusahaan,nama_pt',
    //         '*.entry_date' => 'required|max:255',
    //         '*.kk_number' => 'nullable',
    //         '*.resident_number_nik' => 'required|max:255',
    //         '*.grade' => 'required|max:255',
    //         '*.position' => 'required|exists:master_jabatan,nama_jabatan',
    //         '*.hp_number' => 'required|max:255',
    //         '*.wa_number' => 'required|max:255',
    //         '*.blood_group' => 'required|in:A,B,O,AB',
    //         '*.email' => 'required|unique:karyawan,email|email|max:255',
    //         '*.place_of_birth' => 'required|max:255',
    //         '*.date_of_birth' => 'required|max:255',
    //         '*.age' => 'required|max:255',
    //         '*.ktp_address' => 'required|max:255',
    //         '*.residence_address' => 'required|max:255',
    //         '*.gender' => 'required|in:Male,Female',
    //         '*.married_status' => 'required|in:Single,Married',
    //         '*.education' => 'required|in:SD,SMP,SMA,S1,S2,S3',
    //         '*.school_name' => 'required|max:255',
    //         '*.assignment_district' => 'required|max:255',
    //         '*.clothes_size' => 'required|in:S,M,L,XL,XXL,Jumbo',
    //         '*.contact_number_family' => 'required|max:255',
    //         '*.family_relationship' => 'required|max:255',
    //     ];
    // }

    // public function validationMessages()
    // {
    //     return [
    //         // 'employment_status.required' => 'harusss',
    //         // '3.required' => trans('karyawan.Employment Identity Number is required'),
    //         // 'division.exists' => trans('karyawan.Not an existing ID'),
    //         // 'pt.exists' => trans('karyawan.Not an existing ID'),
    //         // 'employment_status.required' => trans('karyawan.sesuaikan'),
    //     ];
    // }

}