<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $users = User::getAccount();

        return view('account.index', [
            'users' => $users,
            'title' => 'Account List',
            'subtitle' => 'Manage employee accounts',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Account', 'url' => null],
            ]
        ]);
    }

    public function create()
    {
        $roles = User::getRole();
        return view('account.create', [
            'roles' => $roles,
            'title' => 'Create Account',
            'subtitle' => 'Add a new account',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Account', 'url' => route('account.index')],
                ['label' => 'Create', 'url' => null],
            ],
        ]);
    }

    public function store(AccountRequest $request)
    {
        $data = $request->validated();

        DB::table('users')->insert([
            'name'  => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make('konoha'),
            'role_id'    => (int) $data['role_id'],
            'is_active'  => 1,
            'phone'      => $data['phone'],
            'address'    => $data['address'],
            'created_at' => now(),
        ]);

        return redirect()->route('account.index')->with('toast_success', 'User berhasil ditambahkan. Password default: konoha');
    }

    public function edit($id)
    {
        $roles = User::getRole();
        $user = DB::table('users')->where('id', $id)->first();

        return view('account.create', [
            'user' => $user,
            'roles' => $roles,
            'title' => 'Edit Account',
            'subtitle' => 'Update account data',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('home')],
                ['label' => 'Account', 'url' => route('account.index')],
                ['label' => 'Edit', 'url' => null],
            ],
        ]);
    }

    public function update(AccountRequest $request, $id)
    {
        $data = $request->validated();

        DB::table('users')
            ->where('id', $id)
            ->update([
                'name'   => $data['name'],
                'email'       => $data['email'],
                'role_id'     => (int) $data['role_id'],
                'phone'       => $data['phone'],
                'address'     => $data['address'],
                'updated_at'  => now(),
            ]);

        return redirect()->back()->with('toast_success', 'User berhasil diupdate.');
    }

    public function delete(Request $request)
    {
        $ids = $request->selected_users;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'Tidak ada data dipilih');
        }

        User::whereIn('id', $ids)->delete();

        return redirect()->back()->with('toast_success', 'User berhasil dihapus.');
    }
}
