<?php

namespace App\Http\Middleware;

use Closure;
use App\Admin\UserPermission;
use App\Admin\Permission;

class ApiPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        return $next($request);

        $userId = '4';
        $permissions = UserPermission
            ::where('user_id', '=', $userId)
            ->where('active', true)
            ->with('permission')
            ->get();

        $found = false;
        foreach ($permissions as $item) {
            if ($item->permission->code == $permission && $item->permission->active) {
                $found = true;
                break;
            }
        }
        if ($found) {
            return $next($request);
        } else {
            abort(403, 'You do not have access to this module');
        }
    }
}
