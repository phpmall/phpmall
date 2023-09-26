<?php

declare(strict_types=1);

namespace App\Gateways\Portal\Controllers;

use App\Bundles\Foundation\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '提供门户API接口', title: 'API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: 'http://127.0.0.1:8000', description: '开发环境')]
#[OA\Server(url: 'https://api.demo.phpmall.net', description: '测试环境')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'JWT 认证信息', name: 'Authorization', in: 'header', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class BaseController extends Controller
{
    /**
     * 视图渲染
     */
    protected function display($template, array $vars = []): Renderable
    {
        return parent::display('portal::'.$template, $vars);
    }
}
