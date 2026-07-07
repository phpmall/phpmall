<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_regions', function (Blueprint $table) {
            $table->id();
            $table->string('parent_code', 20)->default('0')->comment('父级地区编码');
            $table->string('name', 100)->comment('地区名称');
            $table->string('code', 20)->unique('udx_system_regions_code')->comment('地区编码');
            $table->tinyInteger('level')->unsigned()->comment('地区层级:1省,2市,3区');
            $table->string('zip_code', 20)->default('')->comment('邮编');
            $table->tinyInteger('has_children')->unsigned()->default(0)->comment('是否有子级');
            $table->timestamps();

            $table->index(['parent_code'], 'idx_system_regions_parent_code');
            $table->index(['level'], 'idx_system_regions_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_regions');
    }
};
