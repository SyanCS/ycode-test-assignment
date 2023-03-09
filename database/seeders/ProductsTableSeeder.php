<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\YcodeApiService;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$ycodeApiService = new YcodeApiService();

		$ycodeProducts = $ycodeApiService->getProducts();

		foreach($ycodeProducts['data'] as $ycodeProduct){
			$product = new Product([
				'ycode_id' => $ycodeProduct['_ycode_id'],
				'name' => $ycodeProduct['Name'],
				'color' => $ycodeProduct['Color'],
				'price' => $ycodeProduct['Price'],
				'image' => $ycodeProduct['Image'],
			]);
			$product->save();
		}

		$this->command->info('Products table seeded!');
	}
}
