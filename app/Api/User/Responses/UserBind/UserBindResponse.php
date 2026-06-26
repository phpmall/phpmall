<?php

declare(strict_types=1);

namespace App\Api\User\Responses\UserBind;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserBindResponse')]
class UserBindResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '绑定ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '绑定类型:wechat,qq,weibo,alipay,phone,email', type: 'string')]
    private string $type;

    #[OA\Property(property: 'account', description: '账号标识', type: 'string')]
    private string $account;

    #[OA\Property(property: 'nickname', description: '第三方昵称', type: 'string', nullable: true)]
    private ?string $nickname;

    #[OA\Property(property: 'avatar', description: '第三方头像', type: 'string', nullable: true)]
    private ?string $avatar;

    #[OA\Property(property: 'is_verified', description: '是否已验证:0否，1是', type: 'integer')]
    private int $isVerified;

    #[OA\Property(property: 'bound_at', description: '绑定时间', type: 'string', format: 'date-time')]
    private string $boundAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getAccount(): string
    {
        return $this->account;
    }

    public function setAccount(string $account): void
    {
        $this->account = $account;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getIsVerified(): int
    {
        return $this->isVerified;
    }

    public function setIsVerified(int $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    public function getBoundAt(): string
    {
        return $this->boundAt;
    }

    public function setBoundAt(string $boundAt): void
    {
        $this->boundAt = $boundAt;
    }
}
