<?php

declare(strict_types=1);

namespace app\manager\lbs\contract;

/**
 * Interface LbsContract
 * @package app\manager\lbs\contract
 */
interface LbsContract
{
    /**
     * IP定位
     * 通过终端设备IP地址获取其当前所在地理位置，精确到市级。
     * @param string $ip
     * @return array
     */
    public function ip(string $ip): array;

    /**
     * 逆地址解析（坐标位置描述）
     * 输入坐标返回地理位置信息和附近poi列表。
     * @param string $lat 纬度（39.984154）
     * @param string $lng 经度（116.307490）
     * @return array
     */
    public function location2address(string $lat, string $lng): array;

    /**
     * 地址解析（地址转坐标）
     * @param string $address
     * @return array
     */
    public function address2location(string $address): array;

    /**
     * 获取省市区列表
     * 用于获取全部省市区三级行政区划
     * @param string $id
     * @return array
     */
    public function district(string $id): array;
}
