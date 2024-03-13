<?php

declare(strict_types=1);

namespace App\Api\Auth\Controllers;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '认证接口', title: 'API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/auth/', description: '开发环境')]
abstract class BaseController extends Controller
{
}
