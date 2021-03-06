<?php
namespace rohsyl\LaravelAcl\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;
use rohsyl\LaravelAcl\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AclMiddleware
{
    public function handle($request, Closure $next, $inputs)
    {

        $parameters = explode('$', $inputs);
        $role = $parameters[0] ?? '';
        $guard = $parameters[1] ?? null;

        $auth = auth();
        if(isset($guard)) {
            $auth = $auth->guard($guard);
        }

        if ($auth->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        $roles = array_map(function($item){
            $item = explode(':', $item);
            return [$item[0] => $item[1] ?? ACL_ALLOW];
        }, $roles);

        if (! $auth->user()->hasAclAny($roles)) {
            throw UnauthorizedException::forPermissions();
        }
        return $next($request);
    }
}