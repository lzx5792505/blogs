<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Handlers\FileUploadHandler;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UserRequest;
use Illuminate\Auth\AuthenticationException;

class UsersController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * 注册用户
     * ---------------------------------------------------------------
     *
     * @param 	array $request  验证后的数据
     *
     * @return 	array        	返回用户信息
     */
    public function store(UserRequest $request, UserService $service)
    {
        //获取缓存的验证码key
        $smsCodes = \Cache::get($request->smsCodes_key);

        //验证过期时间
        if (!$smsCodes) {
            abort(403, '验证码失效');
        }

        //验证验证码
        if (!hash_equals($smsCodes['code'], $request->smsCodes_code)) {
            // 返回401
            throw new AuthenticationException('验证码错误');
        }

        //创建用户
        $user = $service->createUser($request, $smsCodes['phone']);
        if (!$user) {
            abort(500, '创建用户失败');
        }

        //清除缓存
        \Cache::forget($request->smsCodes_key);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * ---------------------------------------------------------------
     * 登录用户的信息
     * ---------------------------------------------------------------
     * @return array
     */
    public function  userInfo(Request $request)
    {
        return (new UserResource($request->user()))->showSensitiveFields();
    }

    /**
     * ---------------------------------------------------------------
     * 获取某个用户的详情
     * ---------------------------------------------------------------
     * @param Obj $user user模型参数
     * @param int $request 传入的user模型id
     *
     * @return array user数据
     */
    public function show(User $user, Request $request)
    {
        return new UserResource($user);
    }

    /**
     * ---------------------------------------------------------------
     * 更新用户
     * ---------------------------------------------------------------
     * @param array $request 验证后的数据
     *
     * @return array
     */
    public function update(UserRequest $request, FileUploadHandler $uploader, UserService $service)
    {
        $user = $request->user();

        $attributes = $service->editUser($request, $uploader);

        $user->update($attributes);

        return response()->json([
            'message' => 'Successfully update user!'
        ], 201);
    }
}
