<?php

namespace Database\Seeders;

use App\Models\Entity\UserEntity;
use App\Models\Entity\UserSocialiteEntity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $result = DB::table('users')->count();
            if (empty($result)) {
                $userEntity = new UserEntity();
                $userEntity->setId(1);
                $userEntity->setName('赵四');
                $userEntity->setAvatar('url');
                $userEntity->setBirthday(Carbon::now()->toDateString());
                $userEntity->setMobile('18888888888');
                $userEntity->setMobileVerifiedAt(Carbon::now()->toDateTimeString());
                $userEntity->setPassword(Hash::make('admin123'));
                $userId = DB::table('users')->insertGetId($userEntity->toArray());

                $userAuthEntity = new UserSocialiteEntity();
                $userAuthEntity->setUserId($userId);
                $userAuthEntity->setType('email');
                $userAuthEntity->setIdentifier('18888888888@bb.com');
                $userAuthEntity->setCredentials(Hash::make('admin123'));
                $userAuthEntity->setStatus(1);
                DB::table('user_socialites')->insert($userAuthEntity->toArray());
            }
        });
    }
}
