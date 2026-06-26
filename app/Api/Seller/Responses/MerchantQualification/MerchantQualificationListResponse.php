<?php

declare(strict_types=1);

namespace App\Api\Seller\Responses\MerchantQualification;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SellerMerchantQualificationListResponse')]
class MerchantQualificationListResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '资质ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'type', description: '资质类型:1营业执照,2经营许可证,3品牌授权,4其他', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'name', description: '资质名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'image', description: '资质图片URL', type: 'string')]
    private string $image;

    #[OA\Property(property: 'number', description: '资质编号', type: 'string', nullable: true)]
    private ?string $number;

    #[OA\Property(property: 'valid_start', description: '有效期开始', type: 'string', format: 'date-time', nullable: true)]
    private ?string $validStart;

    #[OA\Property(property: 'valid_end', description: '有效期结束', type: 'string', format: 'date-time', nullable: true)]
    private ?string $validEnd;

    #[OA\Property(property: 'is_permanent', description: '是否永久有效:0否,1是', type: 'integer')]
    private int $isPermanent;

    #[OA\Property(property: 'status', description: '审核状态:0待审核,1已通过,2已拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'created_at', description: '创建时间', type: 'string', format: 'date-time')]
    private string $createdAt;

    #[OA\Property(property: 'updated_at', description: '更新时间', type: 'string', format: 'date-time')]
    private string $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): void
    {
        $this->number = $number;
    }

    public function getValidStart(): ?string
    {
        return $this->validStart;
    }

    public function setValidStart(?string $validStart): void
    {
        $this->validStart = $validStart;
    }

    public function getValidEnd(): ?string
    {
        return $this->validEnd;
    }

    public function setValidEnd(?string $validEnd): void
    {
        $this->validEnd = $validEnd;
    }

    public function getIsPermanent(): int
    {
        return $this->isPermanent;
    }

    public function setIsPermanent(int $isPermanent): void
    {
        $this->isPermanent = $isPermanent;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
