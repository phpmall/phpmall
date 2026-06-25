<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();

            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->unique()->after('email')->comment('手机号');
            }
            if (! Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('phone')->comment('手机号验证时间');
            }
            if (! Schema::hasColumn('users', 'status')) {
                $table->unsignedTinyInteger('status')->default(1)->after('password')->comment('状态：1-正常 2-禁用');
            }
            if (! Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar', 500)->nullable()->after('status')->comment('头像');
            }
            if (! Schema::hasColumn('users', 'nickname')) {
                $table->string('nickname', 100)->nullable()->after('avatar')->comment('昵称');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropColumn([
                'phone',
                'phone_verified_at',
                'status',
                'avatar',
                'nickname',
            ]);
        });
    }
};
