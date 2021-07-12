<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 清空表
        DB::table('xq_region')->truncate();
        // .sql 文件语法要求！每条语句独立一行，不要出现不遵循 sql 语法的行
        $res = fopen(__DIR__ . '/region.sql' ,'r');
        while ($line = fgets($res))
        {
            DB::statement($line);
        }
    }
}
