<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\TipeOutlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TipeOutletController extends Controller
{
    public function index()
    {
        // Ambil outlet_id yang dimiliki user login
$userOutletIds = auth()->user()->outlets->pluck('id')->toArray();
        $search = request()->search;
        //get tipe outlet
        $toutlet = TipeOutlet::when($search, function($toutlet, $search) {
            $toutlet = $toutlet->where('tipe_outlet', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        //return inertia
        return Inertia::render('Apps/toutlet/Index',[
            'toutlet' => $toutlet
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'tipe'       => 'required',
            'status'            => 'required'
        ]);

        //create tipe outlet
        $data = [
            'tipe'       => $request->tipe,
            'status'            => $request->status,
            'created_id'        => Auth::user()->id,
        ];

        // dd($data);

        TipeOutlet::create($data);

        //redirect
        return redirect()->route('apps.toutlet.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'tipe'   => 'required',
            'status'        => 'required',
        ]);
        //update tipe outlet
        $data_tipe_outlet = [
            'tipe'   => $request->tipe,
            'status'        => $request->status,
        ];
        $ubahData = TipeOutlet::findOrFail($id);
        $ubahData->update($data_tipe_outlet);
        //redirect
        return redirect()->route('apps.toutlet.index');
    }
}

