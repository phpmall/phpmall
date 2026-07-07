<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Responses\Index\IndexResponse;
use App\Modules\Content\Services\BannerService;
use App\Modules\Notification\Services\NoticeService;
use App\Modules\Product\Services\ProductCategoryService;
use App\Modules\Product\Services\ProductService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class IndexController extends BaseController
{
    #[OA\Get(path: '/', summary: '首页', security: [[]], tags: ['商城平台'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: IndexResponse::class))]
    public function index(): JsonResponse
    {
        $banners = app(BannerService::class)->getHomeBanners();
        $categories = app(ProductCategoryService::class)->getTree();
        $products = app(ProductService::class)->getRecommendProducts();
        $notices = app(NoticeService::class)->getLatest(5);

        $response = new IndexResponse;
        $response->setBanners($banners);
        $response->setCategories($categories);
        $response->setRecommendProducts($products);
        $response->setNotices($notices);

        return $this->success($response->toArray());
    }
}
