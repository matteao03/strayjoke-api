<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

// 注意，我们要继承的是 jwt 的 BaseMiddleware
class RefreshToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  String $guardName
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guardName)
    {
        $this->checkForToken($request);

        try {
            $id = $this->auth->parseToken()->getPayload()->get('sub');
            if (auth($guardName)->byId($id)) {
                return $next($request);
            }
            throw new UnauthorizedHttpException('jwt-auth', '认证失败，请重新登录');
        } catch (TokenExpiredException $exception) {
            // 此处捕获到了 token 过期所抛出的 TokenExpiredException 异常，我们在这里需要做的是刷新该用户的 token 并将它添加到响应头中
            try {
                // 刷新用户的 token
                $token = auth($guardName)->refresh();
                // 使用一次性登录以保证此次请求的成功
                auth($guardName)->onceUsingId($this->auth->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']);
            } catch (JWTException $exception) {
                // 如果捕获到此异常，即代表 refresh 也过期了，用户无法刷新令牌，需要重新登录。
                throw new UnauthorizedHttpException('jwt-auth', '会话过期，请重新登录');
            }
        } catch (Exception $e) {
            throw new UnauthorizedHttpException('jwt-auth', '认证失败，请重新登录');
        }

        // 在响应头中返回新的 token
        return $next($request)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);
    }
}
