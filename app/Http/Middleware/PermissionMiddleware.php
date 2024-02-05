<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $idPermission): Response
    {
        if ($request->user()->is_super_admin === 0) {
            $role = Role::find($request->user()->role_id);
            $checked = $role->permissions->filter(function($permission) use ($idPermission) {
                return $permission->id === $idPermission;
            });

            if (!count($checked)) {
                abort(403, 'Unauthorized action');
            } else {
                $this->setPermissions();
            }
        }
        return $next($request);
    }

    private function setPermisions() {
        // $user = User::find(request()->user()->id);
        // $user->roles;
        // $permissions = Role::find($user->roles[0]->id);
    }
}
