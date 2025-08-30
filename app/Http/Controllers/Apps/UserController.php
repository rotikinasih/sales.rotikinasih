<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->search;

        // Tambahkan 'outlets' pada with()
        $users = User::with(['roles', 'outlets'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(5);

        return Inertia::render('Apps/Users/Index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $outlets = \App\Models\MasterOutlet::where('status', 1)->get();

        return Inertia::render('Apps/Users/Create', [
            'roles' => $roles,
            'outlets' => $outlets
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required|string|max:255',
            'email'      => 'required|unique:users,email',
            'username'   => 'required|unique:users,username',
            'password'   => 'required|confirmed',
            'roles'      => 'required|array',
            'outlet_ids' => 'nullable|array'
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'username'  => $request->username,
            'password'  => bcrypt($request->password),
            'created_id'=> Auth::id()
        ]);

        $user->assignRole($request->roles);

        // Simpan outlet user (jika ada)
        if ($request->outlet_ids) {
            $user->outlets()->sync($request->outlet_ids);
        }

        return redirect()->route('apps.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::with('roles', 'outlets')->findOrFail($id);
        $roles = Role::all();
        $outlets = \App\Models\MasterOutlet::where('status', 1)->get();

        return Inertia::render('Apps/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
            'outlets' => $outlets
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name'       => 'required|string|max:255',
            'email'      => 'required|unique:users,email,' . $user->id,
            'username'   => 'required|unique:users,username,' . $user->id,
            'password'   => 'nullable|confirmed',
            'roles'      => 'required|array',
            'outlet_ids' => 'nullable|array'
        ]);

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->roles);

        // Update outlet user
        $user->outlets()->sync($request->outlet_ids ?? []);

        return redirect()->route('apps.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('apps.users.index');
    }
}
