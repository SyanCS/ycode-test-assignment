<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'ycode_id' => '167701234205463f52d760d341',
                'name' => 'Basic Tee',
                'color' => 'black',
                'price' => 32.00,
                'image' => 'https://storage.googleapis.com/ycode-prod-uploads/assets/app15751/images/BTtG3IAkhz4pP9NHd1Ava1x5cgAbM05mnzaSrGCJ.jpg',
            ],
            [
                'ycode_id' => '167701242332763f52dc74fed1',
                'name' => 'T-Shirt',
                'color' => 'gray',
                'price' => 32.00,
                'image' => 'https://storage.googleapis.com/ycode-prod-uploads/assets/app15751/images/qiOqsPKNK0mvjTXIpANMFTs8pp9aIGVmhzkOvJmH.jpg',
            ],
        ];

        DB::table('products')->insert($products);
    }
}
