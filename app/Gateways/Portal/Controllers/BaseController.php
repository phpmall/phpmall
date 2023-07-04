<?php

declare(strict_types=1);

namespace App\Gateways\Portal\Controllers;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '门户平台接口', title: 'API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: 'http://127.0.0.1:8000', description: '开发环境')]
#[OA\Server(url: 'https://demo.phpmall.net', description: '测试环境')]
abstract class BaseController extends Controller
{
}
