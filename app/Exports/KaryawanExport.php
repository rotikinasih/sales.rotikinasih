<?php

namespace App\Exports;

use App\Models\Karyawan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Events\AfterSheet;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithEvents;
// use Maatwebsite\Excel\Concerns\FromCollection;

class KaryawanExport implements FromView
{
    /**
     * view
     *
     * @return View
     */
    public function view(): View
    {
        return view('exports.karyawan', [
            'karyawan' => Karyawan::with('perusahaan', 'divisi', 'jabatan')->where('status_karyawan', 0)->get(), 
            'waktu_sekarang' => date("d-m-Y H:i:s")
        ]);
    }

    // /**
    //  * Write code on Method
    //  *
    //  * @return response()
    //  */
    // public function headings() :array
    // {
    //     return [
    //         'No',
    //         'Employee Name',
    //         'Employment Identity Number',
    //         'Employment Status',
    //         'Division',
    //         'PT',
    //         'Entry Date',
    //         'Start of Contract',
    //         'End of Contract',
    //         'KK Number',
    //         'Resident Number NIK',
    //         'Grade',
    //         'Position',
    //         'HP Number',
    //         'WA Number',
    //         'BPJS of Health',
    //         'BPJS of Employment',
    //         'Blood Group',
    //         'Email',
    //         'Place of Birth',
    //         'Date of Birth',
    //         'Age',
    //         'KTP Address',
    //         'Residence Address',
    //         'Gender',
    //         'Married Status',
    //         'Education',
    //         'School Name',
    //         'Assignment District',
    //         'Rekening Bank',
    //         'Clothes Size',
    //         'Contact Number Family',
    //         'Family Relationship'
    //     ];
    // }

    // /**
    //  * Write code on Method
    //  *
    //  * @return response()
    //  */
    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
    //             $event->sheet->getDelegate()->getStyle('A1:AG1')
    //                     ->getFill()
    //                     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //                     ->getStartColor()
    //                     ->setARGB('c491d4');
    //             $event->sheet->getDelegate()->getStyle('A1:AG1')->getFont()->setBold(true);
    //         }
    //     ];
    // }

}
