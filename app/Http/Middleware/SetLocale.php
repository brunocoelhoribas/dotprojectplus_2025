<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class SetLocale {
    public function handle(Request $request, Closure $next) {
        $locale = Session::get('locale', config('app.locale'));

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
