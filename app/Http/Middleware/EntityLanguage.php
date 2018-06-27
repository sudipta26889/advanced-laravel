<?php

namespace App\Http\Middleware;
use App\Models\Entity;
use App\Models\Language;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EntityLanguage extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = null)
    {

        $entity = Entity::first();
        $language = Language::first();
        if (session('entity_id')) {
            $entity = Entity::find(session('entity_id'));
        }
        if (session('lang_id')) {
            $language = Language::find(session('lang_id'));
        }

        // Check if loggedin user belongs to this website or not
        if (Auth::guard($guard)->check()) {
            $user = Auth::user();
            $userEntity = $user->entity;
            $entity = $userEntity;
        }

        session(['entity_id'=>$entity->id, "lang_id" => $language->id]);
        $request->attributes->set('entity_id', $entity->id);
        $request->attributes->set('lang_id', $language->id);
        return $next($request);
    }

    protected $except = [
        'change_site/*',
        'change_lang/*',
        'logout'
    ];
}
