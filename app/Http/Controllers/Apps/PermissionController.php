<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //search
        $search = request()->search;
        //get permissions
        $permissions = Permission::when($search, function($permissions, $search) {
                        $permissions = $permissions->where('name', 'like', '%'. $search . '%');
                        })->latest()->paginate(10)->onEachSide(1);

                        $startIndex = 0;
        //return inertia view
        return inertia('Apps/Permissions/Index', [
            'permissions' => $permissions,
            'no'  => $startIndex,
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'name'             => 'required',
        ]);

        //create phk
        Permission::create([
            'name'             => $request->name,
            'guard_name'       => 'web',
        ]);

        //redirect
        return redirect()->route('apps.permissions.index');
    }

    public function update(Request $request, $id)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'name'             => 'required',
        ]);
        //update divisi
        $data_permissions = [
            'name'             => $request->name,
        ];
        $ubahData = Permission::findOrFail($id);
        $ubahData->update($data_permissions);
        //redirect
        return redirect()->route('apps.permissions.index');
    }
}
