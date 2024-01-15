<?php

namespace Database\Seeders;

use App\Models\Entity\RoleEntity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $result = DB::table('roles')->count();
            if (empty($result)) {
                $roleEntity = new RoleEntity();
                $roleEntity->setId(1);
                $roleEntity->setName('超级管理员');
                $roleEntity->setDescription('');

                DB::table('roles')->insert($roleEntity->toArray());
            }
        });
    }
}
