<?php

namespace Database\Seeders;

use App\Models\Entity\User;
use App\Models\Entity\UserSocialite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $result = DB::table('users')->count();
            if (empty($result)) {
                $userEntity = new User();
                $userEntity->setId(1);
                $userEntity->setName('李四');
                $userEntity->setAvatar('url');
                $userEntity->setBirthday(Carbon::now()->toDateString());
                $userEntity->setUsername('admin');
                $userEntity->setPassword(Str::password('0192023a7bbd73250516f069df18b500')); // admin123
                $userEntity->setPasswordSalt('');
                // $userEntity->setIsAdmin(1);
                $userId = DB::table('users')->insertGetId(collect($userEntity)->toArray());

                $userAuthEntity = new UserSocialite();
                $userAuthEntity->setUserId($userId);
                $userAuthEntity->setType('mobile');
                $userAuthEntity->setIdentifier('18888888888');
                $userAuthEntity->setCredential('');
                $userAuthEntity->setStatus(1);
                DB::table('user_socialites')->insert(collect($userAuthEntity)->toArray());

                $userAuthEntity2 = clone $userAuthEntity;
                $userAuthEntity2->setType('email');
                $userAuthEntity2->setIdentifier('aa@bb.com');
                DB::table('user_socialites')->insert(collect($userAuthEntity2)->toArray());
            }
        });
    }
}
