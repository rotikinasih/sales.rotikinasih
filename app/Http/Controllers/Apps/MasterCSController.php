<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\MasterCS;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MasterCSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Ambil outlet_id yang dimiliki user login
        $userOutletIds = auth()->user()->outlets->pluck('id')->toArray();

        $search = $request->search;
        $csList = MasterCS::when($search, function($query, $search) {
                $query->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('no_hp', 'like', '%' . $search . '%');
            })
            ->latest()->paginate(10)->onEachSide(1);

        return Inertia::render('Apps/mastercs/Index', [
            'csList' => $csList
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:1,2',
        ]);

        MasterCS::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
        ]);

        return redirect()->route('apps.mastercs.index');
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:1,2',
        ]);

        $cs = MasterCS::findOrFail($id);
        $cs->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
        ]);

        return redirect()->route('apps.mastercs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MasterCS::findOrFail($id)->delete();
        return back();
    }
}


