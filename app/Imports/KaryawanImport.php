<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\MasterDivisi;
use App\Models\MasterJabatan;
use App\Models\MasterPerusahaan;
use App\Models\MasterPosisi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class KaryawanImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $errors = []; // array to accumulate errors
    private $status_kerja = ['Kontrak' => 1, 'Tetap' => 2, 'Training' => 3];
    private $jenis_kelamin = ['Laki-laki' => 1, 'Perempuan' => 2];
    // private $komposisi_karyawan = ['Director' => 1, 'Div Head' => 2, 'Dept Head' => 3, 'Sect Head' => 4, 'Head' => 5, 'Staff' => 6, 'Non Staff' => 7];
    private $komposisi_peran = ['Support' => 1, 'Core' => 2];
    private $gol_darah = ['A' => 1, 'B' => 2, 'O' => 3, 'AB' => 4];
    private $status_pernikahan = ['Belum Menikah' => 1, 'Menikah' => 2, 'Janda' => 3, 'Duda' => 4];
    private $pendidikan = ['SD' => 1, 'SMP' => 2, 'SMA' => 3, 'D1' => 4, 'D2' => 5, 'D3' => 6, 'D4' => 7, 'S1' => 8, 'S2' => 9, 'S3' => 10];
    private $ukuran_baju = ['S' => 1, 'M' => 2, 'L' => 3, 'XL' => 4, 'XXL' => 5, 'Jumbo' => 6];
    private $jenis_sosmed = ['Instagram' => 1, 'Facebook' => 2, 'Tiktok' => 3, 'Youtube' => 4];
    private $hubungan_keluarga = ['Suami/Istri' => 1, 'Ayah' => 2, 'Ibu' => 3, 'Kakak/Adik' => 4, 'Paman/Bibi' => 5, 'Kakek/Nenek' => 6];
    private $status_keluarga = ['Kepala Keluarga' => 1, 'Istri' => 2, 'Anak ke 1' => 3, 'Anak ke 2' => 4, 'Anak ke 3' => 5, 'Anak ke 4' => 6, 'Anak ke 5' => 7];
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
            //data pribadi
            'nama_lengkap' => $row[1] == null ? null : $row[1],
            'nama_panggilan' => $row[2] == null ? null : $row[2],
            'tempat_lahir'  => $row[3] == null ? null : $row[3], 
            'tanggal_lahir' => $row[4] == null ? null : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4])->format('Y-m-d'),
            'jenis_kelamin' => $row[5] == null ? null : $this->jenis_kelamin[$row[5]],
            'gol_darah' => $row[6] == null ? null : $this->gol_darah[$row[6]],
            'riwayat_penyakit' => $row[7] == null ? null : $row[7],
            'no_kk' => $row[8] == null ? null : $row[8],
            'nik_penduduk' => $row[9] == null ? null : $row[9],
            'kode_pos' => $row[10] == null ? null : $row[10],
            'alamat_ktp'    => $row[11] == null ? null : $row[11],
            'alamat_domisili'   => $row[12] == null ? null : $row[12],
            'pendidikan'    => $row[13] == null ? null : $this->pendidikan[$row[13]],
            'nama_sekolah'  => $row[14] == null ? null : $row[14],
            'jurusan'  => $row[15] == null ? null : $row[15],
            'email_pribadi' => $row[16] == null ? null : $row[16],
            'no_telp' => $row[17] == null ? null : $row[17],
            'no_wa' => $row[18] == null ? null : $row[18],
            'no_keluarga' => $row[19] == null ? null : $row[19],
            'hubungan_keluarga' => $row[20] == null ? null : $this->hubungan_keluarga[$row[20]], 
            'status_pernikahan' => $row[21] == null ? null : $this->status_pernikahan[$row[21]],
            'status_keluarga' => $row[22] == null ? null : $this->status_keluarga[$row[22]],
            'jenis_sosmed' => $row[23] == null ? null : $this->jenis_sosmed[$row[23]],
            'nama_sosmed' => $row[24] == null ? null : $row[24],

            //data di perusahaan
            'nik_karyawan' => $row[25] == null ? null : $row[25],
            'pt_id' => $row[26] == null ? null : MasterPerusahaan::where('nama_pt', $row[26])->firstOrFail()->id,
            'divisi_id' => $row[27]  == null ? null : MasterDivisi::where('nama_divisi', $row[27])->firstOrFail()->id,
            'jabatan_id' => $row[28] == null ? null : MasterJabatan::where('nama_jabatan', $row[28])->firstOrFail()->id,
            'jabatan_id' => $row[29] == null ? null : MasterPosisi::where('nama_posisi', $row[29])->firstOrFail()->id,
            'grade' => $row[30] == null ? null : $row[30],
            'tanggal_masuk' => $row[31]  == null ? null : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[31])->format('Y-m-d'),
            'status_kerja' => $row[32] == null ? null : $this->status_kerja[$row[32]], 
            // 'komposisi_karyawan' => $row[32] == null ? null : $this->komposisi_karyawan[$row[32]], 
            'komposisi_peran' => $row[33] == null ? null : $this->komposisi_peran[$row[33]], 
            'tanggal_kontrak' => $row[34]  == null ? null : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[34])->format('Y-m-d'),
            'masa_kontrak' => $row[35] == null ? null : $row[35],
            'tanggal_karyawan_tetap' => $row[36]  == null ? null : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[36])->format('Y-m-d'),
            'posisi_awal_diterima' => $row[37] == null ? null : $row[37],
            'kota_rekruitmen' => $row[38] == null ? null : $row[38],
            'kota_penugasan' => $row[39] == null ? null : $row[39],
            'no_npwp' => $row[40] == null ? null : $row[40],
            'no_bpjs_kesehatan' => $row[41] == null ? null : $row[41],
            'no_bpjs_ketenagakerjaan' => $row[42] == null ? null : $row[42],
            'email_internal'  => $row[43] == null ? null : $row[43],
            'nama_bank'  => $row[44] == null ? null : $row[44],
            'rekening'  => $row[45] == null ? null : $row[45],
            'ukuran_baju' => $row[46] == null ? null : $this->ukuran_baju[$row[46]],
            'pengalaman_kerja_terakhir' => $row[47] == null ? null : $row[47],
            'jabatan_kerja_terakhir' => $row[48] == null ? null : $row[48],
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