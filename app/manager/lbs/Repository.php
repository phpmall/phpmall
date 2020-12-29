<?php

declare(strict_types=1);

namespace app\manager\lbs;

use app\manager\lbs\contract\LbsContract;
use app\manager\lbs\contract\RepositoryContract;

/**
 * Class Repository
 * @package app\manager\lbs
 */
class Repository implements RepositoryContract
{
    /**
     * The lbs store implementation.
     *
     * @var LbsContract
     */
    protected $store;

    /**
     * Create a new lbs repository instance.
     *
     * @param LbsContract $store
     * @return void
     */
    public function __construct(LbsContract $store)
    {
        $this->store = $store;
    }

    /**
     * IP定位
     * 通过终端设备IP地址获取其当前所在地理位置，精确到市级。
     * @param string $ip
     * @return array
     */
    public function ip(string $ip): array
    {
        return $this->store->ip($ip);
    }

    /**
     * 逆地址解析（坐标位置描述）
     * 输入坐标返回地理位置信息和附近poi列表。
     * @param string $lat 纬度（39.984154）
     * @param string $lng 经度（116.307490）
     * @return array
     */
    public function location2address(string $lat, string $lng): array
    {
        return $this->store->location2address($lat, $lng);
    }

    /**
     * 地址解析（地址转坐标）
     * @param string $address
     * @return array
     */
    public function address2location(string $address): array
    {
        return $this->store->address2location($address);
    }

    /**
     * 获取省市区列表
     * 用于获取全部省市区三级行政区划
     * @param string $id
     * @return array
     */
    public function district(string $id): array
    {
        return $this->store->district($id);
    }
}
