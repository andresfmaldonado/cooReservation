<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Rules\ValidateRole;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Redirect;
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
            return DataTables::of(User::query())->toJson();
        }

        $table = $builder->columns([
                    ['data' => 'id', 'footer' => 'Id'],
                    ['data' => 'name', 'footer' => 'Name'],
                    ['data' => 'email', 'footer' => 'Email'],
                    ['data' => 'created_at', 'footer' => 'Created At'],
                    ['data' => 'updated_at', 'footer' => 'Updated At'],
                ]);

        return view('user.list-users', compact('table'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select(['id', 'name'])->get();
        return view('user.create-user', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role_id' => ['required', 'integer',  new ValidateRole()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return Redirect::route('create-user')->with('status', 'user-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function processDate($date) {
        return  Carbon::parse($date)->format('Y-m-d H:i');
    }
}
