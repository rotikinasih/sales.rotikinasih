<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Masterjabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class jabatanController extends Controller
{
    public function index()
    {
        $search = request()->search;
        //get jabatan
        $jabatan = Masterjabatan::when($search, function($jabatan, $search) {
            $jabatan = $jabatan->where('nama_jabatan', 'like', '%'. $search . '%');
        })->latest()->paginate(10)->onEachSide(1);

        //return Inertia
        return Inertia::render('Apps/Jabatan/Index',[
            'jabatan' => $jabatan
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_jabatan'      => 'required',
            'status'            => 'required'
        ]);

        //create jabatan
        Masterjabatan::create([
            'nama_jabatan'      => $request->nama_jabatan,
            'status'            => $request->status,
            'created_id'        => Auth::id(),
        ]);

        //redirect
        return redirect()->route('apps.jabatan.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'nama_jabatan'  => 'required',
            'status'        => 'required',
        ]);
        //update jabatan
        $data_jabatan = [
            'nama_jabatan'  => $request->nama_jabatan,
            'status'        => $request->status,
        ];
        $ubahData = Masterjabatan::findOrFail($id);
        $ubahData->update($data_jabatan);
        //redirect
        return redirect()->route('apps.jabatan.index');
    }
}
