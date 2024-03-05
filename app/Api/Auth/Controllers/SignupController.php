<?php

declare(strict_types=1);

namespace App\Api\Auth\Controllers;

use App\Api\Auth\Controllers\UserService;
use App\Bundles\Sms\Services\SmsBundleService;
use App\Exceptions\CustomException;
use App\Services\JWTService;
use App\Http\Controllers\Auth\Requests\Signup\SignupMobileRequest;
use App\Http\Controllers\Auth\Responses\LoginResponse;
use App\Http\Controllers\Auth\Services\Input\RegisterInput;
use Illuminate\Http\JsonResponse;
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
        try {
            $formData = $request->validated();

            $smsService = new SmsBundleService();
            if (! $smsService->checkCode($formData['mobile'], $formData['code'])) {
                throw new CustomException('短信验证码不正确');
            }

            // 校验注册协议
            if ($formData['agreed'] !== 'on') {
                throw new CustomException('请阅读并同意协议');
            }

            // 新用户注册
            $userService = new UserService();
            $userRegisterInput = new RegisterInput();
            $userRegisterInput->setMobile($data['mobile']);
            $userRegisterInput->setCode($data['code']);
            if ($userService->register($userRegisterInput)) {
                // 用户JWT返回
                $JWTService = new JWTService();
                $userOutput = $userService->findOneByMobile($data['mobile']);
                $token = $JWTService->createToken([
                    JWTService::JWT_USER_ID => $userOutput->getId(),
                ]);

                $response = new LoginResponse();
                $response->setToken($token);

                return $this->success($response->toArray());
            }

            throw new CustomException('注册失败');
        } catch (CustomException|Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e->getMessage());
            }

            Log::error($e->getMessage());

            return $this->error('注册错误');
        }
    }
}
