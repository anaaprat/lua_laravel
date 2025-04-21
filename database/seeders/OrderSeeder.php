<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run()
    {
        DB::table('orders')->insert([
            [
                'user_id' => 11,
                'bar_id' => 1,
                'total' => 29.66,
                'status' => 'completed',
                'created_at' => Carbon::parse('2025-04-01 01:41:58'),
                'updated_at' => Carbon::parse('2025-01-30 04:13:16'),
            ],
            [
                'user_id' => 10,
                'bar_id' => 1,
                'total' => 8.49,
                'status' => 'pending',
                'created_at' => Carbon::parse('2025-03-21 09:57:21'),
                'updated_at' => Carbon::parse('2025-02-21 01:13:31'),
            ],
            [
                'user_id' => 74,
                'bar_id' => 1,
                'total' => 36.79,
                'status' => 'canceled',
                'created_at' => Carbon::parse('2025-03-02 08:15:58'),
                'updated_at' => Carbon::parse('2025-03-16 03:47:21'),
            ],
            [
                'user_id' => 87,
                'bar_id' => 1,
                'total' => 46.57,
                'status' => 'completed',
                'created_at' => Carbon::parse('2025-03-22 00:45:56'),
                'updated_at' => Carbon::parse('2025-03-10 22:24:29'),
            ],
            [
                'user_id' => 50,
                'bar_id' => 1,
                'total' => 30.04,
                'status' => 'pending',
                'created_at' => Carbon::parse('2025-02-06 03:09:18'),
                'updated_at' => Carbon::parse('2025-03-05 07:07:12'),
            ],
            [
                'user_id' => 15,
                'bar_id' => 1,
                'total' => 29.48,
                'status' => 'canceled',
                'created_at' => Carbon::parse('2025-03-21 22:11:08'),
                'updated_at' => Carbon::parse('2025-03-20 08:35:23'),
            ],
            [
                'user_id' => 60,
                'bar_id' => 1,
                'total' => 29.37,
                'status' => 'canceled',
                'created_at' => Carbon::parse('2025-03-01 09:20:24'),
                'updated_at' => Carbon::parse('2025-04-06 21:53:26'),
            ],
            [
                'user_id' => 14,
                'bar_id' => 1,
                'total' => 35.37,
                'status' => 'pending',
                'created_at' => Carbon::parse('2025-04-07 11:52:50'),
                'updated_at' => Carbon::parse('2025-02-23 17:33:14'),
            ],
            [
                'user_id' => 14,
                'bar_id' => 1,
                'total' => 22.62,
                'status' => 'pending',
                'created_at' => Carbon::parse('2025-01-22 22:04:23'),
                'updated_at' => Carbon::parse('2025-02-18 11:30:34'),
            ],
            [
                'user_id' => 58,
                'bar_id' => 1,
                'total' => 13.28,
                'status' => 'completed',
                'created_at' => Carbon::parse('2025-02-26 03:04:39'),
                'updated_at' => Carbon::parse('2025-01-16 16:19:30'),
            ],
        ]);
    }
}