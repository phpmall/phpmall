<?php

declare (strict_types=1);

namespace app\controller\user;

use app\support\Controller;
use think\Response;

/**
 * Class InvoiceController
 * @package app\controller\user
 */
class InvoiceController extends Controller
{
    /**
     * @OA\Get(
     *  path="user/invoice",
     *  tags={"user"},
     *  summary="发票",
     *  description="发票的详细描述",
     *  @OA\Response(response="200", description="An example resource")
     * )
     * @return Response
     */
    public function index(): Response
    {
        return $this->succeed('ok');
    }
}
