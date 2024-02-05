<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder, int $id)
    {
        $role = Role::find($id);
        $role->permissions;
        $permissions = Permission::query();
        if (request()->ajax()) {
            try {
                return DataTables::of($permissions)->addColumn('active', function ($item) {
                    return '<input type="checkbox" id="active'.$item->id.'" name="active'.$item->id.'" class="mt-1" onchange="addOrRemovePermission('.$item->id.')" />';
                })
                ->rawColumns(['active'])->toJson();
            } catch (\Throwable $th) {
                return response(500);
            }
        }

        $table = $builder->columns([
                    ['data' => 'name', 'footer' => 'Name'],
                    ['data' => 'created_at', 'footer' => 'Created At'],
                    ['data' => 'updated_at', 'footer' => 'Updated At'],
                    ['data' => 'active', 'footer' => 'Active']
                ]);
        return view('role.role-permissions', compact('role', 'table'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        try {
            $role = Role::find($request->roleId);
            $role->permissions()->detach();
            if (isset($request->permissions)) {
                $role->permissions()->attach($request->permissions);
            }

            return response(200);
        } catch (\Throwable $th) {
            return response(500);
        }
    }
}
