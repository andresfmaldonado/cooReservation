<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            try {
                return DataTables::of(Role::query())->addColumn('actions', function ($item) {
                    return '<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <a href="'.route('edit-role', $item->id).'">Edit</a>
                            </button>
                            <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded" onclick="deleteRole('.$item->id.')">Delete</button>
                            <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded" >
                                <a href="'.route('list-permissions', $item->id).'">Permissions</a>
                            </button>';
                })
                ->rawColumns(['actions'])->toJson();
            } catch (\Throwable $th) {
                return response(500);
            }
        }

        $table = $builder->columns([
                    ['data' => 'id', 'footer' => 'Id'],
                    ['data' => 'name', 'footer' => 'Name'],
                    ['data' => 'created_at', 'footer' => 'Created At'],
                    ['data' => 'updated_at', 'footer' => 'Updated At'],
                    ['data' => 'actions', 'footer' => 'Actions']
                ]);

        return view('role.list-roles', compact('table'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role.create-role');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            Role::create([
                'name' => $request->name
            ]);

            return back()->with('status', 'role-created');
        } catch (\Throwable $th) {
            return back()->with('status', 'role-not-created');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        try {
            $role = Role::find($id);
            return view('role.edit-role', compact('role'));
        } catch (\Throwable $th) {
            return response(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, String $id): RedirectResponse
    {
        try {
            Role::find($id)->update([
                'name' => $request->name
            ]);

            return back()->with('status', 'role-updated');
        } catch (\Throwable $th) {
            return back()->with('status', 'role-not-updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            Role::find($request->id)->delete();

            return response(200);
        } catch (\Throwable $th) {
            return response(500);
        }
    }
}
