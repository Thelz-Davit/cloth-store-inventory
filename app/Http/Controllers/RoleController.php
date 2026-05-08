<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $role = Role::getRole();

        return view('role.index', [
            'roles' => $role,
            'title' => 'Role List',
            'subtitle' => 'Manage roles',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Role', 'url' => null],
            ]
        ]);
    }

    public function create()
    {
        return view('role.create', [
            'title' => 'Create Role',
            'subtitle' => 'Add a new role',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Role', 'url' => route('role.index')],
                ['label' => 'Create', 'url' => null],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role_name' => 'required|string|max:255',
        ]);

        Role::create([
            'role_name' => $data['role_name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('role.index')->with('toast_success', 'Role berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $role = Role::where('role_id', $id)->first();

        return view('role.create', [
            'role' => $role,
            'title' => 'Edit Role',
            'subtitle' => 'Update role data',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Role', 'url' => route('role.index')],
                ['label' => 'Edit', 'url' => null],
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'role_name' => 'required|string|max:255',
        ]);

        Role::where('role_id', $id)->update([
            'role_name' => $data['role_name'],
            'updated_at' => now(),
        ]);

        return redirect()->route('role.index')->with('toast_success', 'Role berhasil diupdate.');
    }

    public function delete(Request $request)
    {
        $ids = $request->selected_roles;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'Tidak ada data dipilih');
        }

        Role::whereIn('role_id', $ids)->delete();

        return redirect()->back()->with('toast_success', 'Role berhasil dihapus.');
    }
}
