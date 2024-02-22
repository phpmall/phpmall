<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portal;

use App\Foundation\Http\Controllers\Controller;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Contact;

#[OA\Info(version: '1.0', description: '基础平台接口', title: 'API文档', contact: new Contact('API Develop Team'))]
#[OA\Server(url: '/api/portal/', description: '开发环境')]
abstract class BaseController extends Controller
{
}
