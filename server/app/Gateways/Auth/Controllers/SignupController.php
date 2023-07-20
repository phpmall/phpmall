<?php

declare(strict_types=1);

namespace App\Gateways\Auth\Controllers;

use App\Constants\GlobalConst;
use App\Gateways\Auth\Requests\Signup\SignupMobileRequest;
use App\Gateways\Auth\Responses\LoginResponse;
use App\Gateways\Auth\Services\AuthService;
use App\Gateways\Auth\Services\Input\UserRegisterInput;
use App\Gateways\Auth\Services\UserService;
use Focite\Builder\Exceptions\CustomException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Throwable;

class SignupController extends BaseController
{
    #[OA\Post(path: '/signup/mobile', summary: '通过手机号码注册', tags: ['注册'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SignupMobileRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function mobile(SignupMobileRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            // 校验短信验证码
            $smsCode = Cache::get(GlobalConst::SMS_CACHE_PREFIX.$data['mobile']);
            if ($smsCode !== $data['code']) {
                return $this->error('短信验证码不正确');
            }

            // 校验注册协议
            if ($data['agreed'] !== 'on') {
                return $this->error('请阅读并同意协议');
            }

            // 新用户注册
            $userService = new UserService();
            $userRegisterInput = new UserRegisterInput();
            $userRegisterInput->setMobile($data['mobile']);
            $userRegisterInput->setCode($data['code']);
            if ($userService->register($userRegisterInput)) {
                // 用户JWT返回
                $authService = new AuthService();
                $userOutput = $userService->findOneByMobile($data['mobile']);
                $token = $authService->createToken([
                    GlobalConst::JWT_USER_ID => $userOutput->getId(),
                ]);

                $response = new LoginResponse();
                $response->setToken($token);

                return $this->success($response->toArray());
            }

            return $this->error('注册失败');
        } catch (CustomException $e) {
            return $this->error($e->getMessage());
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            return $this->error('注册错误');
        }
    }
}
