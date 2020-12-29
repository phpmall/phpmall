<?php

declare(strict_types=1);

namespace app\manager\lbs\driver;

use app\manager\lbs\contract\LbsContract;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Traits\HasHttpRequests;
use GuzzleHttp\Exception\GuzzleException;
use think\facade\Log;

/**
 * Class Tencent
 * @package app\manager\lbs\store
 */
class Tencent implements LbsContract
{
    use HasHttpRequests;

    /**
     * @var string
     */
    protected $baseUri = 'https://apis.map.qq.com/ws/';

    /**
     * @var array
     */
    protected $defaultOptions = [
        'headers' => [
            'Host' => 'apis.map.qq.com',
            'Referer' => 'https://lbs.qq.com/',
        ]
    ];

    /**
     * @var array
     */
    protected $config = [
        'key' => '',
    ];

    /**
     * @var string
     */
    protected $response_type = 'array';

    /**
     * TencentStore constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @param string $ip
     * @return array
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function ip(string $ip): array
    {
        $response = $this->httpGet('location/v1/ip', ['ip' => $ip]);

        if (!empty($response)) {
            return [
                'code' => (string)$response['ad_info']['adcode'],
                'province' => $response['ad_info']['province'],
                'city' => $response['ad_info']['city'],
                'district' => $response['ad_info']['district'],
                'lat' => $response['location']['lat'],
                'lng' => $response['location']['lng'],
            ];
        }

        return [];
    }

    /**
     * @param string $lat
     * @param string $lng
     * @return array
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function location2address(string $lat = '', string $lng = ''): array
    {
        $response = $this->httpGet('geocoder/v1', ['location' => $lat . ',' . $lng]);

        if (!empty($response)) {
            return [
                'code' => $response['ad_info']['adcode'],
                'province' => $response['ad_info']['province'],
                'city' => $response['ad_info']['city'],
                'district' => $response['ad_info']['district'],
                'address' => $response['address'],
            ];
        }

        return [];
    }

    /**
     * @param string $address
     * @return array
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function address2location(string $address = ''): array
    {
        $response = $this->httpGet('geocoder/v1', ['address' => $address]);

        if (!empty($response)) {
            return [
                'code' => $response['ad_info']['adcode'],
                'province' => $response['address_components']['province'],
                'city' => $response['address_components']['city'],
                'district' => $response['address_components']['district'],
                'lat' => $response['location']['lat'],
                'lng' => $response['location']['lng'],
            ];
        }

        return [];
    }

    /**
     * @param string $id
     * @return mixed
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function district(string $id = ''): array
    {
        $query = empty($id) ? [] : ['id' => $id];

        $response = $this->httpGet('district/v1/getchildren', $query);

        $result = [];
        if (!empty($response)) {
            foreach (end($response) as $item) {
                array_push($result, [
                    'code' => $item['id'],
                    'name' => $item['fullname'],
                ]);
            }
        }

        return $result;
    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array $query
     * @return array
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    protected function httpGet(string $url, array $query = []): array
    {
        $query['key'] = $this->config['key'];

        $options = array_merge(self::getDefaultOptions(), $this->defaultOptions);
        $options = array_merge($options, ['query' => $query]);

        $responseRaw = $this->request($url, 'GET', $options);
        $response = $this->castResponseToType($responseRaw, $this->response_type);

        if ($response['status'] === 0) {
            return $response['result'];
        }

        Log::error($response['message']);

        return [];
    }
}
