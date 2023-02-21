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

class KaryawanImport implements WithStartRow, WithValidation, ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $errors = []; // array to accumulate errors
    private $status_kerja = ['Contract' => 0, 'Fixed' => 1];
    private $jenis_kelamin = ['Male' => 0, 'Female' => 1];
    private $gol_darah = ['A' => 0, 'B' => 1, 'O' => 2, 'AB' => 3];
    private $status_pernikahan = ['Single' => 0, 'Married' => 1];
    private $pendidikan = ['SD' => 0, 'SMP' => 1, 'SMA' => 2, 'S1' => 3, 'S2' => 4, 'S3' => 5];
    private $ukuran_baju = ['S' => 0, 'M' => 1, 'L' => 2, 'XL' => 3, 'XXL' => 4, 'Jumbo' => 5];

    public function startRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            $data = [
                'nama_karyawan' => $row['employee_name'],
                'nik_karyawan' => $row['employment_identity_number'],
                'status_kerja' => $this->status_kerja[$row['employment_status']] ?? null,
                'divisi_id' => MasterDivisi::where('nama_divisi', $row['division'])->firstOrFail()->id,
                'pt_id' => MasterPerusahaan::where('nama_pt', $row['pt'])->firstOrFail()->id,
                'tanggal_masuk' => $row['entry_date'],
                'tanggal_kontrak' => $row['start_of_contract'],
                'akhir_kontrak' => $row['end_of_contract'],
                'no_kk' => $row['kk_number'],
                'nik_penduduk' => $row['resident_number_nik'],
                'grade' => $row['grade'],
                'jabatan_id' => MasterJabatan::where('nama_jabatan', $row['position'])->firstOrFail()->id,
                'no_hp' => $row['hp_number'],
                'no_wa' => $row['wa_number'],
                'no_bpjs_kesehatan' => $row['bpjs_of_health'],
                'no_bpjs_ketenagakerjaan'   => $row['bpjs_of_employment'],
                'gol_darah' => $this->gol_darah[$row['blood_group']] ?? null,
                'email' => $row['email'],
                'tempat_lahir'  => $row['place_of_birth'],
                'tanggal_lahir' => $row['date_of_birth'],
                'umur'  => $row['age'],
                'alamat_ktp'    => $row['ktp_address'],
                'alamat_domisili'   => $row['residence_address'],
                'jenis_kelamin' => $this->jenis_kelamin[$row['gender']] ?? null,
                'status_pernikahan' => $this->status_pernikahan[$row['married_status']] ?? null,
                'pendidikan'    => $this->pendidikan[$row['education']] ?? null,
                'nama_sekolah'  => $row['school_name'],
                'kab_penugasan' => $row['assignment_district'],
                'rekening'  => $row['rekening_bank'],
                'ukuran_baju'    => $this->ukuran_baju[$row['clothes_size']] ?? null,
                'no_sdr'    => $row['contact_number_family'],
                'hubungan'  => $row['family_relationship']
            ];
            Karyawan::create($data);
        }
    }

    // this function returns all validation errors after import:
    public function getErrors()
    {
        return $this->errors;
    }

    public function rules(): array
    {
        return [
            '*.employee_name' => 'required|max:255',
            '*.employment_identity_number' => 'required|max:255',
            '*.employment_status' => 'required|in:Contract,Fixed',
            '*.division' => 'required|exists:master_divisi,nama_divisi',
            '*.pt' => 'required|exists:master_perusahaan,nama_pt',
            '*.entry_date' => 'required|max:255',
            '*.kk_number' => 'required|max:255',
            '*.resident_number_nik' => 'required|max:255',
            '*.grade' => 'required|max:255',
            '*.position' => 'required|exists:master_jabatan,nama_jabatan',
            '*.hp_number' => 'required|max:255',
            '*.wa_number' => 'required|max:255',
            '*.blood_group' => 'required|in:A,B,O,AB',
            '*.email' => 'required|unique:karyawan,email|email|max:255',
            '*.place_of_birth' => 'required|max:255',
            '*.date_of_birth' => 'required|max:255',
            '*.age' => 'required|max:255',
            '*.ktp_address' => 'required|max:255',
            '*.residence_address' => 'required|max:255',
            '*.gender' => 'required|in:Male,Female',
            '*.married_status' => 'required|in:Single,Married',
            '*.education' => 'required|in:SD,SMP,SMA,S1,S2,S3',
            '*.school_name' => 'required|max:255',
            '*.assignment_district' => 'required|max:255',
            '*.clothes_size' => 'required|in:S,M,L,XL,XXL,Jumbo',
            '*.contact_number_family' => 'required|max:255',
            '*.family_relationship' => 'required|max:255',
        ];
    }

    public function validationMessages()
    {
        return [
            // 'employment_status.required' => 'harusss',
            // '3.required' => trans('karyawan.Employment Identity Number is required'),
            // 'division.exists' => trans('karyawan.Not an existing ID'),
            // 'pt.exists' => trans('karyawan.Not an existing ID'),
            // 'employment_status.required' => trans('karyawan.sesuaikan'),
        ];
    }

}
