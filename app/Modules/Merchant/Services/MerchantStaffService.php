<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Services;

use App\Modules\Merchant\Models\MerchantStaff;
use App\Modules\Merchant\Repositories\MerchantStaffRepository;
use Illuminate\Support\Facades\Hash;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MerchantStaffService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MerchantStaffRepository $repository,
    ) {}

    public function getRepository(): MerchantStaffRepository
    {
        return $this->repository;
    }

    public function findForMerchant(int $id, int $merchantId): ?MerchantStaff
    {
        return MerchantStaff::where('id', $id)
            ->where('merchant_id', $merchantId)
            ->first();
    }

    public function updateForMerchant(int $id, int $merchantId, array $data): ?MerchantStaff
    {
        $staff = $this->findForMerchant($id, $merchantId);

        if ($staff === null) {
            return null;
        }

        if (isset($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }

        $staff->update($data);

        return $staff->fresh();
    }

    public function deleteForMerchant(int $id, int $merchantId): bool
    {
        $staff = $this->findForMerchant($id, $merchantId);

        if ($staff === null) {
            return false;
        }

        return (bool) $staff->delete();
    }

    public function syncPermissions(int $id, int $merchantId, array $permissionIds): ?MerchantStaff
    {
        $staff = $this->findForMerchant($id, $merchantId);

        if ($staff === null) {
            return null;
        }

        $syncData = [];
        foreach ($permissionIds as $permissionId) {
            $syncData[$permissionId] = ['model_type' => MerchantStaff::class];
        }

        $staff->permissions()->sync($syncData);

        return $staff->fresh()->load('permissions');
    }
}
