<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LecturerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = RoleEnum::LECTURER->value;
        
        if (auth()->user()->role != $role) {
            return redirect()->route('student');
        }

        return $next($request);
    }
}
