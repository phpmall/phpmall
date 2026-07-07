<?php

declare(strict_types=1);

namespace App\Modules\Order\Repositories;

use App\Modules\Order\Models\OrderShipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\RepositoryInterface;
use Juling\Foundation\Repositories\CurdRepository;

class OrderShipmentRepository extends CurdRepository implements RepositoryInterface
{
    /**
     * 定义数据表查询构造器
     */
    public function builder(): Builder
    {
        return DB::table('order_shipments');
    }

    /**
     * 定义数据表模型类
     */
    public function model(): Model
    {
        return new OrderShipment;
    }
}
