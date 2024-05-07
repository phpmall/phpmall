<?php

declare(strict_types=1);

namespace App\Modules\Wechat\Services;

use App\Exceptions\CustomException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Pay\Application;

class PayService extends BaseService
{
    private ?Application $app;

    /**
     * 设置小程序配置
     *
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $config = config('wechat.pay');

        $this->app = new Application($config);
    }

    /**
     * 支付
     *
     * @throws CustomException
     */
    public function getWechatPay(string $tradeNo, array $product, string $openId, float $total): array
    {
        try {
            $config = config('wechat.pay');

            $data = [
                'mchid' => $config['mch_id'], //商户号
                'out_trade_no' => $tradeNo,
                'appid' => config('wechat.mini_app.app_id'), // 服务号的 appid
                'description' => $product['product_name'],
                'notify_url' => $config['callback_url'],
                'amount' => [
                    'total' => intval($total * 100),
                    'currency' => 'CNY',
                ],
                'payer' => [
                    'openid' => $openId, // 下单用户的 openid
                ],
            ];

            $response = $this->app->getClient()->postJson('/v3/pay/transactions/jsapi', $data);

            $result = json_decode($response->getContent(), true);

            if ($response->isFailed()) {
                throw new CustomException($result['message']);
            }

            return $this->app->getUtils()->buildMiniAppConfig($result['prepay_id'], config('wechat.mini_app.app_id'));
        } catch (CustomException $e) {
            throw new CustomException($e->getMessage());
        } catch (\Throwable $e) {
            throw new CustomException(sprintf('JSAPI下单：%s', $e->getMessage()));
        }
    }

    /**
     * 退单
     *
     * @throws
     */
    public function returnWechatPay(string $pay, array $payCallback): array
    {
        try {
            $config = config('wechat.pay');

            $data = [
                'transaction_id' => $payCallback['transaction_id'], //微信支付订单号
                'out_refund_no' => $payCallback['out_trade_no'], //商户退款单号
                'notify_url' => $config['refund_callback_url'],
                'amount' => [
                    'refund' => intval($pay * 100),
                    'total' => intval($pay * 100),
                    'currency' => 'CNY',
                ],
            ];

            $response = $this->app->getClient()->postJson('/v3/refund/domestic/refunds', $data);

            $result = json_decode($response->getContent(), true);

            if ($response->isFailed()) {
                throw new CustomException($result['message']);
            }

            return $result;
        } catch (CustomException $e) {
            throw new CustomException($e->getMessage());
        } catch (\Throwable $e) {
            throw new CustomException(sprintf('微信退款：%s', $e->getMessage()));
        }
    }
}
