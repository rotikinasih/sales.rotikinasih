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
    public function __invoke(Request $request)
    {
        //search
        $search = request()->search;
        //get permissions
        $permissions = Permission::when($search, function($permissions, $search) {
                        $permissions = $permissions->where('name', 'like', '%'. $search . '%');
                        })->latest()->paginate(10)->onEachSide(1);
        //return inertia view
        return inertia('Apps/Permissions/Index', [
            'permissions' => $permissions
        ]);

    }
}
