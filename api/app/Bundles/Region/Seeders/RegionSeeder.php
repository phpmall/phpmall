<?php

declare(strict_types=1);

namespace App\Bundles\Region\Seeders;

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regionService = new RegionService();
        $regions = $regionService->getList();
        if (empty($regions)) {
            $regionData = file_get_contents(dirname(__DIR__).'/Data/region.csv');

            foreach (explode("\n", $regionData) as $line => $row) {
                if ($line === 0) {
                    continue;
                }

                $cols = explode(',', $row);
                $cols = array_map(function ($val) {
                    return is_null($val) ? '' : trim($val, '"');
                }, $cols);

                if (isset($cols[6])) {
                    $regions[] = [
                        'id' => $cols[0],
                        'pid' => $cols[1],
                        'deep' => $cols[2],
                        'name' => $cols[3],
                        'pinyin_prefix' => $cols[4],
                        'pinyin' => $cols[5],
                        'ext_id' => $cols[6],
                        'ext_name' => $cols[7],
                    ];
                }
            }

            $regionService->getRepository()->model()->insertAll($regions);
        }
    }
}
