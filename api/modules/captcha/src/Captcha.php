<?php

declare(strict_types=1);

namespace Juling\Captcha;

use Exception;
use GdImage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class Captcha
{
    // 验证码图片实例
    private GdImage $im;

    // 验证码字体颜色
    private int $color;

    // 验证码字符集合
    protected string $codeSet = '2345678ABCDEFGHJKLMNPQRTUVWXY';

    // 验证码过期时间（s）
    protected int $expire = 1800;

    // 使用背景图片
    protected bool $useImgBg = false;

    // 验证码字体大小(px)
    protected int $fontSize = 25;

    // 是否画混淆曲线
    protected bool $useCurve = true;

    // 是否添加杂点
    protected bool $useNoise = true;

    // 验证码图片高度
    protected int $imageH = 0;

    // 验证码图片宽度
    protected int $imageW = 0;

    // 验证码位数
    protected int $length = 4;

    // 验证码字体，不设置随机获取
    protected string $fontttf = '';

    // 背景颜色
    protected array $bg = [243, 251, 254];

    // 算术验证码
    protected bool $math = false;

    // 缓存Key
    protected string $captchaName = 'CAPTCHA_';

    /**
     * 配置验证码
     * @param array $config
     */
    protected function configure(array $config = []): void
    {
        if (! empty($config)) {
            foreach ($config as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $val;
                }
            }
        }
    }

    /**
     * 创建验证码
     * @param string $uuid
     * @return array
     * @throws Exception
     */
    protected function generate(string $uuid): array
    {
        $bag = '';

        if ($this->math) {
            $this->length = 4;

            $x = random_int(10, 30);
            $y = random_int(1, 9);
            $bag = "{$x} + {$y} = ";
            $key = $x + $y;
            $key .= '';
        } else {
            $characters = str_split($this->codeSet);

            for ($i = 0; $i < $this->length; $i++) {
                $bag .= $characters[rand(0, count($characters) - 1)];
            }

            $key = mb_strtolower($bag, 'UTF-8');
        }

        Cache::put($this->captchaName.md5($uuid), [
            'key' => $key,
        ], 600); // 10 Minutes

        return [
            'key' => $key,
            'value' => $bag,
        ];
    }

    /**
     * 验证验证码是否正确
     * @param string $uuid
     * @param string $code 用户验证码
     * @return bool 用户验证码是否正确
     */
    public function check(string $uuid, string $code): bool
    {
        $key = $this->captchaName.md5($uuid);
        if (! Cache::has($key)) {
            return false;
        }

        $captcha = Cache::get($key);

        if (mb_strtolower($code, 'UTF-8') === $captcha['key']) {
            Cache::forget($key);

            return true;
        }

        return false;
    }

    /**
     * 输出验证码并把验证码的值保存的cache中
     * @param array $uuid
     * @param array $config
     * @return string
     * @throws Exception
     */
    public function create(string $uuid, array $config = []): string
    {
        $this->configure($config);

        $generator = $this->generate($uuid);

        // 图片宽(px)
        $this->imageW || $this->imageW = intval($this->length * $this->fontSize * 1.3 + $this->length * $this->fontSize / 2);
        // 图片高(px)
        $this->imageH || $this->imageH = intval($this->fontSize * 2.5);

        // 建立一幅 $this->imageW x $this->imageH 的图像
        $this->im = imagecreate((int)$this->imageW, (int)$this->imageH);
        // 设置背景
        imagecolorallocate($this->im, $this->bg[0], $this->bg[1], $this->bg[2]);

        // 验证码字体随机颜色
        $this->color = imagecolorallocate($this->im, mt_rand(1, 150), mt_rand(1, 150), mt_rand(1, 150));

        // 验证码使用随机字体
        $ttfPath = dirname(__DIR__) . '/assets/ttfs/';

        if (empty($this->fontttf)) {
            $dir = dir($ttfPath);
            $ttfs = [];
            while (false !== ($file = $dir->read())) {
                if (str_ends_with($file, '.ttf')) {
                    $ttfs[] = $file;
                }
            }
            $dir->close();
            $this->fontttf = $ttfs[array_rand($ttfs)];
        }

        $fontttf = $ttfPath . $this->fontttf;

        if ($this->useImgBg) {
            $this->background();
        }

        if ($this->useNoise) {
            // 绘杂点
            $this->writeNoise();
        }
        if ($this->useCurve) {
            // 绘干扰线
            $this->writeCurve();
        }

        // 绘验证码
        $text = str_split($generator['value']); // 验证码

        foreach ($text as $index => $char) {
            $x = $this->fontSize * ($index + 1) * ($this->math ? 1 : 1.5);
            $y = $this->fontSize + mt_rand(10, 20);

            imagettftext($this->im, intval($this->fontSize), intval($this->fontSize), intval($x), intval($y), $this->color, $fontttf, $char);
        }

        ob_start();
        // 输出图像
        imagepng($this->im);
        $content = ob_get_clean();
        imagedestroy($this->im);

        return 'data:image/png;base64,'.base64_encode($content);
    }

    /**
     * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线(你可以改成更帅的曲线函数)
     *
     *      高中的数学公式咋都忘了涅，写出来
     *        正弦型函数解析式：y=Asin(ωx+φ)+b
     *      各常数值对函数图像的影响：
     *        A：决定峰值（即纵向拉伸压缩的倍数）
     *        b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
     *        φ：决定波形与X轴位置关系或横向移动距离（左加右减）
     *        ω：决定周期（最小正周期T=2π/∣ω∣）
     */
    protected function writeCurve(): void
    {
        $py = 0;

        // 曲线前部分
        $A = mt_rand(1, $this->imageH / 2); // 振幅
        $b = mt_rand(intval(-$this->imageH / 4), intval($this->imageH / 4)); // Y轴方向偏移量
        $f = mt_rand(intval(-$this->imageH / 4), intval($this->imageH / 4)); // X轴方向偏移量
        $T = mt_rand($this->imageH, $this->imageW * 2); // 周期
        $w = (2 * M_PI) / $T;

        $px1 = 0; // 曲线横坐标起始位置
        $px2 = mt_rand($this->imageW / 2, intval($this->imageW * 0.8)); // 曲线横坐标结束位置

        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if (0 != $w) {
                $py = $A * sin($w * $px + $f) + $b + $this->imageH / 2; // y = Asin(ωx+φ) + b
                $i = (int)($this->fontSize / 5);
                while ($i > 0) {
                    imagesetpixel($this->im, intval($px + $i), intval($py + $i), $this->color); // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多
                    $i--;
                }
            }
        }

        // 曲线后部分
        $A = mt_rand(1, $this->imageH / 2); // 振幅
        $f = mt_rand(intval(-$this->imageH / 4), intval($this->imageH / 4)); // X轴方向偏移量
        $T = mt_rand($this->imageH, $this->imageW * 2); // 周期
        $w = (2 * M_PI) / $T;
        $b = $py - $A * sin($w * $px + $f) - $this->imageH / 2;
        $px1 = $px2;
        $px2 = $this->imageW;

        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if (0 != $w) {
                $py = $A * sin($w * $px + $f) + $b + $this->imageH / 2; // y = Asin(ωx+φ) + b
                $i = (int)($this->fontSize / 5);
                while ($i > 0) {
                    imagesetpixel($this->im, intval($px + $i), intval($py + $i), $this->color);
                    $i--;
                }
            }
        }
    }

    /**
     * 画杂点
     * 往图片上写不同颜色的字母或数字
     */
    protected function writeNoise(): void
    {
        $codeSet = '2345678abcdefhijkmnpqrstuvwxyz';
        for ($i = 0; $i < 10; $i++) {
            //杂点颜色
            $noiseColor = imagecolorallocate($this->im, mt_rand(150, 225), mt_rand(150, 225), mt_rand(150, 225));
            for ($j = 0; $j < 5; $j++) {
                // 绘杂点
                imagestring($this->im, 5, mt_rand(-10, $this->imageW), mt_rand(-10, $this->imageH), $codeSet[mt_rand(0, 29)], $noiseColor);
            }
        }
    }

    /**
     * 绘制背景图片
     * 注：如果验证码输出图片比较大，将占用比较多的系统资源
     */
    protected function background(): void
    {
        $path = dirname(__DIR__) . '/assets/bgs/';
        $dir = dir($path);

        $bgs = [];
        while (false !== ($file = $dir->read())) {
            if ('.' != $file[0] && str_ends_with($file, '.jpg')) {
                $bgs[] = $path . $file;
            }
        }
        $dir->close();

        $gb = $bgs[array_rand($bgs)];

        [$width, $height] = @getimagesize($gb);
        // Resample
        $bgImage = @imagecreatefromjpeg($gb);
        @imagecopyresampled($this->im, $bgImage, 0, 0, 0, 0, $this->imageW, $this->imageH, $width, $height);
        @imagedestroy($bgImage);
    }
}
