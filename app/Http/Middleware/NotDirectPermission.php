<?php

namespace App\Http\Middleware;

use Closure;
use \Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\Gate;

class NotDirectPermission
{
    public function handle($request, Closure $next, $permission, $guard = null)
    {

        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
          throw UnauthorizedException::notLoggedIn();
        }

        if (auth('sanctum')->user()->hasRole('super admin')) return $next($request);

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        $allPermissions = [];

        foreach (auth('sanctum')->user()->getAllPermissions() as $allPermission) {
          array_push($allPermissions, $allPermission->name);
        }

        foreach ($permissions as $permission) {
          if (in_array($permission, $allPermissions)) return $next($request);
        }

        throw UnauthorizedException::forPermissions($permissions);
    }
}

