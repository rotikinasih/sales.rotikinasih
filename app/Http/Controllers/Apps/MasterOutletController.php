<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterOutlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MasterOutletController extends Controller
{
    public function index()
    {
        // Ambil outlet_id yang dimiliki user login
        $userOutletIds = auth()->user()->outlets->pluck('id')->toArray();
        $search = request()->search;
        //get posisi
        $outlet = MasterOutlet::when($search, function($outlet, $search) {
            $outlet = $outlet->where('lokasi', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        $tipeOutlets = \App\Models\TipeOutlet::where('status', 1)->get();

        //return inertia
        return Inertia::render('Apps/outlet/Index',[
            'outlet' => $outlet,
            'tipeOutlets' => $tipeOutlets, // <-- kirim ke frontend
        ]);
    }
    
    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
    'lokasi'     => 'required',
    'alamat'     => 'required',
    'lat_pen'   => 'required',
    'long_pen'  => 'required',
    'status'     => 'required',
    'radius'     => 'required|integer|min:0',
    'tipe_outlet_id' => 'required|exists:tipe_outlet,id', // <-- validasi
]);


        //create posisi
        $data = [
            'lokasi'            => $request->lokasi,
            'alamat'            => $request->alamat,
            'lat_pen'          => $request->lat_pen,
            'long_pen'         => $request->long_pen,
            'status'            => $request->status,
            'radius'        => $request->radius,
            'tipe_outlet_id' => $request->tipe_outlet_id, // <-- simpan
            'created_id'        => Auth::user()->id,
        ];

        // dd($data);

        MasterOutlet::create($data);

        //redirect
        return redirect()->route('apps.outlet.index');
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'lokasi'     => 'required',
            'alamat'     => 'required',
            'lat_pen'    => 'required',
            'long_pen'   => 'required',
            'status'     => 'required',
            'radius'     => 'required|integer|min:0',
            'tipe_outlet_id' => 'required|exists:tipe_outlet,id', // <-- validasi
        ]);

        $data_outlet = [
            'lokasi'         => $request->lokasi,
            'alamat'         => $request->alamat,
            'lat_pen'        => $request->lat_pen,
            'long_pen'       => $request->long_pen,
            'status'         => $request->status,
            'radius'         => $request->radius,
            'tipe_outlet_id' => $request->tipe_outlet_id, // <-- simpan
        ];
        $ubahData = MasterOutlet::findOrFail($id);
        $ubahData->update($data_outlet);

        return redirect()->route('apps.outlet.index');
    }
}

