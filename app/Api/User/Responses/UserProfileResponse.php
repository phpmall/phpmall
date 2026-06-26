<?php

declare(strict_types=1);

namespace App\Api\User\Responses;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserProfileResponse')]
class UserProfileResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '用户ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '用户姓名', type: 'string', nullable: true)]
    private ?string $name;

    #[OA\Property(property: 'nickname', description: '用户昵称', type: 'string', nullable: true)]
    private ?string $nickname;

    #[OA\Property(property: 'avatar', description: '用户头像', type: 'string', nullable: true)]
    private ?string $avatar;

    #[OA\Property(property: 'mobile', description: '手机号', type: 'string', nullable: true)]
    private ?string $mobile;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string', nullable: true)]
    private ?string $email;

    #[OA\Property(property: 'gender', description: '性别:0未知，1男，2女', type: 'integer', nullable: true)]
    private ?int $gender;

    #[OA\Property(property: 'birthday', description: '生日', type: 'string', format: 'date', nullable: true)]
    private ?string $birthday;

    #[OA\Property(property: 'addresses', description: '收货地址列表', type: 'array', items: new OA\Items(ref: AddressResponse::class), nullable: true)]
    private ?array $addresses = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
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

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): void
    {
        $this->gender = $gender;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(?string $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getAddresses(): ?array
    {
        return $this->addresses;
    }

    public function setAddresses(?array $addresses): void
    {
        $this->addresses = $addresses;
    }
}
