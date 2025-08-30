<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MasterKendaraanController extends Controller
{
    public function index()
    {
        // Ambil outlet_id yang dimiliki user login
$userOutletIds = auth()->user()->outlets->pluck('id')->toArray();
        $search = request()->search;

        $masterkendaraan = MasterKendaraan::when($search, function($query, $search) {
            $query->where('kode_kendaraan', 'like', '%' . $search . '%')
                  ->orWhere('merk_kendaraan', 'like', '%' . $search . '%')
                  ->orWhere('driver', 'like', '%' . $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        return Inertia::render('Apps/MasterKendaraan/Index', [
    'masterkendaraan' => $masterkendaraan
]);

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'kode_kendaraan'   => 'required|unique:master_kendaraan,kode_kendaraan',
            'merk_kendaraan'   => 'required',
            'driver'           => 'required',
            'daya_angkut'      => 'nullable|numeric|min:0',
            'status'           => 'required|in:1,2',
        ]);

        $data = [
            'kode_kendaraan'   => $request->kode_kendaraan,
            'merk_kendaraan'   => $request->merk_kendaraan,
            'driver'           => $request->driver,
            'daya_angkut'      => $request->daya_angkut,
            'status'           => $request->status,
            'created_id'       => Auth::user()->id,
        ];

        MasterKendaraan::create($data);

        return redirect()->route('apps.masterkendaraan.index');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'kode_kendaraan'   => 'required|unique:master_kendaraan,kode_kendaraan,' . $id,
            'merk_kendaraan'   => 'required',
            'driver'           => 'required',
            'daya_angkut'      => 'nullable|numeric|min:0',
            'status'           => 'required|in:1,2',
        ]);

        $masterkendaraan = MasterKendaraan::findOrFail($id);

        $data_update = [
            'kode_kendaraan'   => $request->kode_kendaraan,
            'merk_kendaraan'   => $request->merk_kendaraan,
            'driver'           => $request->driver,
            'daya_angkut'      => $request->daya_angkut,
            'status'           => $request->status,
        ];

        $masterkendaraan->update($data_update);

        return redirect()->route('apps.masterkendaraan.index');
    }
}

