<?php

/*
 * This file is part of the overtrue/laravel-wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Middleware;

use Closure;
use http\Env\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class OAuthAuthenticate.
 */
class OAuthAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $scopes
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $account = 'default', $scopes = null)
    {
        if(!is_weixin()){
            return $next($request);
        }
        $aipaySessionKey = 'aipay_wechat_info';
        if(!$request->input('openid')){
            $redirectUrl = $request->fullUrl();
            $mchid = config('aipay.mchid');
            $aipayUrl = config('aipay.api_url')."/api/openid?mchid={$mchid}&callback_url={$redirectUrl}";
            return redirect($aipayUrl);
        }
        $data = ['openid'=>$request->input('openid')];
        session([$aipaySessionKey=>$data]);
        return $next($request);
    }
}
