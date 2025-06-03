<?php
public function handle(Request $request, Closure $next, string ...$guards)
{
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            if ($guard === 'siswa') {
                return redirect('/siswa/dashboard');
            }
            if ($guard === 'guru') {
                return redirect('/guru/dashboard');
            }
            return redirect(RouteServiceProvider::HOME);
        }
    }

    return $next($request);
}
