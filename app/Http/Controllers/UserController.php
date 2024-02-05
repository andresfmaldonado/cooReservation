<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            try {
                return DataTables::of(User::query())->addColumn('actions', function ($item) {
                    return '<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <a href="'.route('edit-user', $item->id).'">Edit</a>
                            </button><button class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded" onclick="deleteUser('.$item->id.')">Delete</button>';
                })
                ->rawColumns(['actions'])->toJson();
            } catch (\Throwable $th) {
                return response(500)->json(['message' => 'Unexpected error']);
            }
        }

        $table = $builder->columns([
                    ['data' => 'id', 'footer' => 'Id'],
                    ['data' => 'name', 'footer' => 'Name'],
                    ['data' => 'email', 'footer' => 'Email'],
                    ['data' => 'created_at', 'footer' => 'Created At'],
                    ['data' => 'updated_at', 'footer' => 'Updated At'],
                    ['data' => 'actions', 'footer' => 'Actions']
                ]);

        return view('user.list-users', compact('table'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $roles = Role::select(['id', 'name'])->get();
            return view('user.create-user', compact('roles'));
        } catch (\Throwable $th) {
            return response(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:50'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:'.User::class],
                'role_id' => ['nullable', 'integer'],
                'is_super_admin' => ['nullable'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => isset($request->role_id) ? $request->role_id : env('VISITOR_ROLE_ID'),
                'is_super_admin' => $request->has('is_super_admin'),
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            return back()->with('status', 'user-created');
        } catch (\Throwable $th) {
            return back()->with('status', 'user-not-created');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $roles = Role::select(['id', 'name'])->get();
            return view('user.edit-user', compact('user', 'roles'));
        } catch (\Throwable $th) {
            return response(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:50'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:100'],
                'role_id' => ['nullable', 'integer'],
                'is_super_admin' => ['nullable'],
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()]
            ]);

            User::find($id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => isset($request->role_id) ? $request->role_id : env('VISITOR_ROLE_ID'),
                'is_super_admin' => $request->has('is_super_admin')
            ]);

            if (isset($request->password)) {
                User::find($id)->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            return back()->with('status', 'user-updated');
        } catch (\Throwable $th) {
            return back()->with('status', 'user-not-updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            User::find($request->id)->delete();

            return response(200);
        } catch (\Throwable $th) {
            return response(500);
        }
    }
}
