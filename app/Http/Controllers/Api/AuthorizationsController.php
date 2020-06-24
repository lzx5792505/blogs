<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Arr;
use App\Traits\PassportToken;
use Illuminate\Auth\AuthenticationException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;
use League\OAuth2\Server\AuthorizationServer;
use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use League\OAuth2\Server\Exception\OAuthServerException;

class AuthorizationsController extends Controller
{
    use PassportToken;

    /**
     * ---------------------------------------------------------------
     * 用户登录
     * ---------------------------------------------------------------
     * @return response
     */
    public function store(AuthorizationRequest $originRequest, AuthorizationServer $server, ServerRequestInterface $serverRequest)
    {
        if (empty($originRequest->grant_type)) {
            $parsedBody = $serverRequest->getParsedBody();
            $parsedBody['grant_type'] = config('setting.oauth.grant_type');
            $parsedBody['client_id'] = config('setting.oauth.client_id');
            $parsedBody['client_secret'] = config('setting.oauth.client_secret');
            $serverRequest = $serverRequest->withParsedBody($parsedBody);
        }

        try {
            return $server->respondToAccessTokenRequest($serverRequest, new Psr7Response)->withStatus(201);
        } catch (OAuthServerException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * ---------------------------------------------------------------
     * 第三方登录（目前支持微信）
     * ---------------------------------------------------------------
     * @param string $type 登录类型
     * 
     * @return response 
     */
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        $driver = \Socialite::driver($type);

        $user = User::first();
        try {
            if ($code = $request->code) {
                $response = $driver->getAccessTokenResponse($code);
                $token = Arr::get($response, 'access_token');
            } else {
                $token = $request->access_token;

                if ($type == 'weixin') {
                    $driver->setOpenId($request->openid);
                }
            }

            $oauthUser = $driver->userFromToken($token);
        } catch (\Exception $e) {
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                // 没有用户，默认创建一个用户
                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }

                break;
        }

        $result = $this->getBearerTokenByUser($user, '1', false);

        return response()->json($result)->setStatusCode(201);
    }

    /**
     * ---------------------------------------------------------------
     * 更新token
     * ---------------------------------------------------------------
     * @return response
     */
    public function update(AuthorizationServer $server, ServerRequestInterface $serverRequest)
    {
        try {
            return $server->respondToAccessTokenRequest($serverRequest, new Psr7Response);
        } catch (OAuthServerException $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }

    /**
     * ---------------------------------------------------------------
     * 删除Token
     * ---------------------------------------------------------------
     * @return json
     */
    public function destroy()
    {
        if (auth('api')->check()) {
            auth('api')->user()->token()->revoke();
            return response(null, 204);
        } else {
            throw new AuthenticationException('The token is invalid.');
        }
    }

    /**
     * ---------------------------------------------------------------
     * 数据返回格式
     * ---------------------------------------------------------------
     * @param string $token 用户Token
     *  
     * @return json
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
