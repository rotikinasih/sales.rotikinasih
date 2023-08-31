<?php

namespace App\Http\Controllers\Apps;

use App\Models\KaryawanPHK;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;

class PHKController extends Controller
{
    public function index()
    {
        $search = request()->search;
        //get perusahaan
        $karyawan_phk = KaryawanPHK::with('karyawan')->when($search, function($karyawan_phk, $search) {
            $karyawan_phk = $karyawan_phk->where('nama_pt', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        $karyawan = Karyawan::all();

        //return inertia
        return Inertia::render('Apps/PHK/Index',[
            'karyawan_phk' => $karyawan_phk,
            'karyawan' => $karyawan
        ]);
    }
}
